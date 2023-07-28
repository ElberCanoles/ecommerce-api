<?php

namespace App\Http\Requests\Product;

use App\Domain\Products\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    private const MAXIMUM_NUMBER_OF_IMAGES_ALLOWED = 5;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $numberOfPreloadedImages = count($this->input(key: 'preloaded_images', default: []));
        $maximumNumberOfNewImagesAllowed = self::MAXIMUM_NUMBER_OF_IMAGES_ALLOWED - $numberOfPreloadedImages;

        return [
            'category_id' => ['required', Rule::exists(table: 'categories', column: 'id')],
            'name' => ['required', 'string', 'max:100', Rule::unique(table: 'products')->ignore(request()->product)],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::toArray())],
            'description' => ['required', 'string', 'max:500'],
            'preloaded_images' => ['required_without:images', 'array', 'max:5'],
            'images' => ['required_without:preloaded_images', 'array', 'max:' . $maximumNumberOfNewImagesAllowed],
            'images.*' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5000'],
        ];
    }
}
