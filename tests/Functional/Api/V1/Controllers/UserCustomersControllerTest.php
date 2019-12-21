<?php

/**
 * SetUp: use TestsSeeder
 * TestExample
 * Test current Seeder
 *
 * Check Index for platform roles
 * Check Index for organization roles
 * Check Index If Content Is Empty
 * Check Index If The Access Is Absent By The Role
 *
 * Check Show for platform roles
 * Check Show for organization roles
 * Check Show If The Access Is Absent By The Role
 * Check Show If The Access To The Department Is Absent
 * Check Show If ID is invalid
 *
 * Check Store for platform roles If User exist
 * Check Store for platform roles If User does not exist
 * Check Store for organization roles
 * Check Store For invalid Data
 * Check Store If Access Is Absent By The Role
 * Check Store If Access To Department Is Absent
 *
 * Check Update for platform roles
 * Check Update for organization roles
 * Check Update For invalid Id
 * Check Update For invalid Data
 * Check Update If Access Is Absent By The Role
 * Check Update If Access To Department Is Absent
 *
 * Check Soft Delete for platform roles
 * Check Soft Delete If Id Is Wrong
 * Check Soft Delete If Access Is Absent By The Role
 *
 * Check IndexWithSoftDeleted for platform roles
 * Check IndexWithSoftDeleted If Content Is Empty
 * Check IndexWithSoftDeleted If The Access Is Absent By The Role
 *
 * Check Restore
 * Check Restore If The Customer ID Is Wrong
 * Check Restore If The Access Is Absent By the Role
 *
 * Check Delete Permanently
 * Check Delete Permanently If ID Is Wrong
 * Check Delete Permanently If The Access Is Absent By the Role
 */

namespace App;

use App\Models\UserCustomer;
use Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\WnyTestCase;

class UserCustomersControllerTest extends WnyTestCase
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
        $userCustomers = DB::table('user_customers')->get();
        $this->assertEquals(5, $userCustomers->count());

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Volodymyr Vadiasov', $user->name);
    }

    /**
     * Check Index for platform roles
     *   Check login developer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexForPlatformRoles()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/user-customers?token=' . $token);

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
                            "id",
                            "user_id",
                            "customer_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "user",
                            "customer"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customers are retrieved successfully.", $message);
        $this->assertEquals(5, count($data));
        $this->assertEquals(16, $data[0]['user_id']);
        $this->assertEquals(1, $data[0]['customer_id']);
        $this->assertEquals('Customer A-WNY', $data[0]['user']['name']);
        $this->assertEquals('Customer A-WNY', $data[0]['customer']['name']);
        $this->assertEquals(null, $data[0]['deleted_at']);
    }

    /**
     * Check Index for organization roles
     *   Check login developer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexForOrganizationRoles()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/user-customers?token=' . $token);

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
                            "id",
                            "user_id",
                            "customer_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "user",
                            "customer"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customers are retrieved successfully.", $message);
        $this->assertEquals(4, count($data));
        $this->assertEquals(16, $data[0]['user_id']);
        $this->assertEquals(1, $data[0]['customer_id']);
        $this->assertEquals('Customer A-WNY', $data[0]['user']['name']);
        $this->assertEquals('Customer A-WNY', $data[0]['customer']['name']);
        $this->assertEquals(null, $data[0]['deleted_at']);
    }

    /**
     * Check Index If Content Is Empty:
     *   Check login Organization Spring Superadmin
     *   Get list of customers of Spring
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexIfContentIsEmpty()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->delete('api/user-customers/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->get('api/user-customers?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index If The Access Is Absent By The Role
     *   Customer tries to get user-customers
     */
    public function testIndexIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/user-customers?token=' . $token, []);

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
     * Check show
     */
    public function testShowForPlatformRoles()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/user-customers/1?token=' . $token);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data' =>
                    [
                        "id",
                        "user_id",
                        "customer_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "user",
                        "customer"
                    ]
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is retrieved successfully.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals(16, $data['user_id']);
        $this->assertEquals(1, $data['customer_id']);
        $this->assertEquals('Customer A-WNY', $data['user']['name']);
        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
        $this->assertEquals(null, $data['deleted_at']);
    }

    /**
     * Check Show for organization roles
     */
    public function testShowForOrganizationRoles()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        $response = $this->get('api/user-customers/1?token=' . $token);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data' =>
                    [
                        "id",
                        "user_id",
                        "customer_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "user",
                        "customer"
                    ]
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is retrieved successfully.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals(16, $data['user_id']);
        $this->assertEquals(1, $data['customer_id']);
        $this->assertEquals('Customer A-WNY', $data['user']['name']);
        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
        $this->assertEquals(null, $data['deleted_at']);
    }

    /**
     * Check Show If The Access Is Absent By The Role
     */
    public function testShowIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerWny();

        $response = $this->get('api/user-customers/2?token=' . $token);
        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Show If The Access To The Department Is Absent
     */
    public function testShowIfTheAccessToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        $response = $this->get('api/user-customers/2?token=' . $token);
        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }

    /**
     * Check Show If ID is invalid
     */
    public function testShowIfIdIsInvalid()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/user-customers/22222?token=' . $token);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Incorrect Entity ID.", $message);
        $this->assertEquals(null, $data);
    }


    /**
     * Check Index Of Soft Deleted
     */
    public function testIndexSoftDeleted()
    {
        $token = $this->loginDeveloper();

        // Create soft deleted
        $response = $this->delete('api/user-customers/1?token=' . $token, []);
        $response->assertStatus(200);

        $response = $this->delete('api/user-customers/2?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->get('api/user-customers/soft-deleted?token=' . $token, []);

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
                            "id",
                            "user_id",
                            "customer_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "user",
                            "customer"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Soft-deleted User-Customers are retrieved successfully.", $message);
        $this->assertEquals(2, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(16, $data[0]['user_id']);
        $this->assertEquals(1, $data[0]['customer_id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('Customer A-WNY', $data[0]['user']['name']);
        $this->assertEquals('Customer A-WNY', $data[0]['customer']['name']);
    }

    /**
     * Check IndexWithSoftDeleted If Content Is Empty
     */
    public function testIndexSoftDeletedIfContentIsEmpty()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/user-customers/soft-deleted?token=' . $token, []);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index of Soft Deleted If Access Is Absent By The Role
     *   Check login Customer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexSoftDeletedIfAccessIsAbsentByTheRole()
    {
        $token = $this->loginDeveloper();

        // Create soft deleted
        $response = $this->delete('api/user-customers/1?token=' . $token, []);
        $response->assertStatus(200);

        $response = $this->delete('api/user-customers/2?token=' . $token, []);
        $response->assertStatus(200);

        $token = $this->loginCustomerWny();

        $response = $this->get('api/user-customers/soft-deleted?token=' . $token, []);

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
     * Check Store for platform roles If User exist
     */
    public function testStoreForPlatformRolesIfUserExist()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'email'       => 'wny-customer-e-individual@admin.com',
            'customer_id' => 5
        ];

        // Store the comment
        $response = $this->post('api/user-customers?token=' . $token, $data);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
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
        $this->assertEquals("User-Customer is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_comments
        $userComment = DB::table('user_customers')->where('user_id', '=', 22)->first();
        $this->assertEquals(5, $userComment->id);
    }

    /**
     * Check Store for platform roles If User does not exist
     */
    public function testStoreForPlatformRolesIfUserDoesNotExist()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'email'       => 'wny-customer-f-individual@admin.com',
            'customer_id' => 5
        ];

        // Store the comment
        $response = $this->post('api/user-customers?token=' . $token, $data);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
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
        $this->assertEquals("User-Customer is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_comments
        $userComment = DB::table('user_customers')->where('user_id', '=', 23)->first();
        $this->assertEquals(6, $userComment->id);
    }

    /**
     * Check Store for organization roles
     */
    public function testStoreForOrganizationRoles()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Create data
        $data = [
            'email'       => 'wny-customer-f-individual@admin.com',
            'customer_id' => 5
        ];

        // Store the comment
        $response = $this->post('api/user-customers?token=' . $token, $data);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
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
        $this->assertEquals("User-Customer is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_comments
        $userCustomer = DB::table('user_customers')->where('user_id', '=', 23)->first();
        $this->assertEquals(6, $userCustomer->id);
    }

    /**
     * Check store invalid data
     */
    public function testStoreInvalidData()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'customer_id' => '',
            'user_id'     => ''
        ];

        // Store a new user, user profile
        $response = $this->post('api/user-customers?token=' . $token, $data);

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
        $this->assertEquals(2, count($error['errors']));
    }

    /**
     * Check Store If Access Is Absent By The Role
     */
    public function testStoreIfAccessAbsentByTheRole()
    {
        $token = $this->loginCustomerWny();

        // Create data
        $data = [
            'user_id'     => 22,
            'customer_id' => 5
        ];

        $response = $this->post('api/user-customers?token=' . $token, $data);

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
     * Check Store If Access To Department Is Absent
     */
    public function testStoreIfAccessToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Create data
        $data = [
            'email'       => 'wny-customer-f-individual@admin.com',
            'customer_id' => 5
        ];

        $response = $this->post('api/user-customers?token=' . $token, $data);

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
     * Check Update for platform roles
     */
    public function testUpdateForPlatformRoles()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'customer_id' => 1,
            'user_id'     => 22
        ];

        $response = $this->put('api/user-customers/1?token=' . $token, $data);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'customer_id',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'user',
                    'customer'
                ]
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is updated successfully.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals(1, $data['customer_id']);
        $this->assertEquals(22, $data['user_id']);
    }

    /**
     * Check Store for organization roles
     */
    public function testUpdateForOrganizationRoles()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Create data
        $data = [
            'customer_id' => 1,
            'user_id'     => 22
        ];

        $response = $this->put('api/user-customers/1?token=' . $token, $data);
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'customer_id',
                    'deleted_at',
                    'created_at',
                    'updated_at',
                    'user',
                    'customer'
                ]
            ]
        );

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is updated successfully.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals(1, $data['customer_id']);
        $this->assertEquals(22, $data['user_id']);
    }

    /**
     * Check Update For invalid Id
     */
    public function testUpdateIfInvalidId()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Create data
        $data = [
            'customer_id' => 1,
            'user_id'     => 22
        ];

        $response = $this->put('api/user-customers/22222?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Incorrect entity ID.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update For invalid Data
     */
    public function testUpdateIfDataIsInvalid()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'user_id'     => 111111,
            'customer_id' => 1
        ];

        $response = $this->put('api/user-customers/1?token=' . $token, $data);
        $response->assertStatus(422);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
            ]
        );

        //Check response data
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(422, $code);
        $this->assertEquals("The given data was invalid.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update If Access Is Absent By The Role
     */
    public function testUpdateIfAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerSpring();

        // Create data
        $data = [
            'customer_id' => 1,
            'user_id'     => 22
        ];

        $response = $this->put('api/user-customers/1?token=' . $token, $data);
        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Update If Access To Department Is Absent
     */
    public function testUpdateIfAccessToDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Create data
        $data = [
            'customer_id' => 1,
            'user_id'     => 22
        ];

        $response = $this->put('api/user-customers/1?token=' . $token, $data);
        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission to department is absent.", $message);
    }


    /**
     * Check Soft Delete:
     *   We check that the User-Customer row must change the field deleted_at from null to not null.
     */
    public function testSoftDelete()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-customers/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is soft-deleted successfully.", $message);
        $this->assertEquals(null, $data);

        $customer = DB::table('user_customers')->where('id', 1)->first();
        $this->assertNotEquals(null, $customer->deleted_at);
    }

    /**
     * Check Soft Delete If Id Is Wrong
     */
    public function testSoftDeleteIfIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-customers/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Incorrect entity ID.", $message);
        $this->assertEquals(null, $data);
    }


    /**
     * Check Update If Access Is Absent By The Role
     */
    public function testSoftDeleteIfAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->delete('api/user-customers/1?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }


    /**
     * Check Restore
     */
    public function testRestore()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/user-customers/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/user-customers/1/restore?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(true, $success);
        $this->assertEquals("User-Customer is restored successfully.", $message);

        $userCustomer = UserCustomer::where('id', 1)->first();
        $this->assertEquals(null, $userCustomer->deleted_at);
    }

    /**
     * Check Restore If The Customer ID Is Wrong
     */
    public function testRestoreIfTheCustomerIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/user-customers/2222/restore?token=' . $token, []);

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
     * Check Restore If The Access Is Absent By the Role
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/user-customers/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginCustomerWny();

        // Request
        $response = $this->put('api/user-customers/1restore?token=' . $token, []);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Delete Permanently
     */
    public function testDeletePermanently()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/user-customers/1?token=' . $token, []);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/user-customers/1/permanently?token=' . $token, []);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-Customer is destroyed permanently.", $message);
        $this->assertEquals(null, $data);

        $userCustomer = DB::table('user_customers')->where('id', 1)->first();
        $this->assertEquals(null, $userCustomer);
    }

    /**
     * Check Delete Permanently If ID Is Wrong
     *   We wait for a message about error.
     *     Check login developer
     *     Check response status
     *     Check response structure
     */
    public function testDeletePermanentlyIfIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/customers/2222/comments/1/permanently?token=' . $token, []);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
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
     * Check Delete Permanently If The Access Is Absent By the Role
     */
    public function testDeletePermanentlyIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginDeveloper();

        // Preparation
        $response = $this->delete('api/user-customers/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginCustomerWny();
        // Request
        $response = $this->delete('api/customers/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

//    /**
//     * Check Delete Permanently If There is a child soft-deleted comment
//     *     Check login developer
//     *     Soft delete customer
//     *     Repair customer
//     *     Check response status
//     *     Check response structure
//     *     Check DB: deleted_at
//     */
//    public function testDeletePermanentlyIfThereIsAChildSoftDeletedComment()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Preparation
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token);
//        $response->assertStatus(200);
//
//        $token    = $this->loginDeveloper();
//        $response = $this->delete('api/customers/1/comments/3?token=' . $token);
//        $response->assertStatus(200);
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/3/permanently?token=' . $token);
//
//        $response->assertStatus(455);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(455, $code);
//        $this->assertEquals("There is a child soft-deleted comment", $message);
//        $this->assertEquals(null, $data);
//    }
}
