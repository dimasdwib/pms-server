<?php

namespace App\Models\Rate;

use App\Models\BaseModel;

class Rate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_rate';
    protected $table = 'rates';

    public function room_type() {
        return $this->hasOne('App\Models\RoomType\RoomType', 'id_room_type', 'id_room_type'); 
    }

    /**
     * Scope
     * Rate for reservation
     * Get available rate for reservation
     * @param $query  eloquent instance
     * @param $arrival 
     * @param $departure
     * 
     */
    public function scopeForReservation($query, $arrival, $departure)
    {
        $rates = $query->get();
        $night = date_diff(date_create($arrival), date_create($departure))->format('%a');

        foreach($rates as $key => $rate) {
            $charges = [];
            for ($day = 0; $day < $night; $day++) {
                $date = date('Y-m-d', strtotime($arrival.' +'.$day.' days'));
                $charges[] = [
                    'date' => $date,
                    'amount_nett' => $rate->amount_nett,
                ];
            }
            $rates[$key]->charges = $charges;
            $rates[$key]->date_arrival = $arrival;
            $rates[$key]->date_departure = $departure;
            $rates[$key]->night = $night;
        }
        return $rates;
    }
}