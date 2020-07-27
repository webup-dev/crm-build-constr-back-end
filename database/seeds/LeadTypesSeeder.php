<?php

use Illuminate\Database\Seeder;

/**
 * Seed for lead_types table
 *
 * @category Seed
 * @package  LeadTypes
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class LeadTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('lead_types')->insert(
            [
                'name'            => 'Steep Slope Roofing',
                'organization_id' => 2
            ]
        );

        // 2
        DB::table('lead_types')->insert(
            [
                'name'            => 'Siding',
                'organization_id' => 2
            ]
        );

        // 3
        DB::table('lead_types')->insert(
            [
                'name'            => 'Low Slope Roofing',
                'organization_id' => 2
            ]
        );

        // 4
        DB::table('lead_types')->insert(
            [
                'name'            => 'Windows',
                'organization_id' => 2
            ]
        );

        // 5
        DB::table('lead_types')->insert(
            [
                'name'            => 'Doors',
                'organization_id' => 2
            ]
        );

        // 6
        DB::table('lead_types')->insert(
            [
                'name'            => 'Weatherization',
                'organization_id' => 2
            ]
        );

        // 7
        DB::table('lead_types')->insert(
            [
                'name'            => 'Carpentry',
                'organization_id' => 2
            ]
        );

        // 8
        DB::table('lead_types')->insert(
            [
                'name'            => 'Gutters',
                'organization_id' => 2
            ]
        );

        // 9
        DB::table('lead_types')->insert(
            [
                'name'            => 'Steep Slope Roofing',
                'organization_id' => 9
            ]
        );

        // 10
        DB::table('lead_types')->insert(
            [
                'name'            => 'Siding',
                'organization_id' => 9
            ]
        );

        // 11
        DB::table('lead_types')->insert(
            [
                'name'            => 'Low Slope Roofing',
                'organization_id' => 9
            ]
        );

        // 12
        DB::table('lead_types')->insert(
            [
                'name'            => 'Windows',
                'organization_id' => 9
            ]
        );

        // 13
        DB::table('lead_types')->insert(
            [
                'name'            => 'Doors',
                'organization_id' => 9
            ]
        );

        // 14
        DB::table('lead_types')->insert(
            [
                'name'            => 'Weatherization',
                'organization_id' => 9
            ]
        );

        // 15
        DB::table('lead_types')->insert(
            [
                'name'            => 'Carpentry',
                'organization_id' => 9
            ]
        );

        // 16
        DB::table('lead_types')->insert(
            [
                'name'            => 'Gutters',
                'organization_id' => 9
            ]
        );
    }
}
