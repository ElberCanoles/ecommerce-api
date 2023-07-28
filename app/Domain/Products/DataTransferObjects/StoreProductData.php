<?php

declare(strict_types=1);

namespace App\Domain\Products\DataTransferObjects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductData
{
    public function __construct(
        public int    $categoryId,
        public string $name,
        public float  $price,
        public int    $stock,
        public string $status,
        public string $description,
        public ?array $images
    ) {
    }

    public static function fromRequest(FormRequest $request): self
    {
        return new self(
            categoryId: $request->integer(key: 'category_id'),
            name: Str::ucfirst(Str::lower($request->input(key: 'name'))),
            price: $request->float(key: 'price'),
            stock: $request->integer(key: 'stock'),
            status: $request->input(key: 'status'),
            description: Str::ucfirst(Str::lower($request->input(key: 'description'))),
            images: $request->allFiles()['images'] ?? null
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            categoryId: (int)$data['category_id'],
            name: Str::ucfirst(Str::lower($data['name'])),
            price: (float)$data['price'],
            stock: (int)$data['stock'],
            status: $data['status'],
            description: Str::ucfirst(Str::lower($data['description'])),
            images: $data['images'] ?? null
        );
    }
}
