<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Domain\Users\Actions\StoreUserAction;
use App\Domain\Users\DataTransferObjects\StoreUserData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreUserActionTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_store_a_new_user_on_database(): void
    {
        $data = User::factory()->make();

        (new StoreUserAction())->execute(StoreUserData::fromArray([
            'name' => $data->name,
            'last_name' => $data->last_name,
            'email' => $data->email,
            'password' => 'password'
        ]));

        $this->assertDatabaseCount(table: 'users', count: 1);
        $this->assertDatabaseHas(table: 'users', data: [
            'name' => $data->name,
            'last_name' => $data->last_name,
            'email' => $data->email
        ]);
    }
}
