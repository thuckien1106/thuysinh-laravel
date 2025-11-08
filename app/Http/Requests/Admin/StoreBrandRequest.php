<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120|unique:brands,name',
            'slug' => 'nullable|string|max:140|unique:brands,slug',
        ];
    }
}

