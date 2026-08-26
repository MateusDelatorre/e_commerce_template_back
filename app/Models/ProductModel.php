<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'description',
    'price',
    'image_path',
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
}
