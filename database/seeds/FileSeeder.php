<?php

use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add User Files
         *
         */
        // 1
        DB::table('files')->insert([
            'owner_object_type' => 'customer',
            'owner_object_id'   => 1,
            'description'       => 'Test file',
            'filename'          => 'customer_1_test-file-1.jpg',
            'owner_user_id'     => 16
        ]);

        // 2
        DB::table('files')->insert([
            'owner_object_type' => 'customer',
            'owner_object_id'   => 1,
            'description'       => 'Test file',
            'filename'          => 'customer_1_test-file-2.jpg',
            'owner_user_id'     => 16
        ]);

    }
}
