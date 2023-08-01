<?php

declare(strict_types=1);

namespace App\Domain\Products\Resources;

use App\Domain\Categories\Resources\CategoryResource;
use App\Domain\Images\Resources\ImageResource;
use App\Domain\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => CategoryResource::make($this->whenLoaded(relationship: 'category')),
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'status_key' => $this->status,
            'description' => $this->description,
            'images' => ImageResource::collection($this->whenLoaded(relationship: 'images')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
