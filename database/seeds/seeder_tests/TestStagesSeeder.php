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
class TestStagesSeeder extends Seeder
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
                'workflow_type' => 'request',
            ]
        );

        // 2
        DB::table('stages')->insert(
            [
                'name'            => 'Clarification',
                'organization_id' => 2,
                'workflow_type' => 'request',
            ]
        );

        // 3
        DB::table('stages')->insert(
            [
                'name'            => 'Estimation by estimator',
                'organization_id' => 2,
                'workflow_type' => 'request',
            ]
        );

        // 4
        DB::table('stages')->insert(
            [
                'name'            => 'Receiving architectural drawings and specification documents',
                'organization_id' => 2,
                'workflow_type' => 'request',
            ]
        );

        // 5
        DB::table('stages')->insert(
            [
                'name'            => 'Internal Estimation',
                'organization_id' => 2,
                'workflow_type' => 'request',
            ]
        );

        // 6
        DB::table('stages')->insert(
            [
                'name'            => 'Decision',
                'organization_id' => 2,
                'workflow_type' => 'request',
            ]
        );
    }
}
