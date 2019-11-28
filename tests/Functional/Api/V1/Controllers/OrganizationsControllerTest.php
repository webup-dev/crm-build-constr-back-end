<?php

/**
 * Standard checks:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * SetUp: use TestsSeeder
 * TestExample
 * Test current Seeder
 *
 * Check Index
 * Check Index With Not Full Permissions
 * Check Index If Permission Is Absent Due To Role
 *
 * Check Index SoftDeleted
 * Check Index SoftDeleted If Content Is Empty
 * Check Index SoftDeleted If Permission Is Absent Due To Role
 *
 * Check Show
 * Check Show If Permission is absent by the role
 * Check Show If Permission to department is absent
 * Check Show Not Existing Item
 *
 * Check store
 * Check store If The given data was invalid
 * Check store If Permission is absent due to Role
 * Check store If Permission to the department is absent
 *
 * Check update
 * Check update If The Id Is wrong
 * Check update The given data was invalid
 * Check update Permission is absent by the role
 * Check update Permission to te department is absent
 *
 * Delete
 * Delete If Access Is not Full
 * Delete If The Access Is Not Full
 * Delete If ID is absent
 * Delete If Permission is absent by the role
 * Delete If The Permission to department is absent
 * Delete Impossible to destroy due to child
 * Delete Is Impossible Due To Customer Of Organization
 * Delete Is Impossible Due To User Of Organization
 *
 * Restore
 * Restore If ID is absent
 * Restore If Permission is absent by the role
 * Restore If Permission to department is absent
 * Restore Is Impossible due to deleted parent
 *
 * Permanent Destroy
 * Permanent Destroy If ID is absent
 * Permanent Destroy If Permission is absent by the role
 * Permanent Destroy If Permission to department is absent
 * Permanent Destroy Is Impossible due to soft-deleted child
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
     * SetUp with TestsSeeder
     *
     * @return mixed
     */
    public function setUp()
    : void
    {
        parent::setUp();

        $this->artisan('db:seed --class=TestsSeeder');
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
     * Check seeder.
     *
     * @return void
     */
    public function testSeeder()
    {
        $organizations = DB::table('organizations')->get();
        $this->assertEquals(17, $organizations->count());

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Volodymyr Vadiasov', $user->name);
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
        $token = $this->loginDeveloper();

        $response = $this->get('api/organizations?token=' . $token);

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
                            'name',
                            'parent_id',
                            'deleted_at',
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


        $this->assertEquals(17, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Platform', $data[0]['name']);
        $this->assertEquals('0', $data[0]['level']);
        $this->assertEquals('1', $data[0]['order']);
        $this->assertEquals('', $data[0]['parent_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Organizations retrieved successfully.", $message);
        $this->assertEquals(true, $success);

    }

    /**
     * Check Index With Not Full Permissions
     *   Check login
     *   Get index
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexWithNotFullPermissions()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

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
                            'name',
                            'parent_id',
                            'deleted_at',
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

        $this->assertEquals(8, count($data));
        $this->assertEquals(2, $data[0]['id']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[0]['name']);
        $this->assertEquals(1, $data[0]['level']);
        $this->assertEquals(1, $data[0]['order']);
        $this->assertEquals(1, $data[0]['parent_id']);
        $this->assertEquals("Organizations retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index SoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeleted()
    {
        $token = $this->loginDeveloper();

        // preparation
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response = $this->delete('api/organizations/16?token=' . $token);

        // request
        $response = $this->get('api/organizations/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data' =>
                    [
                        [
                            'id',
                            'level',
                            'order',
                            'name',
                            'parent_id',
                            'deleted_at',
                            'created_at',
                            'updated_at',
                            'subline'
                        ]
                    ],
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array

        $this->assertEquals(2, count($data));
        $this->assertEquals(16, $data[0]['id']);
        $this->assertEquals('Administrative assistant', $data[0]['name']);
        $this->assertEquals('3', $data[0]['level']);
        $this->assertEquals('1', $data[0]['order']);
        $this->assertEquals('10', $data[0]['parent_id']);
        $this->assertEquals('Spring Sheet Metal & Roofing Co.:Administration', $data[0]['subline']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Soft-deleted customers are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
    }

    /**
     * Check Index SoftDeleted If Content Is Empty
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfContentIsEmpty()
    {
        $token = $this->loginDeveloper();

        // request
        $response = $this->get('api/organizations/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index SoftDeleted If Permission Is Absent Due To Role
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfPermissionIsAbsentDueToRole()
    {
        $token = $this->loginDeveloper();

        // preparation
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response = $this->delete('api/organizations/16?token=' . $token);

        // request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->get('api/organizations/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(453);


        $responseJSON = json_decode($response->getContent(), true);
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals("Permission is absent by the role.", $message);
        $this->assertEquals(false, $success);
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
        $token = $this->loginDeveloper();

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
                        'level',
                        'order',
                        'name',
                        'parent_id',
                        'deleted_at',
                        'created_at',
                        'updated_at'
                    ],
                'message'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals('Item is retrieved successfully.', $message);
        $this->assertEquals('Western New York Exteriors, LLC.', $data['name']);
        $this->assertEquals(1, $data['level']);
        $this->assertEquals(1, $data['order']);
        $this->assertEquals(1, $data['parent_id']);
        $this->assertEquals(null, $data['deleted_at']);
    }

    /**
     * Check Show If Permission is absent by the role
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfPermissionIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/organizations/2?token=' . $token, []);

        // Check response status
        $response->assertStatus(453);

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
        $this->assertEquals('Permission is absent by the role.', $message);
    }

    /**
     * Check Show If Permission to department is absent
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfPermissionToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->get('api/organizations/2?token=' . $token, []);

        // Check response status
        $response->assertStatus(454);

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
        $this->assertEquals("Permission to department is absent.", $message);
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
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/organizations/1111?token=' . $token, []);

        // Check response status
        $response->assertStatus(456);

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
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals('Item is absent.', $message);
        $this->assertEquals(456, $code);
        $this->assertEquals(null, $data);

    }

//    /**
//     * Check store:
//     *   Check login
//     *   Store a new organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *   Get DB table Organizations and check last Organization
//     */
//    public function testStore()
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
//        // Create data
//        $data = [
//            'name'      => 'Department 3',
//            'order'     => '4',
//            'parent_id' => 2
//        ];
//
//        // Store a new organization
//        $response = $this->post('api/organizations?token=' . $token, $data, []);
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
//        $this->assertEquals("New Organization is created successfully.", $message);
//
//        // Check DB
//        $organization = DB::table('organizations')->where('name', 'Department 3')->first();
//
//        $this->assertEquals(8, $organization->id);
//        $this->assertEquals(2, $organization->parent_id);
//        $this->assertEquals(3, $organization->level);
//    }
//
//    /**
//     * Check store The given data was invalid:
//     *   Check login
//     *   Store a new organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreIfTheGivenDataWasInvalid()
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
//        // Create data
//        $data = [
//            'order'     => 'b',
//            'name'      => '',
//            'parent_id' => 'c'
//        ];
//
//        // Store a new organization
//        $response = $this->post('api/organizations?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(452);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'errors'
//            ]
//        );
//
//        ///Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $errors       = $responseJSON['errors'];  // array
//
//        $this->assertEquals("The given data was invalid.", $errors['message']);
//        $this->assertEquals(3, count($errors['errors']));
//    }
//
//    /**
//     * Check store Permission is absent Due To Role:
//     *   Check login
//     *   Store a new organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreIfPermissionIsAbsentDueToRole()
//    {
//        // Check login
//        $response = $this->post('api/auth/login', [
//            'email'    => 'estimator@email.com',
//            'password' => '123456'
//        ]);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $token        = $responseJSON['token'];
//
//        $this->get('api/auth/me?token=' . $token, [])->assertJson([
//            'name'  => 'Estimator',
//            'email' => 'estimator@email.com'
//        ])->isOk();
//
//        // Create data
//        $data = [
//            'order'     => '3',
//            'name'      => 'Test Test',
//            'parent_id' => 3
//        ];
//
//        // Store a new organization
//        $response = $this->post('api/organizations?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(453);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                "success",
//                "message"
//            ]
//        );
//
//        ///Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission is absent by the role.", $message);
//    }
//
//    /**
//     * Check update:
//     *   Check login
//     *   Update the Organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdate()
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
//        // Create data
//        $data = [
//            'name'      => 'Department 1 Edited',
//            'order'     => 1,
//            'parent_id' => 4
//        ];
//
//        $response = $this->put('api/organizations/5/?token=' . $token, $data);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//        $data         = json_decode($data);
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals("Organization is updated successfully.", $message);
//        $this->assertEquals("Department 1 Edited", $data->name);
//        $this->assertEquals(4, $data->level);
//        $this->assertEquals(4, $data->parent_id);
//    }
//
//    /**
//     * Check update If The Id Is Absent:
//     *   Check login
//     *   Update the Organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfTheIdIsAbsent()
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
//        // Create data
//        $data = [
//            'name'      => 'Department 1 Edited',
//            'order'     => '2',
//            'parent_id' => 1
//        ];
//
//        $response = $this->put('api/organizations/33/?token=' . $token, $data);
//
//        $response->assertStatus(452);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Could not find Organization.", $message);
//    }
//
//    /**
//     * Check update The given data was invalid:
//     *   Check login
//     *   Update the Organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfTheGivenDataWasInvalid()
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
//        // Create data
//        $data = [
//            'name'      => '',
//            'order'     => 'a',
//            'parent_id' => 'c'
//        ];
//
//        $response = $this->put('api/organizations/2/?token=' . $token, $data);
//
//        $response->assertStatus(452);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'errors'
//            ]
//        );
//
//        ///Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $errors       = $responseJSON['errors'];  // array
//
//        $this->assertEquals("The given data was invalid.", $errors['message']);
//        $this->assertEquals(3, count($errors['errors']));
//    }
//
//    /**
//     * Check update Permission is absent by the role:
//     *   Check login
//     *   Update the Organization
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfPermissionIsAbsentByTheRole()
//    {
//        // Check login
//        $response = $this->post('api/auth/login', [
//            'email'    => 'estimator@email.com',
//            'password' => '123456'
//        ]);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $token        = $responseJSON['token'];
//
//        $this->get('api/auth/me?token=' . $token, [])->assertJson([
//            'name'  => 'Estimator',
//            'email' => 'estimator@email.com'
//        ])->isOk();
//
//        // Create data
//        $data = [
//            'name'      => 'Test',
//            'order'     => 1,
//            'parent_id' => 3
//        ];
//
//        $response = $this->put('api/organizations/4?token=' . $token, $data);
//
//        $response->assertStatus(453);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                "success",
//                "message"
//            ]
//        );
//
//        ///Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission is absent by the role.", $message);
//    }
//
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
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/organizations/17?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is deleted successfully.", $message);

        $organization = DB::table('organizations')->where('id', 17)->first();
        $this->assertNotEquals(null, $organization->deleted_at);
    }

    /**
     * Delete If Access Is not Full
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB
     */
    public function testDeleteIfAccessIsNotFull()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->delete('api/organizations/17?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Organization is deleted successfully.", $message);

        $organization = DB::table('organizations')->where('id', 17)->first();
        $this->assertNotEquals(null, $organization->deleted_at);
    }

    /**
     * Delete If ID is absent
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfIdIsAbsent()
    {
        $token = $this->loginDeveloper();

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
     * Delete If Permission is absent by the role:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfPermissionIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->delete('api/organizations/17/?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Delete If The Permission to department is absent
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteIfPermissionToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->delete('api/organizations/17?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Delete Impossible to destroy due to child
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testDeleteImpossibleToDestroyDueToChild()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/organizations/8?token=' . $token, []);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Impossible to destroy due to child.", $message);
    }

    /**
     * Delete Is Impossible Due To Customer Of Organization
     */
    public function testDeleteIsImpossibleDueToCustomerOfOrganization()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-profiles/8?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/7?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/8?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/7?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/6?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/5?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/4?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/3?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/organizations/2?token=' . $token);
        $response->assertStatus(462);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(462, $code);
        $this->assertEquals("Id of this Entity is used as foreign key", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Delete Is Impossible Due To User Of Organization
     */
    public function testDeleteIsImpossibleDueToUserOfOrganization()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/8?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/organizations/7?token=' . $token);
        $response->assertStatus(462);

        $responseJSON = json_decode($response->getContent(), true);
        $message      = $responseJSON['message'];
        $this->assertEquals("There is profile.", $message);
    }

    /**
     * Check Restore
     */
    public function testRestore()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response     = $this->delete('api/organizations/17?token=' . $token);
        $organization = Organization::onlyTrashed()->where('id', 17)->first();
        $response->assertStatus(200);
        $this->assertNotEquals(null, $organization->deleted_at);

        // Request
        $response = $this->put('api/organizations/17/restore?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Organization is restored successfully.", $message);
        $this->assertEquals(null, $data);

        $organization = Organization::where('id', 17)->first();
        $this->assertEquals(null, $organization->deleted_at);
    }

    /**
     * Check Restore If The ID Is Wrong
     */
    public function testRestoreIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/organizations/4444/restore?token=' . $token, []);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Incorrect the Entity ID in the URL", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Restore If Permission is absent by the role
     */
    public function testRestoreIfPermissionIsAbsentByTheRole()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->put('api/organizations/17/restore?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Restore Permission to department is absent
     */
    public function testRestoreIfPermissionToDepartmentIsAbsent()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/organizations/17?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationSpringSuperadmin();
        $response = $this->put('api/organizations/17/restore?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Restore Is Impossible due to deleted parent
     */
    public function testRestoreIfItIsImpossibleDueToDeletedParent()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/organizations/17?token=' . $token, []);
        $response = $this->delete('api/organizations/8?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/organizations/17/restore?token=' . $token, []);

        $response->assertStatus(455);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(455, $code);
        $this->assertEquals("There is a parent soft-deleted organization.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Delete Permanently
     */
    public function testDeletePermanently()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/organizations/17?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/organizations/17/permanently?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Organization is deleted permanently.", $message);
        $this->assertEquals(null, $data);

        $organization = DB::table('organizations')->where('id', 17)->first();
        $this->assertEquals(null, $organization);
    }

    /**
     * Check Delete Permanently If The ID Is Wrong
     */
    public function testDeletePermanentlyIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/organizations/5555/permanently?token=' . $token, []);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Incorrect the Entity ID in the URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Permanent Destroy Permission is absent by the role
     */
    public function testDeletePermanentlyIfPermissionIsAbsentByTheRole()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/organizations/17?token=' . $token);

        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->delete('api/organizations/17/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Permanent Destroy Permission to department is absent
     */
    public function testDeletePermanentlyIfPermissionToDepartmentIsAbsent()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/organizations/17?token=' . $token);

        // Request
        $token    = $this->loginOrganizationSpringSuperadmin();
        $response = $this->delete('api/organizations/6/permanently?token=' . $token);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Permanent Destroy Is Impossible due to soft-deleted child
     */
    public function testDeletePermanentlyIfThisIsImpossibleDueToSoftDeletedChild()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response = $this->delete('api/organizations/8?token=' . $token);

        // Request
        $response = $this->delete('api/organizations/8/permanently?token=' . $token);

        $response->assertStatus(455);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(455, $code);
        $this->assertEquals("There is a child soft-deleted organization.", $message);
        $this->assertEquals(null, $data);
    }
}
