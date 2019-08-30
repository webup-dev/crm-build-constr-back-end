<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Users
         *
         */
        if (User::where('email', '=', 'superadmin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Super Admin',
                'email'    => 'superadmin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'admin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Admin Admin',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'platform-superadmin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Platform SuperAdmin',
                'email'    => 'platform-superadmin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'platform-admin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Platform Admin',
                'email'    => 'platform-admin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-superadmin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 SuperAdmin',
                'email'    => 'organization-1-superadmin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-general-manager@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 General-Manager',
                'email'    => 'organization-1-general-manager@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-sales-manager-1@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Sales-Manager-1',
                'email'    => 'organization-1-sales-manager-1@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-production-manager-1@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Production-Manager-1',
                'email'    => 'organization-1-production-manager-1@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-administrative-leader@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Administrative-Leader',
                'email'    => 'organization-1-administrative-leader@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-estimator-1@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Estimator-1',
                'email'    => 'organization-1-estimator-1@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-project-manager-1@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Project-Manager-1',
                'email'    => 'organization-1-project-manager-1@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'organization-1-administrative-assistant-1@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Organization-1 Administrative-Assistant-1',
                'email'    => 'organization-1-administrative-assistant-1@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'installer-1-admin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Installer-1 Admin',
                'email'    => 'installer-1-admin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'installer-1-team-1-lead@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Installer-1 Team-1-Lead',
                'email'    => 'installer-1-team-1-lead@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'customer-1-individual@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Customer-1 Individual',
                'email'    => 'customer-1-individual@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }

        if (User::where('email', '=', 'customer-1-organization@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Customer-1 Organization',
                'email'    => 'customer-1-organization@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }


    }
}
