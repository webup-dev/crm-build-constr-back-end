<?php

use App\Models\Vcontroller;
use Illuminate\Database\Seeder;

class VcontrollersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Conrollers
         *
         */
        if (Vcontroller::where('name', '=', 'Books')->first() === null) {
            $controller = Vcontroller::create([
                'name' => 'Books'
            ]);
        }

        if (Vcontroller::where('name', '=', 'Roles')->first() === null) {
            $controller = Vcontroller::create([
                'name' => 'Roles'
            ]);
        }
    }
}
