<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Roles
         *
         */

        // 1
        if (Role::where('name', '=', 'developer')->first() === null) {
            $role = Role::create([
                'name'       => 'developer',
                'created_at' => now()
            ]);
        }

        // 2
        if (Role::where('name', '=', 'platform-superadmin')->first() === null) {
            $role = Role::create([
                'name'       => 'platform-superadmin',
                'created_at' => now()
            ]);
        }

        // 3
        if (Role::where('name', '=', 'platform-admin')->first() === null) {
            $role = Role::create([
                'name'       => 'platform-admin',
                'created_at' => now()
            ]);
        }

        // 4
        if (Role::where('name', '=', 'organization-superadmin')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-superadmin',
                'created_at' => now()
            ]);
        }

        // 5
        if (Role::where('name', '=', 'organization-admin')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-admin',
                'created_at' => now()
            ]);
        }

        // 6
        if (Role::where('name', '=', 'organization-general-manager')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-general-manager',
                'created_at' => now()
            ]);
        }

        // 7
        if (Role::where('name', '=', 'organization-sales-manager')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-sales-manager',
                'created_at' => now()
            ]);
        }

        // 8
        if (Role::where('name', '=', 'organization-production-manager')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-production-manager',
                'created_at' => now()
            ]);
        }

        // 9
        if (Role::where('name', '=', 'organization-administrative-leader')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-administrative-leader',
                'created_at' => now()
            ]);
        }

        // 10
        if (Role::where('name', '=', 'organization-estimator')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-estimator',
                'created_at' => now()
            ]);
        }

        // 11
        if (Role::where('name', '=', 'organization-project-manager')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-project-manager',
                'created_at' => now()
            ]);
        }

        // 12
        if (Role::where('name', '=', 'organization-administrative-assistant')->first() === null) {
            $role = Role::create([
                'name'       => 'organization-administrative-assistant',
                'created_at' => now()
            ]);
        }

        // 13
        if (Role::where('name', '=', 'installer-admin')->first() === null) {
            $role = Role::create([
                'name'       => 'installer-admin',
                'created_at' => now()
            ]);
        }

        // 14
        if (Role::where('name', '=', 'installer-team-lead')->first() === null) {
            $role = Role::create([
                'name'       => 'installer-team-lead',
                'created_at' => now()
            ]);
        }

        // 15
        if (Role::where('name', '=', 'customer-individual')->first() === null) {
            $role = Role::create([
                'name'       => 'customer-individual',
                'created_at' => now()
            ]);
        }

        // 16
        if (Role::where('name', '=', 'customer-organization')->first() === null) {
            $role = Role::create([
                'name'       => 'customer-organization',
                'created_at' => now()
            ]);
        }

        // 17
        if (Role::where('name', '=', 'guest')->first() === null) {
            $role = Role::create([
                'name'       => 'guest',
                'created_at' => now()
            ]);
        }


    }
}
