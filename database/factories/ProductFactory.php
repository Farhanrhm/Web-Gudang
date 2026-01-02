<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->bothify('PROD-####'),
            'name' => $this->faker->words(3, true),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'price' => $this->faker->numberBetween(10000, 5000000),
            'stock' => $this->faker->numberBetween(10, 100),
            'location' => $this->faker->randomElement(['Rak A', 'Rak B', 'Gudang Utama', 'Etalase Depan']),
            'unit' => $this->faker->randomElement(['pcs', 'unit', 'box', 'kg']),
            'description' => $this->faker->sentence(),
            'image' => null, // Atau path dummy jika ada
        ];
    }
}
