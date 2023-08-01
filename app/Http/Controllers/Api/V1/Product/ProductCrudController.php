<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\Domain\Products\Actions\DestroyProductAction;
use App\Domain\Products\Actions\StoreProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\DataTransferObjects\StoreProductData;
use App\Domain\Products\DataTransferObjects\UpdateProductData;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Resources\ProductResource;
use App\Domain\Settings\Enums\Paginator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ProductCrudController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = QueryBuilder::for(subject: Product::class)
            ->allowedFilters(filters: ['name', 'price', 'stock', 'status'])
            ->allowedIncludes(includes: ['category', 'images'])
            ->select(columns: ['id', 'category_id', 'name', 'price', 'stock', 'status', 'description', 'created_at', 'updated_at'])
            ->latest()
            ->paginate(perPage: Paginator::LENGTH_PER_PAGE->value);

        return ProductResource::collection($products);
    }

    public function store(StoreRequest $request, StoreProductAction $storeProductAction): JsonResponse
    {
        $storeProductAction->execute(StoreProductData::fromRequest($request));
        return response()->json(data: ['message' => trans(key: 'responses.record_created')], status: Response::HTTP_CREATED);
    }

    public function show(string|int $id): ProductResource
    {
        $product = QueryBuilder::for(subject: Product::class)
            ->allowedIncludes(includes: ['category', 'images'])
            ->where(column: 'id', operator: '=', value: $id)
            ->select(columns: ['id', 'category_id', 'name', 'price', 'stock', 'status', 'description', 'created_at', 'updated_at'])
            ->firstOrFail();

        return ProductResource::make($product);
    }

    public function update(UpdateRequest $request, Product $product, UpdateProductAction $updateProductAction): JsonResponse
    {
        $updateProductAction->execute(UpdateProductData::fromRequest($request), $product);
        return response()->json(data: ['message' => trans(key: 'responses.record_updated')], status: Response::HTTP_OK);
    }

    public function destroy(Product $product, DestroyProductAction $destroyProductAction): JsonResponse
    {
        $destroyProductAction->execute($product);
        return response()->json(data: ['message' => trans(key: 'responses.record_deleted')], status: Response::HTTP_OK);
    }
}
