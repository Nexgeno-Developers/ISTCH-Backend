<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'preset_amounts',
        'is_active',
        'last_updated_at',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'preset_amounts' => 'array',
        'is_active' => 'boolean',
        'last_updated_at' => 'datetime',
    ];
}
