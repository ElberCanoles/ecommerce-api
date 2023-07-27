<?php

namespace Tests\Feature\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Models\Category;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexCategoryCrudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authorized_user_can_list_categories(): void
    {
        Category::factory(count: 5)->create();

        Sanctum::actingAs($this->user);

        $this->getJson(route(name: 'api.v1.categories.index'))
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->count(key: 'data', length: 5)->has('data.0', function (AssertableJson $data) {
                    $data->has('id')
                        ->has('name');
                })->etc();
            })
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
            ]);
    }

}
