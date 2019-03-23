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
        if ($request->limit != null) {
            $limit = (Int) $request->limit;
        }
        
        $rates = Rate::paginate($limit);
        return RateResource::collection($rates);   
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        return view('rate::show');
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
    }
}
