<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property \App\Models\Tenant|null $tenant
 * @property \App\Models\Landboard|null $landboard
 */

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'is_first_login',
        'name',
        'phone',
        'alt_phone',
        'avatar',
        'bank_name',
        'bank_account',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_first_login' => 'boolean',
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : asset('default-avatar.png');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function landboard()
    {
        return $this->hasOne(Landboard::class);
    }
}
