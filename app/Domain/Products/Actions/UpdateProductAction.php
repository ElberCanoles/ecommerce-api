<?php

declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Factories\ProductUpdateImagesFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\SlugService;
use Exception;

class UpdateProductAction
{
    public function __construct(
        private readonly SlugService                $slugService,
        private readonly ProductUpdateImagesFactory $updateImagesFactory
    ) {
    }

    public function execute(UpdateProductData $updateProductData, Product $product): void
    {
        try {
            $product->fill([
                'category_id' => $updateProductData->categoryId,
                'name' => $updateProductData->name,
                'price' => $updateProductData->price,
                'stock' => $updateProductData->stock,
                'status' => $updateProductData->status,
                'description' => $updateProductData->description,
            ]);

            if ($product->isDirty(attributes: 'name')) {
                $slug = $this->slugService->makeUniqueSlugForEloquentModel(
                    input: $updateProductData->name,
                    modelClassName: Product::class,
                    columName: 'slug'
                );

                $product->slug = $slug;
            }

            $this->updateImagesFactory->create(
                product: $product,
                preloadedImages: $updateProductData->preloaded_images ?? null,
                newImages: $updateProductData->images ?? null
            );

            if ($product->isDirty()) {
                $product->save();
            }
        } catch (Exception $exception) {
            logger()->error(message: 'error updating product on UpdateProductAction.execute', context: [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
