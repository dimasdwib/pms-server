<?php

namespace App\Models\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportFolioHistoryResource extends JsonResource
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
            'reservation_number' => $this->reservation->number(),
            'id_reservation' => $this->reservation->id_reservation,
            'id_folio' => $this->id_reservation_bill,
            'folio_number' => $this->number(),
            'created_at' => (String) $this->created_at,
            'guest' => $this->reservation->booker,
            'status' => $this->status,
            'revenue' => $this->bill->getTotal('room_charge'),
        ];
    }
}
