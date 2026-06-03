<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nurse extends Model
{
    protected $fillable = [
        'user_id', 'position', 'employee_id', 'shift',
        'can_manage_finances', 'can_manage_appointments', 'can_enter_results',
    ];

    protected $casts = [
        'can_manage_finances' => 'boolean',
        'can_manage_appointments' => 'boolean',
        'can_enter_results' => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
