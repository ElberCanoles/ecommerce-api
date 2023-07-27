<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyCategoryCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
    }

    public function test_authorized_user_can_destroy_a_category(): void
    {
        Sanctum::actingAs($this->user);

        $this->deleteJson(route(name: 'api.v1.categories.destroy', parameters: ['category' => $this->category->id]))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->where(key: 'message', expected: trans(key: 'responses.record_deleted'));
            });

        $this->assertDatabaseCount(table: 'categories', count: 1);
        $this->assertSoftDeleted($this->category);
    }

}
