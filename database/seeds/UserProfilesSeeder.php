<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserProfilesSeeder extends Seeder
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
            'deleted_at'       => null
        ]);
    }
}
