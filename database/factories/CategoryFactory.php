<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => Str::ucfirst(Str::lower(fake()->unique()->text(maxNbChars: 30))),
        ];
    }
}
