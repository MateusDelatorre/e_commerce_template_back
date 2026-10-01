<?php

namespace Database\Seeders;

use App\Models\OfficialContact;
use Illuminate\Database\Seeder;

class OfficialContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfficialContact::firstOrCreate(
            ['name' => 'Suporte Oficial'],
            [
                'whatsapp_number' => '5511999999999',
                'email'           => 'suporte@loja.com',
                'is_active'       => true,
            ]
        );
    }
}
