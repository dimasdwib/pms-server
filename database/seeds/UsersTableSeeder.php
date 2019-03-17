<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // superadmin
        DB::table('users')->insert([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('secret'),
        ]);
        
        // default user
        DB::table('users')->insert([
            'name' => 'The User',
            'username' => 'user',
            'email' => 'user@admin.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
