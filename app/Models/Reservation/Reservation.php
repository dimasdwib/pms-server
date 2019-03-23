<?php

namespace App\Models\Reservation;

use App\Models\BaseModel;

class Reservation extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservation';
    protected $table = 'reservations';

    public function booker() {
        return $this->hasOne('App\Models\Guest\Guest', 'id_guest', 'id_booker');
    }

    public function reservation_rooms() {
        return $this->hasMany('App\Models\Reservation\ReservationRoom', 'id_reservation', 'id_reservation');
    }
}