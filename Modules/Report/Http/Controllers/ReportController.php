<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Report\ReportReservationListResource;
use App\Models\Report\ReportArrivalListResource;
use App\Models\Report\ReportDepartureListResource;
use App\Models\Report\ReportGuestInHouseResource;
use App\Models\Report\ReportRoomStatusResource;
use App\Models\Report\ReportFolioHistoryResource;
use App\Models\Reservation\Reservation;
use App\Models\Room\Room;
use App\Models\Reservation\ReservationGuest;
use App\Models\Transaction\ReservationBill;
use DB;

class ReportController extends Controller
{
   
    public function reservation_list(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $reservation = Reservation::whereDate('created_at', '>=', $from)
                                    ->whereDate('created_at', '<=', $to)
                                    ->orderBy('id_reservation', 'desc')
                                    ->get();
        return ReportReservationListResource::collection($reservation);
    }

    public function arrival_list()
    {
        $now = date('Y-m-d');
        $arrival_list = ReservationGuest::limit(20)
                        ->where('date_arrival', $now)
                        ->orderBy('id_reservation_room_guest', 'desc')
                        ->get();

        return ReportArrivalListResource::collection($arrival_list);
    }

    public function departure_list()
    {
        $now = date('Y-m-d');
        $departure_list = ReservationGuest::limit(20)
                        ->where('date_departure', $now)
                        ->orderBy('id_reservation_room_guest', 'desc')
                        ->get();

        return ReportDepartureListResource::collection($departure_list);
    }

    public function guest_in_house()
    {
        $now = date('Y-m-d');
        $guest_in_house = ReservationGuest::limit(20)
                            ->whereNotNull('date_checkin')
                            ->whereNull('date_checkout')
                            ->orderBy('id_reservation_room_guest', 'desc')
                            ->get();

        return ReportGuestInHouseResource::collection($guest_in_house);
    }

    public function room_status()
    {
        $room = Room::all();
        return ReportRoomStatusResource::collection($room);
    }

    public function folio_history(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $reservation_bill = ReservationBill::orderBy('id_reservation_bill', 'desc')
                                            ->whereDate('created_at', '>=', $from)
                                            ->whereDate('created_at', '<=', $to)
                                            ->get();
        $total = 0;
        foreach($reservation_bill as $folio) {
            $total += $folio->bill->getTotal('room_charge');
        }
        return [
            'folios' => ReportFolioHistoryResource::collection($reservation_bill),
            'total' => $total,
        ];
    }

}
