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
        if (Role::where('name', '=', 'superadmin')->first() === null) {
            $role = Role::create([
                'name'     => 'superadmin'
            ]);
        }

        if (Role::where('name', '=', 'developer')->first() === null) {
            $role = Role::create([
                'name'     => 'developer'
            ]);
        }

        if (Role::where('name', '=', 'admin')->first() === null) {
            $role = Role::create([
                'name'     => 'admin'
            ]);
        }

        if (Role::where('name', '=', 'platform-superadmin')->first() === null) {
            $role = Role::create([
                'name'    => 'platform-superadmin'
            ]);
        }

        if (Role::where('name', '=', 'platform-admin')->first() === null) {
            $role = Role::create([
                'name'    => 'platform-admin',
            ]);
        }

        if (Role::where('name', '=', 'organization-superadmin')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-superadmin',
            ]);
        }

        if (Role::where('name', '=', 'organization-general-manager')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-general-manager',
            ]);
        }

        if (Role::where('name', '=', 'organization-sales-manager')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-sales-manager',
            ]);
        }

        if (Role::where('name', '=', 'organization-production-manager')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-production-manager',
            ]);
        }

        if (Role::where('name', '=', 'organization-administrative-leader')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-administrative-leader',
            ]);
        }

        if (Role::where('name', '=', 'organization-estimator')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-estimator',
            ]);
        }

        if (Role::where('name', '=', 'organization-project-manager')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-project-manager',
            ]);
        }

        if (Role::where('name', '=', 'organization-administrative-assistant')->first() === null) {
            $role = Role::create([
                'name'    => 'organization-administrative-assistant',
            ]);
        }

        if (Role::where('name', '=', 'installer-admin')->first() === null) {
            $role = Role::create([
                'name'    => 'installer-admin',
            ]);
        }

        if (Role::where('name', '=', 'installer-team-lead')->first() === null) {
            $role = Role::create([
                'name'    => 'installer-team-lead',
            ]);
        }

        if (Role::where('name', '=', 'customer-individual')->first() === null) {
            $role = Role::create([
                'name'    => 'customer-individual',
            ]);
        }

        if (Role::where('name', '=', 'customer-organization')->first() === null) {
            $role = Role::create([
                'name'    => 'customer-organization',
            ]);
        }

        if (Role::where('name', '=', 'guest')->first() === null) {
            $role = Role::create([
                'name'    => 'guest',
            ]);
        }


    }
}
