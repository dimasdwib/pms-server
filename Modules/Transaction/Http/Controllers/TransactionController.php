<?php

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Transaction\RoomCharge;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\Bill;
use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('transaction::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('transaction::create');
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
    public function show()
    {
        return view('transaction::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('transaction::edit');
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
    public function destroy()
    {
    }

    /**
     * Posting room charge
     */
    public function post_room_charge(Request $request, $id_room_charge)
    { 

        $request->validate([
            'id_bill' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $room_charge = RoomCharge::findOrFail($id_room_charge);
        if ($room_charge->postToBill($request->id_bill)) {
            DB::commit();
            return response()->json([
                'message' => 'Room charge posted succesfully',
                'room_charge' => $room_charge,
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }

    /**
     * add payment
     */
    public function add_payment(Request $request, $id_bill)
    { 

        $request->validate([
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $transaction = new Transaction;
        $transaction->id_bill = $id_bill;
        $transaction->date = date('Y-m-d H:i:s');
        $transaction->amount_nett = $request->amount;
        $transaction->type = 'cr'; // credit
        $transaction->description = 'Payment - '.date('Y-m-d'); // credit

        if ($transaction->save()) {
            DB::commit();
            return response()->json([
                'message' => 'Payment has been added succesfully',
                'payment' => $transaction,
            ]);
        }

        DB::rollBack();
        return $this->response->errorInternal();
    }
}
