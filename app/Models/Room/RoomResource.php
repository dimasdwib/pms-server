<?php

namespace App\Models\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'id_room' => $this->id_room,
            'room_type' => $this->room_type,
            'bed' => $this->bed,
            'id_bed' => $this->id_bed,
            'id_room_type' => $this->id_room_type,
            'floor' => $this->floor,
            'number' => $this->number,
            'phone' => $this->phone,
            'description' => $this->description,
            'dont_disturb' => $this->dont_disturb,
            'hk_status' => $this->hk_status,
            'fo_status' => $this->fo_status,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
