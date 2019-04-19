<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Room;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rooms = Room::all();
        
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

        return response()->json([
            'room_occupied_total' => $room_occupied,
            'room_dirty_total' => $room_dirty,
            'room_vacant_dirty_total' => $room_vacant_dirty,
            'room_total' => $room_total,
        ]);
    }
}
