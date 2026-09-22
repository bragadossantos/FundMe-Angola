<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    /**
     * Use the unguessable token (not the sequential id) for route binding,
     * so a checkout/confirmation URL cannot be enumerated to view or
     * confirm someone else's donation.
     */
    public function getRouteKeyName(): string
    {
        return 'confirmation_token';
    }

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
        static::creating(function ($donation) {
            if (empty($donation->confirmation_token)) {
                $donation->confirmation_token = (string) Str::random(40);
            }
        });

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
