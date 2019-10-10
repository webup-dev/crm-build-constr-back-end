<?php

/**
 * SetUp:
 *   Create user
 *   Create 6 organizations
 *
 * Check Index:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If There Are Not Organizations:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show:
 *   Check login
 *   Get specified item
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show Not Existing Item:
 *   Check login
 *   Get specified item
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store:
 *   Check login
 *   Store a new organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Get DB table Organizations and check last Organization
 *
 * Check store If The Parent Id Is Absent:
 *   Check login
 *   Store a new organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Get DB table Organizations and check last Organization
 *
 * Check store If The Parent Id Is Forbidden:
 *   Check login
 *   Store a new organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Get DB table Organizations and check last Organization
 *
 * Check update:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If The Id Is Absent:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If The Id Is Forbidden:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If The Parent Id Is Wrong:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check DB
 *
 * Delete If The Id Is Wrong:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * tearDown:
 *   Delete pivot tables
 *   Delete Organizations
 *   Delete user
 */

namespace App\Functional\Api\V1\Controllers;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use App\WnyTestCase;
use Hash;
use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrganizationsControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *   Create user
     *   Create 6 organizations
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

        $role = new Role([
            'name'     => 'superadmin'
        ]);

        $role->save();

        $user->roles()->attach(1);

        $organization = new Organization([
            'name'      => 'Central Office',
            'order'     => '1',
            'parent_id' => null,
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Department 1',
            'order'     => '2',
            'parent_id' => '1',
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Department 2',
            'order'     => '3',
            'parent_id' => '1',
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Branch Office',
            'order'     => '4',
            'parent_id' => '1',
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Branch Department 1',
            'order'     => '5',
            'parent_id' => '4',
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Branch Department 2',
            'order'     => '6',
            'parent_id' => '4',
        ]);

        $organization->save();
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

        $response = $this->get('api/organizations?token=' . $token, []);

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
                            'order',
                            'parent_id',
                            'name',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array


        $this->assertEquals(6, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Central Office', $data[0]['name']);
        $this->assertEquals('1', $data[0]['order']);
        $this->assertEquals('', $data[0]['parent_id']);
        $this->assertEquals("Organizations retrieved successfully.", $message);
        $this->assertEquals(true, $success);

    }

    /**
     * Check Index If There Are Not Organizations:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfThereAreNotOrganizations()
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

        // Truncate DB table organizations
        $response = $this->delete('api/organizations/2/?token=' . $token, []);
        $response = $this->delete('api/organizations/3/?token=' . $token, []);
        $response = $this->delete('api/organizations/5/?token=' . $token, []);
        $response = $this->delete('api/organizations/6/?token=' . $token, []);
        $response = $this->delete('api/organizations/4/?token=' . $token, []);
        $response = $this->delete('api/organizations/1/?token=' . $token, []);

        $response = $this->get('api/organizations?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals("Organizations are absent.", $message);
        $this->assertEquals(true, $success);
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
        $response = $this->get('api/organizations/1?token=' . $token, []);

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
                        'parent_id',
                        'order',
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
        $this->assertEquals('Item is retrieved successfully.', $message);
        $this->assertEquals('Central Office', $data['name']);
        $this->assertEquals(null, $data['parent_id']);
        $this->assertEquals(1, $data['order']);
    }

    /**
     * Check Show Not Existing Item:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowNotExistingItem()
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
        $response = $this->get('api/organizations/1111?token=' . $token, []);

        // Check response status
        $response->assertStatus(452);

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

        $this->assertEquals(false, $success);
        $this->assertEquals('Item is absent.', $message);
    }

    /**
     * Check store:
     *   Check login
     *   Store a new organization
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Get DB table Organizations and check last Organization
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

        // Create data
        $data = [
            'name'      => 'Department 3',
            'order'     => '4',
            'parent_id' => 1
        ];

        // Store a new organization
        $response = $this->post('api/organizations?token=' . $token, $data, []);

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

        $this->assertEquals(true, $success);
        $this->assertEquals("New Organization is created successfully.", $message);

        // Check DB
        $organization = DB::table('organizations')->where('name', 'Department 3')->first();
        $this->assertGreaterThanOrEqual(7, $organization->id);
        $this->assertEquals(1, $organization->parent_id);
    }

    /**
     * Check Store If The Parent Id Is Absent:
     *   Check login
     *   Store a new organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfTheParentIdIsAbsent()
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

        // Create data
        $data = [
            'name'      => 'Department 3',
            'order'     => '4',
            'parent_id' => 33
        ];

        // Store a new organization
        $response = $this->post('api/organizations?token=' . $token, $data, []);

        // Check response status
        $response->assertStatus(452);

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

        $this->assertEquals(false, $success);
        $this->assertEquals("Parent Id is impossible.", $message);
    }

    /**
     * Check update:
     *   Check login
     *   Update the Organization
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

        // Create data
        $data = [
            'name'      => 'Department 1 Edited',
            'order'     => '2',
            'parent_id' => 1
        ];

        $response = $this->put('api/organizations/2/?token=' . $token, $data);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is updated successfully.", $message);
        $this->assertEquals("Department 1 Edited", $data->name);
    }

    /**
     * Check update If The Id Is Absent:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheIdIsAbsent()
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

        // Create data
        $data = [
            'name'      => 'Department 1 Edited',
            'order'     => '2',
            'parent_id' => 1
        ];

        $response = $this->put('api/organizations/33/?token=' . $token, $data);

        $response->assertStatus(452);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Could not find Organization.", $message);
    }

    /**
     * Check update If The Id Is Forbidden:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheIdIsForbidden()
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

        // Create data
        $data = [
            'name'      => 'Department 1 Edited',
            'order'     => '2',
            'parent_id' => 2
        ];

        $response = $this->put('api/organizations/2/?token=' . $token, $data);

        $response->assertStatus(452);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Parent Id is impossible.", $message);
    }

    /**
     * Delete:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB
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

        // Request
        $response = $this->delete('api/organizations/3/?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is deleted successfully.", $message);

        $organization = DB::table('organizations')->where('id', 3)->first();
        $this->assertEquals(null, $organization);
    }

    /**
     * Delete If The Id Is Wrong:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB
     */
    public function testDeleteIfTheIdIsWrong()
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
        $response = $this->delete('api/organizations/33/?token=' . $token, []);

        $response->assertStatus(452);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Could not find Organization.", $message);
    }
}
