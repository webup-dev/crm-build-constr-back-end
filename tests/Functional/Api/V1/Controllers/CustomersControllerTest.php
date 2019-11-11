<?php

/**
 * SetUp:
 *   Create 3 user
 *   Create 2 roles
 *   Bind users and roles
 *   Create 1 department
 *   Create 2 profiles
 *
 * Check Index
 * Check Index If The Access Is Not Full
 * Check Index If Content Is Empty
 * Check Index If The Access Is Absent By The Role
 *
 * Check IndexSoftDeleted
 * Check IndexSoftDeleted If Content Is Empty
 * Check IndexSoftDeleted If Access Is Absent:
 *
 * Check store
 * Check store If The Access Is Not Full
 * Check store invalid data
 * Check store If Access Of Role Is Absent:
 * Check store If Access To Department Is Absent:
 *
 * Check show
 * Check show If The Access Is Not Full
 * Check show with absent ID
 * Check show If The Access Is Absent By The Role
 * Check show If Access To Department Is Absent
 *
 * Check update
 * Check update If The Access Is Not Full
 * Check update If Data Is Invalid
 * Check update If The Id Is Wrong
 * Check update If The Access Is Absent By The Role
 * Check update If The Access To The Department Is Absent
 *
 * Check Soft Delete
 * Check Soft Delete If The Access Is Not Full
 * Check Soft Delete If The Access Is Absent By the Role
 * Check Soft Delete If The Id Is Wrong:
 *
 * Check Restore
 * Check Restore If The Access Is Absent By the Role
 * Check Restore If The ID Is Wrong:
 *
 * Check Delete Permanently
 * Check Delete Permanently If The Access Is Absent:
 * Check Delete Permanently If The ID Is Wrong:
 */

namespace App;

use App\Models\Customer;
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

class CustomersControllerTest extends WnyTestCase
{
    /**
     * SetUp:
     *   Create 5 users (user A, user B, user C, customer A, customer B)
     *   Create 3 roles (superadmin, organization-general-manager, customer-individual)
     *   Create 2 customers (customer A, customer B)
     *   Create 2 organizations (organization 1, organization 2)
     *   Create 3 user_profiles (user A, user B, user C)
     *   Bind users and roles (user A - superadmin, user B - organization-general-manager, user C - superadmin, customer A - customer-individual, customer B - customer-individual)
     *   Bind users and user_profiles (user A - user A, user B - user B, user C - user C)
     *   Bind user_profiles and organizations (user A - organization 1, user B - organization 1, user C - organization 2)
     *   Bind users and customers (customer A - customer A, customer B - customer B)
     *   Bind customers and organizations (customer A - organization 1, customer B - organization 1)
     *
     * @return mixed
     */
    public function setUp()
    : void
    {
        parent::setUp();

        /*
         | User        | User ID | Role                         | Organization       | Organization ID |
         |-------------|---------|------------------------------|--------------------|-----------------|
         | User A      | 1       | superadmin                   | Central Department | 1               |
         | User B      | 2       | organization-general-manager | Central Department | 1               |
         | User C      | 3       | organization-general-manager | Branch Department  | 2               |
         | User D      | 6       | superadmin                   | Branch Department  | 2               |
         | Customer A  | 4       | customer-individual          | Central Department | 1               |
         | Customer B  | 5       | customer-individual          | Branch Department  | 2               |
         */
        $user1 = new User([
            'name'     => 'User A',
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $user1->save();

        $user2 = new User([
            'name'     => 'User B',
            'email'    => 'userB@email.com',
            'password' => '123456'
        ]);

        $user2->save();

        $user3 = new User([
            'name'     => 'User C',
            'email'    => 'userC@email.com',
            'password' => '123456'
        ]);

        $user3->save();

        $user4 = new User([
            'name'     => 'Customer A',
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $user4->save();

        $user5 = new User([
            'name'     => 'Customer B',
            'email'    => 'customerB@email.com',
            'password' => '123456'
        ]);

        $user5->save();

        $user6 = new User([
            'name'     => 'User D',
            'email'    => 'userD@email.com',
            'password' => '123456'
        ]);

        $user6->save();

        $role1 = new Role([
            'name' => 'developer'
        ]);

        $role1->save();

        $role2 = new Role([
            'name' => 'organization-general-manager'
        ]);

        $role2->save();

        $role3 = new Role([
            'name' => 'customer-individual'
        ]);

        $role3->save();

        $user1->roles()->attach(1);
        $user2->roles()->attach(2);
        $user3->roles()->attach(2);
        $user4->roles()->attach(3);
        $user5->roles()->attach(3);
        $user6->roles()->attach(1);

        $department1 = new Organization([
            'name' => 'Central Department'
        ]);

        $department1->save();

        $department2 = new Organization([
            'name' => 'Branch Department'
        ]);

        $department2->save();

        $customer1 = new Customer([
            'user_id'         => 4,
            'name'            => 'Customer A',
            'type'            => 'individual',
            'note'            => 'test note',
            'organization_id' => 1
        ]);

        $customer1->save();

        $customer2 = new Customer([
            'user_id'         => 5,
            'name'            => 'Customer B',
            'type'            => 'individual',
            'note'            => 'test note',
            'organization_id' => 2
        ]);

        $customer2->save();

        $userProfile1 = User_profile::create([
            'user_id'          => 1,
            'first_name'       => 'User A',
            'last_name'        => 'User A',
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
            'first_name'       => 'User B',
            'last_name'        => 'User B',
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

        $userProfile2->save();

        $userProfile3 = User_profile::create([
            'user_id'          => 3,
            'first_name'       => 'User C',
            'last_name'        => 'User C',
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
            'user_id'          => 6,
            'first_name'       => 'User D',
            'last_name'        => 'User D',
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

        $userProfile4->save();
    }

    use DatabaseMigrations;

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
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/customers?token=' . $token, []);

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
                            "name",
                            "organization_id",
                            "organization",
                            "type",
                            "note",
                            "deleted_at",
                            "created_at",
                            "updated_at"
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
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(4, $data[0]['user_id']);
        $this->assertEquals('Customer A', $data[0]['name']);
        $this->assertEquals('1', $data[0]['organization_id']);
        $this->assertEquals('individual', $data[0]['type']);
        $this->assertEquals('test note', $data[0]['note']);
        $this->assertEquals("Customers are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index If The Access Is Not Full
     *   User B id=2 role=organization-general-manager org=Central Department org_id=1
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfTheAccessIsNotFull()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userB@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User B',
            'email' => 'userB@email.com'
        ])->isOk();

        // Request
        $response = $this->get('api/customers?token=' . $token, []);

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
                            "name",
                            "organization_id",
                            "organization",
                            "type",
                            "note",
                            "deleted_at",
                            "created_at",
                            "updated_at"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals(1, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(4, $data[0]['user_id']);
        $this->assertEquals('Customer A', $data[0]['name']);
        $this->assertEquals('   1', $data[0]['organization_id']);
        $this->assertEquals('individual', $data[0]['type']);
        $this->assertEquals('test note', $data[0]['note']);
        $this->assertEquals("Customers are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index If Content Is Empty:
     *   Check login User 1
     *   Delete Customer 2
     *   Check login User 3
     *   Try to get list of customers of organization 2
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfContentIsEmpty()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userD@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User D',
            'email' => 'userD@email.com'
        ])->isOk();

        // Delete Customer 2
        $response = $this->delete('api/customers/2?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userC@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User C',
            'email' => 'userC@email.com'
        ])->isOk();

        $response = $this->get('api/customers?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index If The Access Is Absent By The Role:
     *   Customer A tries to get index
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function CheckIndexIfTheAccessIsAbsentByTheRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
        ])->isOk();

        $response = $this->get('api/customers?token=' . $token, []);

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
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Create soft deleted
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->get('api/customers/soft-deleted?token=' . $token, []);

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
                            "name",
                            "organization_id",
                            "organization",
                            "type",
                            "note",
                            "deleted_at",
                            "created_at",
                            "updated_at"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals(1, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Customer A', $data[0]['name']);
        $this->assertEquals('1', $data[0]['organization_id']);
        $this->assertEquals('4', $data[0]['user_id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Soft-deleted customers are retrieved successfully.", $message);
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
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        $response = $this->get('api/customers/soft-deleted?token=' . $token, []);

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
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
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
     * Check store:
     *   Check login
     *   Store a new Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB tables Customers
     */
    public function testStore()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Create data
        $data = [
            'first_name'      => 'Customer C',
            'last_name'       => 'CustomerC',
            'organization_id' => 1,
            'type'            => 'organization',
            'note'            => 'note test',
            'email'           => 'customerC@admin.com',
            'password'        => '12345678'
        ];

        // Store a new user, user-profile
        $response = $this->post('api/customers?token=' . $token, $data, []);

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
        $this->assertEquals("Customer is created successfully.", $message);

        // Check DB table users
        $user = DB::table('users')->where('email', '=', 'customerC@admin.com')->first();
        $this->assertEquals(7, $user->id);
        $this->assertEquals('Customer C CustomerC', $user->name);

        // Check DB table customers
        $customer = DB::table('customers')->where('user_id', '=', 7)->first();
        $this->assertEquals('Customer C CustomerC', $customer->name);
        $this->assertEquals('organization', $customer->type);
        $this->assertEquals('note test', $customer->note);
        $this->assertEquals(1, $customer->organization_id);
    }

    /**
     * Check store invalid data:
     *   Check login
     *   Store a new Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreInvalidData()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Create data
        $data = [
            'first_name'      => '',
            'last_name'       => '',
            'organization_id' => 'a',
            'type'            => '',
            'note'            => 12,
            'email'           => 'admin.com',
            'password'        => ''
        ];

        // Store a new user, user profile
        $response = $this->post('api/customers?token=' . $token, $data, []);

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
        $this->assertEquals(7, count($error['errors']));
    }

    /**
     * Check store If Access Of Role Is Absent:
     *   Check login Customer
     *   Store a new Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfAccessOfRoleIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('Customer A', $name);
        $this->assertEquals("customerA@email.com", $email);

        // Create data
        $data = [
            'first_name'      => 'Customer C',
            'last_name'       => 'CustomerC',
            'organization_id' => 2,
            'type'            => 'organization',
            'note'            => 'note test',
            'email'           => 'customerC@admin.com',
            'password'        => '12345678'
        ];

        // Store a new organization
        $response = $this->post('api/customers?token=' . $token, $data, []);

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
     * Check store If Access To Department Is Absent:
     *   Check login Customer
     *   Store a new Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testStoreIfAccessToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User A', $name);
        $this->assertEquals("userA@email.com", $email);

        // Create data
        $data = [
            'first_name'      => 'Customer C',
            'last_name'       => 'CustomerC',
            'organization_id' => 2,
            'type'            => 'organization',
            'note'            => 'note test',
            'email'           => 'customerC@admin.com',
            'password'        => '12345678'
        ];

        // Store a new organization
        $response = $this->post('api/customers?token=' . $token, $data, []);

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
     * Check show:
     *   Check login
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShow()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User A', $name);
        $this->assertEquals("userA@email.com", $email);

        $response = $this->get('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        'id',
                        'user_id',
                        'name',
                        'type',
                        'note',
                        'organization_id',
                        'deleted_at',
                        'created_at',
                        'updated_at',
                        'user',
                        'organization'
                    ],
                'message'
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Item is retrieved successfully.", $message);
        $this->assertEquals(4, $data['user_id']);
        $this->assertEquals('Customer A', $data['name']);
        $this->assertEquals('individual', $data['type']);
        $this->assertEquals('test note', $data['note']);
        $this->assertEquals(1, $data['organization_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals(4, $data['user']['id']);
        $this->assertEquals("customerA@email.com", $data['user']['email']);
        $this->assertEquals(1, $data['organization']['id']);
        $this->assertEquals('Central Department', $data['organization']['name']);
    }

    /**
     * Check show with Wrong ID:
     *   Check login
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowWithWrongId()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User A', $name);
        $this->assertEquals("userA@email.com", $email);

        $response = $this->get('api/customers/1111?token=' . $token, []);
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
        $this->assertEquals("Item is absent.", $message);
    }

    /**
     * Check show If Access Of Role Is Absent:
     *   Check login Customer
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfAccessOfRoleIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('Customer A', $name);
        $this->assertEquals("customerA@email.com", $email);

        $response = $this->get('api/customers/2?token=' . $token, []);
        $response->assertStatus(453);

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
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check show If Access To Department Is Absent:
     *   Check login Customer
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfAccessToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userB@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User B', $name);
        $this->assertEquals("userB@email.com", $email);

        $response = $this->get('api/customers/2?token=' . $token, []);
        $response->assertStatus(454);

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
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Check update:
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdate()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User A', $name);
        $this->assertEquals("userA@email.com", $email);

        // Create data
        $data = [
            'user_id'         => 4,
            'name'            => 'Customer A Updated',
            'type'            => 'individual',
            'note'            => 'note test',
            'organization_id' => 1
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data, []);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is updated successfully.", $message);
        $this->assertEquals("Customer A Updated", $data->name);

        // Check DB
        $customer = DB::table('customers')->where('name', 'Customer A Updated')->first();
        $this->assertEquals(1, $customer->id);
        $this->assertEquals('Customer A Updated', $customer->name);
    }

    /**
     * Check update If The Access Is Not Full
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheAccessIsNotFull()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $response = $this->get('api/auth/me?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $name         = $responseJSON['name'];  // array
        $email        = $responseJSON['email'];  // array

        $this->assertEquals('User A', $name);
        $this->assertEquals("userA@email.com", $email);

        // Create data
        $data = [
            'user_id'         => 4,
            'name'            => 'Customer A Updated',
            'type'            => 'individual',
            'note'            => 'note test',
            'organization_id' => 1
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data, []);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is updated successfully.", $message);
        $this->assertEquals("Customer A Updated", $data->name);

        // Check DB
        $customer = DB::table('customers')->where('name', 'Customer A Updated')->first();
        $this->assertEquals(1, $customer->id);
        $this->assertEquals('Customer A Updated', $customer->name);
    }

    /**
     * Check update If Data Is Invalid:
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfDataIsInvalid()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Create data
        $data = [
            'user_id'         => 'a',
            'name'            => '',
            'type'            => '',
            'note'            => 23,
            'organization_id' => 'aa'
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data);
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
        $this->assertEquals(5, count($error['errors']));
    }

    /**
     * Check update If The Id Is Wrong
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheIdIsWrong()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Create data
        $data = [
            'user_id'         => 4,
            'name'            => 'Customer A Updated',
            'type'            => 'individual',
            'note'            => 'note test',
            'organization_id' => 1
        ];

        $response = $this->put('api/customers/55555?token=' . $token, $data);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Customer does not exist.", $message);
    }

    /**
     * Check update If Access Of Role Is Absent:
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessOfRoleIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
        ])->isOk();

        // Create data
        $data = [
            'user_id'         => 4,
            'name'            => 'Customer A Updated',
            'type'            => 'individual',
            'note'            => 'note test',
            'organization_id' => 1
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check update If Access To Department Is Absent:
     *   Check login
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Update data
        $data = [
            'user_id'         => 4,
            'name'            => 'Customer A Updated',
            'type'            => 'individual',
            'note'            => 'note test',
            'organization_id' => 2
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
//
    /**
     * Check Soft Delete:
     *   We check that the customer account must change the field deleted_at from null to not null.
     *     Check login
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at of the soft-deleted row
     */
    public function testSoftDelete()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is soft-deleted successfully.", $message);

        $customer = DB::table('customers')->where('id', 1)->first();
        $this->assertNotEquals(null, $customer->deleted_at);

        $user = DB::table('users')->where('id', 4)->first();
        $this->assertNotEquals(null, $customer->deleted_at);
    }

    /**
     * Check Soft Delete If The Access Of Role Is Absent:
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheAccessOfRoleIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/2?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Soft Delete If The Access To Department Is Absent:
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheAccessToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userC@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User C',
            'email' => 'userC@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/2?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Soft Delete If The Id Is Wrong:
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheIdIsWrong()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/2222?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Customer is absent.", $message);
    }

    /**
     * Check Restore:
     *   We check that the Customer Account must change the field deleted_at from not null to null.
     *     Check login
     *     Soft delete customer
     *     Repair customer
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at
     */
    public function testRestore()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response  = $this->delete('api/customers/1?token=' . $token, []);
        $customer = Customer::onlyTrashed()->where('user_id', 4)->first();
        $response->assertStatus(200);
        $this->assertNotEquals(null, $customer->deleted_at);

        // Request
        $response = $this->put('api/customers/1/restore?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is restored successfully.", $message);

        $customer = Customer::where('user_id', 4)->first();
        $this->assertEquals(null, $customer->deleted_at);
        $user = User::where('id', $customer->user_id)->first();
        $this->assertEquals(null, $user->deleted_at);
    }

    /**
     * Check Restore If The Access Is Absent In Role:
     *   We wait for a message about error.
     *     Check login
     *     Soft delete customer
     *     Repair customer
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheAccessIsAbsentInRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response = $this->delete('api/customers/2?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/customers/2/restore?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Restore If The Access Is Absent For Department:
     *   We wait for a message about error.
     *     Check login
     *     Soft delete customer
     *     Repair customer
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheAccessIsAbsentForDepartment()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response = $this->delete('api/customers/2?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer A',
            'email' => 'customerA@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/customers/2/restore?token=' . $token, []);

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
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Request
        $response = $this->put('api/customers/2222/restore?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Customer is absent.", $message);
    }

    /**
     * Check Delete Permanently:
     *     Check login
     *     Soft delete customer
     *     Delete Permanently customer
     *     Check response status
     *     Check response structure
     *     Check DB: customer must be absent
     */
    public function testDeletePermanently()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);

        // Request
        $response = $this->delete('api/customers/1/permanently?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is deleted permanently.", $message);

        $customer = DB::table('customers')->where('id', 1)->first();
        $this->assertEquals(null, $customer);
    }

    /**
     * Check Delete Permanently If The Access To Department Is Absent:
     *   We wait for a message about error.
     *     Check login
     *     Soft delete customer
     *     Delete Permanently customer
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheAccessToDepartmentIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userC@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User C',
            'email' => 'userC@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/2/permanently?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Delete Permanently If The Access Of Role Is Absent:
     *   We wait for a message about error.
     *     Check login
     *     Soft delete customer
     *     Delete Permanently customer
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheAccessOfRoleIsAbsent()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'customerB@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Customer B',
            'email' => 'customerB@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/1/permanently?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Delete Permanently If The ID Is Wrong:
     *   We wait for a message about error.
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheIdIsWrong()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'userA@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'User A',
            'email' => 'userA@email.com'
        ])->isOk();

        // Request
        $response = $this->delete('api/customers/2222/permanently?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Customer is absent.", $message);
    }
}
