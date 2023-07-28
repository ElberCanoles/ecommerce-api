<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Enums\ProductStatus;
use App\Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = Str::random();

        return [
            'category_id' => function () {
                return Category::query()->inRandomOrder()->first()->id;
            },
            'name' => Str::ucfirst(Str::lower($name)),
            'slug' => Str::slug(title: Str::random(length: 6) . ' ' . $name . ' ' . Str::random(length: 4)),
            'description' => Str::ucfirst(Str::lower(fake()->sentence())),
            'price' => rand(1000, 500000),
            'stock' => rand(10, 100),
            'status' => ProductStatus::toArray()[array_rand(ProductStatus::toArray())]
        ];
    }
}
