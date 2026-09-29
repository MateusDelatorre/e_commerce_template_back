<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'number' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Owner',
            'email' => 'mateusowner@email.com',
            'role' => 'owner',
            'password' => Hash::make('qazwsx'),
            'number' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Admin',
            'email' => 'mateusadmin@email.com',
            'role' => 'admin',
            'password' => Hash::make('qazwsx'),
            'number' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Employee',
            'email' => 'mateusemployee@email.com',
            'role' => 'employee',
            'password' => Hash::make('qazwsx'),
            'number' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Mateus Customer',
            'email' => 'mateuscustomer@email.com',
            'role' => 'customer',
            'password' => Hash::make('qazwsx'),
            'number' => '123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
