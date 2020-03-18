<?php

namespace App;

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
class LsCategoriesControllerTest extends WnyTestCase
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
        $lead_sources = DB::table('ls_categories')->get();
        $this->assertEquals(18, $lead_sources->count());

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
        $response = $this->get('api/lead-source-categories?token=' . $token);

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
                            "description",
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
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(18, count($data));
        $this->assertEquals('Blogging', $data[0]['name']);
        $this->assertEquals(null, $data[0]['description']);
        $this->assertEquals(null, $data[0]['deleted_at']);
        $this->assertEquals("LsCategories.index. Result is successful.", $message);
    }

    /**
     * Check Index If Permission Is Absent
     *
     * @return void
     */
    public function testIndexIfPermissionIsAbsent()
    {
        $token = $this->loginOrganizationSpringSuperadmin();

        // Request
        $response = $this->get('api/lead-source-categories?token=' . $token);

        // Check response status
        $response->assertStatus(453);
    }

    /**
     * Check Empty Index For Developer
     *
     * @return void
     */
    public function testIndexIfContentIsEmptyForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
        $response = $this->delete('api/lead-source-categories/3?token=' . $token);
        $response = $this->delete('api/lead-source-categories/4?token=' . $token);
        $response = $this->delete('api/lead-source-categories/5?token=' . $token);
        $response = $this->delete('api/lead-source-categories/6?token=' . $token);
        $response = $this->delete('api/lead-source-categories/7?token=' . $token);
        $response = $this->delete('api/lead-source-categories/8?token=' . $token);
        $response = $this->delete('api/lead-source-categories/9?token=' . $token);
        $response = $this->delete('api/lead-source-categories/10?token=' . $token);
        $response = $this->delete('api/lead-source-categories/11?token=' . $token);
        $response = $this->delete('api/lead-source-categories/12?token=' . $token);
        $response = $this->delete('api/lead-source-categories/13?token=' . $token);
        $response = $this->delete('api/lead-source-categories/14?token=' . $token);
        $response = $this->delete('api/lead-source-categories/15?token=' . $token);
        $response = $this->delete('api/lead-source-categories/16?token=' . $token);
        $response = $this->delete('api/lead-source-categories/17?token=' . $token);
        $response = $this->delete('api/lead-source-categories/18?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/lead-source-categories?token=' . $token);

        // Check response status
        $response->assertStatus(204);
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
        $response = $this->get('api/lead-source-categories/1?token=' . $token);

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
                        "description",
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
        $this->assertEquals("LsCategories.show. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals('Blogging', $data['name']);
        $this->assertEquals('', $data['description']);
        $this->assertEquals(null, $data['deleted_at']);
    }

    /**
     * Check Show If Entity ID is Incorrect
     *
     * @return void
     */
    public function testShowIfEntityIdIsIncorrect()
    {
        $token = $this->loginDeveloper();

        $response = $this->get('api/lead-source-categories/44444?token=' . $token, []);

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
            "LsCategories.show. Incorrect ID in URL.",
            $message
        );
        $this->assertEquals(null, $data);
    }

    /**
     * Check Show With Absent Of Permission
     *
     * @return void
     */
    public function testShowWithAbsentOfPermission()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Store the Lead Source
        $response = $this->get('api/lead-source-categories/1?token=' . $token);

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
     * Check Soft-deleted Index For Developer
     *
     * @return void
     */
    public function testIndexAllSoftDeleted()
    {
        $token = $this->loginDeveloper();

        // Create soft deleted
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response->assertStatus(200);

        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
        $response->assertStatus(200);

        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);

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
                            "description",
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
        $code         = $responseJSON['code'];     // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];     // array

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "LsCategories.indexSoftDeleted. Result is successful.",
            $message
        );
        $this->assertEquals(2, count($data));
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals('Blogging', $data[0]['name']);
        $this->assertNotEquals(null, $data[0]['deleted_at']);
    }

    /**
     * Check Empty Soft-deleted Index
     *
     * @return void
     */
    public function testIndexAllSoftDeletedEmpty()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);

        // Check response status
        $response->assertStatus(204);
    }

    /**
     * Check Soft-deleted Index If Permission Is Absent Due To Role
     *
     * @return void
     */
    public function testIndexAllSoftDeletedIfPermissionIsAbsentDueToRole()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/lead-source-categories/soft-deleted?token=' . $token);

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
     *
     * @return void
     */
    public function testStoreForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"        => 'Test Lead Source Category',
            "description" => 'Description demo text.'
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-source-categories?token=' . $token, $data);

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
        $this->assertEquals("LsCategory.store. Result is successful.", $message);
        $this->assertEquals(null, $data);

        // Check DB table customer_details
        $leadSources = DB::table('ls_categories')
            ->where('id', '=', 19)
            ->first();
        $this->assertEquals('Test Lead Source Category', $leadSources->name);
        $this->assertEquals('Description demo text.', $leadSources->description);
        $this->assertEquals(null, $leadSources->deleted_at);
    }

    /**
     * Check Store With Absent Of Permission
     *
     * @return void
     */
    public function testStoreWithAbsentOfPermission()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Create data
        $data = [
            "name"        => 'Test Lead Source Category',
            "description" => 'Description demo text.'
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-source-categories?token=' . $token, $data);

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
     * Check Store Invalid Data
     *
     * @return void
     */
    public function testStoreInvalidData()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            "name"        => [],
            "description" => []
        ];

        // Store the Lead Source
        $response = $this->post('api/lead-source-categories?token=' . $token, $data);

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
     * Check Update For Developer
     *
     * @return void
     */
    public function testUpdateForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Create data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/lead-source-categories/1?token=' . $token, $data);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LsCategories.update. Result is successful.", $message);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("Description edited", $data['description']);
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
            'description' => 'Description edited'
        ];

        $response = $this->put('api/lead-source-categories/44444?token=' . $token, $data);
        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LsCategories.update. Incorrect ID in URL.", $message);
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
            'description' => []
        ];

        $response = $this->put('api/lead-source-categories/1?token=' . $token, $data);
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
     * Check Update With Absent Of Permission
     *
     * @return void
     */
    public function testUpdateWithAbsentOfPermission()
    {
        $token = $this->loginOrganizationWNYGeneralManager();

        // Update data
        $data = [
            'description' => 'Description edited'
        ];

        $response = $this->put('api/lead-source-categories/1?token=' . $token, $data);

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
     * Check Soft Delete For Developer
     *
     * @return void
     */
    public function testSoftDeleteForDeveloper()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
        $response = $this->delete('api/lead-source-categories/3?token=' . $token);
        $response = $this->delete('api/lead-source-categories/4?token=' . $token);
        $response = $this->delete('api/lead-source-categories/5?token=' . $token);
        $response = $this->delete('api/lead-source-categories/6?token=' . $token);
        $response = $this->delete('api/lead-source-categories/7?token=' . $token);
        $response = $this->delete('api/lead-source-categories/8?token=' . $token);
        $response = $this->delete('api/lead-source-categories/9?token=' . $token);
        $response = $this->delete('api/lead-source-categories/10?token=' . $token);
        $response = $this->delete('api/lead-source-categories/11?token=' . $token);
        $response = $this->delete('api/lead-source-categories/12?token=' . $token);
        $response = $this->delete('api/lead-source-categories/13?token=' . $token);
        $response = $this->delete('api/lead-source-categories/14?token=' . $token);
        $response = $this->delete('api/lead-source-categories/15?token=' . $token);
        $response = $this->delete('api/lead-source-categories/16?token=' . $token);
        $response = $this->delete('api/lead-source-categories/17?token=' . $token);
        $response = $this->delete('api/lead-source-categories/18?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals(
            "LsCategories.softDestroy. Result is successful.",
            $message
        );
        $this->assertEquals(null, $data);

        $leadSources = DB::table('ls_categories')->where('id', 12)->first();
        $this->assertNotEquals(null, $leadSources->deleted_at);
    }

    /**
     * Check Soft Delete For Organization admin
     *
     * @return void
     */
    public function testSoftDeleteForOrganizationAdmin()
    {
        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
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
        $response = $this->delete('api/lead-source-categories/4444?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals(
            "LsCategories.softDestroy. Incorrect ID in URL.",
            $message
        );
        $this->assertEquals(null, $data);
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
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->put('api/lead-source-categories/1/restore?token=' . $token);
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LsCategories.restore. Result is successful.", $message);
        $this->assertEquals(null, $data);

        $leadSources = LsCategory::where('id', 1)->first();
        $this->assertEquals(null, $leadSources->deleted_at);
    }

    /**
     * Check Restore If The File ID Is Wrong
     *
     * @return void
     */
    public function testRestoreIfTheFileIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->put('api/lead-source-categories/4444/restore?token=' . $token);

        $response->assertStatus(456);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(false, $success);
        $this->assertEquals(456, $code);
        $this->assertEquals("LsCategories.restore. Incorrect ID in URL.", $message);
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
        $response = $this->put('api/lead-source-categories/1/restore?token=' . $token);

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

        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->delete('api/lead-source-categories/1/permanently?token=' . $token);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $code         = $responseJSON['code'];
        $message      = $responseJSON['message'];
        $data         = $responseJSON['data'];

        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
        $this->assertEquals("LsCategories.destroyPermanently. Result is successful.", $message);

        $leadSources = DB::table('ls_categories')->where('id', 1)->first();
        $this->assertEquals(null, $leadSources);
    }

    /**
     * Check Delete Permanently If The File ID Is Wrong
     *
     * @return void
     */
    public function testDeletePermanentlyIfIdIsWrong()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->delete(
            'api/lead-source-categories/2222/permanently?token=' . $token
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
            "LsCategories.destroyPermanently. Incorrect ID in URL.",
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
        $response = $this->delete('api/lead-source-categories/1/permanently?token=' . $token);

        $response->assertStatus(453);

        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];
        $message      = $responseJSON['message'];

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }
}
