<?php

declare(strict_types=1);

namespace App\Domain\Products\QueryBuilders;

use App\Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Product create(array $attributes = [])
 * @method static Product|null first()
 * @method static Product|null find($id, $columns = ['*'])
 */
class ProductQueryBuilder extends Builder
{
}
