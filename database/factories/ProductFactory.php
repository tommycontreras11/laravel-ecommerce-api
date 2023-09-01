<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\products>
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
            'name' => fake()->unique()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(1, 20, 100),
            'category_id' => DB::table('categories')->inRandomOrder()->value('id'),
            'inventory_id' => DB::table('product_inventories')->inRandomOrder()->value('id')
        ];
    }
}
