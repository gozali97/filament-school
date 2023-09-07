<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array(
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1111'),
            ),
            array(
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => Hash::make('1111'),
            ),
        );

        DB::table('users')->insert($users);
    }
}
