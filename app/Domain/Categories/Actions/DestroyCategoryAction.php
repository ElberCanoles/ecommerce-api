<?php

declare(strict_types=1);

namespace App\Domain\Categories\Actions;

use App\Domain\Categories\Models\Category;

class DestroyCategoryAction
{
    public function execute(Category $category): void
    {
        $category->delete();
    }
}
