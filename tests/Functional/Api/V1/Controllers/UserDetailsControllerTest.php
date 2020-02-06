<?php

/**
 * SetUp: use TestsSeeder
 * TestExample
 * Test current Seeder
 *
 * Check Index For Developer
 * Check Index For Organization Admin
 * Check Empty Index
 * Check If Permission Is Absent Due To Role
 *
 * Check Show For Developer
 * Check Show For Organization Admin
 * Check Show If The Access Is Absent By The Role
 * Check Show If The Access to The Department Is Absent
 * Check Show If Entity ID is Incorrect
 *
 * Check Store For Developer
 * Check Store For Organization Admin
 * Check Store For Customer
 * Check Store Invalid Data
 * Check Store If The Access to The Department Is Absent
 *
 * Check Update For Developer
 * Check Update For Organization Admin
 * Check Update For Customer
 * Check Update If Entity Id Is wrong
 * Check Update If Data Is Invalid
 * Check Update If The Access to The Department Is Absent
 *
 * Check Soft Delete For Developer
 * Check Soft Delete For Organization Admin
 * Check Soft Delete If The Id Is Wrong
 * Check Soft Delete If The Access Is Absent Due To Role
 *
 * Check Restore
 * Check Restore If The User Details ID Is Wrong
 * Check Restore If The Access Is Absent By the Role
 *
 * Check Delete Permanently
 * Check Delete Permanently If The User Details ID Is Wrong
 * Check Delete Permanently If The Access Is Absent By the Role
 */

namespace App;

use App\Models\Customer;
use App\Models\CustomerComment;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use App\Models\User_role;
use App\Models\UserDetail;
use Hash;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\WnyTestCase;

class UserDetailsControllerTest extends WnyTestCase
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
        $userDetails = DB::table('user_details')->get();
        $this->assertEquals(5, $userDetails->count());

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Volodymyr Vadiasov', $user->name);
    }

    /**
     * Check Index For Developer
     */
    public function testIndexForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/user-details?token=' . $token, []);

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
                            //                            "prefix",
                            "first_name",
                            "last_name",
                            //                            "suffix",
                            //                            "work_title",
                            //                            "work_department",
                            //                            "work_role",
                            //                            "phone_home",
                            //                            "phone_work",
                            //                            "phone_extension",
                            //                            "phone_mob",
                            //                            "phone_fax",
                            //                            "email_work",
                            //                            "email_personal",
                            //                            "line_1",
                            //                            "line_2",
                            //                            "city",
                            //                            "state",
                            //                            "zip",
                            "status",
                            //                            "deleted_at",
                            "created_at",
                            "updated_at"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(5, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(16, $data[0]['user_id']);
//        $this->assertEquals('Mrs', $data[0]['prefix']);
        $this->assertEquals('Evelyn', $data[0]['first_name']);
        $this->assertEquals('Perkins', $data[0]['last_name']);
//        $this->assertEquals('M.D.', $data[0]['suffix']);
//        $this->assertEquals('Central Hospital', $data[0]['work_title']);
//        $this->assertEquals('Surgery', $data[0]['work_department']);
//        $this->assertEquals('Surgeon', $data[0]['work_role']);
//        $this->assertEquals('0119627516', $data[0]['phone_home']);
//        $this->assertEquals('0119627522', $data[0]['phone_work']);
//        $this->assertEquals('123', $data[0]['phone_extension']);
//        $this->assertEquals('0814540666', $data[0]['phone_mob']);
//        $this->assertEquals('0119627523', $data[0]['phone_fax']);
//        $this->assertEquals('Central.Hospital@example.com', $data[0]['email_work']);
//        $this->assertEquals('evelyn.perkins@example.com', $data[0]['email_personal']);
//        $this->assertEquals('9278 new road', $data[0]['line_1']);
//        $this->assertEquals('app 3', $data[0]['line_2']);
//        $this->assertEquals('Kilcoole', $data[0]['city']);
//        $this->assertEquals('OH', $data[0]['state']);
//        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals('active', $data[0]['status']);
//        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data[0]['created_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data[0]['updated_at']);
        $this->assertEquals("User Details are retrieved successfully.", $message);
    }

    /**
     * Check Index For Organization Admin
     */
    public function testIndexForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/user-details?token=' . $token);

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
                            "status",
                            "created_at",
                            "updated_at"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(4, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(16, $data[0]['user_id']);
        $this->assertEquals('Evelyn', $data[0]['first_name']);
        $this->assertEquals('Perkins', $data[0]['last_name']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertEquals('2019-12-30 16:54:04', $data[0]['created_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data[0]['updated_at']);
        $this->assertEquals("User Details are retrieved successfully.", $message);
    }

    /**
     * Check Index If Content Is Empty
     */
    public function testIndexIfContentIsEmpty()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->delete('api/user-details/2?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/user-details?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index If The Access Is Absent By The Role
     */
    public function testIndexIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/user-details?token=' . $token, []);

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
     * Check Show For Developer
     */
    public function testShowForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/user-details/1?token=' . $token, []);

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
                        "id",
                        "user_id",
                        "prefix",
                        "first_name",
                        "last_name",
                        "suffix",
                        "work_title",
                        "work_department",
                        "work_role",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob",
                        "phone_fax",
                        "email_work",
                        "email_personal",
                        "line_1",
                        "line_2",
                        "city",
                        "state",
                        "zip",
                        "status",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "user"
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
        $this->assertEquals("User-details is retrieved successfully.", $message);
        $this->assertEquals(16, $data['user_id']);
        $this->assertEquals(16, $data['user']['id']);
        $this->assertEquals('Mrs', $data['prefix']);
        $this->assertEquals('Evelyn', $data['first_name']);
        $this->assertEquals('Perkins', $data['last_name']);
        $this->assertEquals('M.D.', $data['suffix']);
        $this->assertEquals('Central Hospital', $data['work_title']);
        $this->assertEquals('Surgery', $data['work_department']);
        $this->assertEquals('Surgeon', $data['work_role']);
        $this->assertEquals('0119627516', $data['phone_home']);
        $this->assertEquals('0119627522', $data['phone_work']);
        $this->assertEquals('123', $data['phone_extension']);
        $this->assertEquals('0814540666', $data['phone_mob']);
        $this->assertEquals('0119627523', $data['phone_fax']);
        $this->assertEquals('Central.Hospital@example.com', $data['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data['email_personal']);
        $this->assertEquals('9278 new road', $data['line_1']);
        $this->assertEquals('app 3', $data['line_2']);
        $this->assertEquals('Kilcoole', $data['city']);
        $this->assertEquals('OH', $data['state']);
        $this->assertEquals('93027', $data['zip']);
        $this->assertEquals('active', $data['status']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data['created_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data['updated_at']);
    }

    /**
     * Check Show For Organization Admin
     */
    public function testShowForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/user-details/1?token=' . $token, []);

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
                        "id",
                        "user_id",
                        "prefix",
                        "first_name",
                        "last_name",
                        "suffix",
                        "work_title",
                        "work_department",
                        "work_role",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob",
                        "phone_fax",
                        "email_work",
                        "email_personal",
                        "line_1",
                        "line_2",
                        "city",
                        "state",
                        "zip",
                        "status",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "user"
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
        $this->assertEquals("User-details is retrieved successfully.", $message);
        $this->assertEquals(16, $data['user_id']);
        $this->assertEquals(16, $data['user']['id']);
        $this->assertEquals('Mrs', $data['prefix']);
        $this->assertEquals('Evelyn', $data['first_name']);
        $this->assertEquals('Perkins', $data['last_name']);
        $this->assertEquals('M.D.', $data['suffix']);
        $this->assertEquals('Central Hospital', $data['work_title']);
        $this->assertEquals('Surgery', $data['work_department']);
        $this->assertEquals('Surgeon', $data['work_role']);
        $this->assertEquals('0119627516', $data['phone_home']);
        $this->assertEquals('0119627522', $data['phone_work']);
        $this->assertEquals('123', $data['phone_extension']);
        $this->assertEquals('0814540666', $data['phone_mob']);
        $this->assertEquals('0119627523', $data['phone_fax']);
        $this->assertEquals('Central.Hospital@example.com', $data['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data['email_personal']);
        $this->assertEquals('9278 new road', $data['line_1']);
        $this->assertEquals('app 3', $data['line_2']);
        $this->assertEquals('Kilcoole', $data['city']);
        $this->assertEquals('OH', $data['state']);
        $this->assertEquals('93027', $data['zip']);
        $this->assertEquals('active', $data['status']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data['created_at']);
        $this->assertEquals('2019-12-30 16:54:04', $data['updated_at']);
    }

    /**
     * Check Show If The Access to The Department Is Absent
     */
    public function testShowIfTheAccessToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->get('api/user-details/1?token=' . $token, []);

        // Check response status
        $response->assertStatus(454);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show If Entity ID is Incorrect
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/user-details/44444?token=' . $token, []);

        // Check response status
        $response->assertStatus(456);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'message',
                'data'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("UserDetails.show. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }


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
    /**
     * Check Store For Developer
     */
    public function testStoreForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'user_id'         => 23,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null
        ];

        // Store the comment
        $response = $this->post('api/user-details?token=' . $token, $data);

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
        $this->assertEquals("Item is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $userDetails = DB::table('user_details')->where('id', '=', 6)->first();
        $this->assertEquals(23, $userDetails->user_id);
        $this->assertEquals('Evelyn', $userDetails->first_name);
        $this->assertEquals('Perkins', $userDetails->last_name);
        $this->assertEquals('Mrs', $userDetails->prefix);
        $this->assertEquals('M.D.', $userDetails->suffix);
        $this->assertEquals('Central Hospital', $userDetails->work_title);
        $this->assertEquals('Surgery', $userDetails->work_department);
        $this->assertEquals('Surgeon', $userDetails->work_role);
        $this->assertEquals('0119627516', $userDetails->phone_home);
        $this->assertEquals('0119627522', $userDetails->phone_work);
        $this->assertEquals('123', $userDetails->phone_extension);
        $this->assertEquals('0814540666', $userDetails->phone_mob);
        $this->assertEquals('0119627523', $userDetails->phone_fax);
        $this->assertEquals('Central.Hospital@example.com', $userDetails->email_work);
        $this->assertEquals('evelyn.perkins@example.com', $userDetails->email_personal);
        $this->assertEquals('9278 new road', $userDetails->line_1);
        $this->assertEquals('app 3', $userDetails->line_2);
        $this->assertEquals('Kilcoole', $userDetails->city);
        $this->assertEquals('OH', $userDetails->state);
        $this->assertEquals('93027', $userDetails->zip);
        $this->assertEquals('active', $userDetails->status);
        $this->assertEquals(null, $userDetails->deleted_at);
    }

    /**
     * Check Store For Organization Admin
     */
    public function testStoreForOrganizationAdmin()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'user_id'         => 23,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null
        ];

        // Store the comment
        $response = $this->post('api/user-details?token=' . $token, $data);

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
        $this->assertEquals("Item is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $userDetails = DB::table('user_details')->where('id', '=', 6)->first();
        $this->assertEquals(23, $userDetails->user_id);
        $this->assertEquals('Evelyn', $userDetails->first_name);
        $this->assertEquals('Perkins', $userDetails->last_name);
        $this->assertEquals('Mrs', $userDetails->prefix);
        $this->assertEquals('M.D.', $userDetails->suffix);
        $this->assertEquals('Central Hospital', $userDetails->work_title);
        $this->assertEquals('Surgery', $userDetails->work_department);
        $this->assertEquals('Surgeon', $userDetails->work_role);
        $this->assertEquals('0119627516', $userDetails->phone_home);
        $this->assertEquals('0119627522', $userDetails->phone_work);
        $this->assertEquals('123', $userDetails->phone_extension);
        $this->assertEquals('0814540666', $userDetails->phone_mob);
        $this->assertEquals('0119627523', $userDetails->phone_fax);
        $this->assertEquals('Central.Hospital@example.com', $userDetails->email_work);
        $this->assertEquals('evelyn.perkins@example.com', $userDetails->email_personal);
        $this->assertEquals('9278 new road', $userDetails->line_1);
        $this->assertEquals('app 3', $userDetails->line_2);
        $this->assertEquals('Kilcoole', $userDetails->city);
        $this->assertEquals('OH', $userDetails->state);
        $this->assertEquals('93027', $userDetails->zip);
        $this->assertEquals('active', $userDetails->status);
        $this->assertEquals(null, $userDetails->deleted_at);
    }

    /**
     * Check Store For Customer
     */
    public function testStoreForCustomer()
    {
        $token = $this->loginCustomerFWny();

        // Create data
        $data = [
            'user_id'         => 23,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null
        ];

        // Store the comment
        $response = $this->post('api/user-details?token=' . $token, $data);

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
        $this->assertEquals("Item is created successfully.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $userDetails = DB::table('user_details')->where('id', '=', 6)->first();
        $this->assertEquals(23, $userDetails->user_id);
        $this->assertEquals('Evelyn', $userDetails->first_name);
        $this->assertEquals('Perkins', $userDetails->last_name);
        $this->assertEquals('Mrs', $userDetails->prefix);
        $this->assertEquals('M.D.', $userDetails->suffix);
        $this->assertEquals('Central Hospital', $userDetails->work_title);
        $this->assertEquals('Surgery', $userDetails->work_department);
        $this->assertEquals('Surgeon', $userDetails->work_role);
        $this->assertEquals('0119627516', $userDetails->phone_home);
        $this->assertEquals('0119627522', $userDetails->phone_work);
        $this->assertEquals('123', $userDetails->phone_extension);
        $this->assertEquals('0814540666', $userDetails->phone_mob);
        $this->assertEquals('0119627523', $userDetails->phone_fax);
        $this->assertEquals('Central.Hospital@example.com', $userDetails->email_work);
        $this->assertEquals('evelyn.perkins@example.com', $userDetails->email_personal);
        $this->assertEquals('9278 new road', $userDetails->line_1);
        $this->assertEquals('app 3', $userDetails->line_2);
        $this->assertEquals('Kilcoole', $userDetails->city);
        $this->assertEquals('OH', $userDetails->state);
        $this->assertEquals('93027', $userDetails->zip);
        $this->assertEquals('active', $userDetails->status);
        $this->assertEquals(null, $userDetails->deleted_at);
    }


    /**
     * Check store invalid data
     */
    public function testStoreInvalidData()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'user_id'         => [],
            'prefix'          => [],
            'first_name'      => [],
            'last_name'       => [],
            'suffix'          => [],
            'work_title'      => [],
            'work_department' => [],
            'work_role'       => [],
            'phone_home'      => [],
            'phone_work'      => [],
            'phone_extension' => [],
            'phone_mob'       => [],
            'phone_fax'       => [],
            'email_work'      => [],
            'email_personal'  => [],
            'line_1'          => [],
            'line_2'          => [],
            'city'            => [],
            'state'           => [],
            'zip'             => [],
            'status'          => []
        ];

        // Store a new user, user profile
        $response = $this->post('api/user-details?token=' . $token, $data);

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
        $this->assertEquals(21, count($error['errors']));
    }

    /**
     * Check Store If The Access To The Department Is Absent
     */
    public function testStoreIfTheAccessToTheDepartmentIsAbsent()
    {
        $token = $this->loginCustomerSpring();

        // Create data
        $data = [
            'user_id'         => 23,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null
        ];

        // Store the details
        $response = $this->post('api/user-details?token=' . $token, $data);

        // Check response status
        $response->assertStatus(454);

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

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update For Developer
     */
    public function testUpdateForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'user_id'         => 16,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital Test',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ];

        $response = $this->put('api/user-details/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User Details are updated successfully.", $message);
        $this->assertEquals("Central Hospital Test", $data->work_title);
        $this->assertEquals(1, $data->id);
        $this->assertEquals(16, $data->user_id);
    }

    /**
     * Check Update For Organization Admin
     */
    public function testUpdateForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Create data
        $data = [
            'user_id'         => 16,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital Test',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ];

        $response = $this->put('api/user-details/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User Details are updated successfully.", $message);
        $this->assertEquals("Central Hospital Test", $data->work_title);
        $this->assertEquals(1, $data->id);
        $this->assertEquals(16, $data->user_id);
    }

    /**
     * Check Update For Customer
     */
    public function testUpdateForCustomer()
    {
        $token = $this->loginCustomerWny();

        // Create data
        $data = [
            'user_id'         => 16,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital Test',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ];

        $response = $this->put('api/user-details/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];
        $data         = json_decode($data);

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User Details are updated successfully.", $message);
        $this->assertEquals("Central Hospital Test", $data->work_title);
        $this->assertEquals(1, $data->id);
        $this->assertEquals(16, $data->user_id);
    }

    /**
     * Check Update If Entity Id Is wrong
     */
    public function testUpdateIfEntityIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Update data
        $data = [
            'user_id'         => 16,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital Test',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ];

        $response = $this->put('api/user-details/44444?token=' . $token, $data);
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
     * Check Update If Data Is Invalid
     */
    public function testUpdateIfDataIsInvalid()
    {
        $token = $this->loginDeveloper();

        // Update data
        $data = [
            'user_id'         => [],
            'prefix'          => [],
            'first_name'      => [],
            'last_name'       => [],
            'suffix'          => [],
            'work_title'      => '[]',
            'work_department' => [],
            'work_role'       => [],
            'phone_home'      => [],
            'phone_work'      => [],
            'phone_extension' => [],
            'phone_mob'       => [],
            'phone_fax'       => [],
            'email_work'      => [],
            'email_personal'  => [],
            'line_1'          => [],
            'line_2'          => [],
            'city'            => [],
            'state'           => [],
            'zip'             => [],
            'status'          => []
        ];

        $response     = $this->put('api/user-details/44444?token=' . $token, $data);
        $responseJSON = json_decode($response->getContent(), true);
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
        $this->assertEquals(20, count($error['errors']));
    }

    /**
     * Check Store If The Access to The Department Is Absent
     */
    public function testUpdateIfTheAccessToTheDepartmentIsAbsent()
    {
        $token = $this->loginCustomerSpring();

        // Update data
        $data = [
            'user_id'         => 16,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'work_title'      => 'Central Hospital Test',
            'work_department' => 'Surgery',
            'work_role'       => 'Surgeon',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob'       => '0814540666',
            'phone_fax'       => '0119627523',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'status'          => 'active',
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ];

        $response = $this->put('api/user-details/1?token=' . $token, $data);
        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }


    /**
     * Check Soft Delete For Developer
     */
    public function testSoftDeleteForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-details/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-details is soft-deleted successfully.", $message);
        $this->assertEquals(null, $data);

        $customer = DB::table('user_details')->where('id', 1)->first();
        $this->assertNotEquals(null, $customer->deleted_at);
    }

    /**
     * Check Soft Delete For Organization Admin
     */
    public function testSoftDeleteForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->delete('api/user-details/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User-details is soft-deleted successfully.", $message);
        $this->assertEquals(null, $data);

        $customer = DB::table('user_details')->where('id', 1)->first();
        $this->assertNotEquals(null, $customer->deleted_at);
    }

    /**
     * Check Soft Delete If The Id Is Wrong
     */
    public function testSoftDeleteIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-details/4444?token=' . $token);

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
     * Check Soft Delete If The Access Is Absent Due To Role
     */
    public function testSoftDeleteIfTheAccessIsAbsentDueToRole()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->delete('api/user-details/1?token=' . $token);

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
        $response = $this->delete('api/user-details/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/user-details/1/restore?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User Details are restored successfully.", $message);
        $this->assertEquals(null, $data);

        $userDetails = UserDetail::where('id', 1)->first();
        $this->assertEquals(null, $userDetails->deleted_at);
    }

    /**
     * Check Restore If The User Details ID Is Wrong
     *     Check login
     *     Check response status
     *     Check response structure
     */
    public function testRestoreIfTheUserDetailsIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/user-details/4444/restore?token=' . $token, []);

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
     */
    public function testRestoreIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYSuperadmin();
//        $token = $this->loginCustomerWny();

        // Request
        $response = $this->put('api/user-details/1/restore?token=' . $token);

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
        $response = $this->delete('api/user-details/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/user-details/1/permanently?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("User Details are deleted permanently.", $message);

        $userDetails = DB::table('user_details')->where('id', 1)->first();
        $this->assertEquals(null, $userDetails);
    }

    /**
     * Check Delete Permanently If The User Details ID Is Wrong
     */
    public function testDeletePermanentlyIfTheUserDetailsIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/user-details/2222/permanently?token=' . $token);

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
     * Check Delete Permanently If The Access Is Absent By the Role
     */
    public function testDeletePermanentlyIfTheAccessIsAbsentByTheRole()
    {
        // Preparation
        $token    = $this->loginDeveloper();
        $response = $this->delete('api/user-details/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/user-details/1/permanently?token=' . $token);

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
