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

    public function setStatus($status) {
        $this->status = $status;
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction\Transaction', 'id_bill', 'id_bill');
    }

    public function getBalance()
    {
        $transactions = $this->transactions;
        $dr_total_nett = 0;
        $cr_total_nett = 0;
        $balance = 0;
        foreach($transactions as $key => $transaction) {
            $transactions[$key]->dr_amount_nett = $transaction->dr_amount_nett;
            $transactions[$key]->cr_amount_nett = $transaction->cr_amount_nett;
            $transactions[$key]->pos = $transaction->transaction_category->pos;
            if ($transaction->transaction_category->pos == 'dr') {
                $dr_total_nett += $transaction->dr_amount_nett; 
                $balance += $transaction->dr_amount_nett;
            } else {
                $cr_total_nett += $transaction->cr_amount_nett;
                $balance -= $transaction->cr_amount_nett; 
            }
        }
      
        return $balance;
    }

    public function getTotal($code)
    {
        $transactions = $this->transactions;
        $total = 0;
        foreach($transactions as $key => $transaction) {
            if ($transaction->transaction_category->code == $code) {
              $total += $transaction->dr_amount_nett;
            }
        }
      
        return $total;
    }
}