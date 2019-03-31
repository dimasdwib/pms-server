<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class ReservationBill extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservation_bill';
    protected $table = 'reservation_bills';
    protected $fillable = [
        'id_reservation',
        'id_bill',
    ];

    public function reservation() 
    {
        return $this->hasOne('App\Models\Reservation\Reservation', 'id_reservation', 'id_reservation');        
    }

    public function bill()
    {
        return $this->hasOne('App\Models\Transaction\Bill', 'id_bill', 'id_bill');
    }

    public function number() {
        return '0000'.$this->id_reservation_bill;
    }
}