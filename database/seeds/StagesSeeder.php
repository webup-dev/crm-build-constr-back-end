<?php

use Illuminate\Database\Seeder;

/**
 * Seed for stages table
 *
 * @category Seed
 * @package  Stages
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class StagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('stages')->insert(
            [
                'name'            => 'Documenting',
                'organization_id' => 2,
                'workflow_type' => 'Request',
            ]
        );

        // 2
        DB::table('stages')->insert(
            [
                'name'            => 'Review of plans and specifications',
                'organization_id' => 2,
                'workflow_type' => 'Request',
            ]
        );

        // 3
        DB::table('stages')->insert(
            [
                'name'            => 'Clarification',
                'organization_id' => 2,
                'workflow_type' => 'Request',
            ]
        );

        // 4
        DB::table('stages')->insert(
            [
                'name'            => 'Under review',
                'organization_id' => 2,
                'workflow_type' => 'Request',
            ]
        );

        // 5
        DB::table('stages')->insert(
            [
                'name'            => 'Determination',
                'organization_id' => 2,
                'workflow_type' => 'Request',
            ]
        );
    }
}
