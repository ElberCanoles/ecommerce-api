<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Product;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreProductCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));
        $this->user = User::factory()->create();
        Category::factory(count: 2)->create();

        /**
         * @var Product $product
         */
        $product = Product::factory()->make();

        $this->payload = [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'images' => [UploadedFile::fake()->image(name: 'image.png')->size(kilobytes: 100)]
        ];
    }

    public function test_authorized_user_can_store_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson(route(name: 'api.v1.products.store'), $this->payload)
            ->assertCreated()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_created'));
            });

        $this->assertDatabaseCount(table: 'products', count: 1);
        $this->assertDatabaseCount(table: 'images', count: 1);
        $this->assertDatabaseHas(table: 'products', data: [
            'category_id' => $this->payload['category_id'],
            'name' => $this->payload['name'],
            'description' => $this->payload['description'],
            'price' => $this->payload['price'],
            'stock' => $this->payload['stock'],
            'status' => $this->payload['status']
        ]);
    }
}
