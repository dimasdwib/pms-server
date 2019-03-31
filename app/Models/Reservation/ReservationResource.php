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
        return [
            'id_reservation' => $this->id_reservation,
            'number' => '000000'.$this->id_reservation,
            'booker' => $this->booker,
            'rooms' => $this->reservation_rooms,
            'note' => $this->note,
            'status' => $this->status,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
