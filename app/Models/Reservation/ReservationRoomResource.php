<?php

namespace App\Models\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Transaction\RoomCharge;

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
        $guests = $this->reservation_room_guests;
        foreach($guests as $guest) {
            $guest->guest;
        }
        $main_room_guest = $guests[0];
        return [
            'id_reservation_room' => $this->id_reservation_room,
            'room' => $this->room,
            'guests' => $guests,
            'rate' => [
                'charges' => RoomCharge::where('id_reservation_room_guest', $main_room_guest->id_reservation_room_guest)
                             ->get()
            ], 
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
