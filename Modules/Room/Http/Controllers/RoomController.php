<?php

namespace Modules\Room\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Room\RoomResource;
use App\Models\RoomType\RoomType;
use App\Models\RoomType\RoomTypeResource;
use App\Models\Rate\Rate;
use App\Models\Rate\RateResource;
use App\Models\Bed\Bed;
use App\Models\Bed\BedResource;
use DB;


class RoomController extends Controller
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
        
        $rooms = Room::paginate($limit);
        return RoomResource::collection($rooms);
    }

    /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return Room::all();
    }

    /**
     * Display room and available
     * @return Response JSON
     */
    public function available(Request $request) {

        $request->validate([
            'date_arrival' => 'required',
            'date_departure' => 'required'
        ]);

        $date_arrival = $request->date_arrival;
        $date_departure = $request->date_departure;

        return response()->json([
            'room_types' => RoomType::all(),
            'beds' => Bed::all(),
            'rooms' => Room::all(),
            'rates' => Rate::forReservation($date_arrival, $date_departure),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|max:100',
            'id_room_type' => 'required|numeric',
            'id_bed' => 'required|numeric',
        ]);

        $room = new Room;
        $room->id_room_type = $request->id_room_type;
        $room->id_bed = $request->id_bed;
        $room->id_floor = $request->id_floor;
        $room->number = $request->number;
        $room->phone = $request->phone;
        $room->description = $request->description;
        
        DB::beginTransaction();
        if ($room->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Room has been created successfully',
                'room' => new RoomResource($room),
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
        return new RoomResource(Room::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'required|max:100',
            'id_room_type' => 'required|numeric',
            'id_bed' => 'required|numeric',
        ]);

        $room = Room::findOrFail($id);
        $room->id_room_type = $request->id_room_type;
        $room->id_bed = $request->id_bed;
        $room->id_floor = $request->id_floor;
        $room->number = $request->number;
        $room->phone = $request->phone;
        $room->description = $request->description;
        
        DB::beginTransaction();
        if ($room->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Room has been updated successfully',
                'room' => new RoomResource($room),
            ]);
        }

        DB::rollBack();
        $this->response->errorInternal();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $room = Guest::findOrFail($id);
        if ($room->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Room has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
