<?php

declare(strict_types=1);

namespace App\Domain\Settings\Enums;

enum Paginator: int
{
    case LENGTH_PER_PAGE = 20;
}
