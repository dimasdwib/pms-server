<?php

namespace App\Models\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class InHouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $guests = $this->reservation_room_guests;
        return [
            'id_reservation_room' => $this->id_reservation_room,
            'number' => $this->reservation->number(),
            'id_reservation' => $this->id_reservation,
            'guest' => $guests[0]->guest,
            'room' => $this->room,
            'date_arrival' => $this->date_arrival,
            'date_departure' => $this->date_departure,
            'date_checkin' => $this->date_checkin,
            'date_checkout' => $this->date_checkout,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}