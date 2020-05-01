<?php

use Illuminate\Database\Seeder;
use \App\Models\Lead;

/**
 * Class RequestersSeeder
 */
class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Add Leads
         *
         */
        // 1
        $requester = Lead::create([
            'name'                     => 'Test 1',
            'organization_id'          => 2,
            'due_date'                 => '2020-04-30 13:12:54',
            'anticipated_project_date' => '2020-05-30 13:12:54',
            'lead_type_id'             => 1,
            'lead_status_id'           => 1,
            'declined_reason_other'    => null,
            'lead_source_id'           => 1,
            'stage_id'                 => 1,
            'line_1'                   => '9278 new road',
            'line_2'                   => 'app 3',
            'city'                     => 'Kilcoole',
            'state'                    => 'OH',
            'zip'                      => '93027',
            'requester_id'             => 1,
            'note'                     => 'Note #1.',
            'lead_owner_id'            => 6,
            'created_by_id'            => 6,
            'updated_by_id'            => 10,
            'deleted_at'               => null,
            'created_at'               => '2019-12-30 16:54:04',
            'updated_at'               => '2019-12-30 16:54:04'
        ]);

        // 2
        $requester = Lead::create([
            'name'                     => 'Test 2',
            'organization_id'          => 2,
            'due_date'                 => '2020-04-28 13:12:54',
            'anticipated_project_date' => '2020-05-28 13:12:54',
            'lead_type_id'             => 2,
            'lead_status_id'           => 2,
            'declined_reason_other'    => null,
            'lead_source_id'           => 2,
            'stage_id'                 => 2,
            'line_1'                   => '1 new road',
            'line_2'                   => 'app 23',
            'city'                     => 'Box',
            'state'                    => 'CA',
            'zip'                      => '22222',
            'requester_id'             => 2,
            'note'                     => 'Note #2.',
            'lead_owner_id'            => 6,
            'created_by_id'            => 6,
            'updated_by_id'            => 10,
            'deleted_at'               => null,
            'created_at'               => '2019-12-30 16:54:04',
            'updated_at'               => '2019-12-30 16:54:04'
        ]);
    }
}
