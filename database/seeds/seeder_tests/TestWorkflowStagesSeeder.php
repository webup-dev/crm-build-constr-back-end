<?php

use Illuminate\Database\Seeder;

/**
 * Seed for workflow_stages table
 *
 * @category Seed
 * @package  Workflows
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class TestWorkflowStagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 1,
                'stage_id'    => 1,
                'order'       => 1,
            ]
        );

        // 2
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 1,
                'stage_id'    => 5,
                'order'       => 2,
            ]
        );

        // 3
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 2,
                'stage_id'    => 1,
                'order'       => 1,
            ]
        );

        // 4
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 2,
                'stage_id'    => 2,
                'order'       => 2,
            ]
        );

        // 5
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 2,
                'stage_id'    => 3,
                'order'       => 3,
            ]
        );

        // 6
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 2,
                'stage_id'    => 4,
                'order'       => 4,
            ]
        );

        // 6
        DB::table('workflow_stages')->insert(
            [
                'workflow_id' => 2,
                'stage_id'    => 5,
                'order'       => 5,
            ]
        );
    }
}
