<?php

use Illuminate\Database\Seeder;
use App\Models\Room\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 1;
        $room->id_floor = null;
        $room->number = '101';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 1;
        $room->id_floor = null;
        $room->number = '102';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 2;
        $room->id_bed = 2;
        $room->id_floor = null;
        $room->number = '103';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 3;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '104';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '105';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '106';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '107';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '108';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '109';
        $room->phone = null;
        $room->description = null;
        $room->save();

        $room = new Room;
        $room->id_room_type = 1;
        $room->id_bed = 3;
        $room->id_floor = null;
        $room->number = '110';
        $room->phone = null;
        $room->description = null;
        $room->save();

    }
}
