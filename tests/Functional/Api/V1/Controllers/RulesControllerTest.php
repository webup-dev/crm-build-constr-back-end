<?php

/**
 * SetUp:
 *   Create 1 user
 *   Create 3 roles
 *   Create 1 Controller
 *   Create 2 methods
 *   Create 2 method-roles
 *
 * Check Get Rules:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Get Rules If Roles Are Not Selected:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Get Main Role:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Get Main Role If Roles Are Not Selected:
 *   Check login
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

class RulesControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *   Create 1 user
     *   Create 3 roles
     *   Create 2 Controller
     *   Create 4 methods
     *   Create 3 method-roles
     *   Create 2 user-roles
     *
     * @return mixed
     */
    public function setUp()
    : void
    {
        parent::setUp();

        /*------------------------------------------------/
        |controller1 | method1 | role1, role2 | user1
        |            | method2 | role1        | user1
        |controller2 | method3 | role2        | user1
        |controller3 | method4 | role3        |
        |------------------------------------------------*/

        /**
         * array:4 [
         *  "permissions" => []
         *  "restrictions" => []
         *  "roles" => array:2 [
         *    1 => array:2 [
         *      0 => 1
         *      1 => 2
         *    ],
         *    2 => array:1 [
         *      0 => 3
         *    ]
         *  ]
         *  "names" => array:2 [
         *    1 => array:2 [
         *      "controller" => "Controller1"
         *      "methods" => array:2 [
         *        1 => "MethodA"
         *        2 => "MethodB"
         *      ]
         *    ]
         *    2 => array:2 [
         *      "controller" => "Controller2"
         *      "methods" => array:1 [
         *        3 => "MethodC"
         *      ]
         *    ]
         *  ]
         * ]
         */

        $user1 = new User([
            'name'     => 'Test',
            'email'    => 'test1@email.com',
            'password' => '123456'
        ]);

        $user1->save();

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

        $controller1 = new Vcontroller([
            'name' => 'Controller1',
        ]);

        $controller1->save();

        $controller2 = new Vcontroller([
            'name' => 'Controller2',
        ]);

        $controller2->save();

        $controller3 = new Vcontroller([
            'name' => 'Controller3',
        ]);

        $controller3->save();

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

        $method3 = new Method([
            'name'          => 'MethodC',
            'controller_id' => 2
        ]);

        $method3->save();

        $method4 = new Method([
            'name'          => 'MethodD',
            'controller_id' => 3
        ]);

        $method4->save();

        $method1->roles()->attach($role1);
        $method2->roles()->attach($role1);
        $method1->roles()->attach($role2);
        $method3->roles()->attach($role2);
        $method4->roles()->attach($role3);

        $user1->roles()->attach($role1);
        $user1->roles()->attach($role2);

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
     * Check Get Rules:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testGetRules()
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
        $response = $this->get('api/rules?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'permissions',
                        'restrictions',
                        'roles',
                        'names'
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(4, count($data));
        $this->assertEquals([], $data['permissions']);
        $this->assertEquals([], $data['restrictions']);
        $this->assertEquals(2, count($data['roles']));
        $this->assertEquals('1', $data['roles'][1][0]);
        $this->assertEquals('2', $data['roles'][1][1]);
        $this->assertEquals('3', $data['roles'][2][0]);
        $this->assertEquals('Controller1', $data['names'][1]['controller']);
        $this->assertEquals('MethodA', $data['names'][1]['methods'][1]);
        $this->assertEquals('MethodB', $data['names'][1]['methods'][2]);
        $this->assertEquals('Controller2', $data['names'][2]['controller']);
        $this->assertEquals('MethodC', $data['names'][2]['methods'][3]);
        $this->assertEquals(2, count($data['names'][1]['methods']));
        $this->assertEquals(1, count($data['names'][2]['methods']));
        $this->assertEquals(0, count($data['permissions']));
        $this->assertEquals(0, count($data['restrictions']));
        $this->assertEquals(true, $success);
        $this->assertEquals('Rules are retrieved successfully.', $message);
    }

    /**
     * Check Get Rules If Roles Are Not Selected:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testGetRulesIfRolesAreNotSelected()
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

        // Delete roles
        $this->delete('api/user-roles/1?token=' . $token, []);
        // Request
        $response = $this->get('api/rules?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'permissions',
                        'restrictions',
                        'roles',
                        'names'
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals('Rules are retrieved successfully.', $message);
        $this->assertEquals([], $data['permissions']);
        $this->assertEquals([], $data['restrictions']);
        $this->assertEquals([], $data['roles']);
        $this->assertEquals([], $data['names']);
    }

    /**
     * Check Get Main Role:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testGetMainRole()
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
        $response = $this->get('api/rules/main-role?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' => [
                    'id',
                    'name'
                ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals("The main role is retrieved successfully.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals('superadmin', $data['name']);
    }

    /**
     * Check Get Main Role If Roles Are Not Selected:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testGetMainRoleIfRolesAreNotSelected()
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

        // Delete roles
        $this->delete('api/user-roles/1?token=' . $token, []);

        // Request
        $response = $this->get('api/rules/main-role?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data',
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data      = $responseJSON['data'];  // array

        $this->assertEquals([], $data);
        $this->assertEquals(false, $success);
        $this->assertEquals('Roles are absent.', $message);
    }
}
