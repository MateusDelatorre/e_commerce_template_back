<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialContact extends Model
{
    use HasFactory;

    protected $table = 'official_contacts';

    protected $fillable = [
        'name',
        'whatsapp_number',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
