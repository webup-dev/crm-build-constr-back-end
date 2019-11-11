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
            'name'            => 'Customer A-WNY',
            'user_id'         => 16,
            'type'            => 'individual',
            'organization_id' => 1,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);

        DB::table('customers')->insert([
            'name'            => 'Customer B-Spring',
            'user_id'         => 17,
            'type'            => 'organization',
            'organization_id' => 1,
            'deleted_at'      => null,
            'created_at'      => now()
        ]);

        /*
         * Add Customer Individual account
         */
        DB::table('customer_individuals')->insert([
            'customer_id'            => 1,
            'email'                  => 'wny-customer-individual@admin.com',
            'password'               => bcrypt('12345678'),
            'billing_address_line_1' => 'First av., 15',
            'billing_address_line_2' => 'app. 111',
            'billing_city'           => 'New York',
            'billing_state'          => 'NY',
            'zip'                    => '30101',
            'note'                   => 'Simple note.',
            'created_by_id'          => 1,
            'updated_by_id'          => null,
            'deleted_at'             => null,
            'created_at'             => now()
        ]);
    }
}
