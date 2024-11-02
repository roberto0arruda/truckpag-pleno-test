<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents(base_path('products.json'));

        $product = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR)[0];

        Product::create($product);
    }
}
