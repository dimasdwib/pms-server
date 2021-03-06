<?php

namespace Modules\Reservation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationRoom;
use App\Models\Reservation\ReservationGuest;
use App\Models\Reservation\ReservationGuestResource;
use App\Models\Reservation\ReservationResource;
use App\Models\Reservation\ReservationRoomResource;
use App\Models\Reservation\InHouseResource;
use App\Models\Transaction\RoomCharge;
use App\Models\Transaction\Bill;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\ReservationBill;
use App\Models\Rate\Rate;
use DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = null;
        $search = $request->search;
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }

        $reservations = Reservation::orderBy('id_reservation', 'desc')
                                    ->searchByModel($search, [
                                        'this' => ['id_reservation', 'note'],
                                        'booker' => ['name', 'email', 'phone'],
                                    ])
                                    ->paginate($limit);
        return ReservationResource::collection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'booker' => 'required',
            'adult' => 'numeric',
            'child' => 'numeric',
            'date_arrival' =>'required|date',
            'date_departure' =>'required|date',
        ]);

        $reservation = new Reservation;
        $reservation->id_booker = $request->booker['id_guest'];
        $reservation->status = $request->status == '' ? 'tentative' : $request->status;
        $reservation->note = $request->note;
        $reservation->adult = $request->adult;
        $reservation->child = $request->child;

        DB::beginTransaction();
        $reservation->save();

        foreach($request->rooms as $room) {
            $reservation_room = new ReservationRoom([
                'id_room' => $room['id_room'],
                'id_reservation' => $reservation->id_reservation
            ]);
            $reservation_room->save();
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id_guest'],
                    'id_reservation_room' => $reservation_room->id_reservation_room,
                    'date_arrival' => $request->date_arrival,
                    'date_departure' => $request->date_departure,
                ]);
                $room_guest->save();
            }

            // Insert room rate
            $room_rate = $room['rate'];
            $rate = Rate::where('id_rate', $room_rate['id_rate'])
                    ->forReservation($request->date_arrival, $request->date_departure)
                    ->first();
            foreach($rate['charges'] as $charge) {
                RoomCharge::create([
                    'id_reservation_room_guest' => $room_guest->id_reservation_room_guest,
                    'id_rate' => $rate['id_rate'],
                    'amount_nett' => $charge['amount_nett'],
                    'date' => $charge['date'],
                ]);
            }
        }

        // create bill
        $bill = new Bill;
        $bill->id_guest = $reservation->id_booker;
        $bill->save();

        if ($request->payments != null) {
            foreach($request->payments as $payment) {
                $transaction = new Transaction;
                $transaction->id_bill = $bill->id_bill;
                $transaction->date = date('Y-m-d H:i:s');
                $transaction->amount_nett = $payment['amount'];
                $transaction->id_transaction_category = 2; // deposit
                $transaction->description = 'Deposit - '.date('Y-m-d');
                $transaction->save();
            }
        }

        $reservation_bill = new ReservationBill;
        $reservation_bill->id_reservation = $reservation->id_reservation;
        $reservation_bill->id_bill = $bill->id_bill;
        $reservation_bill->status = 'open'; // open bill
        $reservation_bill->save();

        if ($reservation->id_reservation) {
            DB::commit();
            return response()->json([
                'message' => 'Reservation has been created successfully',
                'reservation' => new ReservationResource($reservation),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation = $reservation->detail();

        return $reservation;
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $reservation = Reservation::findOrFail($id);
        if ($reservation->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Reservation has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Get reservation room
     *
     */
    public function room($id_reservation_room)
    {
        $room = ReservationRoom::findOrFail($id_reservation_room);
        return new ReservationRoomResource($room);
    }

    /**
     * Checkin the guest
     *
     */
    public function checkin($id_reservation_room_guest)
    {
        $guest = ReservationGuest::findOrFail($id_reservation_room_guest);

        DB::beginTransaction();
        if ($guest->checkin()) {
            DB::commit();
            return response()->json([
                'message' => 'Guest has been checked in successfully',
                'guest' => new ReservationGuestResource($guest),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Checkout the guest
     *
     */
    public function checkout($id_reservation_room_guest)
    {
        $guest = ReservationGuest::findOrFail($id_reservation_room_guest);

        DB::beginTransaction();
        if ($guest->checkout()) {
            DB::commit();
            return response()->json([
                'message' => 'Guest has been checked out successfully',
                'guest' => new ReservationGuestResource($guest),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Display list of guest in house
     *
     */
    public function inhouse(Request $request)
    {
        $limit = null;
        $search = $request->search;
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }

        $reservation_rooms = ReservationRoom::searchByModel($search, [
                                                'reservation' => ['id_reservation', 'created_at'],
                                                'room' => ['number'],
                                            ]);
                                            

        if ($search != null) {
            $reservation_rooms->orWhereHas('reservation', function ($q) use ($search) {              
                $q->whereHas('booker', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        $reservation_rooms = $reservation_rooms->inhouse()->paginate($limit);


        return InHouseResource::collection($reservation_rooms);
    }

    /**
     * Change booker 
     * 
     */
    public function booker($id, Request $request) 
    {   
        $request->validate([
            'id_guest' => 'required|numeric',
        ]);

        $id_booker = $request->id_guest;
        $reservation = Reservation::findOrFail($id);
        $reservation->id_booker = $id_booker;

        DB::beginTransaction();
        if ($reservation->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Booker has been updated successfully',
                'reservation' => new ReservationResource($reservation),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Change note 
     * 
     */
    public function note($id, Request $request) 
    {   
        $note = $request->note;
        $reservation = Reservation::findOrFail($id);
        $reservation->note = $note;

        DB::beginTransaction();
        if ($reservation->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Note has been updated successfully',
                'reservation' => new ReservationResource($reservation),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }
    
    /**
     * Update room
     */
    public function update_room($id, Request $request) 
    {   
        $request->validate([
            'id_reservation_room' => 'required|numeric',
            'id_room' => 'required|numeric',
        ]);

        $reservation_room = ReservationRoom::findOrFail($id);
        $reservation_room->id_room = $request->id_room;
        
        DB::beginTransaction();
        if ($reservation_room->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Room has been updated successfully',
                'reservation_room' => new ReservationRoomResource($reservation_room),
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * Delete room
     */
    public function delete_room($id) 
    {   
        $request->validate([
            'id_reservation_room' => 'required|numeric',
        ]);

        $reservation_room = ReservationRoom::findOrFail($id);
        
        DB::beginTransaction();
        if ($reservation_room->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Room has been deleted successfully',
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }
}
