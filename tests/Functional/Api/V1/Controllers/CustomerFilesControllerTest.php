<?php

/**
 * SetUp: use TestsSeeder
 * TestExample
 * Test current Seeder
 *
 * Check Index for platform roles
 * Check Index for organization roles
 * Check Index For Customer
 * Check Index If Content Is Empty
 * Check Index If The Access Is Absent By The Role
 * Check Index If The Access To The Department Is Absent
 * Check Index If customer ID is invalid
 *
 * Check Show for platform roles
 * Check Show for organization roles
 * Check Show For Customer
 * Check Show If The Access Is Absent By The Role
 * Check Show If The Access To The Department Is Absent
 * Check Show If customer ID is invalid
 * Check Show If file ID is invalid
 *
 * Check Store for platform roles
 * Check Store for organization roles
 * Check Store For Customer
 * Check Store For invalid customer ID
 * Check Store For invalid Data
 * Check Store For invalid file extension
 * Check Store If Access Is Absent By The Role
 * Check Store If Access To Department Is Absent
 *
 * Check Update for Owner
 * Check Update For invalid customer Id
 * Check Update For invalid Data
 * Check Update For invalid File Id
 * Check Update For invalid file extension
 * Check Update If Access Is Absent By The Role
 * Check Update If Access To Department Is Absent
 * Check Update If Access Is Absent By The Owner
 *
 * Check Soft Delete for Owner
 * Check Soft Delete If The Customer Id Is Wrong
 * Check Soft Delete If The File Id Is Wrong
 * Check Soft Delete If There is a child comment
 * Check Soft Delete for not Owner
 *
 * Check IndexWithSoftDeleted for platform roles
 * Check IndexWithSoftDeleted If Content Is Empty
 * Check IndexWithSoftDeleted If The Access Is Absent By The Role
 * Check IndexWithSoftDeleted If customer ID is invalid
 *
 * Check Restore
 * Check Restore If The Customer ID Is Wrong
 * Check Restore If The File ID Is Wrong
 * Check Restore If The Access Is Absent By the Role
 * Check Restore If There is a parent soft-deleted comment
 *
 * Check Delete Permanently
 * Check Delete Permanently If The Customer ID Is Wrong
 * Check Delete Permanently If The File ID Is Wrong
 * Check Delete Permanently If The Access Is Absent By the Role
 * Check Delete Permanently If There is a child soft-deleted comment
 */

namespace App;

use App\Models\Customer;
use App\Models\CustomerComment;
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

class CustomerFilesControllerTest extends WnyTestCase
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
        $customerFiles = DB::table('customer_files')->get();
        $this->assertEquals(3, $customerFiles->count());

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
        $response = $this->get('api/customers/1/files?token=' . $token, []);

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
                        'customer',
                        'files' => [
                            [
                                "id",
                                "customer_id",
                                "description",
                                "filename",
                                "owner_user_id",
                                "deleted_at",
                                "created_at",
                                "updated_at"
                            ]
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
        $this->assertEquals("Customer's files are retrieved successfully.", $message);
        $this->assertEquals(2, count($data));
        $this->assertEquals(3, count($data['files']));
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
        $this->assertEquals(1, $data['files'][0]['id']);
        $this->assertEquals(1, $data['files'][0]['customer_id']);
        $this->assertEquals('Description 1', $data['files'][0]['description']);
        $this->assertEquals('customer-a-2019-12-01-12-25-35--1.png', $data['files'][0]['filename']);
        $this->assertEquals(16, $data['files'][0]['owner_user_id']);
        $this->assertEquals(null, $data['files'][0]['deleted_at']);
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
        $response = $this->get('api/customers/1/files?token=' . $token, []);

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
                        'customer',
                        'files' => [
                            [
                                "id",
                                "customer_id",
                                "description",
                                "filename",
                                "owner_user_id",
                                "deleted_at",
                                "created_at",
                                "updated_at"
                            ]
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
        $this->assertEquals("Customer's files are retrieved successfully.", $message);
        $this->assertEquals(2, count($data));
        $this->assertEquals(3, count($data['files']));
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
        $this->assertEquals(1, $data['files'][0]['id']);
        $this->assertEquals(1, $data['files'][0]['customer_id']);
        $this->assertEquals('Description 1', $data['files'][0]['description']);
        $this->assertEquals('customer-a-2019-12-01-12-25-35--1.png', $data['files'][0]['filename']);
        $this->assertEquals(16, $data['files'][0]['owner_user_id']);
        $this->assertEquals(null, $data['files'][0]['deleted_at']);
    }

    /**
     * Check Index For Customer
     *   Check login developer
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testIndexForCustomer()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->get('api/customers/1/files?token=' . $token, []);

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
                        'customer',
                        'files' => [
                            [
                                "id",
                                "customer_id",
                                "description",
                                "filename",
                                "owner_user_id",
                                "deleted_at",
                                "created_at",
                                "updated_at"
                            ]
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
        $this->assertEquals("Customer's files are retrieved successfully.", $message);
        $this->assertEquals(2, count($data));
        $this->assertEquals(3, count($data['files']));
        $this->assertEquals(1, $data['customer']['id']);
        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
        $this->assertEquals(1, $data['files'][0]['id']);
        $this->assertEquals(1, $data['files'][0]['customer_id']);
        $this->assertEquals('Description 1', $data['files'][0]['description']);
        $this->assertEquals('customer-a-2019-12-01-12-25-35--1.png', $data['files'][0]['filename']);
        $this->assertEquals(16, $data['files'][0]['owner_user_id']);
        $this->assertEquals(null, $data['files'][0]['deleted_at']);
    }

//    /**
//     * Check Show All If The Access Is Not Full
//     *   User role=organization-general-manager org=WNY org_id=
//     *   Check login
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllIfTheAccessIsNotFull()
//    {
//        $token = $this->loginOrganizationWNYGeneralManager();
//
//        // Request
//        $response = $this->get('api/customers/1/comments?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'message',
//                'data' =>
//                    [
//                        'customer',
//                        'comments' => [
//                            [
//                                "id",
//                                "customer_id",
//                                "author_id",
//                                "comment",
//                                "parent_id",
//                                "deleted_at",
//                                "created_at",
//                                "updated_at"
//                            ]
//                        ]
//                    ]
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer's comments are retrieved successfully.", $message);
//        $this->assertEquals(2, count($data));
//        $this->assertEquals(4, count($data['comments']));
//        $this->assertEquals(1, $data['customer']['id']);
//        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
//        $this->assertEquals(1, $data['comments'][0]['id']);
//        $this->assertEquals(1, $data['comments'][0]['customer_id']);
//        $this->assertEquals(16, $data['comments'][0]['author_id']);
//        $this->assertEquals('Comment #1 by Customer A-WNY', $data['comments'][0]['comment']);
//        $this->assertEquals(null, $data['comments'][0]['parent_id']);
//        $this->assertEquals(1, $data['comments'][0]['level']);
//        $this->assertEquals(null, $data['comments'][0]['deleted_at']);
//    }
//
//    /**
//     * Check Index If Content Is Empty:
//     *   Check login Organization Spring Superadmin
//     *   Get list of customers of Spring
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllIfContentIsEmpty()
//    {
//        $token = $this->loginOrganizationSpringSuperadmin();
//
//        $response = $this->get('api/customers/2/comments?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(204);
//    }
//
//    /**
//     * Check showAll If The Access Is Absent By The Role:
//     *   Customer Spring tries to get comments of customer WNY
//     *   Check login Customer Spring
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllIfTheAccessIsAbsentByTheRole()
//    {
//        $token = $this->loginCustomerSpring();
//
//        $response = $this->get('api/customers/1/comments?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(453);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $message      = $responseJSON['message'];  // array
//        $success      = $responseJSON['success'];  // array
//
//        $this->assertEquals("Permission is absent by the role.", $message);
//        $this->assertEquals(false, $success);
//    }
//
//    /**
//     * Check ShowAll If The Access To The Department Is Absent
//     *   Customer Organization Spring Superadmin comments of customer WNY
//     *   Check login Organization Spring Superadmin
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllIfTheAccessToTheDepartmentIsAbsent()
//    {
//        $token = $this->loginOrganizationSpringSuperadmin();
//
//        $response = $this->get('api/customers/1/comments?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(454);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $message      = $responseJSON['message'];  // array
//        $success      = $responseJSON['success'];  // array
//
//        $this->assertEquals("Permission to department is absent.", $message);
//        $this->assertEquals(false, $success);
//    }
//
//    /**
//     * Check showAllSoftDeleted:
//     *   Check login developer
//     *   Create SoftDeleted
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllSoftDeleted()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Create soft deleted
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token, []);
//        $response->assertStatus(200);
//
//        $token    = $this->loginDeveloper();
//        $response = $this->delete('api/customers/1/comments/3?token=' . $token, []);
//        $response->assertStatus(200);
//
//        // Request
//        $response = $this->get('api/customers/1/comments/soft-deleted?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'message',
//                'data' =>
//                    [
//                        'customer',
//                        'comments' => [
//                            [
//                                "id",
//                                "customer_id",
//                                "author_id",
//                                "comment",
//                                "parent_id",
//                                "deleted_at",
//                                "created_at",
//                                "updated_at"
//                            ]
//                        ]
//                    ]
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer's soft-deleted comments are retrieved successfully.", $message);
//        $this->assertEquals(2, count($data));
//        $this->assertEquals(2, count($data['comments']));
//        $this->assertEquals(1, $data['customer']['id']);
//        $this->assertEquals('Customer A-WNY', $data['customer']['name']);
//        $this->assertEquals(3, $data['comments'][0]['id']);
//        $this->assertEquals(1, $data['comments'][0]['customer_id']);
//        $this->assertEquals(1, $data['comments'][0]['author_id']);
//        $this->assertEquals(2, $data['comments'][0]['level']);
//        $this->assertEquals('Comment #3 by developer', $data['comments'][0]['comment']);
//        $this->assertEquals(1, $data['comments'][0]['parent_id']);
//        $this->assertNotEquals(null, $data['comments'][0]['deleted_at']);
//    }
//
//    /**
//     * Check showAllSoftDeleted If Content Is Empty:
//     *   Check login developer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllSoftDeletedIfContentIsEmpty()
//    {
//        $token = $this->loginDeveloper();
//
//        $response = $this->get('api/customers/2/comments/soft-deleted?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(204);
//    }
//
//    /**
//     * Check ShowAllSoftDeleted If Access Is Absent By The Role
//     *   Check login Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testShowAllSoftDeletedIfAccessIsAbsentByTheRole()
//    {
//        $token = $this->loginCustomerSpring();
//
//        $response = $this->get('api/customers/1/comments/soft-deleted?token=' . $token, []);
//
//        // Check response status
//        $response->assertStatus(453);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $message      = $responseJSON['message'];  // array
//        $success      = $responseJSON['success'];  // array
//
//        $this->assertEquals("Permission is absent by the role.", $message);
//        $this->assertEquals(false, $success);
//    }
//
//
//    /**
//     * Check store:
//     *   Check login developer
//     *   Store a new Customer's comment
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *   Check DB tables Customer's comment
//     */
//    public function testStore()
//    {
//        $token = $this->loginDeveloper();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 1,
//            'comment'     => 'Comment #4 by developer',
//            'parent_id'   => 1,
//            'level'       => 2
//        ];
//
//        // Store the comment
//        $response = $this->post('api/customers/1/comments?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'message',
//                'data'
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer is created successfully.", $message);
//        $this->assertEquals(null, $data);
//
//        // Check DB table customer_comments
//        $comment = DB::table('customer_comments')->where('comment', '=', 'Comment #4 by developer')->first();
//        $this->assertEquals(5, $comment->id);
//        $this->assertEquals(1, $comment->customer_id);
//        $this->assertEquals(1, $comment->author_id);
//        $this->assertEquals(1, $comment->parent_id);
//    }
//
//    /**
//     * Check store If The Access Is Not Full
//     *   Check login WNY org superadmin
//     *   Store a new Customer's comment
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     *   Check DB tables Customer's comment
//     */
//    public function testStoreIfTheAccessIsNotFull()
//    {
//        $token = $this->loginOrganizationWNYSuperadmin();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 4,
//            'comment'     => 'Comment #4 by WNY superadmin',
//            'parent_id'   => 1,
//            'level'       => 2
//        ];
//
//        // Store the comment
//        $response = $this->post('api/customers/1/comments?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'message',
//                'data'
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer is created successfully.", $message);
//        $this->assertEquals(null, $data);
//
//        // Check DB table customer_comments
//        $comment = DB::table('customer_comments')->where('comment', '=', 'Comment #4 by WNY superadmin')->first();
//        $this->assertEquals(5, $comment->id);
//        $this->assertEquals(1, $comment->customer_id);
//        $this->assertEquals(4, $comment->author_id);
//        $this->assertEquals(1, $comment->parent_id);
//    }
//
//    /**
//     * Check store invalid data:
//     *   Check login developer
//     *   Store a new Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreInvalidData()
//    {
//        $token = $this->loginDeveloper();
//
//        // Create data
//        $data = [
//            'customer_id' => '',
//            'author_id'   => '',
//            'comment'     => null,
//            'parent_id'   => 'string'
//        ];
//
//        // Store a new user, user profile
//        $response = $this->post('api/customers/1/comments?token=' . $token, $data, []);
//
//        // Check response status
//        $response->assertStatus(422);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'error' =>
//                    [
//                        'message',
//                        'errors'
//                    ]
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $error        = $responseJSON['error'];  // array
//
//        $this->assertEquals("The given data was invalid.", $error['message']);
//        $this->assertEquals(5, count($error['errors']));
//    }
//
//    /**
//     * Check store invalid customer ID
//     *   Check login developer
//     *   Store a new Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreInvalidCustomerId()
//    {
//        $token = $this->loginDeveloper();
//
//        // Create data
//        $data = [
//            'customer_id' => 10,
//            'author_id'   => 1,
//            'comment'     => 'Comment #4 by developer',
//            'parent_id'   => null,
//            'level'       => 1
//        ];
//
//        // Store a new user, user profile
//        $response = $this->post('api/customers/1/comments?token=' . $token, $data);
//
//        // Check response status
//        $response->assertStatus(456);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'message',
//                'data'
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];  // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect Entity ID.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check store If Access To Department Is Absent:
//     *   Check login Spring superadmin
//     *   Store a new Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testStoreIfAccessToDepartmentIsAbsent()
//    {
//        $token = $this->loginOrganizationSpringSuperadmin();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 21,
//            'comment'     => 'Comment #4 by Spring superadmin',
//            'parent_id'   => null
//        ];
//
//        // Store a new user, user profile
//        $response = $this->post('api/customers/1/comments?token=' . $token, $data, []);
//        // Check response status
//        $response->assertStatus(454);
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
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission to department is absent.", $message);
//    }
//
//    /**
//     * Check update:
//     *   Check login developer
//     *   Update the Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdate()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 16,
//            'comment'     => 'Comment #1 Edited',
//            'parent_id'   => null
//        ];
//
//        $response = $this->put('api/customers/1/comments/1?token=' . $token, $data);
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//        $data         = json_decode($data);
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer's comment is updated successfully.", $message);
//        $this->assertEquals(1, $data->id);
//        $this->assertEquals(1, $data->customer_id);
//        $this->assertEquals(16, $data->author_id);
//        $this->assertEquals('Comment #1 Edited', $data->comment);
//        $this->assertEquals(null, $data->parent_id);
//    }
//
//    /**
//     * Check update If The Access Is absent (only author)
//     *   Check login Spring Customer
//     *   Update the WNY Customer
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfAccessIsAbsent()
//    {
//        $token = $this->loginCustomerSpring();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 16,
//            'comment'     => 'Comment #1 Edited',
//            'parent_id'   => null
//        ];
//
//        $response = $this->put('api/customers/1/comments/1?token=' . $token, $data);
//        $response->assertStatus(457);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//        $data         = json_decode($data);
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(457, $code);
//        $this->assertEquals("You are not the author.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check update If Comment Id Is wrong
//     *   Check login Spring superadmin
//     *   Update the Customer WNY
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfAccessCommentIdIsWrong()
//    {
//        $token = $this->loginOrganizationWNYSuperadmin();
//
//        // Create data
//        $data = [
//            'customer_id' => 1,
//            'author_id'   => 16,
//            'comment'     => 'Comment #1 Edited',
//            'parent_id'   => null
//        ];
//
//        $response = $this->put('api/customers/1/comments/10?token=' . $token, $data);
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect entity ID.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check update If Data Is Invalid (only comment is changed)
//     *   Check login Spring superadmin
//     *   Update the Customer WNY
//     *   Check response status
//     *   Check response structure
//     *   Check response data
//     */
//    public function testUpdateIfDataIsInvalid()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Create data
//        $data = [
//            'error'     => 1,
//            'author_id' => 16,
//            'comment'   => null,
//            'parent_id' => null
//        ];
//
//        $response = $this->put('api/customers/1/comments/1?token=' . $token, $data);
//        $response->assertStatus(422);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'error' =>
//                    [
//                        'message',
//                        'errors'
//                    ]
//            ]
//        );
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $error        = $responseJSON['error'];  // array
//
//        $this->assertEquals("The given data was invalid.", $error['message']);
//        $this->assertEquals(1, count($error['errors']));
//    }
////
//
//    /**
//     * Check Soft Delete:
//     *   We check that the customer's comment must change the field deleted_at from null to not null.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     *     Check DB: deleted_at of the soft-deleted row
//     */
//    public function testSoftDelete()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token, []);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals("Customer's comment is soft-deleted successfully.", $message);
//        $this->assertEquals(null, $data);
//
//        $customer = DB::table('customer_comments')->where('id', 4)->first();
//        $this->assertNotEquals(null, $customer->deleted_at);
//    }
//
//    /**
//     * Check Soft Delete If The Id Is Wrong:
//     *   We wait for a message about error.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     */
//    public function testSoftDeleteIfTheIdIsWrong()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/4444?token=' . $token, []);
//
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect entity ID.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Soft Delete If There is a child comment
//     *   We wait for a message about error.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     */
//    public function testSoftDeleteIfThereIsAChildComment()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/3?token=' . $token, []);
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
//        $this->assertEquals("There is a child comment.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Restore:
//     *   We check that the Customer Account must change the field deleted_at from not null to null.
//     *     Check login developer
//     *     Soft delete customer
//     *     Repair customer
//     *     Check response status
//     *     Check response structure
//     *     Check DB: deleted_at
//     */
//    public function testRestore()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Preparation
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token, []);
//        $response->assertStatus(200);
//
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->put('api/customers/1/comments/4/restore?token=' . $token, []);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals("Customer Comment restored successfully.", $message);
//
//        $comment = CustomerComment::where('id', 4)->first();
//        $this->assertEquals(null, $comment->deleted_at);
//    }
//
//    /**
//     * Check Restore If The Customer ID Is Wrong
//     *     Check login
//     *     Check response status
//     *     Check response structure
//     */
//    public function testRestoreIfTheCustomerIdIsWrong()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->put('api/customers/2222/comments/1/restore?token=' . $token, []);
//
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect the Entity ID in the URL.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Restore If The Comment ID Is Wrong
//     *     Check login
//     *     Check response status
//     *     Check response structure
//     */
//    public function testRestoreIfTheCommentIdIsWrong()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->put('api/customers/1/comments/5/restore?token=' . $token, []);
//
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect the Entity ID in the URL.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Restore If The Access Is Absent By the Role
//     *     Check login
//     *     Check response status
//     *     Check response structure
//     */
//    public function testRestoreIfTheAccessIsAbsentByTheRole()
//    {
//        $token = $this->loginOrganizationWNYSuperadmin();
//
//        // Request
//        $response = $this->put('api/customers/1/comments/3/restore?token=' . $token, []);
//
//        $response->assertStatus(453);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission is absent by the role.", $message);
//    }
//
//    /**
//     * Check Restore:
//     * Check Restore If There is a parent soft-deleted comment
//     *     Check login developer
//     *     Soft delete customer
//     *     Repair customer
//     *     Check response status
//     *     Check response structure
//     *     Check DB: deleted_at
//     */
//    public function testRestoreIfThereIsAParentSoftDeletedComment()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Preparation
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token, []);
//        $response->assertStatus(200);
//
//        // Preparation
//        $token = $this->loginDeveloper();
//
//        $response = $this->delete('api/customers/1/comments/3?token=' . $token, []);
//        $response->assertStatus(200);
//
//        // Request
//        $response = $this->put('api/customers/1/comments/4/restore?token=' . $token, []);
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
//        $this->assertEquals("There is a parent soft-deleted comment", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Delete Permanently:
//     *     Check login developer
//     *     Soft delete customer
//     *     Delete Permanently customer
//     *     Check response status
//     *     Check response structure
//     *     Check DB: customer must be absent
//     */
//    public function testDeletePermanently()
//    {
//        // Preparation
//        $token    = $this->loginCustomerWny();
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token, []);
//
//        // Request
//        $token    = $this->loginDeveloper();
//        $response = $this->delete('api/customers/1/comments/4/permanently?token=' . $token, []);
//
//        $response->assertStatus(200);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals("Customer's comment is destroyed permanently.", $message);
//
//        $comment = DB::table('customer_comments')->where('id', 4)->first();
//        $this->assertEquals(null, $comment);
//    }
//
//    /**
//     * Check Delete Permanently If The Customer ID Is Wrong
//     *   We wait for a message about error.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     */
//    public function testDeletePermanentlyIfTheCustomerIdIsWrong()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->delete('api/customers/2222/comments/1/permanently?token=' . $token, []);
//
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect the Entity ID in the URL.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Delete Permanently If The Comment ID Is Wrong
//     *   We wait for a message about error.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     */
//    public function testDeletePermanentlyIfTheCommentIdIsWrong()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/666/permanently?token=' . $token, []);
//
//        $response->assertStatus(456);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $code         = $responseJSON['code'];
//        $message      = $responseJSON['message'];
//        $data         = $responseJSON['data'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals(456, $code);
//        $this->assertEquals("Incorrect the Entity ID in the URL.", $message);
//        $this->assertEquals(null, $data);
//    }
//
//    /**
//     * Check Delete Permanently If The Access Is Absent By the Role
//     *   We wait for a message about error.
//     *     Check login developer
//     *     Check response status
//     *     Check response structure
//     */
//    public function testDeletePermanentlyIfTheAccessIsAbsentByTheRole()
//    {
//        $token = $this->loginCustomerWny();
//
//        // Preparation
//        $response = $this->delete('api/customers/1/comments/4?token=' . $token);
//        $response->assertStatus(200);
//
//        // Request
//        $response = $this->delete('api/customers/1/comments/4/permanently?token=' . $token);
//
//        $response->assertStatus(453);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];
//        $message      = $responseJSON['message'];
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission is absent by the role.", $message);
//    }
//
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
