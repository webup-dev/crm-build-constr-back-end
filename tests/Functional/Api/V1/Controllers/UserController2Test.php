<?php

/**
 * SetUp:
 *    Create 2 users
 *    Create 3 roles
 *    Create 2 roles for user #1
 *
 * Test User Roles Index Full:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check role #2
 *
 * Test User Roles Index Full when users are absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check data
 *
 * Test User Roles Index Full when roles are absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check data
 *
 * Test User Roles Index Full when user-roles are absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check data
 *
 * Test User Roles Index:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check if get 2 roles for user
 *       Check role #2
 *
 * Test User Roles Index when they are absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *
 * Test Create Roles for a new User:
 *    Check login (user #1)
 *    Create role #1 for user #2
 *       Check response status
 *       Check response structure
 *       Check role #1
 *
 * Test Create Roles for a User, that has roles:
 *    Create role #3 for user #1
 *       Check response status
 *       Check response structure
 *
 * Test Create Roles for absent User:
 *    Create role #1 for user #3
 *       Check response status
 *       Check response structure
 *
 * Test Edit Roles:
 *    Check login (user #1)
 *    Get roles of user #1
 *    Update role #2 for user #2
 *       Check response status
 *       Check response structure
 *       Check if get 2 roles for user
 *       Check role #1
 *       Check role #2
 *
 * Test Edit Roles for a User, that has not roles:
 *    Update 2 roles for user #2
 *       Check response status
 *       Check response structure
 *
 * Test Edit Roles for absent User:
 *    Update 2 roles for user #3
 *       Check response status
 *       Check response structure
 *
 * Test Delete Roles
 *    Delete Roles for user #1
 *       Check response status
 *       Check response structure
 *       Check DB user-roles
 *
 * Test Delete Roles for a User, that has not roles:
 *    Delete roles for user #2
 *       Check response status
 *       Check response structure
 *
 * Test Delete Roles for absent User:
 *    Delete roles for user #3
 *       Check response status
 *       Check response structure
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Role;
use App\WnyTestCase;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController2Test extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *    Create 2 users
     *    Create 2 roles
     *    Create 2 roles for user #1
     */
    public function setUp()
    : void
    {
        parent::setUp();

        $user1 = new User([
            'name'     => 'Test',
            'email'    => 'test1@email.com',
            'password' => '123456'
        ]);

        $user1->save();

        $user2 = new User([
            'name'     => 'Test2',
            'email'    => 'test2@email.com',
            'password' => '123456'
        ]);

        $user2->save();

        $user3 = new User([
            'name'     => 'Test3',
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $user3->save();

        $role1 = new Role([
            'name'        => 'superadmin',
            'description' => 'Description 1'
        ]);

        $role1->save();

        $role2 = new Role([
            'name'        => 'developer',
            'description' => 'Description 2'
        ]);

        $role2->save();

        $role3 = new Role([
            'name'        => 'platform-superadmin',
            'description' => 'Description 3'
        ]);

        $role3->save();

        $user1->roles()->attach($role1->id);
        $user1->roles()->attach($role2->id);
        $user2->roles()->attach($role3->id);
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

    public function testMe()
    {
        $response = $this->post('api/auth/login', [
            'email'    => 'test1@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test1@email.com'
        ])->isOk();
    }

    /**
     * Test User Roles Index Full:
     *    Check login (user #1)
     *    Get index
     *       Check response status
     *       Check response structure
     *       Check number of users
     *       Check user_id #1
     *       Check role_ids of user #1
     */
    public function testUserRolesIndexFull()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test1@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test',
            'email' => 'test1@email.com'
        ])->isOk();

        $response = $this->get('api/user-roles/full?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' => [],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(count($data), 3);
        $this->assertEquals(2, $data[1]['id']);
        $this->assertEquals('[1,2]', $data[0]['role_ids']);
        $success = $responseJSON['success'];
        $message = $responseJSON['message'];
        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Data is formed successfully.');
    }
}
