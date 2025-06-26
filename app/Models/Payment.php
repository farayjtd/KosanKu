<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_history_id',
        'tenant_id',
        'room_id',
        'landboard_id',
        'invoice_id',
        'external_id',         
        'reference',          
        'payment_method',      
        'amount',
        'penalty_amount',
        'total_amount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function landboard()
    {
        return $this->belongsTo(Landboard::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rentalHistory()
    {
        return $this->belongsTo(RentalHistory::class);
    }

    public function disbursement()
    {
        return $this->hasOne(Disbursement::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaidWithoutDisbursement($query)
    {
        return $query->where('status', 'paid')->whereDoesntHave('disbursement');
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function hasDisbursement()
    {
        return $this->disbursement()->exists();
    }

    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedPenaltyAmountAttribute()
    {
        return 'Rp ' . number_format($this->penalty_amount, 0, ',', '.');
    }
}
