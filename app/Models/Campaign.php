<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'beneficiary_id',
        'hospital_id',
        'title',
        'slug',
        'short_description',
        'story',
        'category',
        'target_amount',
        'raised_amount',
        'currency',
        'status',
        'payment_destination_type',
        'location_province',
        'location_municipality',
        'hospital_name',
        'treatment_location',
        'expected_treatment_date',
        'featured_image',
        'is_featured',
        'verification_badge',
        'rejection_reason',
        'published_at',
        'closed_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'is_featured' => 'boolean',
        'verification_badge' => 'boolean',
        'expected_treatment_date' => 'date',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->whereIn('status', ['published', 'goal_reached', 'payment_processing', 'completed']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function fundPlans()
    {
        return $this->hasMany(CampaignFundPlan::class);
    }

    public function documents()
    {
        return $this->hasMany(CampaignDocument::class);
    }

    public function verifications()
    {
        return $this->hasMany(CampaignVerification::class);
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function paymentDestination()
    {
        return $this->hasOne(PaymentDestination::class);
    }

    public function disbursements()
    {
        return $this->hasMany(FundDisbursement::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Calculated Progress
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0.0;
        }
        $percentage = ($this->raised_amount / $this->target_amount) * 100;
        return round(min(100.0, max(0.0, $percentage)), 1);
    }

    public function getFormattedTargetAmountAttribute(): string
    {
        return number_format($this->target_amount, 2, ',', '.') . ' ' . $this->currency;
    }

    public function getFormattedRaisedAmountAttribute(): string
    {
        return number_format($this->raised_amount, 2, ',', '.') . ' ' . $this->currency;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Rascunho',
            'pending_review' => 'Pendente de Análise',
            'waiting_documents' => 'Aguardando Documentos',
            'under_review' => 'Em Análise Documental',
            'approved' => 'Aprovada',
            'rejected' => 'Rejeitada',
            'suspended' => 'Suspensa',
            'published' => 'Publicada (Recebendo Doações)',
            'goal_reached' => 'Meta Atingida 🎯',
            'payment_processing' => 'Processando Pagamento ⏳',
            'completed' => 'Concluída ✅',
            'closed' => 'Encerrada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'pending_review' => 'bg-warning text-dark',
            'waiting_documents' => 'bg-info text-dark',
            'under_review' => 'bg-primary',
            'approved' => 'bg-info text-dark',
            'rejected' => 'bg-danger',
            'suspended' => 'bg-dark',
            'published' => 'bg-success',
            'goal_reached' => 'bg-success fw-bold',
            'payment_processing' => 'bg-warning text-dark fw-bold',
            'completed' => 'bg-primary fw-bold',
            'closed' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    /**
     * Recalculate raised amount strictly from confirmed (PAID) donations
     */
    public function recalculateRaisedAmount(): void
    {
        $totalPaid = $this->donations()->where('status', 'paid')->sum('amount');
        $this->raised_amount = $totalPaid;

        if ($this->raised_amount >= $this->target_amount && $this->status === 'published') {
            $this->status = 'goal_reached';
        }

        $this->save();
    }
}
