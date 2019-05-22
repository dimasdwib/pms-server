<?php

namespace App\Models\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $reservation_rooms = $this->reservation_rooms;
        $rooms = [];
        $total_checkin = 0;
        $total_checkout = 0;
        $total_room = 0;
        foreach($reservation_rooms as $i => $room) {
            $guests = $room->reservation_room_guests;
            $rooms[$i] = $room;
            $rooms[$i]['guests'] = $guests;
            
            if ($guests[0]->date_checkin) {
                $total_checkin += 1;
            }

            if ($guests[0]->date_checkout) {
                $total_checkout += 1;
            }

            $total_room += 1;
        }

        return [
            'id_reservation' => $this->id_reservation,
            'number' => $this->number(),
            'booker' => $this->booker,
            'rooms' => $rooms,
            'note' => $this->note,
            'total_room' => $total_room,
            'total_checkin' => $total_checkin,
            'total_checkout' => $total_checkout,
            'status' => $this->status,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
