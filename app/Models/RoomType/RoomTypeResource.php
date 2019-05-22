<?php

namespace App\Models\RoomType;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
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
            'id_room_type' => $this->id_room_type,
            'order' => $this->order,
            'code' => $this->code,
            'name' => $this->name,
            'size' => $this->size,
            'description' => $this->description,
            'max_adult' => $this->max_adult,
            'max_child' => $this->max_child,
            'images' => $this->images,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
