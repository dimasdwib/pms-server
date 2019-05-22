<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionGroupSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(GuestSeeder::class);
        $this->call(RoomTypeSeeder::class);
        $this->call(BedSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(RateSeeder::class);
        $this->call(TransactionCategoriesSeeder::class);
        $this->call(ReservationSeeder::class);
    }
}
