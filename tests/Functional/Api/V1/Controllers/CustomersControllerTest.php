<?php

/**
 * SetUp: use TestsSeeder
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
 *
 * Check update. Special test bug 347
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
        $customers = DB::table('customers')->get();
        $this->assertEquals(4, $customers->count());

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Volodymyr Vadiasov', $user->name);
    }

    /**
     * Check Index:
     *   Check login developer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndex()
    {
        $token = $this->loginDeveloper();

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
                            "name",
                            "type",
                            "organization_id",
                            "organization",
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

        $this->assertEquals(4, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Customer A-WNY', $data[0]['name']);
        $this->assertEquals('Individual(s)', $data[0]['type']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[0]['organization']['name']);
        $this->assertEquals("Customers are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check Index If The Access Is Not Full
     *   User role=organization-general-manager org=WNY org_id=d
     *   Check login
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfTheAccessIsNotFull()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

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
                            "name",
                            "type",
                            "organization_id",
                            "organization",
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

        $this->assertEquals(3, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Customer A-WNY', $data[0]['name']);
        $this->assertEquals('Individual(s)', $data[0]['type']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[0]['organization']['name']);
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
        $token = $this->loginOrganizationSpringSuperadmin();

        // Delete Customer 2
        $response = $this->delete('api/customers/2?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

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
    public function testIndexIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerSpring();

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
     *   Check login developer
     *   Create SoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeleted()
    {
        $token = $this->loginDeveloper();

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
                            "name",
                            "type",
                            "organization_id",
                            "organization",
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
        $this->assertEquals('Customer A-WNY', $data[0]['name']);
        $this->assertEquals('2', $data[0]['organization_id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Soft-deleted customers are retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check IndexSoftDeleted If Content Is Empty:
     *   Check login developer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfContentIsEmpty()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/customers/soft-deleted?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check IndexSoftDeleted If Access Is Absent:
     *   Check login Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfAccessIsAbsent()
    {
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/customers/soft-deleted?token=' . $token, []);

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
     *   Check login developer
     *   Store a new Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     *   Check DB tables Customers
     */
    public function testStore()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'name'            => 'Customer New',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
        ];

        // Store a new user, user-profile
        $response = $this->post('api/customers?token=' . $token, $data, []);
//        dd($response);

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

        // Check DB table customers
        $customer = DB::table('customers')->where('name', '=', 'Customer New')->first();
        $this->assertEquals('Business', $customer->type);
        $this->assertEquals('New York', $customer->city);
        $this->assertEquals('Line 1', $customer->line_1);
        $this->assertEquals('Line 2', $customer->line_2);
        $this->assertEquals('CA', $customer->state);
        $this->assertEquals(2, $customer->organization_id);
        $this->assertEquals('01234', $customer->zip);
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
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'name'            => '',
            'organization_id' => '',
            'type'            => '',
            'city'            => '',
            'line_1'          => [],
            'line_2'          => [],
            'state'           => [],
            'zip'             => [],
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
        $this->assertEquals(8, count($error['errors']));
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
        $token = $this->loginCustomerSpring();

        // Create data
        $data = [
            'name'            => 'Customer New',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
        ];

        // Store a new customer
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
        $token = $this->loginOrganizationSpringSuperadmin();

        // Create data
        $data = [
            'name'            => 'Customer New',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
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
     *   Check login developer
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShow()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'data' =>
                    [
                        "id",
                        "name",
                        "type",
                        "organization_id",
                        "city",
                        "line_1",
                        "line_2",
                        "state",
                        "zip",
                        "organization",
                        "deleted_at",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals('Customer A-WNY', $data['name']);
        $this->assertEquals('Individual(s)', $data['type']);
        $this->assertEquals('New York', $data['city']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals(2, $data['organization']['id']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data['organization']['name']);
    }

    /**
     * Check show with Wrong ID:
     *   Check login developer
     *   Get the specified Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowWithWrongId()
    {
        $token = $this->loginDeveloper();

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
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/customers/1?token=' . $token, []);
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
     *   Check login Spring Superadmin
     *   Get the specified Customer of WNY
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testShowIfAccessToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->get('api/customers/1?token=' . $token, []);
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
     *   Check login developer
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdate()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'name'            => 'Customer A-WNY-edited',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
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
        $this->assertEquals("Customer A-WNY-edited", $data->name);
        $this->assertEquals(1, $data->id);
        $this->assertEquals('Business', $data->type);
        $this->assertEquals('New York', $data->city);
    }

    /**
     * Check update If The Access Is Not Full
     *   Check login Spring Superadmin
     *   Update the Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfTheAccessIsNotFull()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Create data
        $data = [
            'name'            => 'Customer A-WNY-edited',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
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
        $this->assertEquals("Customer A-WNY-edited", $data->name);
        $this->assertEquals(1, $data->id);
        $this->assertEquals('Business', $data->type);
        $this->assertEquals('New York', $data->city);
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
        $token = $this->loginOrganizationWNYSuperadmin();

        // Create data
        $data = [
            'name'            => [],
            'organization_id' => [],
            'type'            => [],
            'city'            => [],
            'line_1'          => [],
            'line_2'          => [],
            'state'           => [],
            'zip'             => [],
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
        $this->assertEquals(8, count($error['errors']));
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
        $token = $this->loginOrganizationWNYSuperadmin();


        // Create data
        $data = [
            'name'            => 'Customer A-WNY-edited',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
        ];

        $response = $this->put('api/customers/55555?token=' . $token, $data);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("The given data was invalid.", $message);
    }

    /**
     * Check update If Access Of Role Is Absent:
     *   Check login Spring Customer
     *   Update the WNY Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessOfRoleIsAbsent()
    {
        $token = $this->loginCustomerSpring();

        // Create data
        $data = [
            'name'            => 'Customer A-WNY-edited',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
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
     *   Check login Spring superadmin
     *   Update the Customer WNY
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testUpdateIfAccessToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Update data
        $data = [
            'name'            => 'Customer A-WNY-edited',
            'organization_id' => 2,
            'type'            => 'Business',
            'city'            => 'New York',
            'line_1'          => 'Line 1',
            'line_2'          => 'Line 2',
            'state'           => 'CA',
            'zip'             => '01234',
        ];

        $response = $this->put('api/customers/1?token=' . $token, $data);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }
//

    /**
     * Check Soft Delete:
     *   We check that the customer account must change the field deleted_at from null to not null.
     *     Check login developer
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at of the soft-deleted row
     */
    public function testSoftDelete()
    {
        $token = $this->loginDeveloper();

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
    }

    /**
     * Check Soft Delete If The Access Of Role Is Absent:
     *   We wait for a message about error.
     *     Check login WNY Admin
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheAccessOfRoleIsAbsent()
    {
        $token = $this->loginOrganizationWNYAdmin();

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
     *     Check login Spring
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheAccessToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->delete('api/customers/1?token=' . $token, []);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Check Soft Delete If The Id Is Wrong:
     *   We wait for a message about error.
     *     Check login developer
     *     Check response status
     *     Check response structure
     */
    public function testSoftDeleteIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

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
     *     Check login developer
     *     Soft delete customer
     *     Repair customer
     *     Check response status
     *     Check response structure
     *     Check DB: deleted_at
     */
    public function testRestore()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/customers/1/restore?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is restored successfully.", $message);

        $customer = Customer::where('id', 1)->first();
        $this->assertEquals(null, $customer->deleted_at);
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
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);

        $token = $this->loginCustomerSpring();

        // Request
        $response = $this->put('api/customers/1/restore?token=' . $token, []);

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
        $token = $this->loginDeveloper();

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
     *     Check login developer
     *     Soft delete customer
     *     Delete Permanently customer
     *     Check response status
     *     Check response structure
     *     Check DB: customer must be absent
     */
    public function testDeletePermanently()
    {
        $token = $this->loginDeveloper();

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
     * Check Delete Permanently If The Access Of Role Is Absent:
     *   We wait for a message about error.
     *     Check login developer
     *     Soft delete customer
     *     Check login organization superadmin
     *     Delete Permanently customer
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheAccessOfRoleIsAbsent()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/customers/1?token=' . $token, []);

        $token = $this->loginOrganizationSpringSuperadmin();

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
     *     Check login developer
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/customers/2222/permanently?token=' . $token, []);

        $response->assertStatus(422);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Customer is absent.", $message);
    }

    /**
     * Special test bug 347
     */
    public function testUpdateSpecialTestBug347()
    {
        $token = $this->loginDeveloper();

        $data = [
            'type' => 'Individual(s)'
        ];

        $response = $this->put('api/customers/2?token=' . $token, $data, []);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals("Customer is updated successfully.", $message);
        $this->assertEquals("Customer B-Spring", $data->name);
        $this->assertEquals(2, $data->id);
        $this->assertEquals('Individual(s)', $data->type);
    }
}
