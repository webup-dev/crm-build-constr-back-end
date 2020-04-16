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
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserRolesSeeder::class);
        $this->call(VcontrollersSeeder::class);
        $this->call(MethodsSeeder::class);
        $this->call(MethodRolesSeeder::class);
        $this->call(OrganizationsSeeder::class);
        $this->call(UserProfilesSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(UserCustomersSeeder::class);
        $this->call(CustomerCommentsSeeder::class);
        $this->call(CustomerFilesSeeder::class);
        $this->call(UserDetailsSeeder::class);
        $this->call(FileSeeder::class);
        $this->call(LsCategoriesSeeder::class);
        $this->call(LeadSourcesSeeder::class);
        $this->call(LeadTypesSeeder::class);
        $this->call(LeadStatusesSeeder::class);
        $this->call(StagesSeeder::class);
        $this->call(WorkflowSeeder::class);
        $this->call(WorkflowStagesSeeder::class);
    }
}
