<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionCategory extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'type', 'icon', 'color', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function transactions(): HasMany { return $this->hasMany(Transaction::class, 'category_id'); }
}
