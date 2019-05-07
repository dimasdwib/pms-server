<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Transaction\RoomCharge;
use App\Models\Reservation\ReservationGuest;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $now = date('Y-m-d');
        $last_7days = date('Y-m-d', strtotime($now. '-7 days'));
        $rooms = Room::all();
        $reservation_guest = ReservationGuest::where('date_arrival', '>=', $now)
                                            ->get();
        $room_charges = RoomCharge::select('date', DB::raw('SUM(amount_nett) as total_amount_nett'))
                                ->where('date', '<=', $now)
                                ->where('date', '>', $last_7days)
                                ->where('status', 'charged')
                                ->groupBy('date')
                                ->get()
                                ->pluck('total_amount_nett', 'date');

        $room_occupancy = ReservationGuest::where('date_checkin', '>=', $last_7days)
                                        ->whereNotNull('date_checkout')
                                        ->get();
        $last_week_occupancy_chart = [];                        
        $last_week_revenue_chart = [];
        for ($day = 1; $day <= 7; $day++) {
            $date = date('Y-m-d', strtotime($last_7days. '+'.$day.' days'));
            if (isset($last_week_revenue_chart[$date])) {
                if ($room_charges[$date] != null) {
                    $last_week_revenue_chart[$date]['revenue'] += $room_charges[$date]; 
                }
            } else {
                $last_week_revenue_chart[$date] = [
                    'date' => $date,
                    'revenue' => isset($room_charges[$date]) ? $room_charges[$date] : 0,
                ];
            }

            foreach($room_occupancy as $occ) {
                if (date('Y-m-d', strtotime($occ->date_checkin)) <= $date && date('Y-m-d', strtotime($occ->date_checkout)) >= $date) {
                    if (isset($last_week_occupancy_chart[$date])) {
                        $last_week_occupancy_chart[$date]['occ'] += 1;
                    } else {
                        $last_week_occupancy_chart[$date] = [
                            'date' => $date,
                            'occ' => 1,
                        ];
                    }
                } else {
                    if (!isset($last_week_occupancy_chart[$date])) {
                        $last_week_occupancy_chart[$date] = [
                            'date' => $date,
                            'occ' => 0,
                        ];
                    }
                }
            }
        }

        $room_total = 0;
        $room_occupied = 0;
        $room_dirty = 0;
        $room_vacant_dirty = 0;
        if ($rooms->count() > 0) {
            foreach($rooms as $room) {                
                if ($room->hk_status == 'dirty') {
                    $room_dirty += 1;
                }
                if ($room->fo_status == 'occupied') {
                    $room_occupied += 1;
                }
                if ($room->fo_status == 'vacant' && $room->hk_status == 'dirty') {
                    $room_vacant_dirty += 1;
                }
                $room_total += 1;
            }
        }

        $expected_arrival_total = 0;
        $expected_departure_total = 0;
        if ($reservation_guest->count() > 0) {
            foreach($reservation_guest as $guest) {
                if ($guest->date_arrival == date('Y-m-d') && $guest->date_checkin == null) {
                    $expected_arrival_total += 1;
                }
                if ($guest->date_departure == date('Y-m-d') && $guest->date_checkout == null) {
                    $expected_departure_total += 1;
                }
            }
        }

        return response()->json([
            'room_occupied_total' => $room_occupied,
            'room_dirty_total' => $room_dirty,
            'room_vacant_dirty_total' => $room_vacant_dirty,
            'room_total' => $room_total,
            'expected_arrival_total' => $expected_arrival_total,
            'expected_departure_total' => $expected_departure_total,
            'last_week_revenue_chart' => array_values($last_week_revenue_chart),
            'last_week_occupancy_chart' => array_values($last_week_occupancy_chart),
        ]);
    }
}
