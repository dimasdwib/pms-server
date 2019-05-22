<?php

namespace App\Models\RoomType;

use App\Models\BaseModel;

class RoomType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_room_type';
    protected $table = 'room_types';
}