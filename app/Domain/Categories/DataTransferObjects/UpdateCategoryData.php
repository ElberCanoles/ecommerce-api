<?php

declare(strict_types=1);

namespace App\Domain\Categories\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCategoryData
{
    public function __construct(public string $name)
    {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            name: Str::ucfirst(Str::lower($request->input(key: 'name')))
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: Str::ucfirst(Str::lower($data['name']))
        );
    }
}
