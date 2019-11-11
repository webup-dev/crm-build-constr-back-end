<?php

/**
 * SetUp:
 *   Create user
 *   Create 7 organizations
 *
 * Test example
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
 * Check Index Permission Is Absent:
 *   Check login
 *   Get index
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index With Restrictions:
 *   Check login
 *   Get index
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
 * Check Show Permission is absent by the role:
 *   Check login
 *   Get specified item
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show Permission to department is absent:
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
 * Check store The given data was invalid:
 *   Check login
 *   Store a new organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store Permission is absent:
 *   Check login
 *   Store a new organization
 *   Check response status
 *   Check response structure
 *   Check response data
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
 * Check update The given data was invalid:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update Permission is absent by the role:
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
 * Delete ID is absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete Permission is absent by the role:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete Permission to department is absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Delete Impossible to destroy due to child
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Restore:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Restore ID is absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Restore Permission is absent by the role:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Restore Permission to department is absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Permanent Destroy:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Permanent Destroy ID is absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Permanent Destroy Permission is absent by the role:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Permanent Destroy Permission to department is absent:
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

        /*
         | User                   | User ID | Role                     | Organization   | Organization ID |
         |------------------------|---------|--------------------------|----------------|-----------------|
         | Test                   | 1       | developer                |                |                 |
         | Estimator              | 2       | organization-estimator   |                |                 |
         | Organizational Admin   | 2       | organization-admin       | Another Office | 3               |
         */

        $user1 = new User([
            'name'     => 'Test',
            'email'    => 'test@email.com',
            'password' => '123456'
        ]);

        $user1->save();

        $user2 = new User([
            'name'     => 'Estimator',
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $user2->save();

        $user3 = new User([
            'name'     => 'Organizational Admin',
            'email'    => 'Organizational-Admin@email.com',
            'password' => '123456'
        ]);

        $user3->save();

        $role1 = new Role([
            'name' => 'developer'
        ]);

        $role1->save();

        $role2 = new Role([
            'name' => 'organization-estimator'
        ]);

        $role2->save();

        $role3 = new Role([
            'name' => 'organization-admin'
        ]);

        $role3->save();

        $user1->roles()->attach(1);
        $user2->roles()->attach(2);
        $user3->roles()->attach(3);

        // 1
        $organization = new Organization([
            'name'      => 'Platform',
            'level'     => 1,
            'order'     => 1,
            'parent_id' => null,
        ]);

        $organization->save();

        // 2
        $organization = new Organization([
            'name'      => 'Central Office',
            'level'     => 2,
            'order'     => 1,
            'parent_id' => 1,
        ]);

        // 3
        $organization->save();

        $organization = new Organization([
            'name'      => 'Another Office',
            'level'     => 2,
            'order'     => 2,
            'parent_id' => 1,
        ]);

        $organization->save();

        // 4
        $organization = new Organization([
            'name'      => 'Another Department 1',
            'level'     => 3,
            'order'     => 1,
            'parent_id' => 3,
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Department 1',
            'level'     => 3,
            'order'     => 1,
            'parent_id' => 2,
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Another Department 2',
            'level'     => 3,
            'order'     => 2,
            'parent_id' => 3,
        ]);

        $organization->save();

        $organization = new Organization([
            'name'      => 'Department 2',
            'level'     => 3,
            'order'     => 2,
            'parent_id' => 2,
        ]);

        $organization->save();

        $userProfile1 = User_profile::create([
            'user_id'          => 1,
            'first_name'       => 'Test',
            'last_name'        => 'Developer',
            'title'            => '',
            'department_id'    => 1,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null
        ]);

        $userProfile1->save();

        $userProfile2 = User_profile::create([
            'user_id'          => 3,
            'first_name'       => 'Organizational',
            'last_name'        => 'Admin',
            'title'            => '',
            'department_id'    => 3,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null
        ]);

        $userProfile2->save();


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
                            'level',
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


        $this->assertEquals(7, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Platform', $data[0]['name']);
        $this->assertEquals('1', $data[0]['level']);
        $this->assertEquals('1', $data[0]['order']);
        $this->assertEquals('', $data[0]['parent_id']);
        $this->assertEquals("Organizations retrieved successfully.", $message);
        $this->assertEquals(true, $success);

    }

    /**
     * Check Index With Restrictions:
     *   Check login
     *   Get index
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexWithRestrictions()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'Organizational-Admin@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Organizational Admin',
            'email' => 'Organizational-Admin@email.com'
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
                            'level',
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

        $this->assertEquals(3, count($data));
        $this->assertEquals(4, $data[1]['id']);
        $this->assertEquals('Another Department 1', $data[1]['name']);
        $this->assertEquals(3, $data[1]['level']);
        $this->assertEquals(1, $data[1]['order']);
        $this->assertEquals(3, $data[1]['parent_id']);
        $this->assertEquals("Organizations retrieved successfully.", $message);
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
        $response = $this->get('api/organizations/2?token=' . $token, []);

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
                        'level',
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
        $this->assertEquals(1, $data['parent_id']);
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
            'parent_id' => 2
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

        $this->assertEquals(8, $organization->id);
        $this->assertEquals(2, $organization->parent_id);
        $this->assertEquals(3, $organization->level);
    }

    /**
     * Check store The given data was invalid:
     *   Check login
     *   Store a new organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfTheGivenDataWasInvalid()
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
            'order'     => 'b',
            'name'      => '',
            'parent_id' => 'c'
        ];

        // Store a new organization
        $response = $this->post('api/organizations?token=' . $token, $data, []);

        // Check response status
        $response->assertStatus(452);

        // Check response structure
        $response->assertJsonStructure(
            [
                'errors'
            ]
        );

        ///Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $errors       = $responseJSON['errors'];  // array

        $this->assertEquals("The given data was invalid.", $errors['message']);
        $this->assertEquals(3, count($errors['errors']));
    }

    /**
     * Check store Permission is absent Due To Role:
     *   Check login
     *   Store a new organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfPermissionIsAbsentDueToRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Estimator',
            'email' => 'estimator@email.com'
        ])->isOk();

        // Create data
        $data = [
            'order'     => '3',
            'name'      => 'Test Test',
            'parent_id' => 3
        ];

        // Store a new organization
        $response = $this->post('api/organizations?token=' . $token, $data, []);

        // Check response status
        $response->assertStatus(453);

        // Check response structure
        $response->assertJsonStructure(
            [
                "success",
                "message"
            ]
        );

        ///Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
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
            'order'     => 1,
            'parent_id' => 4
        ];

        $response = $this->put('api/organizations/5/?token=' . $token, $data);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is updated successfully.", $message);
        $this->assertEquals("Department 1 Edited", $data->name);
        $this->assertEquals(4, $data->level);
        $this->assertEquals(4, $data->parent_id);
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
     * Check update The given data was invalid:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheGivenDataWasInvalid()
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
            'name'      => '',
            'order'     => 'a',
            'parent_id' => 'c'
        ];

        $response = $this->put('api/organizations/2/?token=' . $token, $data);

        $response->assertStatus(452);

        // Check response structure
        $response->assertJsonStructure(
            [
                'errors'
            ]
        );

        ///Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $errors       = $responseJSON['errors'];  // array

        $this->assertEquals("The given data was invalid.", $errors['message']);
        $this->assertEquals(3, count($errors['errors']));
    }

    /**
     * Check update Permission is absent by the role:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfPermissionIsAbsentByTheRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Estimator',
            'email' => 'estimator@email.com'
        ])->isOk();

        // Create data
        $data = [
            'name'      => 'Test',
            'order'     => 1,
            'parent_id' => 3
        ];

        $response = $this->put('api/organizations/4?token=' . $token, $data);

        $response->assertStatus(453);

        // Check response structure
        $response->assertJsonStructure(
            [
                "success",
                "message"
            ]
        );

        ///Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
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
        $response = $this->delete('api/organizations/3?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is deleted successfully.", $message);

        $organization = DB::table('organizations')->where('id', 3)->first();
        $this->assertNotEquals(null, $organization->deleted_at);
    }

    /**
     * Delete ID is absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfIdIsAbsent()
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

        $response->assertStatus(455);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Id is absent.", $message);
    }

    /**
     * Delete Permission is absent by the role:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfPermissionIsAbsentByTheRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Estimator',
            'email' => 'estimator@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/organizations/33/?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Delete Permission to department is absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfPermissionToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'Organizational-Admin@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Organizational Admin',
            'email' => 'Organizational-Admin@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/organizations/4?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Check Restore:
     *   User "developer" soft-delete organization Another Department (id #4).
     *   User "developer" restore the soft-deleted organization Another Department (id #4).
     *     Check login
     *     Soft delete organization #4
     *     Restore organization #4
     *     Check response status
     *     Check response structure
     *     Check response data
     */
    public function testRestore()
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

        // Preparation
        $response     = $this->delete('api/organizations/4?token=' . $token, []);
        $organization = Organization::onlyTrashed()->where('id', 4)->first();
        $response->assertStatus(200);
        $this->assertNotEquals(null, $organization->deleted_at);

        // Request
        $response = $this->put('api/organizations/4/restore?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is restored successfully.", $message);

        $organization = Organization::where('id', 4)->first();
        $this->assertEquals(null, $organization->deleted_at);
    }

    /**
     * Check Restore If The ID Is Wrong:
     *   User "developer" restore absent soft-deleted organization (id #4444).
     *     Check login
     *     Soft delete organization #4
     *     Restore organization #4
     *     Check response status
     *     Check response structure
     *     Check response data
     */
    public function testRestoreIfTheIdIsWrong()
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
        $response = $this->put('api/organizations/4444/restore?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Organization is absent.", $message);
    }

    /**
     * Check Restore If Permission is absent by the role:
     *   User "developer" soft-deletes organization Another Department (id #4).
     *   User "estimator" restores the soft-deleted organization Another Department (id #4).
     *     Check login
     *     Soft delete organization #4
     *     Restore organization #4
     *     Check response status
     *     Check response structure
     *     Check response data
     */
    public function testRestoreIfPermissionIsAbsentByTheRole()
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

        // Preparation
        $response     = $this->delete('api/organizations/4?token=' . $token, []);
        $organization = Organization::onlyTrashed()->where('id', 4)->first();

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Estimator',
            'email' => 'estimator@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/organizations/4/restore?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Restore Permission to department is absent:
     *   User "developer" soft-deletes organization Department 1 (id #6).
     *   User "organizational-admin" restores the soft-deleted organization Department 1 (id #6).
     *     Check login
     *     Soft delete organization #6
     *     Restore organization #6
     *     Check response status
     *     Check response structure
     *     Check response data
     */
    public function testRestoreIfPermissionToDepartmentIsAbsent()
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

        // Preparation
        $response     = $this->delete('api/organizations/6?token=' . $token, []);
        $organization = Organization::onlyTrashed()->where('id', 6)->first();

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'Organizational-Admin@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Organizational Admin',
            'email' => 'Organizational-Admin@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/organizations/6/restore?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Check Delete Permanently:
     *   User Developer deletes deletes permanently the soft-deleted organization Department 1 (is #5).
     *     Check login
     *     Soft delete organization #5
     *     Delete Permanently #5
     *     Check response status
     *     Check response structure
     *     Check DB: row with ID=5 must be absent
     */
    public function testDeletePermanently()
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

        // Preparation
        $response = $this->delete('api/organizations/5?token=' . $token, []);

        // Request
        $response = $this->delete('api/organizations/5/permanently?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is deleted permanently.", $message);

        $organization = DB::table('organizations')->where('id', 5)->first();
        $this->assertEquals(null, $organization);
    }

    /**
     * Check Delete Permanently If The ID Is Wrong:
     *   User Developer deletes deletes permanently the soft-deleted organization with id #5555).
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheIdIsWrong()
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
        $response = $this->delete('api/organizations/5555/permanently?token=' . $token, []);

        $response->assertStatus(455);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("ID is absent.", $message);
    }

    /**
     * Permanent Destroy Permission is absent by the role:
     *   User Developer deletes soft-delete organization with id #6).
     *   User Administration Admin deletes permanently the soft-deleted organization with id #6).
     *   We wait for a message about error.
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheAccessIsAbsent()
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

        // Preparation
        $response = $this->delete('api/organizations/6?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'estimator@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Estimator',
            'email' => 'estimator@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/organizations/6/permanently?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Permanent Destroy Permission to department is absent:
     *   User Developer deletes soft-delete organization with id #6).
     *   User Administration Admin deletes permanently the soft-deleted organization with id #6).
     *   We wait for a message about error.
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfPermissionToDepartmentIsAbsent()
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

        // Preparation
        $response = $this->delete('api/organizations/6?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'Organizational-Admin@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Organizational Admin',
            'email' => 'Organizational-Admin@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/organizations/6/permanently?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }
}
