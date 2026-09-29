<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life.',
                'price' => 199.99,
                'stock' => 50,
                'discount' => 15.00,
                'is_featured' => true,
                'total_sold' => 120,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Feature-rich smartwatch with health tracking and GPS.',
                'price' => 349.99,
                'stock' => 30,
                'discount' => 0,
                'is_featured' => true,
                'total_sold' => 85,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'USB-C Hub',
                'description' => '7-in-1 USB-C hub with HDMI, USB 3.0, and SD card reader.',
                'price' => 49.99,
                'stock' => 200,
                'discount' => 10.00,
                'is_featured' => false,
                'total_sold' => 300,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical keyboard with Cherry MX Blue switches.',
                'price' => 129.99,
                'stock' => 75,
                'discount' => 20.00,
                'is_featured' => true,
                'total_sold' => 60,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portable Charger',
                'description' => '20000mAh portable charger with fast charging support.',
                'price' => 39.99,
                'stock' => 3,
                'discount' => 0,
                'is_featured' => false,
                'total_sold' => 450,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
