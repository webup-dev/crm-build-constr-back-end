<?php

namespace App;

use Illuminate\Support\Facades\Schema;

/**
 * Class WnyTestCase
 *
 * @category TestCase
 * @package  App
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Tests
 */
abstract class WnyTestCase extends TestCase
{
    /**
     * Tear down after tests
     *
     * @return void
     */
    public function tearDown()
    : void
    {
        Schema::dropIfExists('books');
        Schema::dropIfExists('method_roles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('methods');
        Schema::dropIfExists('controllers');
        Schema::dropIfExists('customer_individuals');
        Schema::dropIfExists('customer_comments');
        Schema::dropIfExists('customer_files');
        Schema::dropIfExists('user_customers');
        Schema::dropIfExists('user_details');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('files');
        Schema::dropIfExists('lead_types');
        Schema::dropIfExists('lead_statuses');
        Schema::dropIfExists('lead_sources');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('users');
        Schema::dropIfExists('lsCategories');
    }

    /**
     * LoginUser
     *
     * @param array $data Array of email, password
     *
     * @return mixed
     */
    public function loginUser($data)
    {
        // Check login
        $response = $this->post(
            'api/auth/login', [
                'email'    => $data['email'],
                'password' => $data['password']
            ]
        );

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get(
            'api/auth/me?token=' . $token, []
        )->assertJson(
            [
                'name'  => $data['name'],
                'email' => $data['email']
            ]
        )->isOk();

        return $token;
    }

    /**
     * Login Developer
     *
     * @return mixed
     */
    public function loginDeveloper()
    {
        $data = [
            'name'     => 'Volodymyr Vadiasov',
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login PlatformSuperAdmin
     *
     * @return mixed
     */
    public function loginPlatformSuperAdmin()
    {
        $data = [
            'name'     => 'Steven Caamano',
            'email'    => 'platform-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login OrganizationWNYSuperadmin
     *
     * @return mixed
     */
    public function loginOrganizationWNYSuperadmin()
    {
        $data = [
            'name'     => 'WNY SuperAdmin',
            'email'    => 'wny-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login OrganizationWNYGeneralManager
     *
     * @return mixed
     */
    public function loginOrganizationWNYGeneralManager()
    {
        $data = [
            'name'     => 'WNY General-Manager',
            'email'    => 'wny-generalManager@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login OrganizationWNYAdmin
     *
     * @return mixed
     */
    public function loginOrganizationWNYAdmin()
    {
        $data = [
            'name'     => 'WNY Admin',
            'email'    => 'wny-admin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login OrganizationSpringSuperadmin
     *
     * @return mixed
     */
    public function loginOrganizationSpringSuperadmin()
    {
        $data = [
            'name'     => 'Spring Superadmin',
            'email'    => 'Spring-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login CustomerSpring
     *
     * @return mixed
     */
    public function loginCustomerSpring()
    {
        $data = [
            'name'     => 'Customer B-Spring',
            'email'    => 'spring-customer-organization@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login CustomerWny
     *
     * @return mixed
     */
    public function loginCustomerWny()
    {
        $data = [
            'name'     => 'Customer A-WNY',
            'email'    => 'wny-customer-a-individual@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    /**
     * Login CustomerFWny
     *
     * @return mixed
     */
    public function loginCustomerFWny()
    {
        $data = [
            'name'     => 'Customer F-WNY',
            'email'    => 'wny-customer-f-individual@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }
}
