<?php

namespace Tests\Unit\Domain\Categories\Actions;

use App\Domain\Categories\Actions\UpdateCategoryAction;
use App\Domain\Categories\DataTransferObjects\UpdateCategoryData;
use App\Domain\Categories\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_update_a_category_on_database(): void
    {
        /**
         * @var Category $currentCategory
         */
        $currentCategory = Category::factory()->create();

        /**
         * @var Category $newCategory
         */
        $newCategory = Category::factory()->make();

        (new UpdateCategoryAction())->execute(UpdateCategoryData::fromArray([
            'name' => $newCategory->name
        ]), $currentCategory);

        $currentCategory->refresh();

        $this->assertSame(expected: $newCategory->name, actual: $currentCategory->name);
        $this->assertDatabaseCount(table: 'categories', count: 1);
        $this->assertDatabaseHas(table: 'categories', data: [
            'name' => $newCategory->name
        ]);
    }

}
