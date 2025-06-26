<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RentalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'duration_months',
        'is_continue',
        'next_duration_months',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getComputedEndDateAttribute()
    {
        if ($this->end_date) {
            return $this->end_date;
        }

        return Carbon::parse($this->start_date)->addMonths($this->duration_months);
    }

    public function hasDecided()
    {
        return !is_null($this->is_continue);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('end_date');
    }
}
