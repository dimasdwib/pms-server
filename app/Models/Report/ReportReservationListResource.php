<?php

namespace App\Models\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportReservationListResource extends JsonResource
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
            'number' => $this->number(), 
            'id_reservation' => $this->id_reservation, 
            'booker' => $this->booker,
            'status' => $this->status,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
