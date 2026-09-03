<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDestination extends Model
{
    protected $fillable = [
        'campaign_id',
        'destination_type',
        'institution_or_payee_name',
        'bank_name',
        'account_number',
        'iban',
        'mobile_money_number',
        'invoice_reference',
        'authorized_amount',
        'private_notes',
    ];

    protected $casts = [
        'authorized_amount' => 'decimal:2',
    ];

    /**
     * Strict privacy protection: bank details hidden from default JSON serializations
     */
    protected $hidden = [
        'bank_name',
        'account_number',
        'iban',
        'mobile_money_number',
        'private_notes',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function disbursements()
    {
        return $this->hasMany(FundDisbursement::class);
    }

    public function getDestinationTypeLabelAttribute(): string
    {
        return match ($this->destination_type) {
            'hospital_direct' => '🏥 Pagamento Direto à Instituição Médica / Hospital',
            'beneficiary_transfer' => '👤 Transferência Direta ao Beneficiário / Solicitante',
            'split_payment' => '💊 Pagamento Dividido (Instituição & Fornecedores Aprovados)',
            default => ucfirst($this->destination_type),
        };
    }
}
