<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class StoreProductDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('start_at')) {
            $this->merge(['start_at' => now()]);
        }
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'percent' => 'required|integer|min:1|max:90',
            'start_at' => 'nullable|date',
            'end_at' => 'required|date',
            'note' => 'nullable|string|max:120',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $start = Carbon::parse($this->input('start_at'));
            $end = Carbon::parse($this->input('end_at'));
            if ($end->lte($start)) {
                $v->errors()->add('end_at', 'Thời gian kết thúc phải sau thời gian bắt đầu.');
            }
        });
    }
}

