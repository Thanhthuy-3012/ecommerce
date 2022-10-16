<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name_role' => 'Admin',
            ],
            [
                'name_role' => 'Owner Shop',
            ],
            [
                'name_role' => 'User',
            ]
        ]);
    }
}
