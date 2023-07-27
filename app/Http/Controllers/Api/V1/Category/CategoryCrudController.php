<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Category;

use App\Domain\Categories\Actions\DestroyCategoryAction;
use App\Domain\Categories\Actions\StoreCategoryAction;
use App\Domain\Categories\Actions\UpdateCategoryAction;
use App\Domain\Categories\DataTransferObjects\StoreCategoryData;
use App\Domain\Categories\DataTransferObjects\UpdateCategoryData;
use App\Domain\Categories\Models\Category;
use App\Domain\Categories\Resources\CategoryResource;
use App\Domain\Settings\Enums\Paginator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryCrudController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = QueryBuilder::for(subject: Category::class)
            ->allowedFilters(filters: ['name'])
            ->select(columns: ['id', 'name'])
            ->latest()
            ->paginate(perPage: Paginator::LENGTH_PER_PAGE->value);

        return CategoryResource::collection($categories);
    }

    public function store(StoreRequest $request, StoreCategoryAction $storeCategoryAction): JsonResponse
    {
        $storeCategoryAction->execute(StoreCategoryData::fromRequest($request));
        return response()->json(data: ['message' => trans(key: 'responses.record_created')], status: Response::HTTP_CREATED);
    }

    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make($category);
    }

    public function update(UpdateRequest $request, Category $category, UpdateCategoryAction $updateCategoryAction): JsonResponse
    {
        $updateCategoryAction->execute(UpdateCategoryData::fromRequest($request), $category);
        return response()->json(data: ['message' => trans(key: 'responses.record_updated')], status: Response::HTTP_OK);
    }

    public function destroy(Category $category, DestroyCategoryAction $destroyCategoryAction): JsonResponse
    {
        $destroyCategoryAction->execute($category);
        return response()->json(data: ['message' => trans(key: 'responses.record_deleted')], status: Response::HTTP_OK);
    }
}
