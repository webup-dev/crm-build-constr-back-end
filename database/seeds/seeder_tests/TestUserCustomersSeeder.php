<?php

use App\Models\UserCustomer;
use Illuminate\Database\Seeder;

class TestUserCustomersSeeder extends Seeder
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
        $userCustomer = UserCustomer::create([
            'user_id'     => '16',
            'customer_id' => '1',
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        $userCustomer = UserCustomer::create([
            'user_id'     => '17',
            'customer_id' => '2',
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        $userCustomer = UserCustomer::create([
            'user_id'     => '19',
            'customer_id' => '3',
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        $userCustomer = UserCustomer::create([
            'user_id'     => '20',
            'customer_id' => '4',
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        // 5
        $userCustomer = UserCustomer::create([
            'user_id'     => '22',
            'customer_id' => '1',
            'deleted_at'  => null,
            'created_at'  => now()
        ]);
    }
}
