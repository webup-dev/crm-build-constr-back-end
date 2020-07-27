<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add User Profiles
         *
         */
        // 1
        DB::table('user_profiles')->insert([
            'user_id'          => 1,
            'first_name'       => 'Volodymyr',
            'last_name'        => 'Vadiasov',
            'title'            => '',
            'department_id'    => 1,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 2
        DB::table('user_profiles')->insert([
            'user_id'          => 2,
            'first_name'       => 'Steven',
            'last_name'        => 'Caamano',
            'title'            => '',
            'department_id'    => 1,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 3
        DB::table('user_profiles')->insert([
            'user_id'          => 3,
            'first_name'       => 'Platform',
            'last_name'        => 'Admin',
            'title'            => '',
            'department_id'    => 1,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => 'Williams 7',
            'address_line_2'   => '',
            'city'             => 'Kyiv',
            'state'            => 'CA',
            'zip'              => '90001',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 4
        DB::table('user_profiles')->insert([
            'user_id'          => 4,
            'first_name'       => 'WNY',
            'last_name'        => 'Superadmin',
            'title'            => '',
            'department_id'    => 2,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 5
        DB::table('user_profiles')->insert([
            'user_id'          => 5,
            'first_name'       => 'WNY',
            'last_name'        => 'Admin',
            'title'            => '',
            'department_id'    => 2,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 6
        DB::table('user_profiles')->insert([
            'user_id'          => 6,
            'first_name'       => 'WNY',
            'last_name'        => 'General-Manager',
            'title'            => '',
            'department_id'    => 2,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 7
        DB::table('user_profiles')->insert([
            'user_id'          => 11,
            'first_name'       => 'WNY',
            'last_name'        => 'Estimator',
            'title'            => '',
            'department_id'    => 5,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 8
        DB::table('user_profiles')->insert([
            'user_id'          => 12,
            'first_name'       => 'WNY',
            'last_name'        => 'Project-Manager',
            'title'            => '',
            'department_id'    => 7,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);

        // 9
        DB::table('user_profiles')->insert([
            'user_id'          => 21,
            'first_name'       => 'Spring',
            'last_name'        => 'Superadmin',
            'title'            => '',
            'department_id'    => 9,
            'phone_home'       => '',
            'phone_work'       => '',
            'phone_extension'  => '',
            'phone_mob'        => '',
            'email_personal'   => '',
            'email_work'       => '',
            'address_line_1'   => '1504 Scottsville Rd',
            'address_line_2'   => '',
            'city'             => 'New York',
            'state'            => 'NY',
            'zip'              => '14623',
            'status'           => 'active',
            'start_date'       => null,
            'termination_date' => null,
            'deleted_at'       => null,
            'created_at'       => now()
        ]);
    }
}
