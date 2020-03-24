<?php

use Illuminate\Database\Seeder;

/**
 * Seed for   ls_categories table
 *
 * @category Seed
 * @package  LsCategories
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class LsCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('ls_categories')->insert(
            [
                'name' => 'Blogging'
            ]
        );

        // 2
        DB::table('ls_categories')->insert(
            [
                'name' => 'Premium Content'
            ]
        );

        // 3
        DB::table('ls_categories')->insert(
            [
                'name' => 'Organic Search'
            ]
        );

        // 4
        DB::table('ls_categories')->insert(
            [
                'name' => 'Email Marketing'
            ]
        );

        // 5
        DB::table('ls_categories')->insert(
            [
                'name' => 'Digital Advertising'
            ]
        );

        // 6
        DB::table('ls_categories')->insert(
            [
                'name' => 'Media Coverage'
            ]
        );

        // 7
        DB::table('ls_categories')->insert(
            [
                'name' => 'Social Media'
            ]
        );

        // 8
        DB::table('ls_categories')->insert(
            [
                'name' => 'Website'
            ]
        );

        // 9
        DB::table('ls_categories')->insert(
            [
                'name' => 'Direct Marketing'
            ]
        );

        // 10
        DB::table('ls_categories')->insert(
            [
                'name' => 'Traditional Advertising'
            ]
        );

        // 11
        DB::table('ls_categories')->insert(
            [
                'name' => 'Sponsorships'
            ]
        );

        // 12
        DB::table('ls_categories')->insert(
            [
                'name' => 'Affiliate / Partner Programs'
            ]
        );

        // 13
        DB::table('ls_categories')->insert(
            [
                'name' => 'Events / Shows'
            ]
        );

        // 14
        DB::table('ls_categories')->insert(
            [
                'name' => 'Inbound Phone Calls'
            ]
        );

        // 15
        DB::table('ls_categories')->insert(
            [
                'name' => 'Outbound Sales'
            ]
        );

        // 16
        DB::table('ls_categories')->insert(
            [
                'name' => 'Referrals'
            ]
        );

        // 17
        DB::table('ls_categories')->insert(
            [
                'name' => 'Speaking Engagements'
            ]
        );

        // 18
        DB::table('ls_categories')->insert(
            [
                'name' => 'Internet'
            ]
        );


    }
}
