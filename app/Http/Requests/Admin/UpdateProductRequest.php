<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'long_description' => 'nullable|string',
            'specs' => 'nullable|string',
            'care_guide' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|max:2048',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
        ];
    }
}
