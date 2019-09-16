<?php

use App\Models\Action_Role;
use Illuminate\Database\Seeder;

class ActionRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add action_roles
         *
         */
        if (Action_Role::where('action', '=', 'actions.index')->first() === null) {
            $action_role = Action_Role::create([
                'action'     => 'actions.index',
                'role_ids'=>'{[1,2,3]}'
            ]);
        };

        if (Action_Role::where('action', '=', 'actions.create')->first() === null) {
            $action_role = Action_Role::create([
                'action'     => 'actions.create',
                'role_ids'=>'{[1,2,3]}'
            ]);
        };

        if (Action_Role::where('action', '=', 'actions.edit')->first() === null) {
            $action_role = Action_Role::create([
                'action'     => 'actions.edit',
                'role_ids'=>'{[1,2,3]}'
            ]);
        };

        if (Action_Role::where('action', '=', 'actions.delete')->first() === null) {
            $action_role = Action_Role::create([
                'action'     => 'actions.delete',
                'role_ids'=>'{[1,2,3]}'
            ]);
        };

    }
}
