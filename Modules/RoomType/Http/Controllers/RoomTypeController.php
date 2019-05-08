<?php

namespace Modules\RoomType\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\RoomType\RoomType;
use App\Models\RoomType\RoomTypeResource;
use DB;


class RoomTypeController extends Controller
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

        $room_types = RoomType::paginate($limit);
        return response()->json($room_types);
    }

    /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return RoomType::all();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|max:10',
            'name' => 'required|max:100',
            'size' => 'numeric',
            'max_adult' => 'numeric',
            'max_child' => 'numeric',
        ]);

        $room_type = new RoomType;
        $room_type->order = $request->order == null ? 0 : $request->order;
        $room_type->code = $request->code;
        $room_type->name = $request->name;
        $room_type->size = $request->size;
        $room_type->description = $request->description;
        $room_type->max_adult = $request->max_adult;
        $room_type->max_child = $request->max_child;
        $room_type->images = $request->images;

        DB::beginTransaction();
        if ($room_type->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Room type has been created successfully',
                'room_type' => new RoomTypeResource($room_type),
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
        return new RoomTypeResource(RoomType::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:100',
            'name' => 'required|max:100',
            'size' => 'numeric',
            'max_adult' => 'numeric',
            'max_child' => 'numeric',
        ]);

        $room_type = RoomType::findOrFail($id);
        $room_type->order = $request->order == null ? 0 : $request->order;
        $room_type->code = $request->code;
        $room_type->name = $request->name;
        $room_type->size = $request->size;
        $room_type->description = $request->description;
        $room_type->max_adult = $request->max_adult;
        $room_type->max_child = $request->max_child;
        $room_type->images = $request->images;

        DB::beginTransaction();
        if ($room_type->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Room type has been created successfully',
                'room_type' => new RoomTypeResource($room_type),
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
        $room_type = RoomType::findOrFail($id);
        if ($room_type->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Room type has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
