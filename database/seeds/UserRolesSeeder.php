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
            'user_id'    => '1',
            'role_id'    => '1',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '2',
            'role_id'    => '2',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '3',
            'role_id'    => '3',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '4',
            'role_id'    => '4',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '5',
            'role_id'    => '5',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '6',
            'role_id'    => '6',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '7',
            'role_id'    => '6',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '16',
            'role_id'    => '15',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '17',
            'role_id'    => '16',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '18',
            'role_id'    => '17',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '11',
            'role_id'    => '10',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '12',
            'role_id'    => '8',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '19',
            'role_id'    => '15',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '20',
            'role_id'    => '15',
            'created_at' => now()
        ]);

        $userRole = User_role::create([
            'user_id'    => '21',
            'role_id'    => '4',
            'created_at' => now()
        ]);
    }
}
