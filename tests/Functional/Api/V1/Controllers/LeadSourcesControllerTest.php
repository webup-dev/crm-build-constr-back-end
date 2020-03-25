<?php

namespace App;

use App\Models\LeadSource;
use App\Models\LsCategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Migration to create lead_sources table
 * php version 7
 *
 * @category Tests
 * @package  LsCategories
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class LeadSourcesControllerTest extends WnyTestCase
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
        $lead_sources = DB::table('lead_sources')->get();
        $this->assertEquals(6, $lead_sources->count());

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Volodymyr Vadiasov', $user->name);
    }

    /**
     * Check Index For Developer
     *
     * @return void
     */
    public function testIndexForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/lead-sources?token=' . $token);

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
                            "name",
                            "category_id",
                            "organization_id",
                            "status",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "ls_category",
                            "organization"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(5, count($data));
        $this->assertEquals('Website - CertainTeed', $data[0]['name']);
        $this->assertEquals(18, $data[0]['category_id']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('Internet', $data[0]['ls_category']['name']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("LeadSources.index. Result is successful.", $message);
    }

    /**
     * Check Index For WNY organizational user
     *
     * @return void
     */
    public function testIndexForOrganizationWNYGeneralManager()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/lead-sources?token=' . $token);

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
                            "name",
                            "category_id",
                            "organization_id",
                            "status",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "ls_category",
                            "organization"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(5, count($data));
        $this->assertEquals('Website - CertainTeed', $data[0]['name']);
        $this->assertEquals(18, $data[0]['category_id']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('active', $data[0]['status']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('Internet', $data[0]['ls_category']['name']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("LeadSources.index. Result is successful.", $message);
    }

    /**
     * Test IndexForDeveloper
     *
     * @return void
     */
    public function testIndexEmpty()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->get('api/lead-sources?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Index If Permission Is Absent
     *
     * @return void
     */
    public function testIndexIfPermissionIsAbsent()
    {
        $token = $this->loginCustomerWny();

        // Request
        $response = $this->get('api/lead-sources?token=' . $token);

        // Check response status
        $response->assertStatus(453);
    }

    /**
     * Check SoftDeleted Index For Developer
     *
     * @return void
     */
    public function testIndexSoftDeleted()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-sources/5?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-sources/4?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/lead-sources/soft-deleted?token=' . $token);

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
                            "name",
                            "category_id",
                            "organization_id",
                            "status",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "ls_category",
                            "organization"
                        ]
                    ],
                'message'
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(3, count($data));
        $this->assertEquals('Website - Owens Corning', $data[2]['name']);
        $this->assertEquals(18, $data[2]['category_id']);
        $this->assertEquals(2, $data[2]['organization_id']);
        $this->assertEquals('inactive', $data[2]['status']);
        $this->assertNotEquals(null, $data[2]['deleted_at']);
        $this->assertEquals('Internet', $data[2]['ls_category']['name']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[2]['organization']['name']);
        $this->assertEquals("LeadSources.indexSoftDeleted. Result is successful.", $message);
    }

    /**
     * Check Empty SoftDeleted Index For Developer
     *
     * @return void
     */
    public function testIndexSoftDeletedEmpty()
    {
        $token = $this->loginDeveloper();
        $response = $this->put('api/lead-sources/6/restore?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->get('api/lead-sources/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check SoftDeleted Index When Permission is absent due to Role
     *
     * @return void
     */
    public function testIndexSoftDeletedWhenPermissionIsAbsentDueToRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/lead-sources/soft-deleted?token=' . $token);

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
     * Check Show For Developer
     *
     * @return void
     */
    public function testShowForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/lead-sources/1?token=' . $token);

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
                        "name",
                        "category_id",
                        "organization_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "category",
                        "organization"
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadSources.show. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals('Website - CertainTeed', $data['name']);
        $this->assertEquals(18, $data['category_id']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals('Internet', $data['category']['name']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data['organization']['name']);
    }

    /**
     * Check Show If Entity ID is Incorrect
     *
     * @return void
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/lead-sources/44444?token=' . $token, []);

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
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "LeadSources.show. Incorrect ID in URL.",
            $message
        );
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show Permission is absent by the Role
     *
     * @return void
     */
    public function testShowPermissionIsAbsentByTheRole()
    {
        $token = $this->loginCustomerWny();

        $response = $this->get('api/lead-sources/1?token=' . $token);

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
     * Check Show Permission to the department is absent
     *
     * @return void
     */
    public function testShowPermissionToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        $response = $this->get('api/lead-sources/1?token=' . $token);

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

//    /**
//     * Check Soft-deleted Index For Developer
//     *
//     * @return void
//     */
//    public function testIndexAllSoftDeleted()
//    {
//        $token = $this->loginDeveloper();
//
//        // Create soft deleted
//        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
//        $response->assertStatus(200);
//
//        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
//        $response->assertStatus(200);
//
//        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'code',
//                'data' =>
//                    [
//                        [
//                            "id",
//                            "name",
//                            "description",
//                            "deleted_at",
//                            "created_at",
//                            "updated_at"
//                        ]
//                    ],
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];     // array
//        $message      = $responseJSON['message'];  // array
//        $data         = $responseJSON['data'];     // array
//
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//        $this->assertEquals(
//            "LsCategories.indexSoftDeleted. Result is successful.",
//            $message
//        );
//        $this->assertEquals(2, count($data));
//        $this->assertEquals(1, $data[0]['id']);
//        $this->assertEquals('Blogging', $data[0]['name']);
//        $this->assertNotEquals(null, $data[0]['deleted_at']);
//    }
//
//    /**
//     * Check Empty Soft-deleted Index
//     *
//     * @return void
//     */
//    public function testIndexAllSoftDeletedEmpty()
//    {
//        $token = $this->loginDeveloper();
//
//        // Request
//        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);
//
//        // Check response status
//        $response->assertStatus(204);
//    }
//
//    /**
//     * Check Soft-deleted Index If Permission Is Absent Due To Role
//     *
//     * @return void
//     */
//    public function testIndexAllSoftDeletedIfPermissionIsAbsentDueToRole()
//    {
//        $token = $this->loginOrganizationWNYAdmin();
//
//        // Request
//        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);
//
//        // Check response status
//        $response->assertStatus(453);
//
//        $responseJSON = json_decode($response->getContent(), true);
//        $message      = $responseJSON['message'];  // array
//        $success      = $responseJSON['success'];  // array
//
//        $this->assertEquals("Permission is absent by the role.", $message);
//        $this->assertEquals(false, $success);
//    }
//
    /**
     * Check Store For Developer
     *
     * @return void
     */
    public function testStoreForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"            => 'Test Lead Source',
            "category_id"     => 17,
            "organization_id" => 2,
            "status"          => 'active'
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-sources?token=' . $token, $data);

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
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadSources.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $leadSources = DB::table('lead_sources')
            ->where('id', '=', 7)
            ->first();
        $this->assertEquals('Test Lead Source', $leadSources->name);
        $this->assertEquals(17, $leadSources->category_id);
        $this->assertEquals(2, $leadSources->organization_id);
        $this->assertEquals('active', $leadSources->status);
        $this->assertEquals(null, $leadSources->deleted_at);
    }

    /**
     * Check Store When Permission is absent by the Role
     *
     * @return void
     */
    public function testStoreWhenPermissionIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Create data
        $data = [
            "name"            => 'Test Lead Source',
            "category_id"     => 17,
            "organization_id" => 2,
            "status"          => 'active'
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-sources?token=' . $token, $data);

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
     * Check Store When Permission to the department is absent
     *
     * @return void
     */
    public function testStoreWhenPermissionToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Create data
        $data = [
            "name"            => 'Test Lead Source',
            "category_id"     => 17,
            "organization_id" => 2,
            "status"          => 'active'
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-sources?token=' . $token, $data);

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
        $this->assertEquals("Permission to the department is absent.", $message);
    }

    /**
     * Check Store Invalid Data
     *
     * @return void
     */
    public function testStoreInvalidData()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"            => [],
            "category_id"     => [],
            "organization_id" => [],
            "status"          => []
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-sources?token=' . $token, $data);

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
        $this->assertEquals(4, count($error['errors']));
    }

    /**
     * Check Update For Developer
     *
     * @return void
     */
    public function testUpdateForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'name' => 'Name edited'
        ];

        $response = $this->put('api/lead-sources/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadSources.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Name edited", $data['name']);
        $this->assertEquals(18, $data['category_id']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('active', $data['status']);
    }

    /**
     * Check Update If Entity Id Is wrong
     *
     * @return void
     */
    public function testUpdateIfEntityIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Update data
        $data = [
            'name' => 'Name edited'
        ];

        $response = $this->put('api/lead-sources/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LeadSources.update. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Update Invalid Data
     *
     * @return void
     */
    public function testUpdateInvalidData()
    {
        $token = $this->loginDeveloper();

        // Update data
        $data = [
            "name"            => [],
            "category_id"     => [],
            "organization_id" => [],
            "status"          => []
        ];

        $response = $this->put('api/lead-sources/1?token=' . $token, $data);
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
        $this->assertEquals(4, count($error['errors']));
    }

    /**
     * Check Update With Permission is absent by the Role
     *
     * @return void
     */
    public function testUpdateWithPermissionIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Update data
        $data = [
            'name' => 'Name edited'
        ];

        $response = $this->put('api/lead-sources/1?token=' . $token, $data);

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
     * Check Update With Permission to the department is absent
     *
     * @return void
     */
    public function testUpdateWithPermissionToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Update data
        $data = [
            'name' => 'Name edited'
        ];

        $response = $this->put('api/lead-sources/1?token=' . $token, $data);

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
     * Check Soft Delete For Developer
     *
     * @return void
     */
    public function testSoftDeleteForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-sources/5?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "LeadSources.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $leadSources = DB::table('ls_categories')->where('id', 5)->first();
        $this->assertEquals(null, $leadSources->deleted_at);
    }


    /**
     * Check Soft Delete If The Id Is Wrong
     *
     * @return void
     */
    public function testSoftDeleteIfTheIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-sources/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "LeadSources.softDestroy. Incorrect ID in URL.",
            $message
        );
        $this->assertEquals(null, $data);
    }

    /**
     * Check Soft Delete If Permission is absent by the Role
     *
     * @return void
     */
    public function testSoftDeleteIfPermissionIsAbsentByeTheRole()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->delete('api/lead-sources/5?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals(
            "Permission is absent by the role.",
            $message
        );
    }

    /**
     * Check Soft Delete If Permission to the department is absent
     *
     * @return void
     */
    public function testSoftDeleteIfPermissionToTheDepartmentIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->delete('api/lead-sources/5?token=' . $token);

        $response->assertStatus(454);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals(
            "Permission to department is absent.",
            $message
        );
    }


    /**
     * Check Restore
     *
     * @return void
     */
    public function testRestore()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/lead-sources/6/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadSources.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $leadSource = LeadSource::where('id', 6)->first();
        $this->assertEquals(null, $leadSource->deleted_at);
    }

    /**
     * Check Restore If The Entity ID Is Wrong
     *
     * @return void
     */
    public function testRestoreIfTheEntityIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/lead-sources/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LeadSources.restore. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Restore If The Access Is Absent By the Role
     *
     * @return void
     */
    public function testRestoreIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->put('api/lead-sources/6/restore?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Check Delete Permanently
     *
     * @return void
     */
    public function testDeletePermanently()
    {
        // Preparation
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-sources/6/permanently?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadSources.destroyPermanently. Result is successful.", $message);

        $leadSource = DB::table('lead_sources')->where('id', 6)->first();
        $this->assertEquals(null, $leadSource);
    }

    /**
     * Check Delete Permanently If The Entity ID Is Wrong
     *
     * @return void
     */
    public function testDeletePermanentlyIfIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete(
            'api/lead-sources/2222/permanently?token=' . $token
        );

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "LeadSources.destroyPermanently. Incorrect ID in URL.",
            $message
        );
        $this->assertEquals(null, $data);
    }

    /**
     * Check Delete Permanently If The Access Is Absent By the Role
     *
     * @return void
     */
    public function testDeletePermanentlyIfTheAccessIsAbsentByTheRole()
    {
        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/lead-sources/6/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
