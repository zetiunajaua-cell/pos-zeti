<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();

        if (!$category) {
            $category = Category::create([
                'name' => 'Makanan',
                'slug' => 'makanan',
            ]);
        }

        return [
            'category_id' => $category->id,
            'sku' => strtoupper(fake()->bothify('SKU###??')),
            'name' => fake()->words(2, true),
            'purchase_price' => fake()->numberBetween(10000, 50000),
            'sell_price' => fake()->numberBetween(60000, 100000),
            'stock' => fake()->numberBetween(10, 100),
            'unit' => 'pcs',
            'is_active' => true,
        ];
    }
}