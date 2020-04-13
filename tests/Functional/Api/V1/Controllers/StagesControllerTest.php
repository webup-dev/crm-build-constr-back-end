<?php

namespace App;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Tests to test StagesController
 * php version 7
 *
 * @category Tests
 * @package  StagesController
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class StagesControllerTest extends WnyTestCase
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
        $stages = DB::table('stages')->get();
        $this->assertEquals(5, $stages->count());

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
        $response = $this->get('api/stages?token=' . $token);

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
                            "workflow_type",
                            "description",
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
        $this->assertEquals(5, count($data));
        $this->assertEquals('Documenting', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("Stages.index. Result is successful.", $message);
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
        $response = $this->get('api/stages?token=' . $token);

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
                            "workflow_type",
                            "description",
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
        $this->assertEquals(5, count($data));
        $this->assertEquals('Documenting', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals("Stages.index. Result is successful.", $message);
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
        $response = $this->get('api/stages?token=' . $token);

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
        $response = $this->get('api/stages?token=' . $token);

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
        $response = $this->delete('api/stages/5?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/stages/4?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/stages/soft-deleted?token=' . $token);

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
                            "workflow_type",
                            "description",
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
        $this->assertEquals('Under review', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']['name']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals('Western New York Exteriors, LLC.', $data[0]['organization']['name']);
        $this->assertEquals("Stages.indexSoftDeleted. Result is successful.", $message);
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
        $response = $this->get('api/stages/soft-deleted?token=' . $token);

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
        $response = $this->get('api/stages/soft-deleted?token=' . $token);

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

        $response = $this->get('api/stages/organizations?token=' . $token);

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

    /**
     * Check Show For Developer
     *
     * @return void
     */
    public function testShowForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/stages/5?token=' . $token);

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
                        "workflow_type",
                        "description",
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
        $this->assertEquals("Stages.show. Result is successful.", $message);
        $this->assertEquals(5, $data['id']);
        $this->assertEquals('Determination', $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('request', $data['workflow_type']);
        $this->assertEquals('', $data['description']);
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

        $response = $this->get('api/stages/44444?token=' . $token, []);

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
            "Stages.show. Incorrect ID in URL.",
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

        $response = $this->get('api/stages/1?token=' . $token);

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

        $response = $this->get('api/stages/1?token=' . $token);

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
            "name"            => 'Test Stages',
            "organization_id" => 2,
            "workflow_type"   => 'request',
        ];

        // Store the Lead Source
        $response = $this->post('api/stages?token=' . $token, $data);

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
        $this->assertEquals("Stages.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $stage = DB::table('stages')
            ->where('id', '=', 6)
            ->first();
        $this->assertEquals('Test Stages', $stage->name);
        $this->assertEquals(2, $stage->organization_id);
        $this->assertEquals('request', $stage->workflow_type);
        $this->assertEquals('', $stage->description);
        $this->assertEquals(null, $stage->deleted_at);
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
            "name"            => 'Test Stages',
            "organization_id" => 2,
            "workflow_type"   => 'request',
        ];

        // Store the Lead Source
        $response = $this->post('api/stages?token=' . $token, $data);

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
            "name"            => 'Test Stage',
            "organization_id" => 2,
            "workflow_type"   => 'request',
        ];

        // Store the Lead Source
        $response = $this->post('api/stages?token=' . $token, $data);

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
            "workflow_type"   => [],
            "description"     => [],
        ];

        // Store the Lead Source
        $response = $this->post('api/stages?token=' . $token, $data);

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

        $response = $this->put('api/stages/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Stages.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Name edited", $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('request', $data['workflow_type']);
        $this->assertEquals('', $data['description']);
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

        $response = $this->put('api/stages/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Stages.update. Incorrect ID in URL.", $message);
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
            "workflow_type"   => [],
            "description"     => [],
        ];

        $response = $this->put('api/stages/1?token=' . $token, $data);
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

        $response = $this->put('api/stages/1?token=' . $token, $data);

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

        $response = $this->put('api/stages/1?token=' . $token, $data);

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
        $response = $this->delete('api/stages/5?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "Stages.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $stages = DB::table('stages')->where('id', 5)->first();
        $this->assertNotEquals(null, $stages->deleted_at);
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
        $response = $this->delete('api/stages/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "Stages.softDestroy. Incorrect ID in URL.",
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
        $response = $this->delete('api/stages/5?token=' . $token);

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
        $response = $this->delete('api/stages/5?token=' . $token);

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
        $response = $this->delete('api/stages/4?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/stages/4/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Stages.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $stage = Stage::where('id', 4)->first();
        $this->assertEquals(null, $stage['deleted_at']);
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
        $response = $this->put('api/stages/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Stages.restore. Incorrect ID in URL.", $message);
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
        $response = $this->delete('api/stages/4?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();
        // Request
        $response = $this->put('api/stages/8/restore?token=' . $token);

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
        $response = $this->delete('api/stages/4?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/stages/4/permanently?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Stages.destroyPermanently. Result is successful.", $message);

        $stage = DB::table('stages')->where('id', 4)->first();
        $this->assertEquals(null, $stage);
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
            'api/stages/2222/permanently?token=' . $token
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
            "Stages.destroyPermanently. Incorrect ID in URL.",
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
            'api/stages/5/permanently?token=' . $token
        );
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/stages/5/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
