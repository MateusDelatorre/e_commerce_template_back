<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'addressName',
    'receiverName',
    'streetName',
    'number',
    'addressComplement',
    'city',
    'country',
    'state',
    'cep',
    'phone',
])]
class EnderecoModel extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = [
        'user_id',
        'addressName',
        'receiverName',
        'streetName',
        'number',
        'addressComplement',
        'city',
        'country',
        'state',
        'cep',
        'phone',
    ];

    /**
     * Get user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
