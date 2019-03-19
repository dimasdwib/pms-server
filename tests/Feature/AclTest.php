<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AclTest extends TestCase
{

    private $admin;

    public function setUp() {
        parent::setUp();
        $this->admin = User::where('username', '=', 'admin')
                          ->first();
    }

    /**
     * Test Permission Listing
     * @return void
     */
    public function testPermissionsListing() {
      $this->apiAs($this->admin)
            ->get('/api/permission')
            ->assertStatus(200)
            ->assertJson([
                'data' => true,
                'current_page' => true,
            ]);
    }

    /**
     * Test Role Listing
     * @return void
     */
    public function testRolesListing() {
      $this->apiAs($this->admin)
            ->get('/api/role')
            ->assertStatus(200)
            ->assertJson([
                'data' => true,
                'current_page' => true,
            ]);
    }

    /**
     * Test permission CRUD
     */
    public function testPermissionCRUD() {

        // define user
        $user = $this->admin;

        $data_store = [
            'name' => 'Test-Permission-1234',
            'roles' => 'user', // 'user, administrator'
            'users' => '1,2', // '1,2,3'
        ];

        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/permission', $data_store)
                        ->assertStatus(200)
                        ->assertJson(['permission' => true, 'message' => true])
                        ->decodeResponseJson();

        // check database
        $this->assertDatabaseHas('permissions', [
            'name' => $data_store['name'],
        ]);

        $data_update = [
            'name' => 'Test-Permission-Edit-1234',
            'roles' => 'superadmin,user', // 'write-user, read-user'
            'users' => '2', // '1,2,3'
        ];
        // update
        $id = $create['permission']['id'];
        $update = $this->apiAs($user)
                       ->json('PUT', '/api/permission/'.$id, $data_update)
                       ->assertStatus(200)
                       ->assertJson(['permission' => true, 'message' => true]);

        // check database
        $this->assertDatabaseHas('permissions', [
            'name' => $data_update['name'],
        ]);

        // delete
        $delete = $this->apiAs($user)
                       ->JSON('DELETE', '/api/permission/'.$id)
                       ->assertStatus(200)
                       ->assertJson(['message' => true]);

         // check database
        $this->assertDatabaseMissing('permissions', [
            'name' => $data_update['name'],
        ]);
    }

    /**
     * Test Role CRUD
     * @return void
     */
    public function testRolesCRUD() {

        // define user
        $user = $this->admin;

        $data_store = [
            'name' => 'Test Role 1234',
            'permissions' => 'read-users,update-users,delete-users', // 'write-user, read-user'
            'users' => '1,2', // '1,2,3'
        ];

        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/role', $data_store)
                        ->assertStatus(200)
                        ->assertJson(['role' => true, 'message' => true])
                        ->decodeResponseJson();

        // check database
        $this->assertDatabaseHas('roles', [
            'name' => $data_store['name'],
        ]);

        $data_update = [
            'name' => 'Test Role Edit 1234',
            'permissions' => 'read-users,delete-users', // 'write-user, read-user'
            'users' => '2', // '1,2,3'
        ];
        // update
        $id = $create['role']['id'];
        $update = $this->apiAs($user)
                       ->json('PUT', '/api/role/'.$id, $data_update)
                       ->assertStatus(200)
                       ->assertJson(['role' => true, 'message' => true]);

        // check database
        $this->assertDatabaseHas('roles', [
            'name' => $data_update['name'],
        ]);

        // delete
        $delete = $this->apiAs($user)
                       ->JSON('DELETE', '/api/role/'.$id)
                       ->assertStatus(200)
                       ->assertJson(['message' => true]);

         // check database
        $this->assertDatabaseMissing('roles', [
            'name' => $data_update['name'],
        ]);
    }


    /**
     * Test user auth attribute
     * 
     */
    public function testUserAuthAttribute() {
        $this->apiAs($this->admin)
        ->get('/api/users/auth_attribute')
        ->assertStatus(200)
        ->assertJson([
            'user' => true,
        ]);
    }

    /**
     * Test permission group CRUD
     * 
     */
    public function testPermissionGroupCRUD() {

        // define user
        $user = $this->admin;

        $data_store = [
            'group' => 'Test Group 1234',
            'id_parent' => null, // '1,2,3'
        ];

        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/permission/group', $data_store)
                        ->assertStatus(200)
                        ->assertJson(['group' => true, 'message' => true])
                        ->decodeResponseJson();

        // check database
        $this->assertDatabaseHas('permission_group', [
            'group' => $data_store['group'],
            'id_parent' => $data_store['id_parent'],
        ]);

        $data_update = [
            'group' => 'Test Group Update 1234',
            'id_parent' => 2, // '1,2,3'
        ];
        // update
        $id = $create['group']['id'];
        $update = $this->apiAs($user)
                       ->json('PUT', '/api/permission/group/'.$id, $data_update)
                       ->assertStatus(200)
                       ->assertJson(['group' => true, 'message' => true]);

        // check database
        $this->assertDatabaseHas('permission_group', [
            'group' => $data_update['group'],
            'id_parent' => $data_update['id_parent'],
        ]);

        // delete
        $delete = $this->apiAs($user)
                       ->JSON('DELETE', '/api/permission/group/'.$id)
                       ->assertStatus(200)
                       ->assertJson(['message' => true]);

         // check database
        $this->assertDatabaseMissing('permission_group', [
            'group' => $data_update['group'],
        ]);
    }

}
