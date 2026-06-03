<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'trade_name', 'generic_name', 'concentration', 'form',
        'default_dosage', 'default_frequency', 'default_duration', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
