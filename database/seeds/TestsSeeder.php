<?php

use Illuminate\Database\Seeder;

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
         $this->call(TestRolesSeeder::class);
         $this->call(TestUsersSeeder::class);
         $this->call(TestUserProfilesSeeder::class);
         $this->call(TestUserRolesSeeder::class);
         $this->call(TestCustomersSeeder::class);
         $this->call(TestUserCustomersSeeder::class);
         $this->call(TestCustomerCommentsSeeder::class);
         $this->call(TestCustomerFilesSeeder::class);
         $this->call(TestUserDetailsSeeder::class);
    }
}
