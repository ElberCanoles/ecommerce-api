<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Product;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyProductCrudControllerTest extends TestCase
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

    public function test_authorized_user_can_destroy_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->deleteJson(route(name: 'api.v1.products.destroy', parameters: ['product' => $this->product->id]))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_deleted'));
            });

        $this->assertSoftDeleted($this->product);
    }

}
