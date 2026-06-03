<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'entity_type', 'entity_id', 'description',
        'old_values_json', 'new_values_json', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values_json' => 'array',
        'new_values_json' => 'array',
    ];

    public $timestamps = false;

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
