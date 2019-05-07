<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class Transaction extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_transaction';
    protected $table = 'transactions';
    protected $fillable = [
        'id_bill',
        'date',
        'amount_nett',
        'description',
    ];

    public function bill()
    {
        return $this->belongsTo('App\Models\Transaction\Bill', 'id_bill', 'id_bill');
    }

    public function transaction_category()
    {
        return $this->belongsTo('App\Models\Transaction\TransactionCategory', 'id_transaction_category', 'id_transaction_category');
    }

    public function getDrAmountNettAttribute()
    {
        if ($this->transaction_category->pos == 'dr') {
            return $this->amount_nett;
        }
        return 0;
    }

    public function getCrAmountNettAttribute()
    {
        if ($this->transaction_category->pos == 'cr') {
            return $this->amount_nett;
        }
        return 0;
    }
}