<?php

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Organizations
         *
         */
        // 1
        if (Organization::where('name', '=', 'Platform')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Platform',
                'level'      => 0,
                'order'      => 1,
                'parent_id'  => null,
                'created_at' => now()
            ]);
        }

        // 2
        if (Organization::where('name', '=', 'Western New York Exteriors, LLC.')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Western New York Exteriors, LLC.',
                'level'      => 1,
                'order'      => 1,
                'parent_id'  => 1,
                'created_at' => now()
            ]);
        }

        // 3
        if (Organization::where('name', '=', 'Administration')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Administration',
                'level'      => 2,
                'order'      => 1,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 4
        if (Organization::where('name', '=', 'Accounting')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Accounting',
                'level'      => 2,
                'order'      => 2,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 5
        if (Organization::where('name', '=', 'Sales')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Sales',
                'level'      => 2,
                'order'      => 3,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 6
        if (Organization::where('name', '=', 'Service')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Service',
                'level'      => 2,
                'order'      => 4,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 7
        if (Organization::where('name', '=', 'Production')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Production',
                'level'      => 2,
                'order'      => 5,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 8
        if (Organization::where('name', '=', 'Web Support')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Web Support',
                'level'      => 2,
                'order'      => 6,
                'parent_id'  => 2,
                'created_at' => now()
            ]);
        }

        // 9
        if (Organization::where('name', '=', 'Spring Sheet Metal & Roofing Co.')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Spring Sheet Metal & Roofing Co.',
                'level'      => 1,
                'order'      => 2,
                'parent_id'  => 1,
                'created_at' => now()
            ]);
        }

        // 10
        $organization = Organization::create([
            'name'       => 'Administration',
            'level'      => 2,
            'order'      => 1,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 11
        $organization = Organization::create([
            'name'       => 'Accounting',
            'level'      => 2,
            'order'      => 2,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 12
        $organization = Organization::create([
            'name'       => 'Sales',
            'level'      => 2,
            'order'      => 3,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 13
        $organization = Organization::create([
            'name'       => 'Service',
            'level'      => 2,
            'order'      => 4,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 14
        $organization = Organization::create([
            'name'       => 'Production',
            'level'      => 2,
            'order'      => 5,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 15
        $organization = Organization::create([
            'name'       => 'Web Support',
            'level'      => 2,
            'order'      => 6,
            'parent_id'  => 9,
            'created_at' => now()
        ]);

        // 16
        $organization = Organization::create([
            'name'       => 'Administrative assistant',
            'level'      => 3,
            'order'      => 1,
            'parent_id'  => 10,
            'created_at' => now()
        ]);

        // 17
        $organization = Organization::create([
            'name'       => 'Back-end developer',
            'level'      => 3,
            'order'      => 1,
            'parent_id'  => 8,
            'created_at' => now()
        ]);
    }
}
