<?php

/**
 * SetUp: use TestsSeeder
 * TestExample
 * Test current Seeder
 *
 * Check Index For Developer
 * Check Index For Organization Admin
 * Check Index For User-Customer
 * Check Index For Guest
 * Check Empty Index For Developer
 * Check Empty Index Organization Admin
 * Check Empty Index For User-Customer
 * Check For Organization Admin If The Access to The Department Is Absent
 * Check For User-Customer If The Access to The Department Is Absent
 *
 * Check Show For Developer
 * Check Show For Organization Admin
 * Check Show For User-Customer
 * Check Show For Guest
 * Check Show If The Access to The Department Is Absent For Organization Admin
 * Check Show If The Access to The Department Is Absent For User-Customer
 * Check Show If Entity ID is Incorrect
 *
 * Check Store For Developer
 * Check Store For Organization Admin
 * Check Store For Customer
 * Check Store For Guest
 * Check Store Invalid Data
 * Check Store Type Is User But User Has Not A Customer Role
 * Check Store If The Access to The Department Is Absent For Organization Admin
 * Check Store If The Access to The Department Is Absent For User-Customer
 * Check Store If The Customer IDs Are Incorrect
 * Check Store If The User Id Are Incorrect
 * Check Store Invalid Extension
 * Check Store File
 * Check Store File If Extension Is Banned
 *
 * Check Update For Developer
 * Check Update For Organization Admin
 * Check Update For Customer
 * Check Update For Guest
 * Check Update Invalid Data
 * Check Update If The Access to The Department Is Absent For Organization Admin
 * Check Update If The Access to The Department Is Absent For Customer
 *
 * Check Soft Delete For Developer
 * Check Soft Delete For Platform Superadmin
 * Check Soft Delete For Organization admin
 * Check Soft Delete For Owner-Customer
 * Check Soft Delete If The Id Is Wrong
 * Check Soft Delete If You Are Customer And Not The Author
 * Check Soft Delete If You Are Organizational User But Not Admin
 * Check Soft Delete If You Are Organizational Admin From Not Owner-Organization
 *
 * Check Soft-deleted Index For Developer
 * Check Empty Soft-deleted Index
 * Check Soft-deleted Index If Permission Is Absent Due To Role
 *
 * Check Restore
 * Check Restore If The File ID Is Wrong
 * Check Restore If The Access Is Absent By the Role
 *
 * Check Delete Permanently
 * Check Delete Permanently If The File ID Is Wrong
 * Check Delete Permanently If The Access Is Absent By the Role
 */

namespace App;

use App\Models\Customer;
use App\Models\CustomerComment;
use App\Models\File;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use App\Models\User_role;
use App\Models\UserDetail;
use Hash;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\WnyTestCase;
use Illuminate\Support\Facades\Storage;

class FilesControllerTest extends WnyTestCase
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
        $files = DB::table('files')->get();
        $this->assertEquals(2, $files->count());

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
        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'data' =>
                    [
                        [
                            "id",
                            "owner_object_type",
                            "owner_object_id",
                            "description",
                            "filename",
                            "owner_user_id",
                            "deleted_at",
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
        $this->assertEquals(2, count($data));
        $this->assertEquals('customer', $data[0]['owner_object_type']);
        $this->assertEquals(1, $data[0]['owner_object_id']);
        $this->assertEquals('Test file', $data[0]['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data[0]['filename']);
        $this->assertEquals(16, $data[0]['owner_user_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Files.index. Result is successful.", $message);
    }

    /**
     * Check Index For Organization Admin
     */
    public function testIndexForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'data' =>
                    [
                        [
                            "id",
                            "owner_object_type",
                            "owner_object_id",
                            "description",
                            "filename",
                            "owner_user_id",
                            "deleted_at",
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
        $this->assertEquals(2, count($data));
        $this->assertEquals('customer', $data[0]['owner_object_type']);
        $this->assertEquals(1, $data[0]['owner_object_id']);
        $this->assertEquals('Test file', $data[0]['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data[0]['filename']);
        $this->assertEquals(16, $data[0]['owner_user_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Files.index. Result is successful.", $message);
    }

    /**
     * Check Index For User-Customer
     */
    public function testIndexForUserCustomer()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'data' =>
                    [
                        [
                            "id",
                            "owner_object_type",
                            "owner_object_id",
                            "description",
                            "filename",
                            "owner_user_id",
                            "deleted_at",
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
        $this->assertEquals(2, count($data));
        $this->assertEquals('customer', $data[0]['owner_object_type']);
        $this->assertEquals(1, $data[0]['owner_object_id']);
        $this->assertEquals('Test file', $data[0]['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data[0]['filename']);
        $this->assertEquals(16, $data[0]['owner_user_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("Files.index. Result is successful.", $message);
    }

    /**
     * Check Index For Guest
     */
    public function testIndexForGuest()
    {
        // Request
        $response = $this->get('api/customers/1/files');

        // Check response status
        $response->assertStatus(401);
    }

    /**
     * Check Empty Index For Developer
     */
    public function testIndexIfContentIsEmptyForDeveloper()
    {
        $token = $this->loginCustomerWny();

        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/files/2?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginDeveloper();

        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(209);

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

        $this->assertEquals(true, $success);
        $this->assertEquals(209, $code);
        $this->assertEquals("Files.index. Content is empty.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Empty Index Organization Admin
     */
    public function testIndexIfContentIsEmptyForOrganizationAdmin()
    {
        $token = $this->loginCustomerWny();

        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/files/2?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYAdmin();

        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(209);

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

        $this->assertEquals(true, $success);
        $this->assertEquals(209, $code);
        $this->assertEquals("Files.index. Content is empty.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Empty Index For User-Customer
     */
    public function testIndexIfContentIsEmptyForUserCustomer()
    {
        $token = $this->loginCustomerWny();

        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/files/2?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginCustomerWny();

        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(209);

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

        $this->assertEquals(true, $success);
        $this->assertEquals(209, $code);
        $this->assertEquals("Files.index. Content is empty.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check For Organization Admin If The Access to The Department Is Absent
     */
    public function testIndexIfTheAccessToTheDepartmentIsAbsentForOrganizationAdmin()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->get('api/customers/1/files?token=' . $token);

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
        $this->assertEquals("Middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check For User-Customer If The Access to The Department Is Absent
     */
    public function testIndexIfTheAccessToTheDepartmentIsAbsentForUserCustomer()
    {
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/customers/1/files?token=' . $token);

        // Check response status
        $response->assertStatus(458);

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
        $this->assertEquals(458, $code);
        $this->assertEquals("Middleware.Files. Private information.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show For Developer
     */
    public function testShowForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/files/1?token=' . $token);

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
                        "owner_object_type",
                        "owner_object_id",
                        "description",
                        "filename",
                        "owner_user_id",
                        "deleted_at",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals("Files.show. Result is successful.", $message);
        $this->assertEquals('customer', $data['owner_object_type']);
        $this->assertEquals(1, $data['owner_object_id']);
        $this->assertEquals('Test file', $data['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data['filename']);
        $this->assertEquals(16, $data['owner_user_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals("Files.show. Result is successful.", $message);
    }

    /**
     * Check Show For Organization Admin
     */
    public function testShowForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/files/1?token=' . $token);

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
                        "owner_object_type",
                        "owner_object_id",
                        "description",
                        "filename",
                        "owner_user_id",
                        "deleted_at",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals("Files.show. Result is successful.", $message);
        $this->assertEquals('customer', $data['owner_object_type']);
        $this->assertEquals(1, $data['owner_object_id']);
        $this->assertEquals('Test file', $data['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data['filename']);
        $this->assertEquals(16, $data['owner_user_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals("Files.show. Result is successful.", $message);
    }

    /**
     * Check Show For User-Customer
     */
    public function testShowForUserCustomer()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->get('api/files/1?token=' . $token);

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
                        "owner_object_type",
                        "owner_object_id",
                        "description",
                        "filename",
                        "owner_user_id",
                        "deleted_at",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals("Files.show. Result is successful.", $message);
        $this->assertEquals('customer', $data['owner_object_type']);
        $this->assertEquals(1, $data['owner_object_id']);
        $this->assertEquals('Test file', $data['description']);
        $this->assertEquals('customer_1_test-file-1.jpg', $data['filename']);
        $this->assertEquals(16, $data['owner_user_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals("Files.show. Result is successful.", $message);
    }

    /**
     * Check Show For Guest
     */
    public function testShowForGuest()
    {
        // Request
        $response = $this->get('api/files/1');

        // Check response status
        $response->assertStatus(401);
    }

    /**
     * Check Show If The Access to The Department Is Absent For Organization Admin
     */
    public function testShowIfTheAccessToTheDepartmentIsAbsentForOrganizationAdmin()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->get('api/files/1?token=' . $token, []);

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
        $this->assertEquals("middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show If The Access to The Department Is Absent For User-Customer
     */
    public function testShowIfTheAccessToTheDepartmentIsAbsentForUserCustomer()
    {
        $token = $this->loginCustomerSpring();

        $response = $this->get('api/files/1?token=' . $token, []);

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
        $this->assertEquals("Middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show If Entity ID is Incorrect
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/files/44444?token=' . $token, []);

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
        $this->assertEquals("middleware.Files. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft-deleted Index For Developer
     */
    public function testIndexAllSoftDeleted()
    {
        $token = $this->loginCustomerWny();

        // Create soft deleted
        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/files/2?token=' . $token);
        $response->assertStatus(200);

        $token    = $this->loginDeveloper();
        // Request
        $response = $this->get('api/files/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'data' =>
                    [
                        [
                            "id",
                            "owner_object_type",
                            "owner_object_id",
                            "description",
                            "filename",
                            "owner_user_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "author",
                            "owner_object"
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
        $this->assertEquals("Files.indexSoftDeleted. Result is successful.", $message);
        $this->assertEquals(2, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(16, $data[0]['author']['id']);
        $this->assertEquals(1, $data[0]['owner_object']['id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);

    }

    /**
     * Check Empty Soft-deleted Index
     */
    public function testIndexAllSoftDeletedEmpty()
    {
        $token    = $this->loginDeveloper();
        // Request
        $response = $this->get('api/files/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(209);

        // Check response structure
        $response->assertJsonStructure(
            [
                'success',
                'code',
                'data',
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(true, $success);
        $this->assertEquals(209, $code);
        $this->assertEquals("Files.indexSoftDeleted. Content is empty.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft-deleted Index If Permission Is Absent Due To Role
     */
    public function testIndexAllSoftDeletedIfPermissionIsAbsentDueToRole()
    {
        $token    = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/files/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array

        $this->assertEquals("Permission is absent by the role.", $message);
        $this->assertEquals(false, $success);
    }

    /**
     * Check Store For Developer
     */
    public function testStoreForDeveloper()
    {
        $token = $this->loginDeveloper();

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 1,
            "photo"             => $file
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals("Files.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $file     = DB::table('files')->where('id', '=', 3)->first();
        $filename = $file->filename;
        $this->assertEquals('customer', $file->owner_object_type);
        $this->assertEquals(1, $file->owner_object_id);
        $this->assertEquals('Description demo text.', $file->description);
        $this->assertEquals(1, $file->owner_user_id);
        $this->assertEquals(null, $file->deleted_at);

        Storage::disk('public')->assertExists($filename);

        // Delete file from disk
        $q      = Storage::disk('public')->delete($filename);
        $exists = Storage::disk('public')->exists($filename);
        $this->assertEquals(false, $exists);
    }

    /**
     * Check Store For Organization Admin
     */
    public function testStoreForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 5,
            "photo"             => $file
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals("Files.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $file     = DB::table('files')->where('id', '=', 3)->first();
        $filename = $file->filename;
        $this->assertEquals('customer', $file->owner_object_type);
        $this->assertEquals(1, $file->owner_object_id);
        $this->assertEquals('Description demo text.', $file->description);
        $this->assertEquals(5, $file->owner_user_id);
        $this->assertEquals(null, $file->deleted_at);

        Storage::disk('public')->assertExists($filename);

        // Delete file from disk
        $q      = Storage::disk('public')->delete($filename);
        $exists = Storage::disk('public')->exists($filename);
        $this->assertEquals(false, $exists);
    }

    /**
     * Check Store For Customer
     */
    public function testStoreForCustomer()
    {
        $token = $this->loginCustomerWny();

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 16,
            "photo"             => $file
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals("Files.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $file     = DB::table('files')->where('id', '=', 3)->first();
        $filename = $file->filename;
        $this->assertEquals('customer', $file->owner_object_type);
        $this->assertEquals(1, $file->owner_object_id);
        $this->assertEquals('Description demo text.', $file->description);
        $this->assertEquals(16, $file->owner_user_id);
        $this->assertEquals(null, $file->deleted_at);

        Storage::disk('public')->assertExists($filename);

        // Delete file from disk
        $q      = Storage::disk('public')->delete($filename);
        $exists = Storage::disk('public')->exists($filename);
        $this->assertEquals(false, $exists);
    }

    /**
     * Check Store For Guest
     */
    public function testStoreForGuest()
    {
        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 16
        ];

        // Store the comment
        $response = $this->post('api/files?token=');

        // Check response status
        $response->assertStatus(401);
    }

    /**
     * Check Store Invalid Data
     */
    public function testStoreInvalidData()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "owner_object_type" => [],
            "owner_object_id"   => [],
            "description"       => [],
            "filename"          => [],
            "owner_user_id"     => []
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
     * Check Type Is User But User Has Not A Customer Role
     */
    public function testStoreTypeIsUserButUserHasNotACustomerRole()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Create data
        $data = [
            "owner_object_type" => 'user',
            "owner_object_id"   => 16,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 5
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

        // Check response status
        $response->assertStatus(453);

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
        $this->assertEquals(453, $code);
        $this->assertEquals("Files.store. Permission is absent by the role.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Store If The Access to The Department Is Absent For User-Customer
     */
    public function testStoreTheAccessToTheDepartmentIsAbsentForUserCustomer()
    {
        $token = $this->loginCustomerSpring();

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 17
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals("Files.store. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Store If The Access to The Department Is Absent For Organization Admin
     */
    public function testStoreTheAccessToTheDepartmentIsAbsentForOrganizationAdmin()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 21
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals("Files.store. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Store If The Customer IDs Are Incorrect
     */
    public function testStoreIfTheCustomerIdAreIncorrect()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 66666,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 21
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

        // Check response status
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
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals(422, $code);
        $this->assertEquals("Files.store. The given data was invalid.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Store If The User Id Are Incorrect
     */
    public function testStoreIfTheUserIdAreIncorrect()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "owner_object_type" => 'user',
            "owner_object_id"   => 66666,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 21
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

        // Check response status
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
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals(422, $code);
        $this->assertEquals("Files.store. The given data was invalid.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Store File
     */
    public function testStoreFile()
    {
        $token = $this->loginDeveloper();

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.jpg',
            "owner_user_id"     => 1,
            "photo"             => $file
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);
//        dd($response);
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
        $this->assertEquals("Files.store. Result is successful.", $message);
        $file     = DB::table('files')->where('id', 3)->first();
        $filename = $file->filename;
        Storage::disk('public')->assertExists($filename);

        // Delete file from disk
        $q      = Storage::disk('public')->delete($filename);
        $exists = Storage::disk('public')->exists($filename);
        $this->assertEquals(false, $exists);
    }

    /**
     * Check Store File If Extension Is Banned
     */
    public function testStoreFileIfExtensionIsBanned()
    {
        $token = $this->loginDeveloper();

        $file = UploadedFile::fake()->image('avatar.abc');

        // Create data
        $data = [
            "owner_object_type" => 'customer',
            "owner_object_id"   => 1,
            "description"       => 'Description demo text.',
            "filename"          => 'customer_1_test-file-3.abc',
            "owner_user_id"     => 1,
            "photo"             => $file
        ];

        // Store the comment
        $response = $this->post('api/files?token=' . $token, $data);

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
        $this->assertEquals(1, count($error['errors']));
    }


    /**
     * Check Update For Developer
     */
    public function testUpdateForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Description edited", $data['description']);
    }

    /**
     * Check Update For Organization Admin
     */
    public function testUpdateForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Create data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Description edited", $data['description']);
    }

    /**
     * Check Update For Customer
     */
    public function testUpdateForCustomer()
    {
        $token = $this->loginCustomerWny();

        // Create data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Description edited", $data['description']);
    }

    /**
     * Check Update For Guest
     */
    public function testUpdateForGuest()
    {
        // Create data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1', $data);

        // Check response status
        $response->assertStatus(401);
    }

    /**
     * Check Update If Entity Id Is wrong
     */
    public function testUpdateIfEntityIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Update data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("middleware.Files. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update If The Access to The Department Is Absent For Organization Admin
     */
    public function testUpdateIfTheAccessToTheDepartmentIsAbsentForOrganizationAdmin()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Update data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1?token=' . $token, $data);
        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update If The Access to The Department Is Absent For Customer
     */
    public function testUpdateIfTheAccessToTheDepartmentIsAbsentForCustomer()
    {
        $token = $this->loginCustomerSpring();

        // Update data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/files/1?token=' . $token, $data);
        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("Middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft Delete For Developer
     */
    public function testSoftDeleteForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/files/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.softDestroy. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $file = DB::table('files')->where('id', 1)->first();
        $this->assertNotEquals(null, $file->deleted_at);
    }

    /**
     * Check Soft Delete For Platform Superadmin
     */
    public function testSoftDeleteForPlatformSuperadmin()
    {
        $token = $this->loginPlatformSuperAdmin();

        // Request
        $response = $this->delete('api/files/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.softDestroy. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $file = DB::table('files')->where('id', 1)->first();
        $this->assertNotEquals(null, $file->deleted_at);
    }

    /**
     * Check Soft Delete For Organization admin
     */
    public function testSoftDeleteForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->delete('api/files/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.softDestroy. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $file = DB::table('files')->where('id', 1)->first();
        $this->assertNotEquals(null, $file->deleted_at);
    }

    /**
     * Check Soft Delete For Owner-Customer
     */
    public function testSoftDeleteForOwnerCustomer()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->delete('api/files/1?token=' . $token, []);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.softDestroy. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $file = DB::table('files')->where('id', 1)->first();
        $this->assertNotEquals(null, $file->deleted_at);
    }

    /**
     * Check Soft Delete If The Id Is Wrong
     */
    public function testSoftDeleteIfTheIdIsWrong()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->delete('api/files/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("middleware.Files. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft Delete If You Are Customer And Not The Author
     */
    public function testSoftDeleteIfYouAreCustomerAndNotTheAuthor()
    {
        $token = $this->loginCustomerSpring();

        // Request
        $response = $this->delete('api/files/1?token=' . $token);

        $response->assertStatus(457);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(457, $code);
        $this->assertEquals("middleware.Files. You are not the author.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft Delete If You Are Organizational User But Not Admin
     */
    public function testSoftDeleteIfYouAreOrganizationalUserButNotAdmin()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->delete('api/files/1?token=' . $token);

        $response->assertStatus(457);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(457, $code);
        $this->assertEquals("middleware.Files. You are not the author.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft Delete If You Are Organizational Admin From Not Owner-Organization
     */
    public function testSoftDeleteIfYouAreOrganizationalAdminFromNotOwnerOrganization()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->delete('api/files/1?token=' . $token);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(454, $code);
        $this->assertEquals("middleware.Files. Permission to the department is absent.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Restore
     */
    public function testRestore()
    {
        $token = $this->loginCustomerWny();

        // Preparation
        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginDeveloper();
        // Request
        $response = $this->put('api/files/1/restore?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $file = File::where('id', 1)->first();
        $this->assertEquals(null, $file->deleted_at);
    }

    /**
     * Check Restore If The File ID Is Wrong
     */
    public function testRestoreIfTheFileIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/files/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Files.restore. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Restore If The Access Is Absent By the Role
     */
    public function testRestoreIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->put('api/files/1/restore?token=' . $token);

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
        $token    = $this->loginCustomerWny();
        $response = $this->delete('api/files/1?token=' . $token);
        $response->assertStatus(200);

        $token    = $this->loginDeveloper();
        // Request
        $response = $this->delete('api/files/1/permanently?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Files.destroyPermanently. Result is successful.", $message);

        $file = DB::table('files')->where('id', 1)->first();
        $this->assertEquals(null, $file);
    }

    /**
     * Check Delete Permanently If The File ID Is Wrong
     */
    public function testDeletePermanentlyIfTheFileIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/files/2222/permanently?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Files.destroyPermanently. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Delete Permanently If The Access Is Absent By the Role
     */
    public function testDeletePermanentlyIfTheAccessIsAbsentByTheRole()
    {
        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/files/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
