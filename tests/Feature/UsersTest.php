<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{

    private $credential = [
        'username' => 'admin',
        'password' => 'secret',
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test user login
     *
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/users/login', $this->credential);
        $response->assertStatus(200)
                 ->assertJson([
                     'token' => true,
                    ]);
    }

    /**
     * Test user login failed
     *
     */
    public function testUserLoginFailed()
    {
        $credential = [
            'username' => 'the wrong username',
            'password' => 'the wrong password',
        ];
        $response = $this->json('POST', '/api/users/login', $credential);
        $response->assertStatus(401)
                 ->assertJson([
                     'error' => true,
                    ]);
    }

    /**
     * Test user resource listing
     * @return void
     */
    public function testUsersListing()
    {
        $user = User::where('username', '=', 'admin')
                        ->first();
        $this->apiAs($user)
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson([
                'data' => true,
                'current_page' => true,
            ]);
    }

    /**
     * Test users CRUD
     * @return void
     */
    public function testUsersCRUD()
    {
        $user = User::where('username', '=', 'admin')
                        ->first();

        $data_store = [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'email@gmail.com',
            'password' => 'secret',
            'permission' => '',
            'role' => 'user',
        ];

        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/users', $data_store)
                        ->assertStatus(200)
                        ->assertJson(['user' => true, 'message' => true])
                        ->decodeResponseJson();

        // check database
        $this->assertDatabaseHas('users', [
            'name' => $data_store['name'],
            'email' => $data_store['email'],
            'username' => $data_store['username'],
        ]);

        $data_update = [
            'name' => 'Test User Update',
            'username' => 'testuser',
            'email' => 'emailupdate@gmail.com',
            'password' => 'secretos',
            'permission' => '',
            'role' => 'user',
        ];
        // update
        $id = $create['user']['id'];
        $update = $this->apiAs($user)
                       ->json('PUT', '/api/users/'.$id, $data_update)
                       ->assertStatus(200)
                       ->assertJson(['user' => true, 'message' => true]);

        // check database
        $this->assertDatabaseHas('users', [
            'name' => $data_update['name'],
            'email' => $data_update['email'],
            'username' => $data_update['username'],
        ]);

        // delete
        $delete = $this->apiAs($user)
                       ->JSON('DELETE', '/api/users/'.$id)
                       ->assertStatus(200)
                       ->assertJson(['message' => true]);

        // check database
        $this->assertDatabaseMissing('users', [
            'name' => $data_store['name'],
            'email' => $data_store['email'],
            'username' => $data_store['username'],
        ]);
    }

    /**
     * Test cant delete superadmin & current user
     * @return void
     */
    public function testCantDeleteSelfAndSuperAdmin()
    {
        $user = User::where('username', '=', 'admin')
                        ->first();
        $this->apiAs($user)
                ->JSON('DELETE', '/api/users/1')
                ->assertStatus(500)
                ->assertJson(['message' => true]);

        $other_user = User::where('username', '!=', 'admin')
                            ->first();
        $this->apiAs($other_user)
                    ->JSON('DELETE', '/api/users/'.$other_user->id)
                    ->assertStatus(500)
                    ->assertJson(['message' => true]);

    }
}
