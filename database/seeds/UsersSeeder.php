<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add Users
         *
         */
        if (User::where('email', '=', 'superadmin@admin.com')->first() === null) {
            $user = User::create([
                'name'     => 'Super Admin',
                'email'    => 'superadmin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }
    }
}
