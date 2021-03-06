<?php

/**
 * SetUp:
 *   Create 3 user
 *   Create 2 roles
 *   Bind users and roles
 *   Create 1 department
 *   Create 2 profiles
 *
 * Check Index:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If There Are Not Profiles:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Index If Access Is Absent:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check IndexSoftDeleted:
 *   Check login
 *   Create SoftDeleted
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check IndexSoftDeleted If Content Is Empty:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check IndexSoftDeleted If Access Is Absent:
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
 * Check Show Own Profile:
 *   Check login
 *   Get specified item
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show If Access Is Absent:
 *   Check login
 *   Get specified item
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show Own Profile:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Show Not Existing Item:
 *   Check login
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store:
 *   Check login
 *   Store a new User Profile
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check DB tables UserProfiles, Users
 *
 * Check store invalid data:
 *   Check login
 *   Store a new User Profile
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store If Access Is Absent:
 *   Check login
 *   Store a new UserProfile
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
 * Check update own profile:
 *   Check login
 *   Update the Organization
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If The Id Is Wrong
 *   Check login
 *   Update the UserProfile
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Access Is Absent:
 *   Check login
 *   Update the UserProfile
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Soft Delete:
 *   User with full access from the Central Department deletes the profile of the user from Branch Department.
 *   We check that the user profile must change the field deleted_at from null to not null.
 *     Check login
 *     Check response status
 *     Check response structure
 *     Check DB: deleted_at of the soft-deleted row
 *
 * Check Soft Delete If The Access Is Absent:
 *   User with not appropriated access (organization estimator from the Branch Department, user #3) deletes the profile of the user #2 from Branch Department.
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Soft Delete If The Id Is Wrong:
 *   User with full access (superadmin from the Central Department, user #1) deletes the profile of not existing user #2222.
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Restore:
 *   User with full access from the Central Department (user #1) restore the soft-deleted profile of the user from Branch Department (user #3).
 *   We check that the user profile must change the field deleted_at from not null to null.
 *     Check login
 *     Soft delete user #3
 *     Repair user #3
 *     Check response status
 *     Check response structure
 *     Check DB: deleted_at of the ID=3
 *
 * Check Repair If The Access Is Absent:
 *   User with not appropriated access (organization estimator from the Branch Department, user #3) restores the profile of the user #2 from Branch Department.
 *   We wait for a message about error.
 *     Check login
 *     Soft delete user #2
 *     Repair user #2
 *     Check response status
 *     Check response structure
 *
 * Check Repair If The ID Is Wrong:
 *   User with full access (superadmin from the Central Department, user #1) repairs the profile of a not existing user #2222.
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Delete Permanently:
 *   User with full access from the Central Department (user #1) deletes permanently the soft-deleted profile of the user from Branch Department (user #3).
 *   We check that the user profile must change the field deleted_at from not null to null.
 *     Check login
 *     Soft delete user #3
 *     Delete Permanently #3
 *     Check response status
 *     Check response structure
 *     Check DB: row with ID=3 must be absent
 *
 * Check Delete Permanently If The Access Is Absent:
 *   User with not appropriated access (organization estimator from the Branch Department, user #3) deletes permanently the soft-deleted profile of the user #2 from Branch Department.
 *   We wait for a message about error.
 *     Check login
 *     Soft delete user #2
 *     Delete Permanently user #2
 *     Check response status
 *     Check response structure
 *
 * Check Delete Permanently If The ID Is Wrong:
 *   User with full access (superadmin from the Central Department, user #1) deletes permanently the profile of a not existing user #2222.
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 */

namespace App;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use App\Models\User_role;
use Hash;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\WnyTestCase;

class UserProfilesControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *   Create 3 user
     *   Create 2 roles
     *   Create 2 organizations
     *   Bind users and roles
     *   Create 2 profiles
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

        $user3 = new User([
            'name'     => 'Test 3',
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $user3->save();

        $user4 = new User([
            'name'     => 'Test 4',
            'email'    => 'test4@email.com',
            'password' => '123456'
        ]);

        $user4->save();

        $role1 = new Role([
            'name' => 'developer'
        ]);

        $role1->save();

        $role2 = new Role([
            'name' => 'organization-superadmin'
        ]);

        $role2->save();

        $role3 = new Role([
            'name' => 'organization-estimator'
        ]);

        $role3->save();

        $user1->roles()->attach(1);
        $user2->roles()->attach(2);
        $user3->roles()->attach(3);
        $user4->roles()->attach(3);

        $department1 = new Organization([
            'name' => 'Central Department'
        ]);

        $department1->save();

        $department2 = new Organization([
            'name'      => 'Branch Department',
            'parent_id' => 1
        ]);

        $department2->save();

        $department3 = new Organization([
            'name'      => 'Branch Department 1',
            'parent_id' => 2
        ]);

        $department3->save();

        $userProfile1 = User_profile::create([
            'user_id'          => 1,
            'first_name'       => 'TestA',
            'last_name'        => 'TestA',
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
            'user_id'          => 2,
            'first_name'       => 'TestB',
            'last_name'        => 'TestB',
            'title'            => '',
            'department_id'    => 2,
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

        $userProfile3 = User_profile::create([
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'TestC',
            'title'            => '',
            'department_id'    => 2,
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

        $userProfile3->save();

        $userProfile4 = User_profile::create([
            'user_id'          => 4,
            'first_name'       => 'TestD',
            'last_name'        => 'TestD',
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

        $userProfile4->save();
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
        $response = $this->get('api/user-profiles?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);
//        dd($response);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        [
                            "id",
                            "user_id",
                            "first_name",
                            "last_name",
                            "department_id",
                            "organization",
                            "status",
                            "start_date",
                            "termination_date",
                            "created_at",
                            "updated_at",
                            "organization"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals(4, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('TestA', $data[0]['first_name']);
        $this->assertEquals('1', $data[0]['department_id']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertEquals("User Profiles are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index If Access Is Not Full:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfAccessIsNotFull()
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

        $response = $this->get('api/user-profiles?token=' . $token, []);

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
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(2, count($data));
        $this->assertEquals(3, $data[1]['id']);
        $this->assertEquals('TestC', $data[1]['first_name']);
        $this->assertEquals('2', $data[1]['department_id']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertEquals("User Profiles are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index If Access Is Absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        $response = $this->get('api/user-profiles?token=' . $token, []);

        // Check response status
        $response->assertStatus(453);

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

        $this->assertEquals("Permission is absent by the role.", $message);
        $this->assertEquals(false, $success);
    }

    /**
     * Check IndexSoftDeleted:
     *   Check login
     *   Create SoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeleted()
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

        // Create soft deleted
        $response = $this->delete('api/user-profiles/3?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/2?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->get('api/user-profiles/soft-deleted?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        [
                            "id",
                            "user_id",
                            "first_name",
                            "last_name",
                            "department_id",
                            "organization",
                            "status",
                            "start_date",
                            "termination_date",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals(2, count($data));
        $this->assertEquals(2, $data[0]['id']);
        $this->assertEquals('TestB', $data[0]['first_name']);
        $this->assertEquals('2', $data[0]['department_id']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("User and User Profiles are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check IndexSoftDeleted If Content Is Empty:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfContentIsEmpty()
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

        $response = $this->get('api/user-profiles/soft-deleted?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check IndexSoftDeleted If Access Is Absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test4@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 4',
            'email' => 'test4@email.com'
        ])->isOk();

        $response = $this->get('api/user-profiles/soft-deleted?token=' . $token, []);

        // Check response status
        $response->assertStatus(453);

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
        $response = $this->get('api/user-profiles/3?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        "id",
                        "user_id",
                        "first_name",
                        "last_name",
                        "title",
                        "department_id",
                        "organization",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob",
                        "email_personal",
                        "email_work",
                        "address_line_1",
                        "address_line_2",
                        "city",
                        "state",
                        "zip",
                        "status",
                        "start_date",
                        "termination_date",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals('User Profile is retrieved successfully.', $message);
        $this->assertEquals('TestC', $data['last_name']);
        $this->assertEquals(2, $data['department_id']);
    }

    /**
     * Check Show Own Profile:
     * organization-estimator tries to open his profile
     *   Check login
     *   Get specified item
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowOwnProfile()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/user-profiles/3?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        "id",
                        "user_id",
                        "first_name",
                        "last_name",
                        "title",
                        "department_id",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob",
                        "email_personal",
                        "email_work",
                        "address_line_1",
                        "address_line_2",
                        "city",
                        "state",
                        "zip",
                        "status",
                        "start_date",
                        "termination_date",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals('User Profile is retrieved successfully.', $message);
        $this->assertEquals('TestC', $data['last_name']);
        $this->assertEquals(2, $data['department_id']);
    }

    /**
     * Check Show Own Profile:
     *   Check login
     *   Get specified item
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowProfileFromChild()
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
        $response = $this->get('api/user-profiles/4?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        "id",
                        "user_id",
                        "first_name",
                        "last_name",
                        "title",
                        "department_id",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob",
                        "email_personal",
                        "email_work",
                        "address_line_1",
                        "address_line_2",
                        "city",
                        "state",
                        "zip",
                        "status",
                        "start_date",
                        "termination_date",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals('User Profile is retrieved successfully.', $message);
        $this->assertEquals('TestD', $data['last_name']);
        $this->assertEquals(3, $data['department_id']);
    }

    /**
     * Check show If Access Is Absent:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/user-profiles/2?token=' . $token, []);

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
        $response = $this->get('api/user-profiles/1111?token=' . $token, []);

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
        $this->assertEquals('User Profile does not exist.', $message);
    }

    /**
     * Check store:
     *   Check login
     *   Store a new User Profile
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB tables UserProfiles, Users
     */
    public function testStore()
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

        // Create data
        $data = [
            'first_name'       => 'TestD',
            'last_name'        => 'TestD',
            'title'            => '',
            'department_id'    => 2,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => 'testD@admin.com',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => '2019-10-29',
            'termination_date' => null,
            'deleted_at'       => null
        ];

        // Store a new user, user-profile
        $response = $this->post('api/user-profiles?token=' . $token, $data, []);

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
        $this->assertEquals("User and User Profile are created successfully.", $message);

        // Check DB table users
        $user = DB::table('users')->where('email', '=', 'testD@admin.com')->first();
        $this->assertEquals(5, $user->id);
        $this->assertEquals('TestD TestD', $user->name);

        // Check DB table user_profiles
        $userProfile = DB::table('user_profiles')->where('user_id', '=', 5)->first();
        $this->assertEquals('TestD', $userProfile->first_name);
        $this->assertEquals(2, $userProfile->department_id);
    }

    /**
     * Check store invalid data:
     *   Check login
     *   Store a new User, User Profile
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreInvalidData()
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

        // Create data
        $data = [
            'first_name'       => null,
            'last_name'        => null,
            'title'            => 1,
            'department_id'    => null,
            'phone_home'       => null,
            'phone_work'       => null,
            'phone_extension'  => null,
            'phone_mob'        => null,
            'email_personal'   => null,
            'email_work'       => null,
            'address_line_1'   => null,
            'address_line_2'   => null,
            'city'             => null,
            'state'            => null,
            'zip'              => null,
            'status'           => null,
            'start_date'       => '2019-10-29 56',
            'termination_date' => '2019-10-29 56',
            'deleted_at'       => '2019-10-29 5655555555'
        ];

        // Store a new user, user profile
        $response = $this->post('api/user-profiles?token=' . $token, $data, []);

        // Check response status
        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'error' =>
                    [
                        'message',
                        'errors'
                    ]
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $error        = $responseJSON['error'];  // array

        $this->assertEquals("The given data was invalid.", $error['message']);
        $this->assertEquals(19, count($error['errors']));
    }

    /**
     * Check store If Access Is Absent:
     *   Check login
     *   Store a new UserProfile
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Create data
        // Create data
        $data = [
            'first_name'       => 'TestD',
            'last_name'        => 'TestD',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        // Store a new organization
        $response = $this->post('api/user-profiles?token=' . $token, $data, []);

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

        // Create data
        $data = [
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'Test3Updated',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        $response = $this->put('api/user-profiles/3?token=' . $token, $data);
//        dd($response);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("User Profile is updated successfully.", $message);
        $this->assertEquals("Test3Updated", $data->last_name);

        // Check DB
        $userProfile = DB::table('user_profiles')->where('last_name', 'Test3Updated')->first();
        $this->assertEquals(3, $userProfile->id);
        $this->assertEquals(2, $userProfile->department_id);
    }

    /**
     * Check update:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateOwnProfile()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Create data
        $data = [
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'Test3Updated',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        $response = $this->put('api/user-profiles/3?token=' . $token, $data);
//        dd($response);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("User Profile is updated successfully.", $message);
        $this->assertEquals("Test3Updated", $data->last_name);

        // Check DB
        $userProfile = DB::table('user_profiles')->where('last_name', 'Test3Updated')->first();
        $this->assertEquals(3, $userProfile->id);
        $this->assertEquals(2, $userProfile->department_id);
    }

    /**
     * Check update If The Id Is Wrong:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheIdIsWrong()
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

        // Create data
        $data = [
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'Test3Updated',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        $response = $this->put('api/user-profiles/55555?token=' . $token, $data);

        $response->assertStatus(452);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("User Profile does not exist.", $message);
    }

    /**
     * Check update If Access Is Not Full:
     *   Check login
     *   Update the Organization
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessIsNotFull()
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

        // Create data
        $data = [
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'Test3Updated',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        $response = $this->put('api/user-profiles/3?token=' . $token, $data);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("User Profile is updated successfully.", $message);
        $this->assertEquals("Test3Updated", $data->last_name);

        // Check DB
        $userProfile = DB::table('user_profiles')->where('last_name', 'Test3Updated')->first();
        $this->assertEquals(3, $userProfile->id);
        $this->assertEquals(2, $userProfile->department_id);
    }

    /**
     * Check update If Access Is Absent:
     *   Check login
     *   Update the UserProfile
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Create data
        $data = [
            'user_id'          => 3,
            'first_name'       => 'TestC',
            'last_name'        => 'Test3Updated',
            'title'            => '',
            'department_id'    => 2,
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
        ];

        $response = $this->put('api/user-profiles/1?token=' . $token, $data);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Soft Delete:
     * User with full access frm Central Department deletes the profile of the user from Branch Department.
     * We check that the user profile must change the field deleted_at from null to not null.
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check DB: deleted_at of the soft-deleted row
     */
    public function testSoftDelete()
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
        $response = $this->delete('api/user-profiles/3?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("User is soft-deleted successfully.", $message);

        $userProfile = DB::table('user_profiles')->where('id', 3)->first();
        $this->assertNotEquals(null, $userProfile->deleted_at);
    }

    /**
     * Check Soft Delete If The Access Is Not Full:
     *   User with restricted access (organization superadmin from the Branch Department) deletes the profile of the user from its Department.
     *   We check that the user profile must change the field deleted_at from null to not null.
     *     Check login
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at of the soft-deleted row
     */
    public function testSoftDeleteIfTheAccessIsNotFull()
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
        $response = $this->delete('api/user-profiles/3?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("User is soft-deleted successfully.", $message);

        $userProfile = DB::table('user_profiles')->where('id', 3)->first();
        $this->assertNotEquals(null, $userProfile->deleted_at);
    }

    /**
     * Check Soft Delete If The Access Is Absent:
     *   User with not appropriated access (organization estimator from the Branch Department, user #3) deletes the profile of the user #2 from Branch Department.
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheAccessIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/user-profiles/2?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Soft Delete If The Id Is Wrong:
     *   User with full access (superadmin from the Central Department, user #1) deletes the profile of not existing user #2222.
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheIdIsWrong()
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
        $response = $this->delete('api/user-profiles/2222?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("User Profile is absent.", $message);
    }

    /**
     * Check Restore:
     *   User with full access from the Central Department (user #1) restore the soft-deleted profile of the user from Branch Department (user #3).
     *   We check that the user profile must change the field deleted_at from not null to null.
     *     Check login
     *     Soft delete user #3
     *     Repair user #3
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at of the ID=3
     */
    public function testRestore()
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

        // Preparation
        $response = $this->delete('api/user-profiles/3?token=' . $token, []);
        $userRoles = User_role::onlyTrashed()->where('user_id', 3)->get();
        $response->assertStatus(200);
        $userProfile = User_profile::onlyTrashed()->where('id', 3)->first();
        $this->assertNotEquals(null, $userProfile->deleted_at);
        $user = User::onlyTrashed()->where('id', $userProfile->user_id)->first();
        $this->assertNotEquals(null, $user->deleted_at);
        $userRoles = User_role::onlyTrashed()->where('user_id', $userProfile->user_id)->get();
        $this->assertNotEquals(null, $userRoles[0]->deleted_at);

        // Request
        $response = $this->put('api/user-profiles/3/restore?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("User is restored successfully.", $message);

        $userProfile = User_profile::where('id', 3)->first();
        $this->assertEquals(null, $userProfile->deleted_at);
        $user = User::where('id', $userProfile->user_id)->first();
        $this->assertEquals(null, $user->deleted_at);
        $userRole = User_role::where('user_id', $userProfile->user_id)->first();
        $this->assertEquals(null, $userRole->deleted_at);
    }

    /**
     * Check Restore If The Access Is Absent:
     *   User with not appropriated access (organization estimator from the Branch Department, user #3) restores the profile of the user #2 from Branch Department.
     *   We wait for a message about error.
     *     Check login
     *     Soft delete user #2
     *     Restore user #2
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheAccessIsAbsent()
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

        // Preparation
        $response = $this->delete('api/user-profiles/2?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/user-profiles/2/restore?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Restore If The ID Is Wrong:
     *   User with not full access (superadmin from the Central Department, user #1) restores the profile of a not existing user #2222.
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheIdIsWrong()
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
        $response = $this->put('api/user-profiles/2222/restore?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("User Profile is absent.", $message);
    }

    /**
     * Check Delete Permanently:
     *   User with full access from the Central Department (user #1) deletes permanently the soft-deleted profile of the user from Branch Department (user #3).
     *   We check that the user profile must change the field deleted_at from not null to null.
     *     Check login
     *     Soft delete user #3
     *     Delete Permanently #3
     *     Check response status
     *     Check response structure
     *     Check DB: row with ID=3 must be absent
     */
    public function testDeletePermanently()
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

        // Preparation
        $response = $this->delete('api/user-profiles/3?token=' . $token, []);

        // Request
        $response = $this->delete('api/user-profiles/3/permanently?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("User is deleted permanently.", $message);

        $userProfile = DB::table('user_profiles')->where('id', 3)->first();
        $this->assertEquals(null, $userProfile);
    }

    /**
     * Check Delete Permanently If The Access Is Absent:
     *   User with not appropriated access (organization estimator from the Branch Department, user #3) deletes permanently the soft-deleted profile of the user #2 from Branch Department.
     *   We wait for a message about error.
     *     Check login user #1
     *     Soft delete user #2
     *     Check login user #3
     *     Delete Permanently user #2
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheAccessIsAbsent()
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

        // Preparation
        $response = $this->delete('api/user-profiles/2?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'test3@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Test 3',
            'email' => 'test3@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/user-profiles/2/permanently?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Delete Permanently If The ID Is Wrong:
     *   User with full access (superadmin from the Central Department, user #1) deletes permanently the profile of a not existing user #2222.
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheIdIsWrong()
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
        $response = $this->delete('api/user-profiles/2222/permanently?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("User Profile is absent.", $message);
    }
}
