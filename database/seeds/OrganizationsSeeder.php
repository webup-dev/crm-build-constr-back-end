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
        if (Organization::where('name', '=', 'Western New York Exteriors, LLC.')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Western New York Exteriors, LLC.',
                'order'      => '1',
                'parent_id'  => null,
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Administration')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Administration',
                'order'      => '2',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Accounting')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Accounting',
                'order'      => '3',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Sales')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Sales',
                'order'      => '4',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Service')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Service',
                'order'      => '5',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Production')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Production',
                'order'      => '6',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Web Support')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Web Support',
                'order'      => '7',
                'parent_id'  => '1',
                'created_at' => now()
            ]);
        }

        if (Organization::where('name', '=', 'Spring Sheet Metal & Roofing Co.')->first() === null) {
            $organization = Organization::create([
                'name'       => 'Spring Sheet Metal & Roofing Co.',
                'order'      => '1',
                'parent_id'  => null,
                'created_at' => now()
            ]);
        }

        $organization = Organization::create([
            'name'       => 'Administration',
            'order'      => '2',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Accounting',
            'order'      => '3',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Sales',
            'order'      => '4',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Service',
            'order'      => '5',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Production',
            'order'      => '6',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Web Support',
            'order'      => '7',
            'parent_id'  => '8',
            'created_at' => now()
        ]);

        $organization = Organization::create([
            'name'       => 'Administrative assistant',
            'order'      => '1',
            'parent_id'  => '9',
            'created_at' => now()
        ]);
    }
}
