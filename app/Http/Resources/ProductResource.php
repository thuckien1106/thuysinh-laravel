<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'quantity' => (int) $this->quantity,
            'image' => $this->image,
            'image_url' => asset('assets/img/products/' . ($this->image ?: 'placeholder.webp')),
            'category' => $this->whenLoaded('category', fn() => [
                'id' => optional($this->category)->id,
                'name' => optional($this->category)->name,
            ]),
            'brand' => $this->whenLoaded('brand', fn() => [
                'id' => optional($this->brand)->id,
                'name' => optional($this->brand)->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}

