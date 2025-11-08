<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'percent' => (int) $this->percent,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status ?? null,
            'note' => $this->note,
            'product' => $this->whenLoaded('product', fn() => [
                'id' => optional($this->product)->id,
                'name' => optional($this->product)->name,
            ]),
        ];
    }
}

