<?php

namespace App\Models\Reservation;

use App\Models\BaseModel;

class ReservationGuest extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservation_room_guest';
    protected $table = 'reservation_room_guests';
    protected $fillable = ['id_guest', 'id_reservation_room', 'date_arrival', 'date_departure', 'date_checkin', 'date_checkout'];

    public function reservation_room() {
      return $this->belongsTo('App\Models\Reservation\ReservationRoom', 'id_reservation_room', 'id_reservation_room');
    }
}