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
class TestLeadSourcesSeeder extends Seeder
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
                'name'            => 'Website - CertainTeed',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'active'
            ]
        );

        // 2
        DB::table('lead_sources')->insert(
            [
                'name'            => 'Website - GAF',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'active'
            ]
        );

        // 3
        DB::table('lead_sources')->insert(
            [
                'name'            => 'Website - HPwES',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'active'
            ]
        );

        // 4
        DB::table('lead_sources')->insert(
            [
                'name'            => 'Website - IKO',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'active'
            ]
        );

        // 5
        DB::table('lead_sources')->insert(
            [
                'name'            => 'Website - Mastic',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'inactive'
            ]
        );

        // 6
        DB::table('lead_sources')->insert(
            [
                'name'            => 'Website - Owens Corning',
                'category_id'     => 18,
                'organization_id' => 2,
                'status'          => 'inactive',
                'deleted_at'      => now()
            ]
        );

    }
}
