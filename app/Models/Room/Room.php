<?php

namespace App\Models\Room;

use App\Models\BaseModel;

class Room extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_room';
    protected $table = 'rooms';

    public function floor()
    {
        return $this->hasOne('App\Models\Room\Floor', 'id_floor', 'id_floor');
    }

    public function bed()
    {
        return $this->hasOne('App\Models\Bed\Bed', 'id_bed', 'id_bed');
    }

    public function room_type()
    {
        return $this->hasOne('App\Models\RoomType\RoomType', 'id_room_type', 'id_room_type');
    }

    public function scopeForReservation($query, $arrival, $departure)
    {
        $rooms = $query
                ->where('fo_status', '=', 'vacant')
                ->where('hk_status', '<>', 'out_of_order')
                ->get();
        
        return $rooms;
    }

    public function setToOccupied()
    {
        $this->fo_status = 'occupied';
        return $this->save();
    }

    public function setToVacant()
    {
        $this->fo_status = 'vacant';
        return $this->save();
    }

    public function setToDirty()
    {
        $this->hk_status = 'dirty';
        return $this->save();
    }

    public function setToClean()
    {
        $this->hk_status = 'clean';
        return $this->save();
    }
}