<?php

declare(strict_types=1);

namespace App\Domain\Products\Enums;

enum ProductStatus: string
{
    case AVAILABLE = 'modules.products.available';

    case UNAVAILABLE = 'modules.products.unavailable';

    public static function toArray(): array
    {
        return array_column(array: self::cases(), column_key: 'value');
    }
}
