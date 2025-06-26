<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disbursement extends Model
{
    protected $fillable = [
        'payment_id',
        'landboard_id',
        'external_id',             
        'reference',              
        'bank_code',
        'bank_name',
        'account_number',
        'account_holder_name',
        'amount',
        'platform_fee',
        'total_amount',
        'status',
        'description',
        'disbursed_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'disbursed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function landboard(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landboard_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'FAILED');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    public function isFailed(): bool
    {
        return $this->status === 'FAILED';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedPlatformFeeAttribute(): string
    {
        return 'Rp ' . number_format($this->platform_fee, 0, ',', '.');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'badge-warning',
            'COMPLETED' => 'badge-success',
            'FAILED' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'Menunggu',
            'COMPLETED' => 'Selesai',
            'FAILED' => 'Gagal',
            default => 'Tidak Diketahui',
        };
    }
}
