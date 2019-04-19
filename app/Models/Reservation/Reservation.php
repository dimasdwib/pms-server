<?php

namespace App\Models\Reservation;

use App\Models\BaseModel;
use App\Models\Room\Room;
use App\Models\Reservation\ReservationRoom;
use App\Models\Reservation\ReservationGuest;
use App\Models\Transaction\BillResource;

class Reservation extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_reservation';
    protected $table = 'reservations';

    public function booker()
    {
        return $this->hasOne('App\Models\Guest\Guest', 'id_guest', 'id_booker');
    }

    public function reservation_rooms()
    {
        return $this->hasMany('App\Models\Reservation\ReservationRoom', 'id_reservation', 'id_reservation');
    }

    public function reservation_bills()
    {
        return $this->hasMany('App\Models\Transaction\ReservationBill', 'id_reservation', 'id_reservation');
    }

    public function setToDefinite()
    {
        $this->status = 'definite';
        return $this->save();
    }

    public function number() 
    {
        $base = '000000';
        $len = strlen($this->id_reservation);
        return substr($base, 0, strlen($base) - $len).$this->id_reservation;
    }

    public function detail()
    {   

        $reservation_rooms = $this->reservation_rooms;
        $rooms = [];
        foreach($reservation_rooms as $key_room => $room) {
            $room_guests = $room->reservation_room_guests;
            foreach($room_guests as $key_guest => $room_guest) {
                $room_guest->guest;
            }
            $data = $room->room;
            $data['id_reservation_room'] = $room->id_reservation_room;
            $data['guests'] = $room_guests;
            $rooms[] = $data;
        }

        $reservation_bills = $this->reservation_bills;
        $bills = [];
        foreach($reservation_bills as $key_bill => $bill) {
            $bill_data = $bill->bill;
            $bill_data->setNumber($bill->number());
            $data = new BillResource($bill_data);
            $bills[] = $data;
        }

        return [
            'id_reservation' => $this->id_reservation,
            'booker' => $this->booker,
            'number' => $this->number(),
            'status' => $this->status,
            'note' => $this->note,
            'adult' => $this->adult,
            'child' => $this->child,
            'rooms' => $rooms,
            'bills' => $bills,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}