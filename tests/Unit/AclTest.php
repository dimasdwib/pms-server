<?php

namespace Tests\Unit;

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

    public function testDeleteAdminRoleValidation() {
        // define user
        $user = $this->admin;
        // delete
        $create = $this->apiAs($user)
                        ->json('DELETE', '/api/role/1')
                        ->assertStatus(500)
                        ->assertJson(['message' => true]);
    }

    public function testCreateRoleValidation() {
        // define user
        $user = $this->admin;
        $data_store = [
            'name' => '', // empty role name
            'permissions' => '',
            'users' => '',
        ];
        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/role', $data_store)
                        ->assertStatus(500)
                        ->assertJson(['message' => true]);
    }

    public function testCreatePermissionValidation() {
        // define user
        $user = $this->admin;
        $data_store = [
            'name' => '', // empty permission name
            'roles' => '',
            'users' => '',
        ];
        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/permission', $data_store)
                        ->assertStatus(500)
                        ->assertJson(['message' => true]);
    }

}
