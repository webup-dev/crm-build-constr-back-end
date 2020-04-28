<?php

namespace App;

use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Test of MenusController
 *
 * @category Test
 * @package  Test
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Test
 */
class MenusControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp: useTestsSeeder
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
     * Check Index:
     *   Check developer login
     *   Check getting of users
     *   Check response status
     *   Check response structure
     *   Check response data
     *
     * @return void
     */
    public function testSeeder()
    {
        $token = $this->loginDeveloper();

        // Request
        $response = $this->get('api/organizations?token=' . $token);

        // Check response status
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array

        $this->assertCount(17, $data);
    }

    /**
     * Check GetSoftDeleted
     *   Check login of developer
     *   User developer soft-delete 3 customers
     *   User developer soft-delete 2 user profiles
     *   Request getSoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     *
     * @return void
     */
    public function testGetSoftDeleted()
    {
        $token = $this->loginDeveloper();

        // Deleting
        $response = $this->delete('api/customers/3?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/2?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/8?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/7?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/16?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-types/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-types/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-statuses/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-statuses/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/stages/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        // Request
        $response = $this->get('api/soft-deleted-items?token=' . $token);

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
                        [
                            "name",
                            "url",
                            "count"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertCount(12, $data);

        $this->assertEquals('User Profiles', $data[0]['name']);
        $this->assertEquals("user-profiles/soft-deleted", $data[0]['url']);

        $this->assertEquals(3, $data[1]['count']);
        $this->assertEquals('Customers', $data[1]['name']);
        $this->assertEquals("customers/soft-deleted", $data[1]['url']);

        $this->assertEquals(2, $data[2]['count']);
        $this->assertEquals('Organizations', $data[2]['name']);
        $this->assertEquals("organizations/soft-deleted", $data[2]['url']);

        $this->assertEquals(4, $data[3]['count']);
        $this->assertEquals('User-Customers', $data[3]['name']);
        $this->assertEquals("user-customers/soft-deleted", $data[3]['url']);

        $this->assertEquals(2, $data[5]['count']);
        $this->assertEquals('Lead Source Categories', $data[5]['name']);
        $this->assertEquals("lead-source-categories/soft-deleted", $data[5]['url']);

        $this->assertEquals(1, $data[6]['count']);
        $this->assertEquals('Lead Sources', $data[6]['name']);
        $this->assertEquals("lead-sources/soft-deleted", $data[6]['url']);

        $this->assertEquals(2, $data[7]['count']);
        $this->assertEquals('Trades', $data[7]['name']);
        $this->assertEquals("trades/soft-deleted", $data[7]['url']);

        $this->assertEquals(2, $data[8]['count']);
        $this->assertEquals('Lead Statuses', $data[8]['name']);
        $this->assertEquals("lead-statuses/soft-deleted", $data[8]['url']);

        $this->assertEquals(1, $data[9]['count']);
        $this->assertEquals('Stages', $data[9]['name']);
        $this->assertEquals("stages/soft-deleted", $data[9]['url']);

        $this->assertEquals(1, $data[10]['count']);
        $this->assertEquals('Workflows', $data[10]['name']);
        $this->assertEquals("workflows/soft-deleted", $data[10]['url']);

        $this->assertEquals(1, $data[11]['count']);
        $this->assertEquals('Requesters', $data[11]['name']);
        $this->assertEquals("requesters/soft-deleted", $data[11]['url']);

        $this->assertEquals("Soft-deleted retrieved successfully.", $message);
        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
    }

    /**
     * Check GetSoftDeleted If The Access Is Not Full
     *   Check login of developer
     *   User developer soft-delete 3 customers
     *   User developer soft-delete 2 user profiles
     *   User developer soft-delete 2 organizations
     *   Check login of organizational-superadmin
     *   Request getSoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     *
     * @return void
     */
    public function testGetSoftDeletedIfTheAccessIsNotFull()
    {
        $token = $this->loginDeveloper();

        // Deleting
        $response = $this->delete('api/customers/3?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/2?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/8?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/7?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/16?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-source-categories/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-source-categories/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-types/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-types/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-statuses/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/lead-statuses/2?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/stages/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/workflows/1?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/requesters/1?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYSuperadmin();

        // Request
        $response = $this->get('api/soft-deleted-items?token=' . $token, []);

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
                        [
                            "name",
                            "url",
                            "count"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertCount(12, $data);

        $this->assertEquals('User Profiles', $data[0]['name']);
        $this->assertEquals("user-profiles/soft-deleted", $data[0]['url']);

        $this->assertEquals(2, $data[1]['count']);
        $this->assertEquals('Customers', $data[1]['name']);
        $this->assertEquals("customers/soft-deleted", $data[1]['url']);

        $this->assertEquals(1, $data[2]['count']);
        $this->assertEquals('Organizations', $data[2]['name']);
        $this->assertEquals("organizations/soft-deleted", $data[2]['url']);

        $this->assertEquals(3, $data[3]['count']);
        $this->assertEquals('User-Customers', $data[3]['name']);
        $this->assertEquals("user-customers/soft-deleted", $data[3]['url']);

        $this->assertEquals(2, $data[5]['count']);
        $this->assertEquals('Lead Source Categories', $data[5]['name']);
        $this->assertEquals("lead-source-categories/soft-deleted", $data[5]['url']);

        $this->assertEquals(1, $data[6]['count']);
        $this->assertEquals('Lead Sources', $data[6]['name']);
        $this->assertEquals("lead-sources/soft-deleted", $data[6]['url']);

        $this->assertEquals(2, $data[7]['count']);
        $this->assertEquals('Trades', $data[7]['name']);
        $this->assertEquals("trades/soft-deleted", $data[7]['url']);

        $this->assertEquals(2, $data[8]['count']);
        $this->assertEquals('Lead Statuses', $data[8]['name']);
        $this->assertEquals("lead-statuses/soft-deleted", $data[8]['url']);

        $this->assertEquals(1, $data[9]['count']);
        $this->assertEquals('Stages', $data[9]['name']);
        $this->assertEquals("stages/soft-deleted", $data[9]['url']);

        $this->assertEquals(1, $data[10]['count']);
        $this->assertEquals('Workflows', $data[10]['name']);
        $this->assertEquals("workflows/soft-deleted", $data[10]['url']);

        $this->assertEquals(1, $data[11]['count']);
        $this->assertEquals('Requesters', $data[11]['name']);
        $this->assertEquals("requesters/soft-deleted", $data[11]['url']);

        $this->assertEquals("Soft-deleted retrieved successfully.", $message);
        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
    }

    /**
     * Check GetSoftDeleted If The Access Is Absent By The Role
     *   Check login of developer
     *   User developer soft-delete 3 customers
     *   User developer soft-delete 1 user profiles
     *   Check login of estimator
     *   Request getSoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     *
     * @return void
     */
    public function testGetSoftDeletedIfTheAccessIsAbsentByTheRole()
    {
        $token = $this->loginDeveloper();

        // Deleting
        $response = $this->delete('api/customers/3?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/2?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/8?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/17?token=' . $token);
        $response->assertStatus(200);
        $response = $this->delete('api/organizations/16?token=' . $token);
        $response->assertStatus(200);

        $token = $this->loginOrganizationWNYGeneralManager();

        // Request
        $response = $this->get('api/soft-deleted-items?token=' . $token, []);

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
        $success      = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array

        $this->assertEquals(false, $success);
        $this->assertEquals("Permission is absent by the role.", $message);
    }

    /**
     * Special Test. Bug CCFEC-385
     * OrganizationWNYAdmin tries to get Data for dashboard
     *
     * @return void
     */
    public function testGetSoftDeletedSpecialTestBug385()
    {
        $token = $this->loginOrganizationWNYAdmin();

        // Request
        $response = $this->get('api/soft-deleted-items?token=' . $token, []);

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
                        [
                            "name",
                            "url",
                            "count"
                        ]
                    ]
            ]
        );
        $responseJSON = json_decode($response->getContent(), true);
        $success      = $responseJSON['success'];  // array
        $code         = $responseJSON['code'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertCount(12, $data);

        $this->assertEquals(0, $data[0]['count']);
        $this->assertEquals('User Profiles', $data[0]['name']);
        $this->assertEquals("user-profiles/soft-deleted", $data[0]['url']);

        $this->assertEquals(0, $data[1]['count']);
        $this->assertEquals('Customers', $data[1]['name']);
        $this->assertEquals("customers/soft-deleted", $data[1]['url']);

        $this->assertEquals(0, $data[2]['count']);
        $this->assertEquals('Organizations', $data[2]['name']);
        $this->assertEquals("organizations/soft-deleted", $data[2]['url']);

        $this->assertEquals(0, $data[3]['count']);
        $this->assertEquals('User-Customers', $data[3]['name']);
        $this->assertEquals("user-customers/soft-deleted", $data[3]['url']);

        $this->assertEquals(1, $data[6]['count']);
        $this->assertEquals('Lead Sources', $data[6]['name']);
        $this->assertEquals("lead-sources/soft-deleted", $data[6]['url']);

        $this->assertEquals(0, $data[7]['count']);
        $this->assertEquals('Trades', $data[7]['name']);
        $this->assertEquals("trades/soft-deleted", $data[7]['url']);

        $this->assertEquals(0, $data[8]['count']);
        $this->assertEquals('Lead Statuses', $data[8]['name']);
        $this->assertEquals("lead-statuses/soft-deleted", $data[8]['url']);

        $this->assertEquals(0, $data[9]['count']);
        $this->assertEquals('Stages', $data[9]['name']);
        $this->assertEquals("stages/soft-deleted", $data[9]['url']);

        $this->assertEquals(0, $data[10]['count']);
        $this->assertEquals('Workflows', $data[10]['name']);
        $this->assertEquals("workflows/soft-deleted", $data[10]['url']);

        $this->assertEquals(0, $data[11]['count']);
        $this->assertEquals('Requesters', $data[11]['name']);
        $this->assertEquals("requesters/soft-deleted", $data[11]['url']);

        $this->assertEquals("Soft-deleted retrieved successfully.", $message);
        $this->assertEquals(true, $success);
        $this->assertEquals(200, $code);
    }
}
