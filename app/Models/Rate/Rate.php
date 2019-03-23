<?php

namespace App\Models\Rate;

use App\Models\BaseModel;

class Rate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_rate';
    protected $table = 'rates';

    public function room_type() {
        return $this->hasOne('App\Models\RoomType\RoomType', 'id_room_type', 'id_room_type'); 
    }
}