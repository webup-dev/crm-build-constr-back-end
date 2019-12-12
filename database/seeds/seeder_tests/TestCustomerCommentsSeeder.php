<?php

use Illuminate\Database\Seeder;

class TestCustomerCommentsSeeder extends Seeder
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
            'level'       => 1,
            'comment'     => "Comment #1 by Customer A-WNY",
            'deleted_at'  => null,
            'created_at'  => '2019-12-01 12:25:35'
        ]);

        // 2
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 16,
            'parent_id'   => null,
            'level'       => 1,
            'comment'     => "Comment #2 by Customer A-WNY",
            'deleted_at'  => null,
            'created_at'  => '2019-12-01 13:25:35'
        ]);

        // 3
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 1,
            'parent_id'   => 1,
            'level'       => 2,
            'comment'     => "Comment #3 by developer",
            'deleted_at'  => null,
            'created_at'  => '2019-12-01 14:25:35'
        ]);

        // 4
        DB::table('customer_comments')->insert([
            'customer_id' => 1,
            'author_id'   => 16,
            'parent_id'   => 3,
            'level'       => 3,
            'comment'     => "Comment #4 by Customer A-WNY",
            'deleted_at'  => null,
            'created_at'  => '2019-12-01 15:25:35'
        ]);
    }
}
