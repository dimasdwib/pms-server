<?php

namespace Modules\Reservation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationRoom;
use App\Models\Reservation\ReservationGuest;
use App\Models\Reservation\ReservationResource;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = null;
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }
        
        $reservations = Reservation::paginate($limit);
        return ReservationResource::collection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate([
            'id_booker' => 'required|numeric',
            'adult' => 'numeric',
            'child' => 'numeric',
            'date_arrival' =>'required|date',
            'date_departure' =>'required|date',
        ]);

        $reservation = new Reservation;
        $reservation->id_booker = $request->id_booker;
        $reservation->status = $request->status;
        $reservation->note = $request->note;
        $reservation->adult = $request->adult;
        $reservation->child = $request->child;
        
        DB::beginTransaction();

        $reservation_rooms = [];
        foreach($request->rooms as $room) {
            $reservasion_room = new ReservationRoom(['id_room' => $room['id']]);

            $room_guests = [];
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id_guest'],
                    'date_arrival' => $request->date_arrival,
                    'date_departure' => $request->date_departure,
                ]);
                $room_guests[] = $room_guest;
            }

            $reservation_room->reservation_room_guests()->saveMany($room_guests);
            $reservation_rooms[] = $reservasion_room;
        }

        $reservation->reservation_rooms()->saveMany($reservation_rooms);

        if ($reservation->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Reservation has been created successfully',
                'bed' => new ReservationResource($bed),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('reservation::show');
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
    public function destroy()
    {
    }
}
