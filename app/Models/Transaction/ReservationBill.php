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

    public function number()
    {
        $base = '000000';
        $len = strlen($this->id_reservation_bill);
        return substr($base, 0, strlen($base) - $len).$this->id_reservation_bill;
    }

    public function closeBill() 
    {
        $this->status = 'closed';
        return $this->save();
    }
}