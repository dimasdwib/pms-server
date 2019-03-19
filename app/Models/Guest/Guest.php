<?php

namespace App\Models\Guest;

use App\Models\BaseModel;

class Guest extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_guest';
    protected $table = 'guests';
}