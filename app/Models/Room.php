<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'landboard_id',
        'type',
        'gender_type',
        'price',
        'room_number',
        'status',
    ];

    public function landboard()
    {
        return $this->belongsTo(Landboard::class);
    }

     public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function token()
    {
        return $this->hasOne(Token::class)->latestOfMany();
    }
    public function rentalHistories()
    {
        return $this->hasMany(\App\Models\RentalHistory::class);
    }

}
