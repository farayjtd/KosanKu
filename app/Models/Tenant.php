<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'account_id',
        'room_id',
        'address',
        'gender',
        'activity_type',
        'institution_name',
        'photo',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rentalHistories()
    {
        return $this->hasMany(RentalHistory::class, 'tenant_id');
    }
}
