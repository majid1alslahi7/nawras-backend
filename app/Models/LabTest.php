<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'test_name', 'test_code', 'category', 'normal_range', 'unit', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
