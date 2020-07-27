<?php

use Illuminate\Database\Seeder;

class CustomerFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('customer_files')->insert([
            'customer_id'   => 1,
            'description'   => 'Description 1',
            'filename'      => 'customer-a-2019-12-01-12-25-35--1.png',
            'owner_user_id' => 16,
            'deleted_at'    => null,
            'created_at'    => '2019-12-01 12:25:35'
        ]);

        // 2
        DB::table('customer_files')->insert([
            'customer_id'   => 1,
            'description'   => 'Description 2',
            'filename'      => 'customer-a-2019-12-01-12-25-36--2.png',
            'owner_user_id' => 1,
            'deleted_at'    => null,
            'created_at'    => '2019-12-01 12:25:36'
        ]);

        // 3
        DB::table('customer_files')->insert([
            'customer_id'   => 1,
            'description'   => 'Description 3',
            'filename'      => 'customer-a-2019-12-01-12-25-37--3.png',
            'owner_user_id' => 16,
            'deleted_at'    => null,
            'created_at'    => '2019-12-01 12:25:37'
        ]);
    }
}
