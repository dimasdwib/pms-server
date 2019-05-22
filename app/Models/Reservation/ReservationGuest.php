<?php

namespace App\Models\Reservation;

use App\Models\BaseModel;
use App\Models\Room\Room;

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

    public function reservation_room()
    {
        return $this->belongsTo('App\Models\Reservation\ReservationRoom', 'id_reservation_room', 'id_reservation_room');
    }

    public function guest()
    {
        return $this->hasOne('App\Models\Guest\Guest', 'id_guest', 'id_guest');
    }

    public function checkin()
    {
        $this->date_checkin = date('Y-m-d H:i:s');
        
        $id_room = $this->reservation_room->id_room;
        $id_reservation = $this->reservation_room->id_reservation;

        $room = Room::findOrFail($id_room);
        // set room to occupied
        $room->setToOccupied();
        
        // set room to dirty
        $room->setToDirty();

        // set reservation to definite
        $reservation = Reservation::findOrFail($id_reservation);
        $reservation->setToDefinite(); 

        return $this->save();
    }

    public function checkout()
    {
        $this->date_checkout = date('Y-m-d H:i:s');

        $id_room = $this->reservation_room->id_room;
        
        // set room to vacant
        Room::findOrFail($id_room)->setToVacant();

        return $this->save();
    }
}