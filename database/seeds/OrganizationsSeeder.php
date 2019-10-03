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
        if (Organization::where('name', '=', 'Central Office')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Central Office',
                'order'     => '1',
                'parent_id'     => null,
            ]);
        }

        if (Organization::where('name', '=', 'Department 1')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Department 1',
                'order'     => '2',
                'parent_id'     => '1',
            ]);
        }

        if (Organization::where('name', '=', 'Department 2')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Department 2',
                'order'     => '3',
                'parent_id'     => '1',
            ]);
        }

        if (Organization::where('name', '=', 'Branch Office')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Branch Office',
                'order'     => '4',
                'parent_id'     => '1',
            ]);
        }

        if (Organization::where('name', '=', 'Branch Department 1')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Branch Department 1',
                'order'     => '5',
                'parent_id'     => '4',
            ]);
        }

        if (Organization::where('name', '=', 'Branch Department 2')->first() === null) {
            $organization = Organization::create([
                'name'     => 'Branch Department 2',
                'order'     => '6',
                'parent_id'     => '4',
            ]);
        }
    }
}
