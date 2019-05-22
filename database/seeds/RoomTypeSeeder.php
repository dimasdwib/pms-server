<?php

use Illuminate\Database\Seeder;
use App\Models\RoomType\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_type = new RoomType;
        $room_type->order = 0;
        $room_type->code = 'STD';
        $room_type->name = 'Standart';
        $room_type->size = '20';
        $room_type->description = 'Standart Room';
        $room_type->max_adult = 2;
        $room_type->max_child = 2;
        $room_type->images = null;
        $room_type->save();

        $room_type = new RoomType;
        $room_type->order = 1;
        $room_type->code = 'DLX';
        $room_type->name = 'Deluxe';
        $room_type->size = '25';
        $room_type->description = 'Deluxe Room';
        $room_type->max_adult = 2;
        $room_type->max_child = 2;
        $room_type->images = null;
        $room_type->save();

        $room_type = new RoomType;
        $room_type->order = 2;
        $room_type->code = 'EXC';
        $room_type->name = 'Executive';
        $room_type->size = '30';
        $room_type->description = 'Executive Room';
        $room_type->max_adult = 2;
        $room_type->max_child = 2;
        $room_type->images = null;
        $room_type->save();

    }
}
