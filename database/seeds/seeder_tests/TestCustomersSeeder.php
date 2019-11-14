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
        DB::table('customers')->insert([
            'name'            => 'Customer A-WNY',
            'user_id'         => 16,
            'type'            => 'individual',
            'organization_id' => 2,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);

        DB::table('customers')->insert([
            'name'            => 'Customer B-Spring',
            'user_id'         => 17,
            'type'            => 'organization',
            'organization_id' => 9,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);

        DB::table('customers')->insert([
            'name'            => 'Customer B-WNY',
            'user_id'         => 19,
            'type'            => 'individual',
            'organization_id' => 2,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);
    }
}
