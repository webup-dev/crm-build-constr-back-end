<?php

namespace App;

use App\Models\LeadStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Tests to test LeadStatusesController
 * php version 7
 *
 * @category Tests
 * @package  LeadStatusesController
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class LeadStatusesControllerTest extends WnyTestCase
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
        $leadStatuses = DB::table('lead_statuses')->get();
        $this->assertEquals(8, $leadStatuses->count());

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
        $response = $this->get('api/lead-statuses?token=' . $token);

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
                            "organization_id",
                            "parent_id",
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
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(8, count($data));
        $this->assertEquals('Unqualified', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals(null, $data[0]['parent_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("LeadStatuses.index. Result is successful.", $message);
    }

    /**
     * Check Index For WNY admin
     *
     * @return void
     */
    public function testIndexForWNYAdmin()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/lead-statuses?token=' . $token);

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
                            "organization_id",
                            "parent_id",
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
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(8, count($data));
        $this->assertEquals('Unqualified', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals(null, $data[0]['parent_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("LeadStatuses.index. Result is successful.", $message);
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
        $response = $this->get('api/lead-statuses?token=' . $token);

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
        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/lead-statuses?token=' . $token);

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
        $response = $this->delete('api/lead-statuses/8?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-statuses/7?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/lead-statuses/soft-deleted?token=' . $token);

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
                            "organization_id",
                            "parent_id",
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
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(2, count($data));
        $this->assertEquals('Duplicate', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals(3, $data[0]['parent_id']);
        $this->assertEquals(null, $data[0]['other_reason']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[0]['organization']['name']);
        $this->assertEquals("LeadStatuses.indexSoftDeleted. Result is successful.", $message);
    }

    /**
     * Check Empty SoftDeleted Index For Developer
     *
     * @return void
     */
    public function testIndexSoftDeletedEmpty()
    {
        $token    = $this->loginDeveloper();

        // Request
        $response = $this->get('api/lead-statuses/soft-deleted?token=' . $token);

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
        $response = $this->get('api/lead-statuses/soft-deleted?token=' . $token);

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
     * Check getListOfOrganizations ForDeveloper
     *
     * @return void
     */
    public function testGetListOfOrganizationsForDeveloper()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/lead-statuses/organizations?token=' . $token);

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
        $data         = $responseJSON['data'];     // array
        $message      = $responseJSON['message'];  // array
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];     // array

        $this->assertEquals(2, count($data));
        $this->assertEquals(2, $data[0]['id']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['name']
        );
        $this->assertEquals(1, $data[0]['level']);
        $this->assertEquals(1, $data[0]['order']);
        $this->assertEquals(1, $data[0]['parent_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            "Trait.getListOfOrganizations. Result is successful.",
            $message
        );
        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);

    }
//
//    /**
//     * Check getListOfOrganizations For Spring Superadmin
//     *
//     * @return void
//     */
//    public function testGetListOfOrganizationsForSpringSuperadmin()
//    {
//        $token = $this->loginOrganizationSpringSuperadmin();
//
//        $response = $this->get('api/lead-statuses/organizations?token=' . $token);
//
//        // Check response status
//        $response->assertStatus(200);
//
//        // Check response structure
//        $response->assertJsonStructure(
//            [
//                'success',
//                'data' =>
//                    [
//                        [
//                            'id',
//                            'level',
//                            'order',
//                            'name',
//                            'parent_id',
//                            'deleted_at',
//                            'created_at',
//                            'updated_at'
//                        ]
//                    ],
//                'message'
//            ]
//        );
//        $responseJSON = json_decode($response->getContent(), true);
//        $data         = $responseJSON['data'];     // array
//        $message      = $responseJSON['message'];  // array
//        $success      = $responseJSON['success'];  // array
//        $code         = $responseJSON['code'];     // array
//
//        $this->assertEquals(1, count($data));
//        $this->assertEquals(9, $data[0]['id']);
//        $this->assertEquals(
//            'Spring Sheet Metal & Roofing Co.',
//            $data[0]['name']
//        );
//        $this->assertEquals(1, $data[0]['level']);
//        $this->assertEquals(2, $data[0]['order']);
//        $this->assertEquals(1, $data[0]['parent_id']);
//        $this->assertEquals(null, $data[0]['deleted_at']);
//        $this->assertEquals(
//            "Trait.getListOfOrganizations. Result is successful.",
//            $message
//        );
//        $this->assertEquals(true, $success);
//        $this->assertEquals(200, $code);
//
//    }
//
//    /**
//     * Check getListOfOrganizations If There Is Not Permission Due To Role
//     *
//     * @return void
//     */
//    public function testGetListOfOrganizationsIfThereIsNotPermissionDueToRole()
//    {
//        $token = $this->loginOrganizationWNYGeneralManager();
//
//        $response = $this->get('api/lead-statuses/organizations?token=' . $token);
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
//
//        //Check response data
//        $responseJSON = json_decode($response->getContent(), true);
//        $success      = $responseJSON['success'];  // array
//        $message      = $responseJSON['message'];  // array
//
//        $this->assertEquals(false, $success);
//        $this->assertEquals("Permission is absent by the role.", $message);
//    }
//
    /**
     * Check Show For Developer
     *
     * @return void
     */
    public function testShowForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/lead-statuses/8?token=' . $token);

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
                        "organization_id",
                        "parent_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
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
        $this->assertEquals("LeadStatuses.show. Result is successful.", $message);
        $this->assertEquals(8, $data['id']);
        $this->assertEquals('Other', $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals(3, $data['parent_id']);
        $this->assertEquals(null, $data['other_reason']);
        $this->assertEquals(null, $data['deleted_at']);
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

        $response = $this->get('api/lead-statuses/44444?token=' . $token, []);

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
            "LeadStatuses.show. Incorrect ID in URL.",
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

        $response = $this->get('api/lead-statuses/1?token=' . $token);

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

        $response = $this->get('api/lead-statuses/1?token=' . $token);

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
     * Check Store For Developer
     *
     * @return void
     */
    public function testStoreForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"            => 'Test Lead Status',
            "organization_id" => 2,
            "parent_id"       => 3,
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-statuses?token=' . $token, $data);

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
        $this->assertEquals("LeadStatuses.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $leadStatus = DB::table('lead_statuses')
            ->where('id', '=', 9)
            ->first();
        $this->assertEquals('Test Lead Status', $leadStatus->name);
        $this->assertEquals(2, $leadStatus->organization_id);
        $this->assertEquals(3, $leadStatus->parent_id);
        $this->assertEquals(null, $leadStatus->deleted_at);
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
            "name"            => 'Test Lead Status',
            "organization_id" => 2
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-statuses?token=' . $token, $data);

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
            "organization_id" => 2
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-statuses?token=' . $token, $data);

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
            "organization_id" => [],
            "parent_id"       => [],
            "other_reason"    => [],
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-statuses?token=' . $token, $data);

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

        $response = $this->put('api/lead-statuses/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadStatuses.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Name edited", $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals(null, $data['parent_id']);
        $this->assertEquals(null, $data['other_reason']);
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

        $response = $this->put('api/lead-statuses/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LeadStatuses.update. Incorrect ID in URL.", $message);
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
            "organization_id" => [],
            "parent_id"       => [],
            "other_reason"    => [],
        ];

        $response = $this->put('api/lead-statuses/1?token=' . $token, $data);
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

        $response = $this->put('api/lead-statuses/1?token=' . $token, $data);

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

        $response = $this->put('api/lead-statuses/1?token=' . $token, $data);

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
        $response = $this->delete('api/lead-statuses/8?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "LeadStatuses.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $leadStatus = DB::table('lead_statuses')->where('id', 8)->first();
        $this->assertNotEquals(null, $leadStatus->deleted_at);
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
        $response = $this->delete('api/lead-statuses/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "LeadStatuses.softDestroy. Incorrect ID in URL.",
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
        $response = $this->delete('api/lead-statuses/5?token=' . $token);

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
        $response = $this->delete('api/lead-statuses/5?token=' . $token);

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

        // Preparation
        $response = $this->delete('api/lead-statuses/8?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/lead-statuses/8/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadStatuses.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $leadStatus = LeadStatus::where('id', 8)->first();
        $this->assertEquals(null, $leadStatus['deleted_at']);
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
        $response = $this->put('api/lead-statuses/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LeadStatuses.restore. Incorrect ID in URL.", $message);
        $this->assertEquals(null, $data);
    }

    /**
     * Check Restore If The Access Is Absent By the Role
     *
     * @return void
     */
    public function testRestoreIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginDeveloper();
        // Preparation
        $response = $this->delete('api/lead-statuses/8?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();
        // Request
        $response = $this->put('api/lead-statuses/8/restore?token=' . $token);

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
        $response = $this->delete('api/lead-statuses/8?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/lead-statuses/8/permanently?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LeadStatuses.destroyPermanently. Result is successful.", $message);

        $leadStatus = DB::table('lead_statuses')->where('id', 8)->first();
        $this->assertEquals(null, $leadStatus);
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
            'api/lead-statuses/2222/permanently?token=' . $token
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
            "LeadStatuses.destroyPermanently. Incorrect ID in URL.",
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
        // Preparation
        $token = $this->loginDeveloper();
        $response = $this->delete(
            'api/lead-statuses/8/permanently?token=' . $token
        );
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/lead-statuses/8/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
