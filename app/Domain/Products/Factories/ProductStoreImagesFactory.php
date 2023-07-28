<?php

declare(strict_types=1);

namespace App\Domain\Products\Factories;

use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Exception;

class ProductStoreImagesFactory
{
    public const PRODUCTS_GALLERY_PATH = 'images/products';

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function create(Product $product, array $images): void
    {
        try {
            $newImagesPaths = $this->fileService->uploadMultipleFiles(
                files: $images,
                relativePath: self::PRODUCTS_GALLERY_PATH
            );

            foreach ($newImagesPaths as $imagePath) {
                $product->images()->create([
                    'path' => $imagePath
                ]);
            }
        } catch (Exception $exception) {
            logger()->error(message: 'error storing product images on ProductStoreImagesFactory.create', context: [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
