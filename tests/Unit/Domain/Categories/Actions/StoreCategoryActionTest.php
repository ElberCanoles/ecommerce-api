<?php

namespace Tests\Unit\Domain\Categories\Actions;

use App\Domain\Categories\Actions\StoreCategoryAction;
use App\Domain\Categories\DataTransferObjects\StoreCategoryData;
use App\Domain\Categories\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_store_a_new_category_on_database(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->make();

        (new StoreCategoryAction())->execute(StoreCategoryData::fromArray([
            'name' => $category->name
        ]));

        $this->assertDatabaseCount(table: 'categories', count: 1);
        $this->assertDatabaseHas(table: 'categories', data: [
            'name' => $category->name
        ]);
    }

}
