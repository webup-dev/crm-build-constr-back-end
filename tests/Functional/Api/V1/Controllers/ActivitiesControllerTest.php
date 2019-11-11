<?php

/**
 * SetUp:
 *   Create 2 user
 *   Create 2 roles
 *   Bind users and roles (1-superadmin, 2-guest)
 *   Create 2 activities
 *
 * Check Index:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If User Can Not Permission:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Delete:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Delete If User Can Not Permission:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * tearDown:
 *   Delete user-roles
 *   Delete users
 *   Delete roles
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Activity;
use App\Models\Role;
use App\WnyTestCase;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActivitiesControllerTest extends WnyTestCase
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

        $user1 = new User([
            'name'     => 'Test 1',
            'email'    => 'test1@email.com',
            'password' => '123456'
        ]);

        $user1->save();

        $user2 = new User([
            'name'     => 'Test 2',
            'email'    => 'test2@email.com',
            'password' => '123456'
        ]);

        $user2->save();

        $role1 = new Role([
            'name'     => 'developer'
        ]);

        $role1->save();

        $role2 = new Role([
            'name'     => 'customer-organization'
        ]);

        $role2->save();

        $user1->roles()->attach(1);
        $user1->roles()->attach(2);

        $data1           = [];
        $data1['uri']    = 'test/test';
        $data1['method'] = 'GET';

        $data1     = json_encode($data1);
//        print_r($data1);
//        exit();
        $activity1 = new Activity([
            'user_id' => 1,
            'req'     => $data1
        ]);
        $activity1->save();

        $data2           = [];
        $data2['uri']    = 'test/test';
        $data2['method'] = 'POST';

        $data2     = json_encode($data2);
        $activity2 = new Activity([
            'user_id' => 2,
            'req'     => $data2
        ]);
        $activity2->save();
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
     * Check Index:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndex()
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
            'name'  => 'Test 1',
            'email' => 'test1@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/activities?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);
//        print_r($response);
        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $req[0]=json_decode($data[0]['req']);
        $req[1]=json_decode($data[1]['req']);
//        print_r($req[0]);

        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(1, $data[0]['user']['id']);
        $this->assertEquals('Test 1', $data[0]['user']['name']);
        $this->assertEquals('test/test', $req[0]->uri);
        $this->assertEquals('GET', $req[0]->method);
        $this->assertEquals(2, $data[1]['id']);
        $this->assertEquals(2, $data[1]['user']['id']);
        $this->assertEquals('Test 2', $data[1]['user']['name']);
        $this->assertEquals('test/test', $req[1]->uri);
        $this->assertEquals('POST', $req[1]->method);
        $this->assertEquals(true, $success);
        $this->assertEquals("Activities are retrieved successfully.", $message);
    }

    /**
     * Check Index If User Can Not Permission:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfUserCanNotPermission()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test2@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 2',
            'email' => 'test2@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/activities?token=' . $token, []);

        // Check response status
        $response->assertStatus(401);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals('Permission is absent by the role.', $message);
    }

    /**
     * Check Delete:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
        public function testDelete()
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
            'name'  => 'Test 1',
            'email' => 'test1@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/activities?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Activities are deleted successfully.", $message);

        $activities = Activity::whereIn('id', [1, 2])->get();
        $this->assertEquals(0, $activities->count());
    }

    /**
     * Check Delete If User Can Not Permission:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfUserCanNotPermission()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test2@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 2',
            'email' => 'test2@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/activities?token=' . $token, []);

        $response->assertStatus(401);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);

        $activities = Activity::whereIn('id', [1, 2])->get();
        $this->assertEquals(2, $activities->count());
    }
}
