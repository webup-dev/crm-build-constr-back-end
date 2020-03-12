<?php

use Illuminate\Database\Seeder;

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
         $this->call(LeadSourcesSeeder::class);
    }
}
