<?php

use Illuminate\Database\Seeder;

class CustomerCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 16,
            'parent_id'   => null,
            'comment'     => "Comment #1 by Customer A-WNY",
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        // 2
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 1,
            'parent_id'   => 1,
            'comment'     => "Comment #2 by developer",
            'deleted_at'  => null,
            'created_at'  => now()
        ]);

        // 3
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 16,
            'parent_id'   => 2,
            'comment'     => "Comment #3 by Customer A-WNY",
            'deleted_at'  => null,
            'created_at'  => now()
        ]);
    }
}
