<?php

use App\Models\User_role;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Add User Roles
         *
         */
        $userRole = User_role::create([
            'user_id' => '1',
            'role_id' => '1'
        ]);

        $userRole = User_role::create([
            'user_id' => '2',
            'role_id' => '1'
        ]);

        $userRole = User_role::create([
            'user_id' => '3',
            'role_id' => '17'
        ]);
    }
}
