<?php

namespace Modules\Floor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Room\Floor;
use App\Models\Room\FloorResource;
use DB;

class FloorController extends Controller
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
        
        $floors = Floor::orderBy('order', 'asc')
                        ->searchByModel($search, [
                            'this' => ['name', 'description'],
                        ])
                        ->paginate($limit);
        return FloorResource::collection($floors);
    }

        /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return FloorResource::collection(Floor::all());
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order' => 'numeric',
            'name' => 'required|max:20',
        ]);

        $floor = new Floor;
        $floor->order = $request->order;
        $floor->name = $request->name;
        $floor->description = $request->description;

        DB::beginTransaction();
        if ($floor->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Floor has been created successfully',
                'floor' => new FloorResource($floor),
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
        return new FloorResource(Floor::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order' => 'numeric',
            'name' => 'required|max:20',
        ]);

        $floor = Floor::findOrFail($id);
        $floor->order = $request->order;
        $floor->name = $request->name;
        $floor->description = $request->description;

        DB::beginTransaction();
        if ($floor->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Floor has been updated successfully',
                'floor' => new FloorResource($floor),
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
        $floor = Floor::findOrFail($id);
        if ($floor->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Floor has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
