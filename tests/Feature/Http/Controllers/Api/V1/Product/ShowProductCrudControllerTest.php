<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Product;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowProductCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();
        Category::factory(count: 2)->create();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_authorized_user_can_show_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.v1.products.show', parameters: ['product' => $this->product->id]))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('category_id')
                        ->has('name')
                        ->has('price')
                        ->has('stock')
                        ->has('status_key')
                        ->has('description')
                        ->has('created_at')
                        ->has('updated_at');
                });
            });
    }

}
