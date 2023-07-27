<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreCategoryCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        /**
         * @var Category $category
         */
        $category = Category::factory()->make();

        $this->payload = [
            'name' => $category->name
        ];
    }

    public function test_authorized_user_can_store_a_new_category(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson(route(name: 'api.v1.categories.store'), $this->payload)
            ->assertCreated()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_created'));
            });

        $this->assertDatabaseCount(table: 'categories', count: 1);
    }

}
