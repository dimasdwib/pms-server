<?php

namespace App\Models\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportGuestInHouseResource extends JsonResource
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
            'number' => $this->reservation_room->reservation->number(),
            'id_reservation' => $this->reservation_room->reservation->id_reservation,
            'id_reservation_room' => $this->reservation_room->id_reservation_room,
            'room' => $this->reservation_room->room->number,
            'guest' => $this->guest,
            'checkin' => $this->date_checkin,
            'arrival' => $this->date_arrival,
            'departure' => $this->date_departure,
        ];
    }
}
