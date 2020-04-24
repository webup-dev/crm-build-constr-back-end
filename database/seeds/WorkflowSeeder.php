<?php

use Illuminate\Database\Seeder;

/**
 * Seed for workflows table
 *
 * @category Seed
 * @package  Workflows
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Seed
 */
class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('workflows')->insert(
            [
                'name'            => 'Simple',
                'organization_id' => 2,
                'workflow_type'   => 'Request',
            ]
        );

        // 2
        DB::table('workflows')->insert(
            [
                'name'            => 'Full',
                'organization_id' => 2,
                'workflow_type'   => 'Request',
            ]
        );
    }
}
