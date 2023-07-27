<?php

declare(strict_types=1);

namespace App\Domain\Categories\Actions;

use App\Domain\Categories\DataTransferObjects\UpdateCategoryData;
use App\Domain\Categories\Models\Category;

class UpdateCategoryAction
{
    public function execute(UpdateCategoryData $updateCategoryData, Category $category): void
    {
        $category->fill([
            'name' => $updateCategoryData->name
        ]);

        if ($category->isDirty()) {
            $category->save();
        }
    }
}
