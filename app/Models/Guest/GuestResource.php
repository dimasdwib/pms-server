<?php

namespace App\Models\Guest;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class GuestResource extends JsonResource
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
            'id' => $this->id_guest,
            'name' => $this->name,
            'title' => $this->title,
            'email' => $this->email,
            'address' => $this->address,
            'zipcode' => $this->zipcode,
            'id_country' => $this->id_country,
            'id_state' => $this->id_state,
            'id_city' => $this->id_city,
            'phone' => $this->phone,
            'idcard' => $this->idcard,
            'created_at' => (String) $this->created_at,
            'updated_at' => (String) $this->updated_at,
        ];
    }
}
