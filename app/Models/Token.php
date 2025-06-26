<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'room_id', 
        'token', 
        'used', 
        'expires_at', 
        'duration'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function isExpired()
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }
}
