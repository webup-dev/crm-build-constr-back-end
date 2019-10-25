<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
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
            'name'            => 'Customer A Individual',
            'user_id'         => 17,
            'type'            => 'individual',
            'organization_id' => 1,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);

        DB::table('customers')->insert([
            'name'            => 'Customer B Organization',
            'user_id'         => 18,
            'type'            => 'organization',
            'organization_id' => 1,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);
    }
}
