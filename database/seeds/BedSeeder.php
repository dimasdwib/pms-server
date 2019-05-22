<?php

use Illuminate\Database\Seeder;
use App\Models\Bed\Bed;

class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bed = new Bed;
        $bed->code = 'SGL';
        $bed->name = 'Single Bed';
        $bed->description = 'Single Bed Small Size';
        $bed->save();

        $bed = new Bed;
        $bed->code = 'TWN';
        $bed->name = 'Twin Bed';
        $bed->description = 'Twin Bed Small Size';
        $bed->save();

        $bed = new Bed;
        $bed->code = 'DBL';
        $bed->name = 'Double Bed';
        $bed->description = 'Double Bed King Size';
        $bed->save();

    }
}
