<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Transaction\Bill;
use App\Models\Transaction\Transaction;

class RoomCharge extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_room_charge';
    protected $table = 'room_charges';
    protected $fillable = [
        'id_reservation_room_guest',
        'id_rate',
        'id_transaction',
        'amount_nett',
        'status',
        'date',
    ];

    public function rate()
    {
        return $this->hasOne('App\Models\Rate\Rate', 'id_rate', 'id_rate');
    }

    public function reservation_room_guest()
    {
        return $this->belongsTo('App\Models\Reservation\ReservationGuest', 'id_reservation_room_guest', 'id_reservation_room_guest');
    }

    /**
     * Post to reservation billing
     * @param $id_bill
     * 
     */
    public function postToBill($id_bill)
    {
        $rate = $this->rate;
        $room = $this->reservation_room_guest->reservation_room->room;
        $bill = Bill::findOrFail($id_bill);
        
        $transaction = new Transaction;
        $transaction->id_bill = $id_bill;
        $transaction->date = date('Y-m-d H:i:s');
        $transaction->amount_nett = $this->amount_nett;
        $transaction->id_transaction_category = 1; // room charge
        $transaction->description = $room->number.' - '.$rate->code.' - '.$this->date;
        $transaction->save();

        // update room charge status
        $this->status = 'charged';
        $this->id_transaction = $transaction->id_transaction;

        return $this->save();
    }
}