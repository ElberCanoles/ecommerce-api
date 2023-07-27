<?php

namespace Tests\Unit\Domain\Categories\Actions;

use App\Domain\Categories\Actions\DestroyCategoryAction;
use App\Domain\Categories\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyCategoryActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_destroy_a_category_on_database_with_soft_deletes(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        (new DestroyCategoryAction())->execute($category);

        $this->assertDatabaseCount(table: 'categories', count: 1);
        $this->assertSoftDeleted($category);
    }

}
