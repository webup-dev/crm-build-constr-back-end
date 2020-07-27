<?php

use Illuminate\Database\Seeder;
use \App\Models\Requester;

/**
 * Class RequestersSeeder
 */
class TestRequestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Add User Roles
         *
         */
        // 1
        $requester = Requester::create([
            'organization_id' => 2,
            'prefix'          => 'Mrs',
            'first_name'      => 'Evelyn',
            'last_name'       => 'Perkins',
            'suffix'          => 'M.D.',
            'email_work'      => 'Central.Hospital@example.com',
            'email_personal'  => 'evelyn.perkins@example.com',
            'line_1'          => '9278 new road',
            'line_2'          => 'app 3',
            'city'            => 'Kilcoole',
            'state'           => 'OH',
            'zip'             => '93027',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob1'      => '0814540666',
            'phone_mob2'      => '0814540667',
            'phone_fax'       => '0119627523',
            'website'         => 'website1.com',
            'other_source'    => 'Other source 1',
            'note'            => 'Note #1.',
            'created_by_id'   => 6,
            'updated_by_id'   => 10,
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ]);

        // 2
        $requester = Requester::create([
            'organization_id' => 2,
            'prefix'          => 'Mr',
            'first_name'      => 'Eve',
            'last_name'       => 'Lerison',
            'suffix'          => 'M.D.',
            'email_work'      => 'lerison.corporation@example.com',
            'email_personal'  => 'eve.lerison@example.com',
            'line_1'          => '123 river side street',
            'line_2'          => 'app 31',
            'city'            => 'New Lioncity',
            'state'           => 'CA',
            'zip'             => '93027',
            'phone_home'      => '0119627516',
            'phone_work'      => '0119627522',
            'phone_extension' => '123',
            'phone_mob1'      => '0814540666',
            'phone_mob2'      => '0814540667',
            'phone_fax'       => '0119627523',
            'website'         => 'website2.com',
            'other_source'    => 'Other source 2',
            'note'            => 'Note #2.',
            'created_by_id'   => 6,
            'updated_by_id'   => 9,
            'deleted_at'      => null,
            'created_at'      => '2019-12-30 16:54:04',
            'updated_at'      => '2019-12-30 16:54:04'
        ]);
    }
}
