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
 *
 * Test User Roles Index for Specified User:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check response data
 *
 * Test User Roles Index for Specified User That Is Absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check response data
 *
 * Test User Roles Index for Specified User When User-Roles Are Absent:
 *    Check login (user #1)
 *    Get index
 *       Check response status
 *       Check response structure
 *       Check response data
 *
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

class UserControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *    Create 3 users
     *    Create 3 roles
     *    Create 2 roles (1,2) for user #1
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
     *       Check response data
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
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('[1,2]', $data[0]['role_ids']);
        $success = $responseJSON['success'];
        $message = $responseJSON['message'];
        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Data is formed successfully.');
    }


//    /**
//     * Test User Roles Index Full when user-roles are absent:
//     *    Check login (user #1)
//     *    Get index
//     *       Check response status
//     *       Check response structure
//     *       Check data
//     */
//    public function testUserRolesIndexFullWhenUserRolesAreAbsent()
//    {
//        // Check login
//        $response = $this->post('api/auth/login', [
//            'email'    => 'test1@email.com',
//            'password' => '123456'
//        ]);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $token        = $responseJSON['token'];
//
//        $this->get('api/auth/me?token=' . $token, [])->assertJson([
//            'name'  => 'Test',
//            'email' => 'test1@email.com'
//        ])->isOk();
//
//        $this->delete('api/user-roles/1?token=' . $token, []);
//        $this->delete('api/user-roles/2?token=' . $token, []);
//        $response = $this->get('api/user-roles/full?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(422);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//        $this->assertEquals(false, $success);
//        $this->assertEquals('User-Roles do not exist.', $message);
//    }

    /**
     * Test User Roles Index:
     *    Check login (user #1)
     *    Get index
     *       Check response status
     *       Check response structure
     *       Check if get 2 roles for user
     *       Check role #2
     */
    public function testUserRolesIndex()
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

        $response = $this->get('api/user-roles?token=' . $token, []);

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
                            'user_id',
                            'role_id',
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
        $this->assertEquals($data[1]['user_id'], 1);
        $this->assertEquals($data[1]['role_id'], 2);
    }

    /**
     * Test Create Roles for a new User:
     *    Check login (user #1)
     *    Create role #1 for user #2
     *       Check response status
     *       Check response structure
     *       Check role #1
     */
    public function testCreateRolesForNewUser()
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

        // Store a new roles for User
        $data     = [
            "user_id"  => 2,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];
        $response = $this->post('api/user-roles/2?token=' . $token, $data);

        // Check response status
        $response->assertStatus(200);

//        print_r($response);
//        exit();


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
        $this->assertEquals($message, 'New Roles for User created successfully.');

        // Check DB
        $user_roles = DB::table('user_roles')->where('user_id', 2)->get();
        $this->assertEquals(2, $user_roles[1]->role_id);
        $this->assertEquals(2, $user_roles[1]->user_id);
    }

    /**
     * Test Create Roles for a User, that has roles:
     *    Create role for user #1
     *       Check response status
     *       Check response structure
     */
    public function testCreateRolesForUserThatHasRoles()
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

        // Store a new roles for User
        $data = [
            "user_id"  => 1,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->post('api/user-roles/1?token=' . $token, $data);

        // Check response status
        $response->assertStatus(406);

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
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Creating is impossible. User has roles already.');
    }

    /**
     * Test Create Roles for absent User:
     *    Create role #1 for user #3
     *       Check response status
     *       Check response structure
     */
    public function testCreateRolesForAbsentUser()
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

        // Store a new role for User
        $data = [
            "user_id"  => 33,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->post('api/user-roles/33?token=' . $token, $data);

        // Check response status
        $response->assertStatus(422);

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
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Creating is impossible. User does not exist.');
    }

    /**
     * Test Edit Roles:
     *    Check login (user #1)
     *    Update roles for user #1 (1,2 =>2,3)
     *       Check response status
     *       Check response structure
     *       Check if get 2 roles for user
     *       Check role #1
     *       Check role #2
     *       Check role #3
     */
    public function testEditRoles()
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

        // Update roles for User
        $data = [
            "user_id"  => 1,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->put('api/user-roles/1?token=' . $token, $data);

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
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, true);
        $this->assertEquals($message, 'Roles for User are updated successfully.');
    }

    /**
     * Test Edit Roles for a User, that has not roles:
     *    Update 2 roles for user #2
     *       Check response status
     *       Check response structure
     */
    public function testEditRolesForUserThatHasNotRoles()
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

        // Update roles for User
        $data = [
            "user_id"  => 3,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->put('api/user-roles/3?token=' . $token, $data);

        // Check response status
        $response->assertStatus(406);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Updating is impossible. User does not have roles yet.');

    }

    /**
     * Test Edit Roles for absent User:
     *    Update 2 roles for user #3
     *       Check response status
     *       Check response structure
     */
    public function testEditRolesForAbsentUser()
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

        // Update roles for User
        $data = [
            "user_id"  => 33,
            "role_ids" => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->put('api/user-roles/33?token=' . $token, $data);

        // Check response status
        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'Updating is impossible. User does not exist.');

    }

    /**
     * Test Delete Roles
     *    Delete Roles for user #1
     *       Check response status
     *       Check response structure
     *       Check DB user-roles
     */
    public function testDeleteRoles()
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

        $response = $this->delete('api/user-roles/1?token=' . $token, []);

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
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, true);
        $this->assertEquals($message, "User Roles are deleted successfully.");

        $roles = DB::table("user_roles")->where('user_id', 1)->get();
        $this->assertEquals(0, $roles->count(), "Roles are not deleted.");
    }

    /**
     * Test Delete Roles for a User, that has not roles:
     *    Delete roles for user #2
     *       Check response status
     *       Check response structure
     */
    public function testDeleteRolesForUserThatDoesNotHaveRoles()
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

        $response = $this->delete('api/user-roles/3?token=' . $token, []);

        // Check response status
        $response->assertStatus(406);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'It is impossible to delete Roles. User does not have roles.');
    }

    /**
     * Test Delete Roles for absent User:
     *    Delete roles for user #3
     *       Check response status
     *       Check response structure
     */
    public function testDeleteRolesForAbsentUser()
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

        $response = $this->delete('api/user-roles/33?token=' . $token, []);

        // Check response status
        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $this->assertEquals($success, false);
        $this->assertEquals($message, 'It is impossible to delete Roles. User does not exist.');
    }

//    /**
//     * Test User Roles Index when they are absent:
//     *    Check login (user #1)
//     *    Get index
//     *       Check response status
//     *       Check response structure:
//     */
//    public function testUserRolesIndexWhenTheyAreAbsent()
//    {
//        // Check login
//        $response = $this->post('api/auth/login', [
//            'email'    => 'test1@email.com',
//            'password' => '123456'
//        ]);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $token        = $responseJSON['token'];
//
//        $this->get('api/auth/me?token=' . $token, [])->assertJson([
//            'name'  => 'Test',
//            'email' => 'test1@email.com'
//        ])->isOk();
//
//        // Deleting of all user_roles
//        $this->delete('api/user-roles/1?token=' . $token, []);
//        $this->delete('api/user-roles/2?token=' . $token, []);
//
//        $response = $this->get('api/user-roles?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(404);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//
//        $success = $responseJSON['success'];  // array
//        $message = $responseJSON['message'];  // array
//
//        $this->assertEquals($success, false);
//        $this->assertEquals($message, "User-roles not found.");
//    }

    /** Test User Roles Index for Specified User:
     *    Check login (user #1)
     *    Get index
     *       Check response status
     *       Check response structure
     *       Check response data
     */
    public function testUserRolesIndexForSpecifiedUser()
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

        // Request
        $response = $this->get('api/user-roles/1?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        [
                            'role_id',
                            'name'
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(count($data), 2);
        $this->assertEquals('1', $data[0]['role_id']);
        $this->assertEquals('superadmin', $data[0]['name']);
        $this->assertEquals('2', $data[1]['role_id']);
        $this->assertEquals('developer', $data[1]['name']);
        $this->assertEquals(true, $success);
        $this->assertEquals('User-Roles retrieved successfully', $message);
    }

    /** Test User Roles Index for Specified User That Is Absent:
     *    Check login (user #1)
     *    Get index
     *       Check response status
     *       Check response structure
     *       Check response data
     */
    public function testUserRolesIndexForSpecifiedUserThatIsAbsent()
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

        // Request
        $response = $this->get('api/user-roles/33?token=' . $token, []);

        // Check response status
        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success         = $responseJSON['success'];  // array
        $message         = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals("User does not exist.", $message);
    }

    /** Test User Roles Index for Specified User When User-Roles Are Absent:
     *    Check login (user #1)
     *    Get index
     *       Check response status
     *       Check response structure
     *       Check response data
     */
    public function testUserRolesIndexForSpecifiedUserWhenUserRolesAreAbsent()
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

        // Request
        $response = $this->get('api/user-roles/33?token=' . $token, []);

        // Check response status
        $response->assertStatus(422);

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
        $this->assertEquals("User does not exist.", $message);
    }
}
