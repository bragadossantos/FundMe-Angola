<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_reference',
        'is_anonymous',
        'donor_message',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPublicDonorNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Doador Anónimo';
        }
        return $this->donor_name ?: ($this->user ? $this->user->name : 'Doador Solidário');
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, ',', '.') . ' ' . $this->currency;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendente',
            'processing' => 'Em Processamento',
            'paid' => 'Confirmada / Paga ✅',
            'failed' => 'Falhada ❌',
            'cancelled' => 'Cancelada',
            'refunded' => 'Reembolsada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-info text-dark',
            'paid' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            'refunded' => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    protected static function booted()
    {
        static::saved(function ($donation) {
            if ($donation->campaign) {
                $donation->campaign->recalculateRaisedAmount();
            }
        });

        static::deleted(function ($donation) {
            if ($donation->campaign) {
                $donation->campaign->recalculateRaisedAmount();
            }
        });
    }
}
