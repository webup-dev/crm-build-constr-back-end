<?php

use Illuminate\Database\Seeder;

/**
 * Seed for lead_statuses table
 *
 * @category Seed
 * @package  LeadStatuses
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class LeadStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Unqualified',
                'organization_id' => 2
            ]
        );

        // 2
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Qualified & Accepted',
                'organization_id' => 2
            ]
        );

        // 3
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Declined',
                'organization_id' => 2
            ]
        );

        // 4
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Not within company scope',
                'organization_id' => 2,
                'parent_id' => 3
            ]
        );

        // 5
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Unable to perform work',
                'organization_id' => 2,
                'parent_id' => 3
            ]
        );

        // 6
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Test',
                'organization_id' => 2,
                'parent_id' => 3
            ]
        );

        // 7
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Duplicate',
                'organization_id' => 2,
                'parent_id' => 3
            ]
        );

        // 8
        DB::table('lead_statuses')->insert(
            [
                'name'            => 'Other',
                'organization_id' => 2,
                'parent_id' => 3
            ]
        );
    }
}
