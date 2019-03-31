<?php

use Illuminate\Database\Seeder;
use App\Models\Guest\Guest;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guest = new Guest;
        $guest->name = 'Jhon Doe';
        $guest->email = 'JhonDoe@gmail.com';
        $guest->address = 'Jl. Sesama';
        $guest->title = 'mr';
        $guest->phone = '082221234711';
        $guest->save();

        $guest = new Guest;
        $guest->name = 'Jane Doe';
        $guest->title = 'ms';
        $guest->email = 'Janedoe@gmail.com';
        $guest->address = 'Jl. Sesama II';
        $guest->phone = '082221234222';
        $guest->save();

        $guest = new Guest;
        $guest->name = 'James Bourne';
        $guest->title = 'mr';
        $guest->email = 'jbourne@gmail.com';
        $guest->address = 'Jl. Tiga Sesama';
        $guest->phone = '081121989006';
        $guest->save();

        $guest = new Guest;
        $guest->name = 'James Casey';
        $guest->title = 'mr';
        $guest->email = 'jamescaset@gmail.com';
        $guest->address = 'Jl. Empat Sesama';
        $guest->phone = '081121987706';
        $guest->save();

    }
}
