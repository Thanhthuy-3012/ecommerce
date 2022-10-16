<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'user_name' => 'admin',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('admin123'),
                'role_id'   => 1,
            ],
            [
                'user_name' => 'owner shop',
                'email'     => 'owner_shop@gmail.com',
                'password'  => Hash::make('shop123'),
                'role_id'   => 2,
            ]
        ]);
    }
}
