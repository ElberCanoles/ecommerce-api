<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->text(maxNbChars: 30),
        ];
    }
}
