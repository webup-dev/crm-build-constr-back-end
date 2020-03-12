<?php

namespace App;

use App\TestCase;
use Illuminate\Support\Facades\Schema;

abstract class WnyTestCase extends TestCase
{
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
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('users');
        Schema::dropIfExists('lead_sources');
    }

    private function loginUser($data)
    {
        // Check login
        $response = $this->post('api/auth/login', [
            'email'    => $data['email'],
            'password' => $data['password']
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token        = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [])->assertJson([
            'name'  => $data['name'],
            'email' => $data['email']
        ])->isOk();

        return $token;
    }

    public function loginDeveloper()
    {
        $data = [
            'name'     => 'Volodymyr Vadiasov',
            'email'    => 'developer@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginPlatformSuperAdmin()
    {
        $data = [
            'name'     => 'Steven Caamano',
            'email'    => 'platform-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginOrganizationWNYSuperadmin()
    {
        $data = [
            'name'     => 'WNY SuperAdmin',
            'email'    => 'wny-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginOrganizationWNYGeneralManager()
    {
        $data = [
            'name'     => 'WNY General-Manager',
            'email'    => 'wny-generalManager@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginOrganizationWNYAdmin()
    {
        $data = [
            'name'     => 'WNY Admin',
            'email'    => 'wny-admin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginOrganizationSpringSuperadmin()
    {
        $data = [
            'name'     => 'Spring Superadmin',
            'email'    => 'Spring-superadmin@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginCustomerSpring()
    {
        $data = [
            'name'     => 'Customer B-Spring',
            'email'    => 'spring-customer-organization@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

    public function loginCustomerWny()
    {
        $data = [
            'name'     => 'Customer A-WNY',
            'email'    => 'wny-customer-a-individual@admin.com',
            'password' => '12345678'
        ];

        return $this->loginUser($data);
    }

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
