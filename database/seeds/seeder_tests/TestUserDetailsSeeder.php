<?php

use Illuminate\Database\Seeder;
use \App\Models\UserDetail;

class TestUserDetailsSeeder extends Seeder
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
        $userDetail = UserDetail::create([
            'user_id'=>16,
            'prefix'=>'Mrs',
            'first_name'=>'Evelyn',
            'last_name'=>'Perkins',
            'suffix'=>'M.D.',
            'work_title'=>'Central Hospital',
            'work_department'=>'Surgery',
            'work_role'=>'Surgeon',
            'phone_home'=>'0119627516',
            'phone_work'=>'0119627522',
            'phone_extension'=>'123',
            'phone_mob'=>'0814540666',
            'phone_fax'=>'0119627523',
            'email_work'=>'Central.Hospital@example.com',
            'email_personal'=>'evelyn.perkins@example.com',
            'line_1'=>'9278 new road',
            'line_2'=>'app 3',
            'city'=>'Kilcoole',
            'state'=>'OH',
            'zip'=>'93027',
            'status'=>'active',
            'deleted_at'=>null,
            'created_at' => '2019-12-30 16:54:04',
            'updated_at' => '2019-12-30 16:54:04'
        ]);

        // 2
        $userDetail = UserDetail::create([
            'user_id'=>17,
            'prefix'=>'Mr',
            'first_name'=>'Lue',
            'last_name'=>'Barton',
            'suffix'=>'Ph.D.',
            'work_title'=>'Bell Laboratory',
            'work_department'=>'Light Storing Crystals',
            'work_role'=>'Senior engineer',
            'phone_home'=>'8514824903',
            'phone_work'=>'8514824955',
            'phone_extension'=>'45',
            'phone_mob'=>'8514824111',
            'phone_fax'=>'8514824956',
            'email_work'=>'Bell.Laboratory@example.com',
            'email_personal'=>'lue.barton@example.com',
            'line_1'=>'1345 Andreane Harbor',
            'line_2'=>'app 4',
            'city'=>'Schowalterside',
            'state'=>'MI',
            'zip'=>'93028',
            'status'=>'active',
            'deleted_at'=>null,
            'created_at' => '2019-12-30 16:54:04',
            'updated_at' => '2019-12-30 16:54:04'
        ]);

        // 3
        $userDetail = UserDetail::create([
            'user_id'=>19,
            'prefix'=>'Mr',
            'first_name'=>'Pat',
            'last_name'=>'Ferry',
            'suffix'=>'Esq.',
            'work_title'=>'retired',
            'work_department'=>'',
            'work_role'=>'',
            'phone_home'=>'2238057917',
            'phone_work'=>'',
            'phone_extension'=>'',
            'phone_mob'=>'8514824333',
            'phone_fax'=>'',
            'email_work'=>'',
            'email_personal'=>'pat.ferry@example.com',
            'line_1'=>'92571 Toy Gateway',
            'line_2'=>'app 5',
            'city'=>'New Geoport',
            'state'=>'IO',
            'zip'=>'93033',
            'status'=>'active',
            'deleted_at'=>null,
            'created_at' => '2019-12-30 16:54:04',
            'updated_at' => '2019-12-30 16:54:04'
        ]);

        // 4
        $userDetail = UserDetail::create([
            'user_id'=>20,
            'prefix'=>'Ms',
            'first_name'=>'Tod',
            'last_name'=>'Hane',
            'suffix'=>'',
            'work_title'=>'retired',
            'work_department'=>'',
            'work_role'=>'',
            'phone_home'=>'3750441879',
            'phone_work'=>'',
            'phone_extension'=>'',
            'phone_mob'=>'8514824444',
            'phone_fax'=>'',
            'email_work'=>'',
            'email_personal'=>'tod.hane@example.com',
            'line_1'=>'3122 Zieme Circle',
            'line_2'=>'app 6',
            'city'=>'North Katelyn',
            'state'=>'HA',
            'zip'=>'93044',
            'status'=>'active',
            'deleted_at'=>null,
            'created_at' => '2019-12-30 16:54:04',
            'updated_at' => '2019-12-30 16:54:04'
        ]);

        // 5
        $userDetail = UserDetail::create([
            'user_id'=>22,
            'prefix'=>'Mrs',
            'first_name'=>'Tyra',
            'last_name'=>'Bechtelar',
            'suffix'=>'',
            'work_title'=>'housewife',
            'work_department'=>'',
            'work_role'=>'',
            'phone_home'=>'2644570291',
            'phone_work'=>'',
            'phone_extension'=>'',
            'phone_mob'=>'8514825555',
            'phone_fax'=>'',
            'email_work'=>'',
            'email_personal'=>'tyra.bechtelar@example.com',
            'line_1'=>'6566 Frankie Lights',
            'line_2'=>'app 7',
            'city'=>'Port Savannahburgh',
            'state'=>'AL',
            'zip'=>'93055',
            'status'=>'active',
            'deleted_at'=>null,
            'created_at' => '2019-12-30 16:54:04',
            'updated_at' => '2019-12-30 16:54:04'
        ]);
    }
}
