<?php

/**
 * SetUp:
 *   Create 1 user
 *   Create 3 roles
 *   Create 2 methods
 *   Create 2 method-roles
 *
 * Check Index Of Roles For Specified Method:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index Of Roles For Specified Method If Method Id Is Wrong:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index Of Roles For Specified Method If Roles Are Absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store If Method Roles Is Single:
 *   Check login
 *   Store a new method-roles (array of 1 item)
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check last method-role in DB
 *
 * Check store If Method Roles Are Multiple:
 *   Check login
 *   Store a new method-roles (array of 2 item)
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check new method-roles in DB
 *
 * Check store If Method ID Is Wrong:
 *   Check login
 *   Store a new action-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store If One Of The Roles ID Is Wrong:
 *   Check login
 *   Store a new action-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update:
 *   Check login
 *   Update the method-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Method ID is Wrong:
 *   Check login
 *   Update the method-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update One Of The Roles ID Is Wrong:
 *   Check login
 *   Update the method-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Delete:
 *   Check login
 *   Delete method-role
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check DB
 *
 * Check Delete If Method-Role ID is Wrong:
 *   Check login
 *   Delete action-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Delete Roles Of The Specified Method:
 *   Check login
 *   Delete method-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Delete Roles Of The Specified Method If Method Id Is Wrong:
 *   Check login
 *   Delete method-roles
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * tearDown:
 *   Delete method_roles
 *   Delete roles
 *   Delete methods
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Action_role;
use App\Models\Method;
use App\Models\Role;
use App\Models\Vcontroller;
use App\WnyTestCase;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MethodRolesControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * Create setup
     *
     * @return mixed
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

        $role1 = new Role([
            'name'        => 'Role 1',
            'description' => 'Description 1'
        ]);

        $role1->save();

        $role2 = new Role([
            'name'        => 'Role 2',
            'description' => 'Description 2'
        ]);

        $role2->save();

        $role3 = new Role([
            'name'        => 'Role 3',
            'description' => 'Description 3'
        ]);

        $role3->save();

        $controller1 = new Vcontroller([
            'name' => 'Controller1',
        ]);

        $controller1->save();

        $method1 = new Method([
            'name'          => 'MethodA',
            'controller_id' => 1
        ]);

        $method1->save();

        $method2 = new Method([
            'name'          => 'MethodB',
            'controller_id' => 1
        ]);

        $method2->save();

        $method1->roles()->attach($role1);
        $method1->roles()->attach($role2);

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
     * Check Index Of Roles For Specified Method:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexOfRolesForSpecifiedMethod()
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
        $response = $this->get('api/method-roles/1?token=' . $token, []);

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
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(1, $data[0]['role_id']);
        $this->assertEquals('Role 1', $data[0]['name']);
        $this->assertEquals(2, $data[1]['id']);
        $this->assertEquals(2, $data[1]['role_id']);
        $this->assertEquals('Role 2', $data[1]['name']);
        $this->assertEquals(true, $success);
        $this->assertEquals('Method-Roles are retrieved successfully.', $message);
    }

    /**
     * Check Index Of Roles For Specified Method If Method Id Is Wrong:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfMethodIsAbsent()
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
        $response = $this->get('api/method-roles/33?token=' . $token, []);

        // Check response status
        $response->assertStatus(452);

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
        $this->assertEquals('Method does not exist.', $message);
    }

    /**
     * Check Index Of Roles For Specified Method If Roles Are Absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexOfRolesForSpecifiedMethodIfRolesAreAbsent()
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
        $response = $this->get('api/method-roles/2?token=' . $token, []);

        // Check response status
        $response->assertStatus(209);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals('Method-Roles are absent.', $message);
    }

//    /**
//     * Check store If Method Roles Are Single:
//     *   Check login
//     *   Store a new method-roles (array of 2 item)
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *   Check new method-roles in DB
//     */
//    public function testStoreIfMethodRolesIsSingle()
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
//        $data = [
//            'rows' => [
//                [
//                    'method_id' => 2,
//                    'role_id'   => 3
//                ]
//            ]
//        ];
//
//        // Store a new action-roles
//        $response = $this->post('api/method-roles?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(200);
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
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals('New Method-Roles are created successfully.', $message);
//
//        // Check DB
//        $methodRoles     = DB::table('method_roles')->get()->keyBy('id');
//        $count           = $methodRoles->count();
//        $lastMethodRoles = $methodRoles[$count];
//        $this->assertEquals(2, $lastMethodRoles->method_id);
//        $this->assertEquals(3, $lastMethodRoles->role_id);
//    }
//
//    /**
//     * Check store If Method Roles Are Multiple:
//     *   Check login
//     *   Store a new method-roles (array of 2 item)
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *   Check new method-roles in DB
//     */
//    public function testStoreIfMethodRolesAreMultiple()
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
//        // Store a new action-roles
//        $data     = [
//            'rows' => [
//                [
//                    'method_id' => 2,
//                    'role_id'   => 3
//                ],
//                [
//                    'method_id' => 1,
//                    'role_id'   => 3
//                ]
//            ]
//        ];
//        $response = $this->post('api/method-roles?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(200);
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
//
//        $this->assertEquals($success, true);
//        $this->assertEquals($message, 'New Method-Roles are created successfully.');
//
//        // Check DB
//        $methodRoles     = DB::table('method_roles')->get()->keyBy('id');
//        $count           = $methodRoles->count();
//        $lastMethodRoles = $methodRoles[$count];
//        $this->assertEquals(1, $lastMethodRoles->method_id);
//        $this->assertEquals(3, $lastMethodRoles->role_id);
//        $penultMethodRoles = $methodRoles[$count - 1];
//        $this->assertEquals(2, $penultMethodRoles->method_id);
//        $this->assertEquals(3, $penultMethodRoles->role_id);
//    }
//
//    /**
//     * Check store If Method ID Is Wrong:
//     *   Check login
//     *   Store a new action-roles
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *
//     */
//    public function testStoreIfMethodIdIsWrong()
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
//        // Store a new method-roles
//        $data     = [
//            'rows' => [
//                [
//                    'method_id' => 33,
//                    'role_id'   => 3
//                ]
//            ]
//        ];
//        $response = $this->post('api/method-roles?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(452);
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
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Method does not exist.", $message);
//    }
//
//    /**
//     * Check store If One Of The Roles ID Is Wrong:
//     *   Check login
//     *   Store a new action-roles
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreIfOneOfTheRolesIdIsWrong()
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
//        // Store a new method-roles
//        $data     = [
//            'rows' => [
//                [
//                    'method_id' => 1,
//                    'role_id'   => 44
//                ],
//                [
//                    'method_id' => 1,
//                    'role_id'   => 3
//                ]
//            ]
//        ];
//        $response = $this->post('api/method-roles?token=' . $token, $data, []);
//
//
//        // Check response status
//        $response->assertStatus(452);
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
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Role does not exist.", $message);
//    }

    /**
     * Test Create Roles for a new Method:
     *    Check login (user #1)
     *    Create role #1 for method #2
     *       Check response status
     *       Check response structure
     *       Check role #1
     */
    public function testCreateRolesForNewMethod()
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

        // Store a new roles for Method
        $data     = [
            "method_id" => 2,
            "role_ids"  => [['id' => 1], ['id' => 2]]
        ];
        $response = $this->post('api/method-roles/2?token=' . $token, $data);

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
        $this->assertEquals($message, 'New Roles for Method created successfully.');

        // Check DB
        $method_roles = DB::table('method_roles')->where('method_id', 2)->get();
        $this->assertEquals(2, $method_roles[1]->role_id);
        $this->assertEquals(2, $method_roles[1]->method_id);
    }

    /**
     * Test Create Roles for a Method, that has roles:
     *    Create role for user #1
     *       Check response status
     *       Check response structure
     */
    public function testCreateRolesForMethodThatHasRoles()
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
            "method_id" => 1,
            "role_ids"  => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->post('api/method-roles/1?token=' . $token, $data);

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
        $this->assertEquals($message, 'Creating is impossible. Method has roles already.');
    }

    /**
     * Test Create Roles for absent Method:
     *    Create role #1 for user #3
     *       Check response status
     *       Check response structure
     */
    public function testCreateRolesForAbsentMethod()
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

        // Store a new role for Method
        $data = [
            "method_id" => 3333,
            "role_ids"  => [['id' => 1], ['id' => 2]]
        ];

        $response = $this->post('api/method-roles/3333?token=' . $token, $data);

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
        $this->assertEquals($message, 'Creating is impossible. Method does not exist.');
    }

    /** Check update:
     *   Check login
     *   Update the method-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdate()
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
        $data     = ['role_ids' => [['id' => 1], ['id' => 3]]];
        $response = $this->put('api/method-roles/1?token=' . $token, $data, []);

        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Roles for Method are updated successfully.", $message);

        // Check DB
        $methodRoles = DB::table('method_roles')->where('method_id', 1)->get();
        $count       = $methodRoles->count();
        $this->assertEquals(2, $count);
        $this->assertEquals(1, $methodRoles[0]->role_id);
        $this->assertEquals(3, $methodRoles[1]->role_id);
    }

    /**
     * Check update If Method ID is Wrong:
     *   Check login
     *   Update the method-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfMethodIdIsWrong()
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
        $data     = ['role_ids' => [['id' => 1111], ['id' => 3]]];
        $response = $this->put('api/method-roles/1111?token=' . $token, $data, []);

        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Method does not exist.", $message);
    }

    /**
     * Check update If One Of The Roles ID Is Wrong:
     *   Check login
     *   Update the method-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfOneOfTheRolesIdIsWrong()
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
        $data     = ['role_ids' => [['id' => 1111], ['id' => 3]]];
        $response = $this->put('api/method-roles/1?token=' . $token, $data, []);

        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("One of the roles does not exist.", $message);
    }

    /** Check Delete:
     *   Check login
     *   Delete method-role
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB
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
            'name'  => 'Test',
            'email' => 'test1@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/method-role/2?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Method-Role is deleted successfully.");

        // Check DB
        $methodRoles = DB::table('method_roles')->where('method_id', 1)->get();
        $count       = $methodRoles->count();
        $this->assertEquals(2, $count);
        $this->assertEquals(1, $methodRoles[0]->role_id);
        $this->assertEquals(1, $methodRoles[0]->method_id);
        $this->assertNotEquals(null, $methodRoles[1]->deleted_at);
    }

    /**
     * Check Delete If Method-Role ID is Wrong:
     *   Check login
     *   Delete action-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfMethodRoleIdIsWrong()
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
        $response = $this->delete('api/method-role/4?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals($success, false);
        $this->assertEquals($message, "Method-Role does not exist.");
    }

    /**
     * Check Delete Roles Of The Specified Method:
     *   Check login
     *   Delete method-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteRolesOfTheSpecifiedMethod()
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
        $response = $this->delete('api/method-roles/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Method-Roles are deleted successfully.");
    }

    /**
     * Check Delete Roles Of The Specified Method If Method Id Is Wrong:
     *   Check login
     *   Delete method-roles
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteRolesOfTheSpecifiedMethodIfMethodIdIsWrong()
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
        $response = $this->delete('api/method-roles/33?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals($success, false);
        $this->assertEquals($message, "Method does not exist.");
    }
}
