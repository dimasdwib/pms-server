<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Acl\PermissionGroup;
use App\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'read-users']); // 1
        Permission::create(['name' => 'create-users']); // 2
        Permission::create(['name' => 'update-users']); // 3
        Permission::create(['name' => 'delete-users']); // 4

        Permission::create(['name' => 'test-permission']); // 5

        // assign to group
        // 1 -> Users CRUD
        PermissionGroup::find(1)->assignPermission(1);
        PermissionGroup::find(1)->assignPermission(2);
        PermissionGroup::find(1)->assignPermission(3);
        PermissionGroup::find(1)->assignPermission(4);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo(['read-users']);

        // Assign to user
        $admin = User::where('username', 'admin')->first();
        $user = User::where('username', 'user')->first();
        $admin->assignRole('superadmin');
        $user->assignRole('user');

    }
}
