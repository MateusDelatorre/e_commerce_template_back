<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'stock' => $this->stock,
            'discount' => $this->discount,
            'is_featured' => $this->is_featured,
            'created_at' => $this->created_at,
            'total_sold' => $this->total_sold,
        ];
    }
}
