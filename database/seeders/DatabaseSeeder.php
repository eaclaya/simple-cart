<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        Product::insert([
            [
                'name' => 'Focus Paper Refill',
                'price' => 13.00,
                'stock_quantity' => 24,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-01.jpg',
            ],
            [
                'name' => 'Focus Card Holder',
                'price' => 64.00,
                'stock_quantity' => 18,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-02.jpg',
            ],
            [
                'name' => 'Focus Carry Pouch',
                'price' => 32.00,
                'stock_quantity' => 14,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-03.jpg',
            ],
            [
                'name' => 'Focus Multi-Pack',
                'price' => 39.00,
                'stock_quantity' => 22,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-04.jpg',
            ],
            [
                'name' => 'Machined Mechanical Pencil',
                'price' => 35.00,
                'stock_quantity' => 16,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-05.jpg',
            ],
            [
                'name' => 'Brass Scissors',
                'price' => 50.00,
                'stock_quantity' => 12,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-06.jpg',
            ],
            [
                'name' => 'Electric Kettle',
                'price' => 149.00,
                'stock_quantity' => 10,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-07.jpg',
            ],
            [
                'name' => 'Leather Workspace Pad',
                'price' => 165.00,
                'stock_quantity' => 9,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-08.jpg',
            ],
            [
                'name' => 'Leather Long Wallet',
                'price' => 118.00,
                'stock_quantity' => 11,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-09.jpg',
            ],
            [
                'name' => 'Machined Sphere Puzzle',
                'price' => 95.00,
                'stock_quantity' => 13,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-10.jpg',
            ],
            [
                'name' => 'Minimalist Wristwatch',
                'price' => 149.00,
                'stock_quantity' => 8,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-11.jpg',
            ],
            [
                'name' => 'Motto Leather Coaster Set',
                'price' => 18.00,
                'stock_quantity' => 20,
                'image_url' => 'https://tailwindcss.com/plus-assets/img/ecommerce-images/category-page-01-image-card-12.jpg',
            ],
        ]);
    }
}
