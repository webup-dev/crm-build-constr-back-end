<?php

/**
 * SetUp. Tests use:
 *   5 users (developer, organizational-admin, estimator)
 *   2 customers
 *   1 organization
 *
 * TestsExample
 *
 * TestSeeder
 *
 * Check GetSoftDeleted
 * Check GetSoftDeleted If The Access Is Not Full
 * Check GetSoftDeleted If The Access Is Absent By The Role
 */

namespace App;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User_profile;
use App\Models\User_role;
use Hash;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\WnyTestCase;

class MenusControllerTest extends WnyTestCase
{
    use DatabaseMigrations;

    /**
     * SetUp:
     *   Create 5 users (user A, user B, user C, customer A, customer B)
     *   Create 3 roles (superadmin, organization-general-manager, customer-individual)
     *   Create 2 customers (customer A, customer B)
     *   Create 2 organizations (organization 1, organization 2)
     *   Create 3 user_profiles (user A, user B, user C)
     *   Bind users and roles (user A - superadmin, user B - organization-general-manager, user C - superadmin, customer A - customer-individual, customer B - customer-individual)
     *   Bind users and user_profiles (user A - user A, user B - user B, user C - user C)
     *   Bind user_profiles and organizations (user A - organization 1, user B - organization 1, user C - organization 2)
     *   Bind users and customers (customer A - customer A, customer B - customer B)
     *   Bind customers and organizations (customer A - organization 1, customer B - organization 1)
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
     */
    public function testSeeder()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Volodymyr Vadiasov',
            'email' => 'developer@admin.com'
        ])->isOk();

        // Request
        $response = $this->get('api/organizations?token=' . $token, []);

        // Check response status
        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(17, count($data));
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
     */
    public function testGetSoftDeleted()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Volodymyr Vadiasov',
            'email' => 'developer@admin.com'
        ])->isOk();

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
        $code         = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(2, count($data));
        $this->assertEquals('User Profiles', $data[0]['name']);
        $this->assertEquals("user-profiles/soft-deleted", $data[0]['url']);
        $this->assertEquals(3, $data[1]['count']);
        $this->assertEquals('Customers', $data[1]['name']);
        $this->assertEquals("customers/soft-deleted", $data[1]['url']);

        $this->assertEquals("Soft-deleted retrieved successfully.", $message);
        $this->assertEquals(true, $success);
    }

    /**
     * Check GetSoftDeleted If The Access Is Not Full
     *   Check login of developer
     *   User developer soft-delete 3 customers
     *   User developer soft-delete 2 user profiles
     *   Check login of organizational-superadmin
     *   Request getSoftDeleted
     *   Check response status
     *   Check response structure
     *   Check response data
     */
    public function testGetSoftDeletedIfTheAccessIsNotFull()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Volodymyr Vadiasov',
            'email' => 'developer@admin.com'
        ])->isOk();

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

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'wny-superadmin@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'WNY SuperAdmin',
            'email' => 'wny-superadmin@admin.com'
        ])->isOk();

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
        $code         = $responseJSON['success'];  // array
        $message      = $responseJSON['message'];  // array
        $data         = $responseJSON['data'];  // array

        $this->assertEquals(2, count($data));
        $this->assertEquals('User Profiles', $data[0]['name']);
        $this->assertEquals("user-profiles/soft-deleted", $data[0]['url']);
        $this->assertEquals(2, $data[1]['count']);
        $this->assertEquals('Customers', $data[1]['name']);
        $this->assertEquals("customers/soft-deleted", $data[1]['url']);
        $this->assertEquals("Soft-deleted retrieved successfully.", $message);
        $this->assertEquals(true, $success);
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
     */
    public function testGetSoftDeletedIfTheAccessIsAbsentByTheRole()
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'Volodymyr Vadiasov',
            'email' => 'developer@admin.com'
        ])->isOk();

        // Deleting
        $response = $this->delete('api/customers/3?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/2?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/customers/1?token=' . $token, []);
        $response->assertStatus(200);
        $response = $this->delete('api/user-profiles/8?token=' . $token, []);
        $response->assertStatus(200);

        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => 'wny-estimator@admin.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => 'WNY Estimator',
            'email' => 'wny-estimator@admin.com'
        ])->isOk();

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
}
