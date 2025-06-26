<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'room_id', 
        'name'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
