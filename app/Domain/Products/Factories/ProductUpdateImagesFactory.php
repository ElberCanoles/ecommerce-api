<?php

declare(strict_types=1);

namespace App\Domain\Products\Factories;

use App\Domain\Products\Enums\ProductFileStorageDirectories;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\FileService;
use Exception;

class ProductUpdateImagesFactory
{

    public function __construct(private readonly FileService $fileService)
    {
    }

    public function create(Product $product, array $preloadedImages = null, array $newImages = null): void
    {
        try {
            $currentImagesPaths = $product->images()->get()->pluck(value: 'path')->toArray();

            $preloadedImagesPaths = array_map(function ($image) {
                return $image['path'];
            }, array: $preloadedImages ?? []);

            $this->removeImagesFromProduct($product, array_diff($currentImagesPaths, $preloadedImagesPaths));

            $this->addImagesToProduct($product, $newImages);

        } catch (Exception $exception) {
            logger()->error(message: 'error updating product images on ProductUpdateImagesFactory.create', context: [
                'message' => $exception->getMessage()
            ]);
        }
    }

    private function removeImagesFromProduct(Product $product, array $images): void
    {
        if (count($images) > 0) {
            $this->fileService->removeMultipleFiles(
                filesUrl: $images,
                fromThePrefix: '/images'
            );

            $product->images()
                ->whereIn(column: 'path', values: $images)
                ->delete();
        }
    }

    private function addImagesToProduct(Product $product, ?array $newImages): void
    {
        $newImagesPaths = [];

        if (isset($newImages) && count($newImages) > 0) {
            $newImagesPaths = $this->fileService->uploadMultipleFiles(
                files: $newImages,
                relativePath: ProductFileStorageDirectories::PRODUCTS_GALLERY_PATH->value
            );
        }

        foreach ($newImagesPaths as $imagePath) {
            $product->images()->create([
                'path' => $imagePath
            ]);
        }
    }
}
