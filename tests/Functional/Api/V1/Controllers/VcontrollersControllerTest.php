<?php

/**
 * SetUp:
 * Create user
 * Create 2 controllers
 *
 * Check Index:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If There Are Not Controllers:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store:
 *   Check login
 *   Store a new controller
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Get DB table Controllers and check last controller in it
 *
 * Check update:
 *   Check login
 *   Update the controller 2
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check show:
 *   Check login
 *   Update the controller 2
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check show If Controller Id Is Wrong:
 *   Check login
 *   Update the controller 2
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Controller Id Is Wrong:
 *   Check login
 *   Update the controller
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete:
 *   Check login
 *   Delete not existing role
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check deleting in DB
 *
 * Delete If Controller Is Wrong:
 *   Check login
 *   Delete not existing role
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * tearDown:
 * Delete controllers
 * Delete user
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Book;
use App\Models\Role;
use App\Models\Vcontroller;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class VcontrollersControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Create user
     * Create 2 controllers
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
            'name' => 'Controller1'
        ]);

        $controller1->save();

        $controller2 = new Vcontroller([
            'name' => 'Controller2'
        ]);

        $controller2->save();
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
        $response = $this->get('api/controllers?token=' . $token, []);

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
        $this->assertEquals('Vcontrollers retrieved successfully.', $message);
        $this->assertEquals($data[0]['name'], "Controller1");
        $this->assertEquals($data[1]['name'], "Controller2");
    }

    /**
     * Check Index If There Are Not Controllers:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfThereAreNotControllers()
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
        $this->delete('api/controllers/1?token=' . $token, []);
        $this->delete('api/controllers/2?token=' . $token, []);
        $response = $this->get('api/controllers?token=' . $token, []);

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
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals('Vcontrollers retrieved successfully.', $message);
        $this->assertEquals(0, count($data));
    }

    /** Check store:
     *   Check login
     *   Store a new controller
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Get DB table Controllers and check last controller in it
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

        // Store a new controller
        $response = $this->post('api/controllers?token=' . $token, [
            'name' => 'Controller3'
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
        $this->assertEquals($message, 'New Controller created successfully.');

        // Check DB
        $controller = DB::table('controllers')->where('name', 'Controller3')->first();
        $this->assertEquals(3, $controller->id);
        $this->assertEquals('Controller3', $controller->name);
    }

    /** Check update:
     *   Check login
     *   Update the controller 2
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

        $response = $this->put('api/controllers/2?token=' . $token, [
            "name" => "Controller2Updated"
        ]);

        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure([
                'success',
                'data' =>
                    [
                        'id',
                        'name',
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
        $this->assertEquals($message, "Controller is updated successfully.");
        $this->assertEquals("Controller2Updated", $data['name']);
    }

    /** Check update If Controller Id Is Wrong:
     *   Check login
     *   Update the controller
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfControllerIdIsWrong()
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

        $response = $this->put('api/controllers/33?token=' . $token, [
            "name" => "Controller2Updated"
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
        $this->assertEquals($message, "Controller does not exist.");
    }

    /** Check show:
     *   Check login
     *   Get the controller 2
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
        $response = $this->get('api/controllers/2?token=' . $token, []);

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
        $this->assertEquals('Controller retrieved successfully.', $message);
        $this->assertEquals('Controller2', $data['name']);
    }

    /** Check show If Controller Id Is Wrong:
     *   Check login
     *   Get the controller 2
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfControllerIdIsWrong()
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
        $response = $this->get('api/controllers/33?token=' . $token, []);

        // Check response status
        $response->assertStatus(422);

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
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals('Controller not found.', $message);
        $this->assertEquals('Empty', $data);
    }


    /** Delete:
     *    Check login
     *    Delete not existing role
     *    Check response status
     *    Check response structure
     *    Check response data
     *    Check deleting in DB
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

        $response = $this->delete('api/controllers/2?token=' . $token, []);

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
        $this->assertEquals($message, "Controller is deleted successfully.");
    }

    /**
     * Delete If Controller Is Wrong:
     *   Check login
     *   Delete not existing role
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfControllerIdIsWrong()
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
        $response = $this->delete('api/controllers/33?token=' . $token, []);

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
        $this->assertEquals($message, "Controller does not exist.");
    }
}
