<?php

/**
 * SetUp:
 *   Create user
 *   Create 2 controllers
 *   Create 2 methods for controller #1
 *
 * Check Index:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If There Are Not Methods:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store:
 *   Check login
 *   Store a new method
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Get DB table Methods and check last method in it
 *
 * Check store If Controller Does Not Exist:
 *   Check login
 *   Store a new method
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check show:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check show If Method Id Is Wrong:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update:
 *   Check login
 *   Update the method
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Method Id Is Wrong:
 *   Check login
 *   Update the method
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete:
 *   Check login
 *   Delete method
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check deleting in DB
 *
 * Delete If Method ID Is Wrong:
 *   Check login
 *   Delete method
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * tearDown:
 *   Delete methods
 *   Delete controllers
 *   Delete user
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Method;
use App\Models\Method_role;
use App\Models\Role;
use App\Models\Vcontroller;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class MethodsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create 1 user
     * Create 2 controllers
     * Create 2 methods for controller #1
     * Create 3 roles
     * Create 2 method-roles
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

        $controller1 = new Vcontroller([
            'name' => 'ControllerA'
        ]);

        $controller1->save();

        $controller2 = new Vcontroller([
            'name' => 'Controller2'
        ]);

        $controller2->save();

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

        $role1 = new Role([
            'name'          => 'Role1'
        ]);

        $role1->save();

        $role2 = new Role([
            'name'          => 'Role2'
        ]);

        $role2->save();

        $role3 = new Role([
            'name'          => 'Role3'
        ]);

        $role3->save();

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

        // Request
        $response = $this->get('api/methods/1?token=' . $token, []);

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
                            'controller_id',
                            'role_ids',
                            'role_names',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals('Methods are retrieved successfully.', $message);
        $this->assertEquals("MethodA", $data[0]['name']);
        $this->assertEquals(1, $data[0]['controller_id']);
        $this->assertEquals([1,2], $data[0]['role_ids']);
        $this->assertEquals('Role1, Role2', $data[0]['role_names']);
        $this->assertEquals("MethodB", $data[1]['name']);
        $this->assertEquals(1, $data[1]['controller_id']);
        $this->assertEquals([], $data[1]['role_ids']);
        $this->assertEquals('', $data[1]['role_names']);
    }

//    /**
//     * Check Index If There Are Not Methods:
//     *   Check login
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testIndexIfThereAreNotMethods()
//    {
//        // Check login
//        $response = $this->post('api/auth/login', [
//            'email'    => 'test@email.com',
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
//            'email' => 'test@email.com'
//        ])->isOk();
//
//        // Request
//        $response = $this->get('api/methods/2?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(204);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'data',
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals('Methods are retrieved successfully.', $message);
//        $this->assertEquals(0, count($data));
//    }

    /**
     * Check store:
     *   Check login
     *   Store a new method
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Get DB table Methods and check last method in it
     */
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

        // Store a new method to the controller #2
        $response = $this->post('api/methods/2?token=' . $token, [
            'name'          => 'methodC',
            'controller_id' => 2
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
        $this->assertEquals($message, 'New Method is created successfully.');

        // Check DB
        $method = DB::table('methods')->where('name', 'methodC')->first();
        $this->assertEquals(3, $method->id);
        $this->assertEquals(2, $method->controller_id);
    }

    /**
     * Check store If Controller Does Not Exist:
     *   Check login
     *   Store a new method
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfControllerDoesNotExist()
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

        // Store a new method to the controller #3
        $response = $this->post('api/methods/3?token=' . $token, [
            'name'          => 'methodC',
            'controller_id' => 3
        ]);

        // Check response status
        $response->assertStatus(204);
//        print_r($response);

        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );

        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals('Controller does not exist', $message);
    }

    /**
     * Check show:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
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

        // Request
        $response = $this->get('api/methods/1/show?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);
//        print_r($response);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'controller_id',
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
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals('Method is retrieved successfully.', $message);
        $this->assertEquals('MethodA', $data['name']);
        $this->assertEquals('1', $data['controller_id']);
    }

    /**
     * Check show If Method Id Is Wrong:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfMethodIdIsWrong()
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

        // Request
        $response = $this->get('api/methods/3/show?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);

        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals('Method does not exist.', $message);
    }

    /** Check update:
     *   Check login
     *   Update the method #2
     *   Check response status
     *   Check response structure
     *   Check response data
     */
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

        // Request

        $response = $this->put('api/methods/2?token=' . $token, [
            "name" => "methodUpdated"
        ]);

        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure([
                'success',
                'data' =>
                    [
                        'id',
                        'name',
                        'controller_id',
                        'created_at',
                        'updated_at'
                    ],
                'message'
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Method is updated successfully.");
        $this->assertEquals("methodUpdated", $data['name']);
    }

    /**
     * Check update If Method Id Is Wrong:
     *   Check login
     *   Update the method
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfMethodIdIsWrong()
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

        // Request

        $response = $this->put('api/methods/33?token=' . $token, [
            "name" => "methodUpdated"
        ]);

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

        $this->assertEquals($success, false);
        $this->assertEquals($message, "Method does not exist.");
    }

    /**
     * Delete:
     *   Check login
     *   Delete method
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check deleting in DB
     */
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

        $response = $this->delete('api/methods/2?token=' . $token, []);

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

        $this->assertEquals($success, true);
        $this->assertEquals($message, "Method is deleted successfully.");

        $method = DB::table('methods')->where('id', 2)->get();
        $this->assertEquals(0, $method->count(), 'Method is not deleted.');
    }

    /**
     * Delete If Method ID Is Wrong:
     *   Check login
     *   Delete method
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfMethodIdIsWrong()
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

        // Request
        $response = $this->delete('api/methods/33?token=' . $token, []);

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
     *   Delete methods
     *   Delete controllers
     *   Delete user
     */
    public function tearDown()
    : void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub

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

        // Requests
        $response = $this->delete('api/methods/1?token=' . $token, []);
        $response = $this->delete('api/methods/2?token=' . $token, []);
        $response = $this->delete('api/controllers/1?token=' . $token, []);
        $response = $this->delete('api/controllers/2?token=' . $token, []);
    }
}
