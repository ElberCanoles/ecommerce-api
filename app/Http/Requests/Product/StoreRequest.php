<?php

namespace App\Http\Requests\Product;

use App\Domain\Products\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', Rule::exists(table: 'categories', column: 'id')],
            'name' => ['required', 'string', 'max:100', 'unique:products'],
            'price' => ['required', 'numeric', 'min:1', 'max:99999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:9999999'],
            'status' => ['required', Rule::in(ProductStatus::toArray())],
            'description' => ['required', 'string', 'max:500'],
            'images' => ['required', 'array', 'max:5'],
            'images.*' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5000'],
        ];
    }
}
