<?php

namespace App;

use App\Models\Requester;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

/**
 * Tests to test RequestersController
 * php version 7
 *
 * @category Tests
 * @package  RequestersController
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
class RequestersControllerTest extends WnyTestCase
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
        $requesters = DB::table('requesters')->get();
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
        $response = $this->get('api/requesters?token=' . $token);

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
                            "first_name",
                            "last_name",
                            "organization_id",
                            "prefix",
                            "suffix",
                            "email_work",
                            "email_personal",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "phone_home",
                            "phone_work",
                            "phone_extension",
                            "phone_mob1",
                            "phone_mob2",
                            "phone_fax",
                            "website",
                            "other_source",
                            "note",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "created_by",
                            "updated_by",
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
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Mrs', $data[0]['prefix']);
        $this->assertEquals('Evelyn', $data[0]['first_name']);
        $this->assertEquals('Perkins', $data[0]['last_name']);
        $this->assertEquals('M.D.', $data[0]['suffix']);
        $this->assertEquals('Central.Hospital@example.com', $data[0]['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data[0]['email_personal']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals('0119627516', $data[0]['phone_home']);
        $this->assertEquals('0119627522', $data[0]['phone_work']);
        $this->assertEquals('123', $data[0]['phone_extension']);
        $this->assertEquals('0814540666', $data[0]['phone_mob1']);
        $this->assertEquals('0814540667', $data[0]['phone_mob2']);
        $this->assertEquals('0119627523', $data[0]['phone_fax']);
        $this->assertEquals('website1.com', $data[0]['website']);
        $this->assertEquals('Other source 1', $data[0]['other_source']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('WNY General-Manager', $data[0]['created_by']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['updated_by']['name']);
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
        $response = $this->get('api/requesters?token=' . $token);

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
                            "first_name",
                            "last_name",
                            "organization_id",
                            "prefix",
                            "suffix",
                            "email_work",
                            "email_personal",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "phone_home",
                            "phone_work",
                            "phone_extension",
                            "phone_mob1",
                            "phone_mob2",
                            "phone_fax",
                            "website",
                            "other_source",
                            "note",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "created_by",
                            "updated_by",
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
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Mrs', $data[0]['prefix']);
        $this->assertEquals('Evelyn', $data[0]['first_name']);
        $this->assertEquals('Perkins', $data[0]['last_name']);
        $this->assertEquals('M.D.', $data[0]['suffix']);
        $this->assertEquals('Central.Hospital@example.com', $data[0]['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data[0]['email_personal']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals('0119627516', $data[0]['phone_home']);
        $this->assertEquals('0119627522', $data[0]['phone_work']);
        $this->assertEquals('123', $data[0]['phone_extension']);
        $this->assertEquals('0814540666', $data[0]['phone_mob1']);
        $this->assertEquals('0814540667', $data[0]['phone_mob2']);
        $this->assertEquals('0119627523', $data[0]['phone_fax']);
        $this->assertEquals('website1.com', $data[0]['website']);
        $this->assertEquals('Other source 1', $data[0]['other_source']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('WNY General-Manager', $data[0]['created_by']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['updated_by']['name']);
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
        $response = $this->get('api/requesters?token=' . $token);

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
        $response = $this->get('api/requesters?token=' . $token);

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
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/requesters/soft-deleted?token=' . $token);

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
                            "first_name",
                            "last_name",
                            "organization_id",
                            "prefix",
                            "suffix",
                            "email_work",
                            "email_personal",
                            "line_1",
                            "line_2",
                            "city",
                            "state",
                            "zip",
                            "phone_home",
                            "phone_work",
                            "phone_extension",
                            "phone_mob1",
                            "phone_mob2",
                            "phone_fax",
                            "website",
                            "other_source",
                            "note",
                            "created_by_id",
                            "updated_by_id",
                            "deleted_at",
                            "created_at",
                            "updated_at",
                            "organization",
                            "created_by",
                            "updated_by"
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
        $this->assertEquals(2, $data[0]['organization_id']);
        $this->assertEquals('Mrs', $data[0]['prefix']);
        $this->assertEquals('Evelyn', $data[0]['first_name']);
        $this->assertEquals('Perkins', $data[0]['last_name']);
        $this->assertEquals('M.D.', $data[0]['suffix']);
        $this->assertEquals('Central.Hospital@example.com', $data[0]['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data[0]['email_personal']);
        $this->assertEquals('9278 new road', $data[0]['line_1']);
        $this->assertEquals('app 3', $data[0]['line_2']);
        $this->assertEquals('Kilcoole', $data[0]['city']);
        $this->assertEquals('OH', $data[0]['state']);
        $this->assertEquals('93027', $data[0]['zip']);
        $this->assertEquals('0119627516', $data[0]['phone_home']);
        $this->assertEquals('0119627522', $data[0]['phone_work']);
        $this->assertEquals('123', $data[0]['phone_extension']);
        $this->assertEquals('0814540666', $data[0]['phone_mob1']);
        $this->assertEquals('0814540667', $data[0]['phone_mob2']);
        $this->assertEquals('0119627523', $data[0]['phone_fax']);
        $this->assertEquals('website1.com', $data[0]['website']);
        $this->assertEquals('Other source 1', $data[0]['other_source']);
        $this->assertEquals('Note #1.', $data[0]['note']);
        $this->assertEquals(6, $data[0]['created_by_id']);
        $this->assertEquals(10, $data[0]['updated_by_id']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data[0]['organization']['name']
        );
        $this->assertEquals('WNY General-Manager', $data[0]['created_by']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data[0]['updated_by']['name']);
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
        $response = $this->get('api/requesters/soft-deleted?token=' . $token);

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
        $response = $this->get('api/requesters/soft-deleted?token=' . $token);

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
        $response = $this->get('api/requesters/1?token=' . $token);

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
                        "first_name",
                        "last_name",
                        "organization_id",
                        "prefix",
                        "suffix",
                        "email_work",
                        "email_personal",
                        "line_1",
                        "line_2",
                        "city",
                        "state",
                        "zip",
                        "phone_home",
                        "phone_work",
                        "phone_extension",
                        "phone_mob1",
                        "phone_mob2",
                        "phone_fax",
                        "website",
                        "other_source",
                        "note",
                        "created_by_id",
                        "updated_by_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
                        "organization",
                        "created_by",
                        "updated_by"
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
        $this->assertEquals("Requesters.show. Result is successful.", $message);
        $this->assertEquals(2, $data['organization_id']);
        $this->assertEquals('Mrs', $data['prefix']);
        $this->assertEquals('Evelyn', $data['first_name']);
        $this->assertEquals('Perkins', $data['last_name']);
        $this->assertEquals('M.D.', $data['suffix']);
        $this->assertEquals('Central.Hospital@example.com', $data['email_work']);
        $this->assertEquals('evelyn.perkins@example.com', $data['email_personal']);
        $this->assertEquals('9278 new road', $data['line_1']);
        $this->assertEquals('app 3', $data['line_2']);
        $this->assertEquals('Kilcoole', $data['city']);
        $this->assertEquals('OH', $data['state']);
        $this->assertEquals('93027', $data['zip']);
        $this->assertEquals('0119627516', $data['phone_home']);
        $this->assertEquals('0119627522', $data['phone_work']);
        $this->assertEquals('123', $data['phone_extension']);
        $this->assertEquals('0814540666', $data['phone_mob1']);
        $this->assertEquals('0814540667', $data['phone_mob2']);
        $this->assertEquals('0119627523', $data['phone_fax']);
        $this->assertEquals('website1.com', $data['website']);
        $this->assertEquals('Other source 1', $data['other_source']);
        $this->assertEquals('Note #1.', $data['note']);
        $this->assertEquals(6, $data['created_by_id']);
        $this->assertEquals(10, $data['updated_by_id']);
        $this->assertEquals(null, $data['deleted_at']);
        $this->assertEquals(
            'Western New York Exteriors, LLC.',
            $data['organization']['name']
        );
        $this->assertEquals('WNY General-Manager', $data['created_by']['name']);
        $this->assertEquals('WNY Administrative-Leader', $data['updated_by']['name']);
    }

    /**
     * Check Show If Entity ID is Incorrect
     *
     * @return void
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/requesters/44444?token=' . $token, []);

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
            "Requesters.show. Incorrect ID in URL.",
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

        $response = $this->get('api/requesters/1?token=' . $token);

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

        $response = $this->get('api/requesters/1?token=' . $token);

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
            "first_name" => 'First',
            "last_name" => 'Last',
            "organization_id" => 2,
            "prefix" => 'Mr',
            "suffix" => 'M.D.',
            "email_work" => 'mail@exit.com',
            "email_personal" => 'mail@exit.com',
            "line_1" => 'Line 1',
            "line_2" => 'Line 2',
            "city" => 'City',
            "state" => 'NY',
            "zip" => '01234',
            "phone_home" => '0119627516',
            "phone_work" => '0119627517',
            "phone_extension" => '0111',
            "phone_mob1" => '0119627518',
            "phone_mob2" => '0119627519',
            "phone_fax" => '0119627520',
            "website" => 'website.com',
            "other_source" => 'other',
            "note" => 'Note #456.',
            "created_by_id" => 6,
        ];

        // Store the Workflow
        $response = $this->post('api/requesters?token=' . $token, $data);

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
        $this->assertEquals("Requesters.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $requester = DB::table('requesters')
            ->where('id', '=', 3)
            ->first();
        $this->assertEquals(2, $requester->organization_id);
        $this->assertEquals('Mr', $requester->prefix);
        $this->assertEquals('First', $requester->first_name);
        $this->assertEquals('Last', $requester->last_name);
        $this->assertEquals('M.D.', $requester->suffix);
        $this->assertEquals('mail@exit.com', $requester->email_work);
        $this->assertEquals('mail@exit.com', $requester->email_personal);
        $this->assertEquals('Line 1', $requester->line_1);
        $this->assertEquals('Line 2', $requester->line_2);
        $this->assertEquals('City', $requester->city);
        $this->assertEquals('NY', $requester->state);
        $this->assertEquals('01234', $requester->zip);
        $this->assertEquals('0119627516', $requester->phone_home);
        $this->assertEquals('0119627517', $requester->phone_work);
        $this->assertEquals('0111', $requester->phone_extension);
        $this->assertEquals('0119627518', $requester->phone_mob1);
        $this->assertEquals('0119627519', $requester->phone_mob2);
        $this->assertEquals('0119627520', $requester->phone_fax);
        $this->assertEquals('website.com', $requester->website);
        $this->assertEquals('other', $requester->other_source);
        $this->assertEquals('Note #456.', $requester->note);
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
            "first_name" => 'First',
            "last_name" => 'Last',
            "organization_id" => 2,
            "prefix" => 'Mr',
            "suffix" => 'M.D.',
            "email_work" => 'mail@exit.com',
            "email_personal" => 'mail@exit.com',
            "line_1" => 'Line 1',
            "line_2" => 'Line 2',
            "city" => 'City',
            "state" => 'NY',
            "zip" => '01234',
            "phone_home" => '0119627516',
            "phone_work" => '0119627517',
            "phone_extension" => '0111',
            "phone_mob1" => '0119627518',
            "phone_mob2" => '0119627519',
            "phone_fax" => '0119627520',
            "website" => 'website.com',
            "other_source" => 'other',
            "note" => 'Note #456.',
            "created_by_id" => 6,
        ];

        // Store the Workflow
        $response = $this->post('api/requesters?token=' . $token, $data);

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
            "first_name" => 'First',
            "last_name" => 'Last',
            "organization_id" => 2,
            "prefix" => 'Mr',
            "suffix" => 'M.D.',
            "email_work" => 'mail@exit.com',
            "email_personal" => 'mail@exit.com',
            "line_1" => 'Line 1',
            "line_2" => 'Line 2',
            "city" => 'City',
            "state" => 'NY',
            "zip" => '01234',
            "phone_home" => '0119627516',
            "phone_work" => '0119627517',
            "phone_extension" => '0111',
            "phone_mob1" => '0119627518',
            "phone_mob2" => '0119627519',
            "phone_fax" => '0119627520',
            "website" => 'website.com',
            "other_source" => 'other',
            "note" => 'Note #456.',
            "created_by_id" => 6,
        ];

        // Store the Workflow
        $response = $this->post('api/requesters?token=' . $token, $data);

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
            "first_name" => [],
            "last_name" => [],
            "organization_id" => [],
            "prefix" => [],
            "suffix" => [],
            "email_work" => [],
            "email_personal" => [],
            "line_1" => [],
            "line_2" => [],
            "city" => [],
            "state" => [],
            "zip" => [],
            "phone_home" => [],
            "phone_work" => [],
            "phone_extension" => [],
            "phone_mob1" => [],
            "phone_mob2" => [],
            "phone_fax" => [],
            "website" => [],
            "other_source" => [],
            "note" => [],
            "created_by_id" => [],
        ];

        // Store the Workflow
        $response = $this->post('api/requesters?token=' . $token, $data);

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
        $this->assertEquals(22, count($error['errors']));
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
            "first_name" => 'First 1',
            "last_name" => 'Last 1',
            "organization_id" => 2,
            "prefix" => 'Mrs',
            "suffix" => 'M.F.',
            "email_work" => 'mail1@exit.com',
            "email_personal" => 'mail1@exit.com',
            "line_1" => 'Line 11',
            "line_2" => 'Line 21',
            "city" => 'City1',
            "state" => 'CA',
            "zip" => '012341',
            "phone_home" => '01196275161',
            "phone_work" => '01196275171',
            "phone_extension" => '01111',
            "phone_mob1" => '01196275181',
            "phone_mob2" => '01196275191',
            "phone_fax" => '01196275201',
            "website" => 'website1.com',
            "other_source" => 'other1',
            "note" => 'Note #4561.',
            "created_by_id" => 6,
            "updated_by_id" => 10,
        ];

        $response = $this->put('api/requesters/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Requesters.update. Result is successful.", $message);

        $requester = DB::table('requesters')
            ->where('id', '=', 1)
            ->first();
        $this->assertEquals(2, $requester->organization_id);
        $this->assertEquals('Mrs', $requester->prefix);
        $this->assertEquals('First 1', $requester->first_name);
        $this->assertEquals('Last 1', $requester->last_name);
        $this->assertEquals('M.F.', $requester->suffix);
        $this->assertEquals('mail1@exit.com', $requester->email_work);
        $this->assertEquals('mail1@exit.com', $requester->email_personal);
        $this->assertEquals('Line 11', $requester->line_1);
        $this->assertEquals('Line 21', $requester->line_2);
        $this->assertEquals('City1', $requester->city);
        $this->assertEquals('CA', $requester->state);
        $this->assertEquals('012341', $requester->zip);
        $this->assertEquals('01196275161', $requester->phone_home);
        $this->assertEquals('01196275171', $requester->phone_work);
        $this->assertEquals('01111', $requester->phone_extension);
        $this->assertEquals('01196275181', $requester->phone_mob1);
        $this->assertEquals('01196275191', $requester->phone_mob2);
        $this->assertEquals('01196275201', $requester->phone_fax);
        $this->assertEquals('website1.com', $requester->website);
        $this->assertEquals('other1', $requester->other_source);
        $this->assertEquals('Note #4561.', $requester->note);
        $this->assertEquals(6, $requester->created_by_id);
        $this->assertEquals(10, $requester->updated_by_id);
        $this->assertEquals(null, $requester->deleted_at);
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
        // Create data
        $data = [
            "first_name" => 'First 1',
            "last_name" => 'Last 1',
            "organization_id" => 2,
            "prefix" => 'Mrs',
            "suffix" => 'M.F.',
            "email_work" => 'mail1@exit.com',
            "email_personal" => 'mail1@exit.com',
            "line_1" => 'Line 11',
            "line_2" => 'Line 21',
            "city" => 'City1',
            "state" => 'CA',
            "zip" => '012341',
            "phone_home" => '01196275161',
            "phone_work" => '01196275171',
            "phone_extension" => '01111',
            "phone_mob1" => '01196275181',
            "phone_mob2" => '01196275191',
            "phone_fax" => '01196275201',
            "website" => 'website1.com',
            "other_source" => 'other1',
            "note" => 'Note #4561.',
            "created_by_id" => 6,
            "updated_by_id" => 10,
        ];

        $response = $this->put('api/requesters/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Requesters.update. Incorrect ID in URL.", $message);
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
            "first_name" => [],
            "last_name" => [],
            "organization_id" => [],
            "prefix" => [],
            "suffix" => [],
            "email_work" => [],
            "email_personal" => [],
            "line_1" => [],
            "line_2" => [],
            "city" => [],
            "state" => [],
            "zip" => [],
            "phone_home" => [],
            "phone_work" => [],
            "phone_extension" => [],
            "phone_mob1" => [],
            "phone_mob2" => [],
            "phone_fax" => [],
            "website" => [],
            "other_source" => [],
            "note" => [],
            "created_by_id" => [],
            "updated_by_id" => [],
        ];

        $response = $this->put('api/requesters/1?token=' . $token, $data);
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
        $this->assertCount(23, $error['errors']);
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

        $response = $this->put('api/requesters/1?token=' . $token, $data);

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

        $response = $this->put('api/requesters/1?token=' . $token, $data);

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
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "Requesters.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $requester = DB::table('requesters')->where('id', 1)->first();
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
        $response = $this->delete('api/requesters/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "Requesters.softDestroy. Incorrect ID in URL.",
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
        $response = $this->delete('api/requesters/1?token=' . $token);

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
        $response = $this->delete('api/requesters/1?token=' . $token);

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
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/requesters/1/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Requesters.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $requester = Requester::with(['organization', 'createdBy'])
            ->where('id', 1)->first();
        $this->assertEquals('WNY General-Manager', $requester['createdBy']['name']);
        $this->assertEquals(null, $requester['deleted_at']);
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
        $response = $this->put('api/requesters/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("Requesters.restore. Incorrect ID in URL.", $message);
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
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();
        // Request
        $response = $this->put('api/requesters/1/restore?token=' . $token);

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
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/requesters/1/permanently?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("Requesters.destroyPermanently. Result is successful.", $message);

        $requester = Requester::whereId(1)->first();
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
            'api/requesters/2222/permanently?token=' . $token
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
            "Requesters.destroyPermanently. Incorrect ID in URL.",
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
            'api/requesters/1/permanently?token=' . $token
        );
        $response->assertStatus(200);

        // Request
        $token    = $this->loginOrganizationWNYGeneralManager();
        $response = $this->delete('api/requesters/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
