<?php

use Illuminate\Database\Seeder;

/**
 * Seed for lead_sources table
 *
 * @category Seed
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class LeadSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('lead_sources')->insert(
            [
                'name' => 'Blogging'
            ]
        );

        // 2
        DB::table('lead_sources')->insert(
            [
                'name' => 'Premium Content'
            ]
        );

        // 3
        DB::table('lead_sources')->insert(
            [
                'name' => 'Organic Search'
            ]
        );

        // 4
        DB::table('lead_sources')->insert(
            [
                'name' => 'Email Marketing'
            ]
        );

        // 5
        DB::table('lead_sources')->insert(
            [
                'name' => 'Digital Advertising'
            ]
        );

        // 6
        DB::table('lead_sources')->insert(
            [
                'name' => 'Media Coverage'
            ]
        );

        // 7
        DB::table('lead_sources')->insert(
            [
                'name' => 'Social Media'
            ]
        );

        // 8
        DB::table('lead_sources')->insert(
            [
                'name' => 'Website'
            ]
        );

        // 9
        DB::table('lead_sources')->insert(
            [
                'name' => 'Direct Marketing'
            ]
        );

        // 10
        DB::table('lead_sources')->insert(
            [
                'name' => 'Traditional Advertising'
            ]
        );

        // 11
        DB::table('lead_sources')->insert(
            [
                'name' => 'Sponsorships'
            ]
        );

        // 12
        DB::table('lead_sources')->insert(
            [
                'name' => 'Affiliate / Partner Programs'
            ]
        );

        // 13
        DB::table('lead_sources')->insert(
            [
                'name' => 'Events / Shows'
            ]
        );

        // 14
        DB::table('lead_sources')->insert(
            [
                'name' => 'Inbound Phone Calls'
            ]
        );

        // 15
        DB::table('lead_sources')->insert(
            [
                'name' => 'Outbound Sales'
            ]
        );

        // 16
        DB::table('lead_sources')->insert(
            [
                'name' => 'Referrals'
            ]
        );

        // 17
        DB::table('lead_sources')->insert(
            [
                'name' => 'Speaking Engagements'
            ]
        );

        // 18
        DB::table('lead_sources')->insert(
            [
                'name' => 'Traditional / Offline Networking'
            ]
        );


    }
}
