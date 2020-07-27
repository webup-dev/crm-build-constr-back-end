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
                'name' => 'Billboard'
            ]
        );

        // 2
        DB::table('ls_categories')->insert(
            [
                'name' => 'Canvassing'
            ]
        );

        // 3
        DB::table('ls_categories')->insert(
            [
                'name' => 'Company Sign'
            ]
        );

        // 4
        DB::table('ls_categories')->insert(
            [
                'name' => 'Contest'
            ]
        );

        // 5
        DB::table('ls_categories')->insert(
            [
                'name' => 'Direct Mail'
            ]
        );

        // 6
        DB::table('ls_categories')->insert(
            [
                'name' => 'Email Campaign'
            ]
        );

        // 7
        DB::table('ls_categories')->insert(
            [
                'name' => 'Home Show'
            ]
        );

        // 8
        DB::table('ls_categories')->insert(
            [
                'name' => 'Flyer'
            ]
        );

        // 9
        DB::table('ls_categories')->insert(
            [
                'name' => 'Internet'
            ]
        );

        // 10
        DB::table('ls_categories')->insert(
            [
                'name' => 'Kiosk'
            ]
        );

        // 11
        DB::table('ls_categories')->insert(
            [
                'name' => 'Lead Service'
            ]
        );

        // 12
        DB::table('ls_categories')->insert(
            [
                'name' => 'Magazine'
            ]
        );

        // 13
        DB::table('ls_categories')->insert(
            [
                'name' => 'Network/Vendor'
            ]
        );

        // 14
        DB::table('ls_categories')->insert(
            [
                'name' => 'Newsletter'
            ]
        );

        // 15
        DB::table('ls_categories')->insert(
            [
                'name' => 'Newspaper'
            ]
        );

        // 16
        DB::table('ls_categories')->insert(
            [
                'name' => 'Other'
            ]
        );

        // 17
        DB::table('ls_categories')->insert(
            [
                'name' => 'Phone Book'
            ]
        );

        // 18
        DB::table('ls_categories')->insert(
            [
                'name' => 'Previous Customer'
            ]
        );

        // 19
        DB::table('ls_categories')->insert(
            [
                'name' => 'Radio'
            ]
        );

        // 20
        DB::table('ls_categories')->insert(
            [
                'name' => 'Referral'
            ]
        );

        // 21
        DB::table('ls_categories')->insert(
            [
                'name' => 'Self Generated'
            ]
        );

        // 22
        DB::table('ls_categories')->insert(
            [
                'name' => 'Social Media'
            ]
        );

        // 23
        DB::table('ls_categories')->insert(
            [
                'name' => 'Telemarking'
            ]
        );

        // 24
        DB::table('ls_categories')->insert(
            [
                'name' => 'TV'
            ]
        );

        // 25
        DB::table('ls_categories')->insert(
            [
                'name' => 'Walk In'
            ]
        );


    }
}
