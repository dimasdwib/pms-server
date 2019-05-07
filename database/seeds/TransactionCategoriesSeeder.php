<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction\TransactionCategory;

class TransactionCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $transaction_categories = [
            [
                'id_transaction_category' => 1,
                'code' => 'room_charge',
                'name' => 'Room Charge',
                'is_default' => 1,
                'pos' => 'dr',
            ],
            [
                'id_transaction_category' => 2,
                'code' => 'deposit',
                'name' => 'Deposit',
                'is_default' => 1,
                'pos' => 'cr',
            ],
            [
                'id_transaction_category' => 3,
                'code' => 'payment',
                'name' => 'Payment',
                'is_default' => 1,
                'pos' => 'cr',
            ],
            [
                'id_transaction_category' => 4,
                'code' => 'refund',
                'name' => 'Refund',
                'is_default' => 1,
                'pos' => 'cr',
            ],
        ];

        foreach($transaction_categories as $val) {
           TransactionCategory::create($val);
        }        
    }
}
