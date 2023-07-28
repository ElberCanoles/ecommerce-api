<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Support\Str;

class SlugService
{
    private const SALT_LENGTH = 10;

    /**
     * @throws CircularDependencyException
     * @throws BindingResolutionException
     */
    public function makeUniqueSlugForEloquentModel(string $input, string $modelClassName, string $columName): string
    {
        $safeSlug = $this->makeSafeSlug($input);

        $model = Container::getInstance()->build(concrete: $modelClassName);

        while ($model::query()->where($columName, $safeSlug)->exists()) {
            $safeSlug = $this->makeSafeSlug($input);
        }

        return $safeSlug;
    }

    private function makeSafeSlug(string $input): string
    {
        $preKeyword = $this->makeSafeHash();

        $postKeyword = $this->makeSafeHash();

        $baseSlug = "$preKeyword $input $postKeyword";

        return Str::slug($baseSlug);
    }

    private function makeSafeHash(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(length: (self::SALT_LENGTH - (self::SALT_LENGTH % 2)) / 2));
    }
}
