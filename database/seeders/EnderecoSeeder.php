<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\EnderecoModel;

class EnderecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'mateusdeveloper@email.com')->first();

        if ($user) {
            EnderecoModel::create([
                'user_id' => $user->id,
                'addressName'       => 'Meu Ap',
                'receiverName'      => 'Mateus',
                'streetName'        => 'Rua onde moro',
                'number'            => '31',
                'addressComplement' => 'Apto 1',
                'city'              => 'São Paulo',
                'country'           => 'Brasil',
                'state'             => 'SP',
                'cep'               => '01010101',
                'phone'             => '11912345678',
            ]);
        }

        $user2 = User::where('email', 'mateusadmin@email.com')->first();

        if ($user2) {
            EnderecoModel::create([
                'user_id' => $user2->id,
                'addressName'       => 'Meu Ap',
                'receiverName'      => 'Mateus',
                'streetName'        => 'Rua onde moro',
                'number'            => '31',
                'addressComplement' => 'Apto 1',
                'city'              => 'São Paulo',
                'country'           => 'Brasil',
                'state'             => 'SP',
                'cep'               => '01010101',
                'phone'             => '11912345678',
            ]);
        }

        $user3 = User::where('email', 'mateuscustomer@email.com')->first();

        if ($user3) {
            EnderecoModel::create([
                'user_id' => $user3->id,
                'addressName'       => 'Meu Ap',
                'receiverName'      => 'Mateus',
                'streetName'        => 'Rua onde moro',
                'number'            => '31',
                'addressComplement' => 'Apto 1',
                'city'              => 'São Paulo',
                'country'           => 'Brasil',
                'state'             => 'SP',
                'cep'               => '01010101',
                'phone'             => '11912345678',
            ]);
        }
    }
}
