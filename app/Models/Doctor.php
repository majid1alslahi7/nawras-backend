<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $fillable = [
        'user_id', 'specialty', 'license_number', 'qualification',
        'experience_years', 'clinic_name', 'signature_image', 'working_hours_json',
    ];

    protected $casts = [
        'working_hours_json' => 'array',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
