<?php

use Illuminate\Database\Seeder;
use App\Models\Acl\PermissionGroup;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $permissionGroup = new PermissionGroup;
        $permissionGroup->group = 'User CRUD';
        $permissionGroup->id_parent = null;
        $permissionGroup->save();

        $permissionGroup = new PermissionGroup;
        $permissionGroup->group = 'Test Child Group';
        $permissionGroup->id_parent = 1;
        $permissionGroup->save();

        $permissionGroup = new PermissionGroup;
        $permissionGroup->group = 'Test Group';
        $permissionGroup->id_parent = null;
        $permissionGroup->save();

    }
}
