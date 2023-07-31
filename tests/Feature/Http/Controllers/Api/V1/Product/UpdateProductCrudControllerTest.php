<?php

namespace Http\Controllers\Api\V1\Product;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateProductCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake(config(key: 'filesystems.default'));
        Category::factory(count: 2)->create();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();

        /**
         * @var Product $newProductData
         */
        $newProductData = Product::factory()->make();

        $this->payload = [
            'category_id' => $newProductData->category_id,
            'name' => $newProductData->name,
            'description' => $newProductData->description,
            'price' => $newProductData->price,
            'stock' => $newProductData->stock,
            'status' => $newProductData->status,
            'preloaded_images' => [],
            'images' => [
                UploadedFile::fake()->image(name: 'image_one.png')->size(kilobytes: 100),
                UploadedFile::fake()->image(name: 'image_two.png')->size(kilobytes: 100)
            ]
        ];
    }

    public function test_authorized_user_can_update_a_product(): void
    {
        Sanctum::actingAs($this->user);

        $this->putJson(route(name: 'api.v1.products.update', parameters: ['product' => $this->product->id]), $this->payload)
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_updated'));
            });

        $this->product->refresh();

        $this->assertSame(expected: $this->payload['category_id'], actual: $this->product->category_id);
        $this->assertSame(expected: $this->payload['name'], actual: $this->product->name);
        $this->assertSame(expected: $this->payload['description'], actual: $this->product->description);
        $this->assertSame(expected: (float)$this->payload['price'], actual: $this->product->price);
        $this->assertSame(expected: $this->payload['stock'], actual: $this->product->stock);
        $this->assertSame(expected: $this->payload['status'], actual: $this->product->status);

        $this->assertDatabaseCount(table: 'images', count: 2);
        $this->assertDatabaseHas(table: 'images', data: [
            'imageable_type' => Product::class,
            'imageable_id' => $this->product->id
        ]);
    }

}
