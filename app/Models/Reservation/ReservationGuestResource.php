<?php

namespace App\Models\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationGuestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_reservation_room_guest' => $this->id_reservation_room_guest,
            'guest' => $this->guest,
            'date_arrival' => $this->date_arrival,
            'date_departure' => $this->date_departure,
            'date_checkin' => $this->date_checkin,
            'date_checkout' => $this->date_checkout,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
