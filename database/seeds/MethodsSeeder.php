<?php

use App\Models\Method;
use Illuminate\Database\Seeder;

class MethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Add Methods
         *
         */
        $method = Method::create([
            'name' => 'index',
            'controller_id' => '1'
        ]);

        $method = Method::create([
            'name' => 'create',
            'controller_id' => '1'
        ]);

        $method = Method::create([
            'name' => 'edit',
            'controller_id' => '1'
        ]);

        $method = Method::create([
            'name' => 'delete',
            'controller_id' => '1'
        ]);


    }
}
