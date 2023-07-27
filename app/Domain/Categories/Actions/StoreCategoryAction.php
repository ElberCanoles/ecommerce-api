<?php

declare(strict_types=1);

namespace App\Domain\Categories\Actions;

use App\Domain\Categories\DataTransferObjects\StoreCategoryData;
use App\Domain\Categories\Models\Category;

class StoreCategoryAction
{
    public function execute(StoreCategoryData $storeCategoryData): void
    {
        Category::query()->create([
            'name' => $storeCategoryData->name
        ]);
    }
}
