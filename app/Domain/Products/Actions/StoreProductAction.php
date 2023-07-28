<?php

declare(strict_types=1);

namespace App\Domain\Products\Actions;

use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\Factories\ProductStoreImagesFactory;
use App\Domain\Products\Models\Product;
use App\Domain\Shared\Services\SlugService;
use Exception;

class StoreProductAction
{
    public function __construct(
        private readonly SlugService               $slugService,
        private readonly ProductStoreImagesFactory $storeImagesFactory
    ) {
    }

    public function execute(StoreProductData $storeProductData): void
    {
        try {
            $product = Product::query()->create([
                'category_id' => $storeProductData->categoryId,
                'name' => $storeProductData->name,
                'slug' => $this->slugService->makeUniqueSlugForEloquentModel(
                    input: $storeProductData->name,
                    modelClassName: Product::class,
                    columName: 'slug'
                ),
                'price' => $storeProductData->price,
                'stock' => $storeProductData->stock,
                'status' => $storeProductData->status,
                'description' => $storeProductData->description,
            ]);

            if (isset($storeProductData->images)) {
                $this->storeImagesFactory->create($product, $storeProductData->images);
            }
        } catch (Exception $exception) {
            logger()->error(message: 'error storing product on StoreProductAction.execute', context: [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
