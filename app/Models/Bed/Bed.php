<?php

namespace App\Models\Bed;

use App\Models\BaseModel;

class Bed extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_bed';
    protected $table = 'beds';
}