<?php

namespace App\Models\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportRoomStatusResource extends JsonResource
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
          'number' => $this->number,
          'bed' => $this->bed->name,
          'room_type' => $this->room_type->name,
          'fo_status' => $this->fo_status,
          'hk_status' => $this->hk_status,
        ];
    }
}
