<?php

namespace Modules\Guest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Guest\Guest;
use App\Models\Guest\GuestResource;
use DB;

class GuestController extends Controller
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
        
        $guests = Guest::paginate($limit);
        return GuestResource::collection($guests);
    }

    /**
     * Display all resource
     * @return Response
     */
    public function all() {
        return Guest::all();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $guest = new Guest;
        $guest->name = $request->name;
        $guest->title = $request->title;
        $guest->email = $request->email;
        $guest->address = $request->address;
        $guest->zipcode = $request->zipcode;
        $guest->id_country = $request->id_country;
        $guest->id_state = $request->id_state;
        $guest->id_city = $request->id_city;
        $guest->phone = $request->phone;
        $guest->idcard = $request->idcard;

        DB::beginTransaction();
        if ($guest->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Guest has been created successfully',
                'guest' => new GuestResource($guest),
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
        return new GuestResource(Guest::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $guest = Guest::findOrFail($id);
        $guest->name = $request->name;
        $guest->title = $request->title;
        $guest->email = $request->email;
        $guest->address = $request->address;
        $guest->zipcode = $request->zipcode;
        $guest->id_country = $request->id_country;
        $guest->id_state = $request->id_state;
        $guest->id_city = $request->id_city;
        $guest->phone = $request->phone;
        $guest->idcard = $request->idcard;

        DB::beginTransaction();
        if ($guest->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Guest has been updated successfully',
                'guest' => new GuestResource($guest),
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
        $guest = Guest::findOrFail($id);
        if ($guest->delete()) {
            DB::commit();
            return response()->json([
                'message' => 'Guest has been deleted successfully',
            ]);
        }
        DB::rollBack();
        return $this->response->errorInternal();
    }
}
