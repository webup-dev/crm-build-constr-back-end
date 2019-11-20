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

        // 1
        DB::table('users')->insert([
            'name'       => 'Volodymyr Vadiasov',
            'email'      => 'developer@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 2
        DB::table('users')->insert([
            'name'       => 'Steven Caamano',
            'email'      => 'platform-superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 3
        DB::table('users')->insert([
            'name'       => 'Platform Admin',
            'email'      => 'platform-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 4
        DB::table('users')->insert([
            'name'       => 'WNY SuperAdmin',
            'email'      => 'wny-superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 5
        DB::table('users')->insert([
            'name'       => 'WNY Admin',
            'email'      => 'wny-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 6
        DB::table('users')->insert([
            'name'       => 'WNY General-Manager',
            'email'      => 'wny-generalManager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 7
        DB::table('users')->insert([
            'name'       => 'Spring General-Manager',
            'email'      => 'Spring-general-manager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 8
        DB::table('users')->insert([
            'name'       => 'WNY Sales-Manager',
            'email'      => 'wny-sales-manager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 9
        DB::table('users')->insert([
            'name'       => 'WNY Production-Manager',
            'email'      => 'wny-production-manager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 10
        DB::table('users')->insert([
            'name'       => 'WNY Administrative-Leader',
            'email'      => 'wny-administrative-leader@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 11
        DB::table('users')->insert([
            'name'       => 'WNY Estimator',
            'email'      => 'wny-estimator@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 12
        DB::table('users')->insert([
            'name'       => 'WNY Project-Manager',
            'email'      => 'wny-project-manager@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 13
        DB::table('users')->insert([
            'name'       => 'WNY Administrative-Assistant',
            'email'      => 'wny-administrative-assistant@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 14
        DB::table('users')->insert([
            'name'       => 'WNY Installer-Admin',
            'email'      => 'wny-installer-admin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 15
        DB::table('users')->insert([
            'name'       => 'WNY Installer-Team-Lead',
            'email'      => 'wny-installer-team-lead@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 16
        DB::table('users')->insert([
            'name'       => 'Customer A-WNY',
            'email'      => 'wny-customer-a-individual@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 17
        DB::table('users')->insert([
            'name'       => 'Customer B-Spring',
            'email'      => 'spring-customer-organization@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 18
        DB::table('users')->insert([
            'name'       => 'Demo Guest',
            'email'      => 'guest@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 19
        DB::table('users')->insert([
            'name'       => 'Customer C-WNY',
            'email'      => 'wny-customer-c-individual@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 20
        DB::table('users')->insert([
            'name'       => 'Customer D-WNY',
            'email'      => 'wny-customer-d-individual@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

        // 21
        DB::table('users')->insert([
            'name'       => 'Spring Superadmin',
            'email'      => 'Spring-superadmin@admin.com',
            'password'   => bcrypt('12345678'),
            'created_at' => now()
        ]);

    }
}
