<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            [
                'name' => 'Laravel Course',
                'price' => 99.99,
                'description' => 'Master Laravel from scratch.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vue.js Guide',
                'price' => 59.99,
                'description' => 'Learn frontend with Vue.js',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
