<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class Bill extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_bill';
    protected $table = 'bills';
    protected $fillable = [
        'id_guest',
    ];

    public function guest() {
      return $this->hasOne('App\Models\Guest\Guest', 'id_guest', 'id_guest');
    }

    public function setNumber($number) {
      $this->number = $number;
    }

    public function transactions()
    {
      return $this->hasMany('App\Models\Transaction\Transaction', 'id_bill', 'id_bill');
    }
}