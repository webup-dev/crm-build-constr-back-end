<?php

use Illuminate\Database\Seeder;

/**
 * Seeder for tests
 *
 * @category Seed
 * @package  WNY
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TestOrganizationsSeeder::class);
        $this->call(TestStagesSeeder::class);
        $this->call(TestRolesSeeder::class);
        $this->call(TestUsersSeeder::class);
        $this->call(TestUserProfilesSeeder::class);
        $this->call(TestUserRolesSeeder::class);
        $this->call(TestCustomersSeeder::class);
        $this->call(TestUserCustomersSeeder::class);
        $this->call(TestCustomerCommentsSeeder::class);
        $this->call(TestCustomerFilesSeeder::class);
        $this->call(TestUserDetailsSeeder::class);
        $this->call(TestFileSeeder::class);
        $this->call(TestLsCategoriesSeeder::class);
        $this->call(TestLeadSourcesSeeder::class);
        $this->call(TestLeadTypesSeeder::class);
        $this->call(TestLeadStatusesSeeder::class);
        $this->call(TestWorkflowSeeder::class);
        $this->call(TestWorkflowStagesSeeder::class);
        $this->call(TestRequestersSeeder::class);
    }
}
