<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\DataTransferObjects\StoreUserData;
use App\Domain\Users\Models\User;

class StoreUserAction
{
    public function execute(StoreUserData $storeUserData): User
    {
        return User::query()->create([
            'name' => $storeUserData->name,
            'last_name' => $storeUserData->lastName,
            'email' => $storeUserData->email,
            'password' => $storeUserData->password
        ]);
    }
}
