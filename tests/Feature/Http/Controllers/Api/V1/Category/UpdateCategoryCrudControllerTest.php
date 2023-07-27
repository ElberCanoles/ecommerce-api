<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateCategoryCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();

        /**
         * @var Category $category
         */
        $category = Category::factory()->make();

        $this->payload = [
            'name' => $category->name
        ];
    }

    public function test_authorized_user_can_update_a_category(): void
    {
        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.v1.categories.update', parameters: ['category' => $this->category->id]), $this->payload)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_updated'));
            });

        $this->category->refresh();

        $this->assertSame(expected: $this->payload['name'], actual: $this->category->name);
        $this->assertDatabaseCount(table: 'categories', count: 1);
        $this->assertDatabaseHas(table: 'categories', data: [
            'name' => $this->payload['name']
        ]);
    }
}
