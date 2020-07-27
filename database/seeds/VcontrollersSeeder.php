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
         * Add Controllers
         *
         */
        if (Vcontroller::where('name', '=', 'Books')->first() === null) {
            $controller = Vcontroller::create([
                'name'       => 'Books',
                'created_at' => now()
            ]);
        }

        if (Vcontroller::where('name', '=', 'Roles')->first() === null) {
            $controller = Vcontroller::create([
                'name'       => 'Roles',
                'created_at' => now()
            ]);
        }
    }
}
