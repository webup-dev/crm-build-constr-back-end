<?php

/**
 * SetUp:
 * Create user
 * Create 2 roles
 *
 * Check Index:
 * Check login
 * Check response status
 * Check response structure
 * Check if get 2 roles
 *
 * Check store:
 * Check login
 * Store a new role
 * Check response status
 * Check response structure
 * Check response data
 * Get DB table Roles and check last role with controller
 *
 * Show:
 * Check login
 * Get roles from DB roles
 *   Check the count of roles (2)
 * Get last ID from DB roles
 * Check getting role with id = ID+1 by controller:
 *   Check response status
 *   Check response structure
 *   Check response data
 * Get role with id = ID with controller
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update:
 * Check login
 * Get lastRole from DB
 * Update the role
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete:
 * Check login
 * Get lastRole from DB
 * Delete not existing role
 *   Check response status
 *   Check response structure
 *   Check response data
 * Delete the last role
 *   Check response status
 *   Check response structure
 *   Check response data
 * Check deleting in DB
 *
 * tearDown:
 * Delete one last (id) role
 * Delete user
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Book;
use App\Models\Role;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class RolesControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create a new user
     *
     * @return mixed
     */
    public function setUp()
    : void
    {
        parent::setUp();

        $user = new User([
            'name'     => 'Test',
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $user->save();

        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     */
    public function testIndex()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test@email.com'
        ])->isOk();

        $response = $this->get('api/roles?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        [
                            'id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(count($data), 2);
    }

    public function testStore()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test@email.com'
        ])->isOk();

        // Store a new role
        $response = $this->post('api/role?token=' . $token, [
            'name'        => 'Role 3 Test Store',
            'description' => 'Description 3'
        ], []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'New Role created successfully.');

        // Check DB
        $role = DB::table('roles')->where('name', 'Role 3 Test Store')->first();
        $this->assertGreaterThanOrEqual(1, $role->id);
        $this->assertEquals('Role 3 Test Store', $role->name);
    }

    public function testShow()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test@email.com'
        ])->isOk();

        // Get roles from DB roles
        $roles = DB::table('roles')->get();

        // Check the count of roles (2)
        $this->assertEquals(count($roles), 2);
        $lastRole   = $roles->last();
        $lastRoleId = $lastRole->id;

        // Check fail getting role with id = ID+1 by controller:
        $response = $this->get('api/roles/' . ($lastRoleId + 1) . '?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);

        // Check getting role with id = ID by controller:
        $response = $this->get('api/roles/' . $lastRoleId . '?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'description',
                        'created_at',
                        'updated_at'
                    ],
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Role retrieved successfully.');
    }

    public function testUpdate()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test@email.com'
        ])->isOk();

        // Get roles from DB roles
        $roles      = DB::table('roles')->get();
        $lastRole   = $roles->last();
        $lastRoleId = $lastRole->id;

        $response = $this->put('api/roles/' . $lastRoleId . '?token=' . $token, [
            "name" => "Test Role Updated"
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Role is updated successfully.");
        $this->assertEquals($data->name, "Test Role Updated");
    }

    public function testDelete()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test@email.com'
        ])->isOk();

        // Get roles from DB roles
        $roles      = DB::table('roles')->get();
        $lastRole   = $roles->last();
        $lastRoleId = $lastRole->id;
        $failRoleId = $lastRoleId + 1;

        // Delete not existing role
        $response = $this->delete('api/roles/' . $failRoleId . '?token=' . $token, []);

        $response->assertStatus(204);


        // Delete the last role
        $response = $this->delete('api/roles/' . $lastRoleId . '?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Role is deleted successfully.");
    }
}
