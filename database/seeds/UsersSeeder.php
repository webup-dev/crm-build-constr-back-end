<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        DB::table('users')->insert([
            'name'       => 'Super Admin',
            'email'      => 'superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Volodymyr Vadiasov',
            'email'      => 'developer@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Steven Caamano',
            'email'      => 'platform-superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Platform Admin',
            'email'      => 'platform-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-A SuperAdmin',
            'email'      => 'organization-A-superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-A Admin',
            'email'      => 'organization-A-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-A General-Manager',
            'email'      => 'organization-A-generalManager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 General-Manager',
            'email'      => 'organization-1-general-manager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Sales-Manager-1',
            'email'      => 'organization-1-sales-manager-1@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Production-Manager-1',
            'email'      => 'organization-1-production-manager-1@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Administrative-Leader',
            'email'      => 'organization-1-administrative-leader@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Estimator-1',
            'email'      => 'organization-1-estimator-1@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Project-Manager-1',
            'email'      => 'organization-1-project-manager-1@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Organization-1 Administrative-Assistant-1',
            'email'      => 'organization-1-administrative-assistant-1@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Installer-1 Admin',
            'email'      => 'installer-1-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Installer-1 Team-1-Lead',
            'email'      => 'installer-1-team-1-lead@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Customer-1 Individual',
            'email'      => 'customer-1-individual@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Customer-1 Organization',
            'email'      => 'customer-1-organization@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        DB::table('users')->insert([
            'name'       => 'Demo Guest',
            'email'      => 'guest@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);


    }
}
