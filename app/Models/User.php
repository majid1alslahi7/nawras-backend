<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'full_name', 'email', 'phone', 'password', 'role', 'avatar', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function doctor() { return $this->hasOne(Doctor::class); }
    public function nurse() { return $this->hasOne(Nurse::class); }
    public function userTokens() { return $this->hasMany(UserToken::class); }
    public function visits() { return $this->hasMany(Visit::class, 'doctor_id'); }
    public function labRequests() { return $this->hasMany(LabRequest::class, 'doctor_id'); }
    public function prescriptions() { return $this->hasMany(Prescription::class, 'doctor_id'); }
    public function labResultsEntered() { return $this->hasMany(LabResult::class, 'entered_by'); }
    public function transactions() { return $this->hasMany(Transaction::class, 'entered_by'); }
    public function isDoctor(): bool { return $this->role === 'doctor'; }
    public function isNurse(): bool { return $this->role === 'nurse'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
