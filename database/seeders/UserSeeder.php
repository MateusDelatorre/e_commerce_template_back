<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Mateus Developer',
            'email' => 'mateusdeveloper@email.com',
            'role' => 'developer',
            'password' => Hash::make('qazwsx'),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Admin',
            'email' => 'mateusadmin@email.com',
            'role' => 'admin',
            'password' => Hash::make('qazwsx'),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Customer',
            'email' => 'mateuscustomer@email.com',
            'role' => 'customer',
            'password' => Hash::make('qazwsx'),
        ]);

    }
}
