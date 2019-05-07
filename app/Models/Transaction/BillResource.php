<?php

namespace App\Models\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
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
        return [
            'id_bill' => $this->id_bill,
            'guest' => $this->guest,
            'number' => $this->number,
            'cr_total_nett' => $cr_total_nett,
            'dr_total_nett' => $dr_total_nett,
            'balance' => $balance,
            'status' => $this->status,
            'transactions' => $transactions,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
