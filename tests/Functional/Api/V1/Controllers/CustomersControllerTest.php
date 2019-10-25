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
 * Check Index If Content Is Empty:
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
 * Check store:
 *   Check login
 *   Store a new Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *   Check DB tables Customers
 *
 * Check store invalid data:
 *   Check login
 *   Store a new Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store If Access Of Role Is Absent:
 *   Check login Customer
 *   Store a new Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check store If Access To Department Is Absent:
 *   Check login Customer
 *   Store a new Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update:
 *   Check login
 *   Update the Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Data Is Invalid:
 *   Check login
 *   Update the Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If The Id Is Wrong
 *   Check login
 *   Update the Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check update If Access Is Absent:
 *   Check login
 *   Update the Customer
 *   Check response status
 *   Check response structure
 *   Check response data
 *
 * Check Soft Delete:
 *   We check that the customer account must change the field deleted_at from null to not null.
 *     Check login
 *     Check response status
 *     Check response structure
 *     Check DB: deleted_at of the soft-deleted row
 *
 * Check Soft Delete If The Access Is Absent:
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Soft Delete If The Id Is Wrong:
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Restore:
 *   We check that the Customer Account must change the field deleted_at from not null to null.
 *     Check login
 *     Soft delete customer
 *     Repair customer
 *     Check response status
 *     Check response structure
 *     Check DB: deleted_at
 *
 * Check Restore If The Access Is Absent:
 *   We wait for a message about error.
 *     Check login
 *     Soft delete customer
 *     Repair customer
 *     Check response status
 *     Check response structure
 *
 * Check Repair If The ID Is Wrong:
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
 *
 * Check Delete Permanently:
 *     Check login
 *     Soft delete customer
 *     Delete Permanently customer
 *     Check response status
 *     Check response structure
 *     Check DB: customer must be absent
 *
 * Check Delete Permanently If The Access Is Absent:
 *   We wait for a message about error.
 *     Check login
 *     Soft delete customer
 *     Delete Permanently customer
 *     Check response status
 *     Check response structure
 *
 * Check Delete Permanently If The ID Is Wrong:
 *   We wait for a message about error.
 *     Check login
 *     Check response status
 *     Check response structure
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

        $role1 = new Role([
            'name' => 'superadmin'
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
            'organization_id' => 1
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
     * Check Index If Content Is Empty:
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfContentIsEmpty()
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

        $response = $this->get('api/customers?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);
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

        $this->assertEquals("You do not have permissions.", $message);
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
        $response = $this->delete('api/customers/2?token=' . $token, []);
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

        $this->assertEquals(2, count($data));
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

        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals(6, $user->id);
        $this->assertEquals('Customer C CustomerC', $user->name);

        // Check DB table customers
        $customer = DB::table('customers')->where('user_id', '=', 6)->first();
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
        $this->assertEquals("You do not have permissions.", $message);
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
