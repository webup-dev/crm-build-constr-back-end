<?php

namespace App;

use App\Models\Lead;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Tests to test LeadsController
 * php version 7
 *
 * @category Tests
 * @package  LeadsController
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class LeadsControllerTest extends WnyTestCase
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
        $requesters = DB::table('leads')->get();
        $this->assertEquals(2, $requesters->count());

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
        $response = $this->get('api/leads?token=' . $token);

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
                            "due_date",
                            "anticipated_project_date",
                            "lead_type_id",
                            "lead_status_id",
                            "declined_reason_other",
                            "lead_source_id",
                            "stage_id",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "requester_id",
                            "note",
                            "lead_owner_id",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "lead_type",
                            "lead_status",
                            "lead_source",
                            "stage",
                            "requester",
                            "owner",
                            "creator",
                            "editor",
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
        $this->assertEquals('Test 1', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('2020-04-30 13:12:54', $data[0]['due_date']);
        $this->assertEquals('2020-05-30 13:12:54', $data[0]['anticipated_project_date']);
        $this->assertEquals(1, $data[0]['lead_type_id']);
        $this->assertEquals(1, $data[0]['lead_status_id']);
        $this->assertEquals(null, $data[0]['declined_reason_other']);
        $this->assertEquals(1, $data[0]['lead_source_id']);
        $this->assertEquals(1, $data[0]['stage_id']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals(1, $data[0]['requester_id']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['lead_owner_id']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('Steep Slope Roofing', $data[0]['lead_type']['name']);
        $this->assertEquals('Unqualified', $data[0]['lead_status']['name']);
        $this->assertEquals('Website - CertainTeed', $data[0]['lead_source']['name']);
        $this->assertEquals('Documenting', $data[0]['stage']['name']);
        $this->assertEquals('Evelyn', $data[0]['requester']['first_name']);
        $this->assertEquals('WNY General-Manager', $data[0]['owner']['name']);
        $this->assertEquals('WNY General-Manager', $data[0]['creator']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['editor']['name']);
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
        $response = $this->get('api/leads?token=' . $token);

        // Check response status
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
                            "due_date",
                            "anticipated_project_date",
                            "lead_type_id",
                            "lead_status_id",
                            "declined_reason_other",
                            "lead_source_id",
                            "stage_id",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "requester_id",
                            "note",
                            "lead_owner_id",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "lead_type",
                            "lead_status",
                            "lead_source",
                            "stage",
                            "requester",
                            "owner",
                            "creator",
                            "editor",
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
        $this->assertEquals('Test 1', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('2020-04-30 13:12:54', $data[0]['due_date']);
        $this->assertEquals('2020-05-30 13:12:54', $data[0]['anticipated_project_date']);
        $this->assertEquals(1, $data[0]['lead_type_id']);
        $this->assertEquals(1, $data[0]['lead_status_id']);
        $this->assertEquals(null, $data[0]['declined_reason_other']);
        $this->assertEquals(1, $data[0]['lead_source_id']);
        $this->assertEquals(1, $data[0]['stage_id']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals(1, $data[0]['requester_id']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['lead_owner_id']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('Steep Slope Roofing', $data[0]['lead_type']['name']);
        $this->assertEquals('Unqualified', $data[0]['lead_status']['name']);
        $this->assertEquals('Website - CertainTeed', $data[0]['lead_source']['name']);
        $this->assertEquals('Documenting', $data[0]['stage']['name']);
        $this->assertEquals('Evelyn', $data[0]['requester']['first_name']);
        $this->assertEquals('WNY General-Manager', $data[0]['owner']['name']);
        $this->assertEquals('WNY General-Manager', $data[0]['creator']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['editor']['name']);
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
        $response = $this->get('api/leads?token=' . $token);

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
        $token = $this->loginCustomerFWny();

        // Request
        $response = $this->get('api/leads?token=' . $token);

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
        $response = $this->delete('api/leads/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/leads/soft-deleted?token=' . $token);

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
                            "due_date",
                            "anticipated_project_date",
                            "lead_type_id",
                            "lead_status_id",
                            "declined_reason_other",
                            "lead_source_id",
                            "stage_id",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "requester_id",
                            "note",
                            "lead_owner_id",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "lead_type",
                            "lead_status",
                            "lead_source",
                            "stage",
                            "requester",
                            "owner",
                            "creator",
                            "editor"
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
        $this->assertEquals('Test 1', $data[0]['name']);
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('2020-04-30 13:12:54', $data[0]['due_date']);
        $this->assertEquals('2020-05-30 13:12:54', $data[0]['anticipated_project_date']);
        $this->assertEquals(1, $data[0]['lead_type_id']);
        $this->assertEquals(1, $data[0]['lead_status_id']);
        $this->assertEquals(null, $data[0]['declined_reason_other']);
        $this->assertEquals(1, $data[0]['lead_source_id']);
        $this->assertEquals(1, $data[0]['stage_id']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals(1, $data[0]['requester_id']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['lead_owner_id']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('Steep Slope Roofing', $data[0]['lead_type']['name']);
        $this->assertEquals('Unqualified', $data[0]['lead_status']['name']);
        $this->assertEquals('Website - CertainTeed', $data[0]['lead_source']['name']);
        $this->assertEquals('Documenting', $data[0]['stage']['name']);
        $this->assertEquals('Evelyn', $data[0]['requester']['first_name']);
        $this->assertEquals('WNY General-Manager', $data[0]['owner']['name']);
        $this->assertEquals('WNY General-Manager', $data[0]['creator']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['editor']['name']);
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
        $response = $this->get('api/leads/soft-deleted?token=' . $token);

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
        $response = $this->get('api/leads/soft-deleted?token=' . $token);

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
        $response = $this->get('api/leads/1?token=' . $token);

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
                        "due_date",
                        "anticipated_project_date",
                        "lead_type_id",
                        "lead_status_id",
                        "declined_reason_other",
                        "lead_source_id",
                        "stage_id",
                        "line_1",
                        "line_2",
                        "city",
                        "state",
                        "zip",
                        "requester_id",
                        "note",
                        "lead_owner_id",
                        "created_by_id",
                        "updated_by_id",
                        "deleted_at",
                        "created_at",
                        "updated_at"
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
        $this->assertEquals("Leads.show. Result is successful.", $message);
        $this->assertEquals('Test 1', $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('2020-04-30 13:12:54', $data['due_date']);
        $this->assertEquals('2020-05-30 13:12:54', $data['anticipated_project_date']);
        $this->assertEquals(1, $data['lead_type_id']);
        $this->assertEquals(1, $data['lead_status_id']);
        $this->assertEquals(null, $data['declined_reason_other']);
        $this->assertEquals(1, $data['lead_source_id']);
        $this->assertEquals(1, $data['stage_id']);
        $this->assertEquals('9278 new road', $data['line_1']);
        $this->assertEquals('app 3', $data['line_2']);
        $this->assertEquals('Kilcoole', $data['city']);
        $this->assertEquals('OH', $data['state']);
        $this->assertEquals('93027', $data['zip']);
        $this->assertEquals(1, $data['requester_id']);
        $this->assertEquals('Note #1.', $data['note']);
        $this->assertEquals(6, $data['lead_owner_id']);
        $this->assertEquals(6, $data['created_by_id']);
        $this->assertEquals(10, $data['updated_by_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data['organization']['name']
        );
        $this->assertEquals('Steep Slope Roofing', $data['lead_type']['name']);
        $this->assertEquals('Unqualified', $data['lead_status']['name']);
        $this->assertEquals('Website - CertainTeed', $data['lead_source']['name']);
        $this->assertEquals('Documenting', $data['stage']['name']);
        $this->assertEquals('Evelyn', $data['requester']['first_name']);
        $this->assertEquals('WNY General-Manager', $data['owner']['name']);
        $this->assertEquals('WNY General-Manager', $data['creator']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data['editor']['name']);
    }

    /**
     * Check Show If Entity ID is Incorrect
     *
     * @return void
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/leads/44444?token=' . $token, []);

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
            "Leads.show. Incorrect ID in URL.",
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

        $response = $this->get('api/leads/1?token=' . $token);

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

        $response = $this->get('api/leads/1?token=' . $token);

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
            "name"                     => 'Test name',
            "organization_id"          => 2,
            "due_date"                 => '2000-12-10 13:13:13',
            "anticipated_project_date" => '2020-12-10 13:13:13',
            "lead_type_id"             => 1,
            "lead_status_id"           => 1,
            "declined_reason_other"    => '',
            "lead_source_id"           => 1,
            "stage_id"                 => 1,
            "line_1"                   => 'Line 1',
            "line_2"                   => 'Line 2',
            "city"                     => 'City',
            "state"                    => 'OH',
            "zip"                      => '65412',
            "requester_id"             => 1,
            "note"                     => 'Note Test.',
            "lead_owner_id"            => 6,
            "created_by_id"            => 6,
            "updated_by_id"            => null,
            "deleted_at"               => null
        ];

        // Store the Workflow
        $response = $this->post('api/leads?token=' . $token, $data);

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
        $this->assertEquals("Leads.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $requester = DB::table('leads')
            ->where('id', '=', 3)
            ->first();
        $this->assertEquals('Test name', $requester->name);
        $this->assertEquals(2, $requester->organization_id);
        $this->assertEquals('2000-12-10 13:13:13', $requester->due_date);
        $this->assertEquals('2020-12-10 13:13:13', $requester->anticipated_project_date);
        $this->assertEquals(1, $requester->lead_type_id);
        $this->assertEquals(1, $requester->lead_status_id);
        $this->assertEquals(null, $requester->declined_reason_other);
        $this->assertEquals(1, $requester->lead_source_id);
        $this->assertEquals(1, $requester->stage_id);
        $this->assertEquals('Line 1', $requester->line_1);
        $this->assertEquals('Line 2', $requester->line_2);
        $this->assertEquals('City', $requester->city);
        $this->assertEquals('OH', $requester->state);
        $this->assertEquals('65412', $requester->zip);
        $this->assertEquals(1, $requester->requester_id);
        $this->assertEquals('Note Test.', $requester->note);
        $this->assertEquals(6, $requester->lead_owner_id);
        $this->assertEquals(6, $requester->created_by_id);
        $this->assertEquals(null, $requester->updated_by_id);
        $this->assertEquals(null, $requester->deleted_at);
    }

    /**
     * Check Store When Permission is absent by the Role
     *
     * @return void
     */
    public function testStoreWhenPermissionIsAbsentByTheRole()
    {
        $token = $this->loginCustomerFWny();

        // Create data
        $data = [
            "name" => 'First',
        ];

        // Store the Workflow
        $response = $this->post('api/leads?token=' . $token, $data);

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
            "name"                     => 'Test name',
            "organization_id"          => 2,
            "due_date"                 => '2000-12-10 13:13:13',
            "anticipated_project_date" => '2020-12-10 13:13:13',
            "lead_type_id"             => 1,
            "lead_status_id"           => 1,
            "declined_reason_other"    => '',
            "lead_source_id"           => 1,
            "stage_id"                 => 1,
            "line_1"                   => 'Line 1',
            "line_2"                   => 'Line 2',
            "city"                     => 'City',
            "state"                    => 'OH',
            "zip"                      => '65412',
            "requester_id"             => 1,
            "note"                     => 'Note Test.',
            "lead_owner_id"            => 6,
            "created_by_id"            => 6,
            "updated_by_id"            => null,
            "deleted_at"               => null
        ];

        // Store the Workflow
        $response = $this->post('api/leads?token=' . $token, $data);

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
            "name"                     => [],
            "organization_id"          => [],
            "due_date"                 => [],
            "anticipated_project_date" => [],
            "lead_type_id"             => [],
            "lead_status_id"           => [],
            "declined_reason_other"    => [],
            "lead_source_id"           => [],
            "stage_id"                 => [],
            "line_1"                   => [],
            "line_2"                   => [],
            "city"                     => [],
            "state"                    => [],
            "zip"                      => [],
            "requester_id"             => [],
            "note"                     => [],
            "lead_owner_id"            => [],
            "created_by_id"            => [],
            "updated_by_id"            => [],
            "deleted_at"               => [],
        ];

        // Store the Workflow
        $response = $this->post('api/leads?token=' . $token, $data);

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
        $this->assertCount(20, $error['errors']);
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
            "name" => 'Name edited',
            "updated_by_id" => 10,
        ];

        $response = $this->put('api/leads/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Leads.update. Result is successful.", $message);

        $requester = DB::table('leads')
            ->where('id', '=', 1)
            ->first();
        $this->assertEquals('Name edited', $data['name']);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('2020-04-30 13:12:54', $data['due_date']);
        $this->assertEquals('2020-05-30 13:12:54', $data['anticipated_project_date']);
        $this->assertEquals(1, $data['lead_type_id']);
        $this->assertEquals(1, $data['lead_status_id']);
        $this->assertEquals(null, $data['declined_reason_other']);
        $this->assertEquals(1, $data['lead_source_id']);
        $this->assertEquals(1, $data['stage_id']);
        $this->assertEquals('9278 new road', $data['line_1']);
        $this->assertEquals('app 3', $data['line_2']);
        $this->assertEquals('Kilcoole', $data['city']);
        $this->assertEquals('OH', $data['state']);
        $this->assertEquals('93027', $data['zip']);
        $this->assertEquals(1, $data['requester_id']);
        $this->assertEquals('Note #1.', $data['note']);
        $this->assertEquals(6, $data['lead_owner_id']);
        $this->assertEquals(6, $data['created_by_id']);
        $this->assertEquals(10, $data['updated_by_id']);
        $this->assertEquals(null, $data['deleted_at']);
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
            "name" => 'First 1',
            "updated_by_id" => 10,
        ];

        $response = $this->put('api/leads/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Leads.update. Incorrect ID in URL.", $message);
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
            "name"                     => [],
            "organization_id"          => [],
            "due_date"                 => [],
            "anticipated_project_date" => [],
            "lead_type_id"             => [],
            "lead_status_id"           => [],
            "declined_reason_other"    => [],
            "lead_source_id"           => [],
            "stage_id"                 => [],
            "line_1"                   => [],
            "line_2"                   => [],
            "city"                     => [],
            "state"                    => [],
            "zip"                      => [],
            "requester_id"             => [],
            "note"                     => [],
            "lead_owner_id"            => [],
            "created_by_id"            => [],
            "updated_by_id"            => [],
            "deleted_at"               => [],
        ];

        $response = $this->put('api/leads/1?token=' . $token, $data);
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
        $this->assertCount(20, $error['errors']);
    }

    /**
     * Check Update With Permission is absent by the Role
     *
     * @return void
     */
    public function testUpdateWithPermissionIsAbsentByTheRole()
    {
        $token = $this->loginCustomerFWny();

        // Update data
        $data = [
            'first_name' => 'Name edited'
        ];

        $response = $this->put('api/leads/1?token=' . $token, $data);

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
            'first_name' => 'Name edited'
        ];

        $response = $this->put('api/leads/1?token=' . $token, $data);

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
        $response = $this->delete('api/leads/1?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "Leads.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $requester = DB::table('leads')->where('id', 1)->first();
        $this->assertNotEquals(null, $requester->deleted_at);
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
        $response = $this->delete('api/leads/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "Leads.softDestroy. Incorrect ID in URL.",
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
        $token = $this->loginCustomerFWny();

        // Request
        $response = $this->delete('api/leads/1?token=' . $token);

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
        $response = $this->delete('api/leads/1?token=' . $token);

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
        $response = $this->delete('api/leads/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/leads/1/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Leads.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $lead = Lead::with(['organization', 'creator'])
            ->where('id', 1)->first();
        $this->assertEquals('WNY General-Manager', $lead['creator']['name']);
        $this->assertEquals(null, $lead['deleted_at']);
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
        $response = $this->put('api/leads/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Leads.restore. Incorrect ID in URL.", $message);
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
        $response = $this->delete('api/leads/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();
        // Request
        $response = $this->put('api/leads/1/restore?token=' . $token);

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
        $response = $this->delete('api/leads/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/leads/1/permanently?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Leads.destroyPermanently. Result is successful.", $message);

        $requester = Lead::whereId(1)->first();
        $this->assertEquals(null, $requester);
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
            'api/leads/2222/permanently?token=' . $token
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
            "Leads.destroyPermanently. Incorrect ID in URL.",
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
            'api/leads/1/permanently?token=' . $token
        );
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/leads/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
