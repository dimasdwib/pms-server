<?php

namespace Modules\Bed\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Bed\Bed;
use App\Models\Bed\BedResource;
use DB;

class BedController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $limit = null;
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }
        
        $beds = Bed::paginate($limit);
        return BedResource::collection($beds);
    }

    /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return Bed::all();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:10',
        ]);

        $bed = new Bed;
        $bed->code = $request->code;
        $bed->name = $request->name;
        $bed->description = $request->description;

        DB::beginTransaction();
        if ($bed->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Bed has been created successfully',
                'bed' => new BedResource($bed),
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
        return new BedResource(Bed::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:50',
        ]);

        $bed = Bed::findOrFail($id);
        $bed->code = $request->code;
        $bed->name = $request->name;
        $bed->description = $request->description;

        DB::beginTransaction();
        if ($bed->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Bed has been updated successfully',
                'bed' => new BedResource($bed),
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
        $bed = Bed::findOrFail($id);
        if ($bed->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Bed has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
