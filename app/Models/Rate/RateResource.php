<?php

namespace App\Models\Rate;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'id_rate' => $this->id_rate,
            'room_type' => $this->room_type,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'amount_nett' => $this->amount_nett,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
