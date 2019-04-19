<?php

namespace App\Models\Reservation;

use App\Models\BaseModel;

class ReservationRoom extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservation_room';
    protected $table = 'reservation_rooms';
    protected $fillable = ['id_reservation', 'id_room'];

    public function room() {
        return $this->hasOne('App\Models\Room\Room', 'id_room', 'id_room');
    }

    public function reservation_room_guests() {
        return $this->hasMany('App\Models\Reservation\ReservationGuest', 'id_reservation_room', 'id_reservation_room');
    }

    public function scopeInHouse($query)
    {   
        return $query->join('reservation_room_guests', 'reservation_room_guests.id_reservation_room', '=', 'reservation_rooms.id_reservation_room')
                     ->whereNotNull('date_checkin')
                     ->whereNull('date_checkout');
    }

    public function reservation() {
        return $this->belongsTo('App\Models\Reservation\Reservation', 'id_reservation', 'id_reservation');
    }
}