<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModel extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
    ];

    /**
     * Calculate the subtotal for this line item (after discount).
     */
    public function getSubtotalAttribute(): float
    {
        $effectivePrice = $this->unit_price * (1 - $this->discount / 100);

        return round($effectivePrice * $this->quantity, 2);
    }

    /**
     * Get the order this item belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }

    /**
     * Get the product for this line item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
