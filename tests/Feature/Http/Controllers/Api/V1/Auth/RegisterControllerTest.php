<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\V1\Auth;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_can_register_with_valid_data(): void
    {
        $user = User::factory()->make();

        $payload = [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(uri: route(name: 'api.v1.register'), data: $payload);

        $response->assertCreated();

        $this->assertDatabaseCount(table: 'users', count: 1);
        $this->assertDatabaseHas(table: 'users', data: [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email
        ]);
    }

    public function test_guest_user_can_not_register_with_invalid_data(): void
    {
        $payload = [];

        $response = $this->postJson(uri: route(name: 'api.v1.register'), data: $payload);

        $response->assertUnprocessable();

        $this->assertDatabaseEmpty(table: 'users');
    }

}
