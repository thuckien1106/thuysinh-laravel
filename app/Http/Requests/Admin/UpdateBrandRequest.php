<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id') ?? $this->route('brand');
        return [
            'name' => 'required|string|max:120|unique:brands,name,'.$id,
            'slug' => 'nullable|string|max:140|unique:brands,slug,'.$id,
        ];
    }
}

