<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Product;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexProductCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Category::factory(count: 2)->create();
    }

    public function test_authorized_user_can_get_all_products(): void
    {
        Product::factory(count: 2)->create();
        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.v1.products.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [],
                'links' => [],
                'meta' => [
                    "current_page",
                    "from",
                    "last_page",
                    "links" => [
                    ],
                    "path",
                    "per_page",
                    "to",
                    "total"
                ]
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->count(key: 'data', length: 2)->has('data.0', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('category_id')
                        ->has('name')
                        ->has('price')
                        ->has('stock')
                        ->has('status_key')
                        ->has('description')
                        ->has('created_at')
                        ->has('updated_at');
                })->etc();
            });
    }

}
