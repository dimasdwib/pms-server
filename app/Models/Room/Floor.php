<?php

namespace App\Models\Room;

use App\Models\BaseModel;

class Floor extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_floor';
    protected $table = 'floors';

}