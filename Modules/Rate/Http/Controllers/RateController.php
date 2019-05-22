<?php

namespace Modules\Rate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Rate\Rate;
use App\Models\Rate\RateResource;
use DB;

class RateController extends Controller
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
        
        $rates = Rate::searchByModel($search, [
                        'this' => ['name', 'code', 'description', 'amount_nett'],
                        'room_type' => ['name', 'code'],
                    ])
                    ->paginate($limit);
        return RateResource::collection($rates);   
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
            'id_room_type' => 'required',
            'amount_nett' => 'required|numeric',
        ]);

        $rate = new Rate;
        $rate->code = $request->code;
        $rate->name = $request->name;
        $rate->description = $request->description;
        $rate->id_room_type = $request->id_room_type;
        $rate->amount_nett = $request->amount_nett;

        DB::beginTransaction();
        if ($rate->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Rate has been created successfully',
                'bed' => new RateResource($rate),
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
        return new RateResource(Rate::findOrFail($id));
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:10',
            'name' => 'required|max:100',
            'id_room_type' => 'required',
            'amount_nett' => 'required|numeric',
        ]);

        $rate = Rate::findOrFail($id);
        $rate->code = $request->code;
        $rate->name = $request->name;
        $rate->description = $request->description;
        $rate->id_room_type = $request->id_room_type;
        $rate->amount_nett = $request->amount_nett;

        DB::beginTransaction();
        if ($rate->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Rate has been updated successfully',
                'bed' => new RateResource($rate),
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
        $rate = Rate::findOrFail($id);
        if ($rate->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Rate has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
