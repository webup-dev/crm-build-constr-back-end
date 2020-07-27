<?php

namespace App;

use App\Models\Workflow;
use App\Models\WorkflowStage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Tests to test WorkflowsController
 * php version 7
 *
 * @category Tests
 * @package  WorkflowsController
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class WorkflowsControllerTest extends WnyTestCase
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
        $workflows = DB::table('workflows')->get();
        $this->assertEquals(2, $workflows->count());

        $workflow_stages = DB::table('workflow_stages')->get();
        $this->assertEquals(7, $workflow_stages->count());

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
        $response = $this->get('api/workflows?token=' . $token);

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
                            "organization",
                            "stages"
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
        $this->assertCount(2, $data);
        $this->assertEquals('Simple', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals(2, count($data[0]['stages']));
        $this->assertEquals(1, $data[0]['stages'][0]['id']);
        $this->assertEquals('Documenting', $data[0]['stages'][0]['name']);
        $this->assertEquals(2, $data[0]['stages'][0]['organization_id']);
        $this->assertEquals('Request', $data[0]['stages'][0]['workflow_type']);
        $this->assertEquals('', $data[0]['stages'][0]['description']);
        $this->assertEquals(1, $data[0]['stages'][0]['pivot']['order']);
        $this->assertEquals("Workflows.index. Result is successful.", $message);
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
        $response = $this->get('api/workflows?token=' . $token);

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
                            "organization",
                            "stages"
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
        $this->assertEquals('Simple', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals(2, count($data[0]['stages']));
        $this->assertEquals(1, $data[0]['stages'][0]['id']);
        $this->assertEquals('Documenting', $data[0]['stages'][0]['name']);
        $this->assertEquals(2, $data[0]['stages'][0]['organization_id']);
        $this->assertEquals('Request', $data[0]['stages'][0]['workflow_type']);
        $this->assertEquals('', $data[0]['stages'][0]['description']);
        $this->assertEquals(1, $data[0]['stages'][0]['pivot']['order']);
        $this->assertEquals("Workflows.index. Result is successful.", $message);
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
        $response = $this->get('api/workflows?token=' . $token);

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
        $response = $this->get('api/workflows?token=' . $token);

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
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/workflows/soft-deleted?token=' . $token);

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
                            "organization",
                            "stages"
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
        $this->assertCount(1, $data);
        $this->assertEquals('Simple', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Request', $data[0]['workflow_type']);
        $this->assertEquals('', $data[0]['description']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals(
            "Workflows.indexSoftDeleted. Result is successful.",
            $message
        );
        $this->assertCount(2, $data[0]['stages']);
        $this->assertEquals(1, $data[0]['stages'][0]['pivot']['order']);
        $this->assertEquals(1, $data[0]['stages'][0]['pivot']['stage_id']);
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
        $response = $this->get('api/workflows/soft-deleted?token=' . $token);

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
        $response = $this->get('api/workflows/soft-deleted?token=' . $token);

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
        $response = $this->get('api/workflows/1?token=' . $token);

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
                        "organization",
                        "stages"
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
        $this->assertEquals("Workflows.show. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals('Simple', $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('Request', $data['workflow_type']);
        $this->assertEquals('', $data['description']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data['organization']['name']
        );
        $this->assertEquals('Documenting', $data['stages'][0]['name']);
        $this->assertEquals(1, $data['stages'][0]['pivot']['order']);
        $this->assertEquals(1, $data['stages'][0]['pivot']['stage_id']);
    }

    /**
     * Check Show If Entity ID is Incorrect
     *
     * @return void
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/workflows/44444?token=' . $token, []);

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
            "Workflows.show. Incorrect ID in URL.",
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

        $response = $this->get('api/workflows/1?token=' . $token);

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

        $response = $this->get('api/workflows/1?token=' . $token);

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
            "name"            => 'Test Workflow',
            "organization_id" => 2,
            "workflow_type"   => 'Request',
            "stages"          => [
                ['id' => 1, 'order' => 1],
                ['id' => 2, 'order' => 2],
                ['id' => 3, 'order' => 3],
                ['id' => 5, 'order' => 4],
            ]
        ];

        // Store the Workflow
        $response = $this->post('api/workflows?token=' . $token, $data);

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
        $this->assertEquals("Workflows.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $workflow = DB::table('workflows')
            ->where('id', '=', 3)
            ->first();
        $this->assertEquals('Test Workflow', $workflow->name);
        $this->assertEquals(2, $workflow->organization_id);
        $this->assertEquals('Request', $workflow->workflow_type);
        $this->assertEquals('', $workflow->description);
        $this->assertEquals(null, $workflow->deleted_at);

        $workflowStages = WorkflowStage::whereWorkflowId(3)->get();
        $this->assertCount(4, $workflowStages);
        $this->assertEquals(8, $workflowStages[0]['id']);
        $this->assertEquals(3, $workflowStages[0]['workflow_id']);
        $this->assertEquals(1, $workflowStages[0]['stage_id']);
        $this->assertEquals(1, $workflowStages[0]['order']);
    }

    /**
     * Check Store For Developer If Stages Are Empty
     *
     * @return void
     */
    public function testStoreForDeveloperIfStagesAreEmpty()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"            => 'Test Workflow',
            "organization_id" => 2,
            "workflow_type"   => 'Request',
            "stages"          => []
        ];

        // Store the Workflow
        $response = $this->post('api/workflows?token=' . $token, $data);

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
        $this->assertEquals("Workflows.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $workflow = DB::table('workflows')
            ->where('id', '=', 3)
            ->first();
        $this->assertEquals('Test Workflow', $workflow->name);
        $this->assertEquals(2, $workflow->organization_id);
        $this->assertEquals('Request', $workflow->workflow_type);
        $this->assertEquals('', $workflow->description);
        $this->assertEquals(null, $workflow->deleted_at);

        $workflowStages = WorkflowStage::whereWorkflowId(3)->get();
        $this->assertCount(0, $workflowStages);
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
            "name"            => 'Test Workflow',
            "organization_id" => 2,
            "workflow_type"   => 'Request',
            "stages"          => [
                ['id' => 1, 'order' => 1],
                ['id' => 2, 'order' => 2],
                ['id' => 3, 'order' => 3],
                ['id' => 5, 'order' => 4],
            ]
        ];

        // Store the Workflow
        $response = $this->post('api/workflows?token=' . $token, $data);

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
            "name"            => 'Test Workflow',
            "organization_id" => 2,
            "workflow_type"   => 'Request',
            "stages"          => [
                ['id' => 1, 'order' => 1],
                ['id' => 2, 'order' => 2],
                ['id' => 3, 'order' => 3],
                ['id' => 5, 'order' => 4],
            ]
        ];

        // Store the Workflow
        $response = $this->post('api/workflows?token=' . $token, $data);

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

        // Store the Workflow
        $response = $this->post('api/workflows?token=' . $token, $data);

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
            'name'   => 'Name edited',
            "stages" => [
                ['id' => 1, 'order' => 1],
                ['id' => 2, 'order' => 2],
                ['id' => 5, 'order' => 3],
            ]
        ];

        $response = $this->put('api/workflows/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Workflows.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Name edited", $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('Request', $data['workflow_type']);
        $this->assertEquals('', $data['description']);

        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data['organization']['name']
        );

        $this->assertCount(3, $data['stages']);
        $this->assertEquals(1, $data['stages'][0]['id']);
        $this->assertEquals('Documenting', $data['stages'][0]['name']);
        $this->assertEquals('Request', $data['stages'][0]['workflow_type']);
        $this->assertEquals(null, $data['stages'][0]['description']);
        $this->assertEquals(null, $data['stages'][0]['deleted_at']);

        $this->assertEquals(1, $data['stages'][0]['pivot']['workflow_id']);
        $this->assertEquals(1, $data['stages'][0]['pivot']['stage_id']);
        $this->assertEquals(1, $data['stages'][0]['pivot']['order']);
    }

    /**
     * Check Update For Developer If Stages Are Empty
     *
     * @return void
     */
    public function testUpdateForDeveloperIfStagesAreEmpty()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'name'   => 'Name edited',
            "stages" => []
        ];

        $response = $this->put('api/workflows/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Workflows.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Name edited", $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('Request', $data['workflow_type']);
        $this->assertEquals('', $data['description']);

        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data['organization']['name']
        );

        $this->assertCount(0, $data['stages']);
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
            'name'   => 'Name edited',
            "stages" => [
                ['id' => 1, 'order' => 1],
                ['id' => 2, 'order' => 2],
                ['id' => 5, 'order' => 3],
            ]
        ];

        $response = $this->put('api/workflows/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Workflows.update. Incorrect ID in URL.", $message);
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

        $response = $this->put('api/workflows/1?token=' . $token, $data);
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
        $this->assertCount(4, $error['errors']);
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

        $response = $this->put('api/workflows/1?token=' . $token, $data);

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

        $response = $this->put('api/workflows/1?token=' . $token, $data);

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
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "Workflows.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $workflow = DB::table('workflows')->where('id', 1)->first();
        $this->assertNotEquals(null, $workflow->deleted_at);

        $workflowStages = WorkflowStage::withTrashed()
            ->where('workflow_id', 1)->get();
        $this->assertCount(2, $workflowStages);
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
        $response = $this->delete('api/workflows/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "Workflows.softDestroy. Incorrect ID in URL.",
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
        $response = $this->delete('api/workflows/5?token=' . $token);

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
        $response = $this->delete('api/workflows/5?token=' . $token);

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
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/workflows/1/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Workflows.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $workflow = Workflow::with(['organization', 'stages'])->where('id', 1)->first();
        $this->assertCount(2, $workflow['stages']);
        $this->assertEquals(null, $workflow['deleted_at']);
        $this->assertEquals(1, $workflow['stages'][0]['pivot']['stage_id']);
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
        $response = $this->put('api/workflows/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Workflows.restore. Incorrect ID in URL.", $message);
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
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();
        // Request
        $response = $this->put('api/workflows/1/restore?token=' . $token);

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
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/workflows/1/permanently?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Workflows.destroyPermanently. Result is successful.", $message);

        $workflow = DB::table('workflows')->where('id', 1)->first();
        $this->assertEquals(null, $workflow);

        $workflowStages = WorkflowStage::whereWorkflowId(1)->get();
        $this->assertCount(0, $workflowStages);

        $workflowStages = WorkflowStage::withTrashed()
            ->whereWorkflowId(1)->get();
        $this->assertCount(0, $workflowStages);
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
            'api/workflows/2222/permanently?token=' . $token
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
            "Workflows.destroyPermanently. Incorrect ID in URL.",
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
            'api/workflows/1/permanently?token=' . $token
        );
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/workflows/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
