<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestTest extends TestCase
{

    private $credential = [
        'username' => 'admin',
        'password' => 'secret',
    ];

    /**
     * Test guest resource listing
     * @return void
     */
    public function testGuestListing()
    {
        $user = User::where('username', '=', 'admin')
                        ->first();
        $this->apiAs($user)
            ->get('/api/guest')
            ->assertStatus(200)
            ->assertJson([
                'current_page' => true,
                'total' => true,
            ]);
    }

    /**
     * Test guest CRUD
     * @return void
     */
    public function testGuestCRUD()
    {
        $user = User::where('username', '=', 'admin')
                        ->first();

        $data_store = [
            'name' => 'Test Guest '.date('His'),
        ];

        // create
        $create = $this->apiAs($user)
                        ->json('POST', '/api/guest', $data_store)
                        ->assertStatus(200)
                        ->assertJson(['guest' => true, 'message' => true])
                        ->decodeResponseJson();

        // check database
        $this->assertDatabaseHas('guests', [
            'name' => $data_store['name'],
        ]);

        $data_update = [
            'name' => 'Test User Update'.date('His'),
        ];
        // update
        $id = $create['guest']['id'];
        $update = $this->apiAs($user)
                       ->json('PUT', '/api/guest/'.$id, $data_update)
                       ->assertStatus(200)
                       ->assertJson(['guest' => true, 'message' => true]);

        // check database
        $this->assertDatabaseHas('guests', [
            'name' => $data_update['name'],
        ]);

        // delete
        $delete = $this->apiAs($user)
                       ->JSON('DELETE', '/api/guest/'.$id)
                       ->assertStatus(200)
                       ->assertJson(['message' => true]);

        // check database
        $this->assertDatabaseMissing('guests', [
            'name' => $data_store['name'],
        ]);
    }

}
