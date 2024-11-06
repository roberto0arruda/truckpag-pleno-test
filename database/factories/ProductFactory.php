<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->numberBetween(1, 1_000_000),
            'status' => fake()->randomElement(['draft', 'trash', 'published']),
            'imported_t' => fake()->date(),
            'url' => fake()->url(),
            'creator' => fake()->name(),
            'created_t' => fake()->unixTime(),
            'last_modified_t' => fake()->unixTime(),
            'product_name' => fake()->name(),
            'quantity' => fake()->numberBetween(1, 100),
            'brands' => fake()->name(),
            'categories' => fake()->name(),
            'labels' => fake()->name(),
            'cities' => fake()->name(),
            'purchase_places' => fake()->name(),
            'stores' => fake()->name(),
            'ingredients_text' => fake()->name(),
            'traces' => fake()->name(),
            'serving_size' => fake()->name(),
            'serving_quantity' => fake()->name(),
            'nutriscore_score' => fake()->numberBetween(1, 100),
            'nutriscore_grade' => fake()->name(),
            'main_category' => fake()->name(),
            'image_url' => fake()->url(),
        ];
    }
}
