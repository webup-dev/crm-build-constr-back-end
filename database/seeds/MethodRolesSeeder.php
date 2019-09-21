<?php

use App\Models\Method_role;
use Illuminate\Database\Seeder;

class MethodRolesSeeder extends Seeder
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
        $action_role = Method_role::create([
            'method_id'   => 1,
            'role_id' => 1
        ]);

        $action_role = Method_role::create([
            'method_id'   => 2,
            'role_id' => 1
        ]);

        $action_role = Method_role::create([
            'method_id'   => 1,
            'role_id' => 2
        ]);

        $action_role = Method_role::create([
            'method_id'   => 2,
            'role_id' => 2
        ]);

    }
}
