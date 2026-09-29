<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'description',
    'price',
    'image_path',
    'stock',
    'discount',
    'is_featured',
    'total_sold',
])]
class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'stock',
        'discount',
        'is_featured',
        'total_sold',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'discount'    => 'decimal:2',
        'is_featured' => 'boolean',
        'stock'       => 'integer',
        'total_sold'  => 'integer',
    ];

    /**
     * The accessors to append to the model's array and JSON form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Get the full public URL for the product image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the order items referencing this product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'product_id');
    }
}
