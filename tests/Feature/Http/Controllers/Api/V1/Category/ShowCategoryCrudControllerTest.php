<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowCategoryCrudControllerTest extends TestCase
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

    public function test_authorized_user_can_show_a_category(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.v1.categories.show', parameters: ['category' => $this->category->id]))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('name');
                });
            });
    }
}
