<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignDocument extends Model
{
    protected $fillable = [
        'campaign_id',
        'document_type',
        'original_name',
        'file_path',
        'file_mime',
        'file_size',
        'is_private',
        'verification_status',
        'admin_notes',
        'uploaded_by',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDocumentTypeNameAttribute(): string
    {
        return match ($this->document_type) {
            'identity_card' => 'Documento de Identificação (BI / Passaporte)',
            'medical_report' => 'Relatório Médico Oficial',
            'prescription' => 'Receita / Prescrição Médica',
            'surgery_request' => 'Pedido / Indicação de Cirurgia',
            'hospital_budget' => 'Orçamento Hospitalar / Clínica',
            'invoice' => 'Fatura / Recibo Hospitalar',
            'medical_declaration' => 'Declaração Médica Autorizada',
            default => 'Outro Documento Comprovativo',
        };
    }
}
