<?php

use Illuminate\Database\Seeder;

class TestCustomersSeeder extends Seeder
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
        DB::table('customers')->insert([
            'name'                   => 'Customer A-WNY',
            'type'                   => 'Individual(s)',
            'city'                   => 'New York',
            'line_1'                 => 'Line 1',
            'organization_id'        => 2,
            'customer_owner_user_id' => 4,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);

        // 2
        DB::table('customers')->insert([
            'name'                   => 'Customer B-Spring',
            'type'                   => 'Business',
            'city'                   => 'New York',
            'line_1'                 => 'Line 1',
            'organization_id'        => 9,
            'customer_owner_user_id' => 21,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);

        // 3
        DB::table('customers')->insert([
            'name'                   => 'Customer C-WNY',
            'type'                   => 'Individual(s)',
            'city'                   => 'New York',
            'line_1'                 => 'Line 1',
            'organization_id'        => 2,
            'customer_owner_user_id' => 4,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);

        // 4
        DB::table('customers')->insert([
            'name'                   => 'Customer D-WNY',
            'type'                   => 'Individual(s)',
            'city'                   => 'New York',
            'line_1'                 => 'Line 1',
            'organization_id'        => 2,
            'customer_owner_user_id' => 4,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);

        // 5
        DB::table('customers')->insert([
            'name'                   => 'Customer E-WNY',
            'type'                   => 'Individual(s)',
            'city'                   => 'New York',
            'line_1'                 => 'Line 1',
            'organization_id'        => 2,
            'customer_owner_user_id' => 4,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);
    }
}
