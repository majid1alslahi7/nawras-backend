<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'setting_type', 'description', 'updated_by'];
    public $timestamps = false;
}
