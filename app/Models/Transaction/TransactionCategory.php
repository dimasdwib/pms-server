<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class TransactionCategory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_transaction_category';
    protected $table = 'transaction_categories';

    public function transaction()
    {
        return $this->hasMany('App\Models\Transaction\Transaction', 'id_transaction_category', 'id_transaction_category');
    }
}