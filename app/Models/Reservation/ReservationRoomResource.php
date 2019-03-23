<?php

namespace App\Models\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationRoomResource extends JsonResource
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
            'id' => $this->id_reservation_room,
            'room' => $this->room,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
