<?php

use Illuminate\Database\Seeder;
use App\Models\Rate\Rate;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $rate = new Rate;
        $rate->code = 'STDRO';
        $rate->id_room_type = 1; // std room
        $rate->name = 'Standard Room Only';
        $rate->description = 'Standard room only rate';
        $rate->amount_nett = 200000;
        $rate->save();

        $rate = new Rate;
        $rate->code = 'DLXRO';
        $rate->id_room_type = 2; // dlx room
        $rate->name = 'Deluxe Room Only';
        $rate->description = 'Deluxe room only rate';
        $rate->amount_nett = 250000;
        $rate->save();

        $rate = new Rate;
        $rate->code = 'EXCRO';
        $rate->id_room_type = 3; // exc room
        $rate->name = 'Executive Room Only';
        $rate->description = 'Executive room only rate';
        $rate->amount_nett = 300000;
        $rate->save();

        $rate = new Rate;
        $rate->code = 'EXCRB';
        $rate->id_room_type = 3; // exc room
        $rate->name = 'Executive Room Breakfast';
        $rate->description = 'Executive room with breakfast rate';
        $rate->amount_nett = 320000;
        $rate->save();
    }
}
