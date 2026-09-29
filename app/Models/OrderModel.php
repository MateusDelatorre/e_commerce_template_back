<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'endereco_id',
        'status',
        'total',
        'notes',
    ];

    /**
     * Valid status transitions.
     * Each status maps to the statuses it can transition to.
     */
    public const STATUS_TRANSITIONS = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => [],
        'cancelled' => [],
    ];

    /**
     * Check whether transitioning to the given status is valid.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = self::STATUS_TRANSITIONS[$this->status] ?? [];

        return in_array($newStatus, $allowed, true);
    }

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the delivery address for the order.
     */
    public function endereco(): BelongsTo
    {
        return $this->belongsTo(EnderecoModel::class, 'endereco_id');
    }

    /**
     * Get the items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
