<?php

declare(strict_types=1);

namespace App\Domain\Categories\QueryBuilders;

use App\Domain\Categories\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Category create(array $attributes = [])
 * @method static Category|null first()
 * @method static Category|null find($id, $columns = ['*'])
 */
class CategoryQueryBuilder extends Builder
{
}
