<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the order into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
			'public_reference' => $this->public_reference,
            'status'     => $this->status,
            'payment_method' => $this->payment_method,
            'total'      => $this->total,
            'notes'      => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user'       => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'phone' => $this->user->number,
                ];
            }),
            'endereco'   => $this->whenLoaded('endereco', function () {
                return [
                    'id'          => $this->endereco->id,
                    'addressName' => $this->endereco->addressName,
                    'receiverName' => $this->endereco->receiverName,
                    'streetName'  => $this->endereco->streetName,
                    'number'      => $this->endereco->number,
                    'addressComplement' => $this->endereco->addressComplement,
                    'city'        => $this->endereco->city,
                    'state'       => $this->endereco->state,
                    'cep'         => $this->endereco->cep,
                    'phone'       => $this->endereco->phone,
                ];
            }),
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'product_id' => $item->product_id,
                        'product'    => $item->relationLoaded('product') ? [
                            'id'        => $item->product->id,
                            'name'      => $item->product->name,
                            'image_url' => $item->product->image_url,
                        ] : null,
                        'quantity'   => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'discount'   => $item->discount,
                        'subtotal'   => $item->subtotal,
                    ];
                });
            }),
        ];
    }
}
