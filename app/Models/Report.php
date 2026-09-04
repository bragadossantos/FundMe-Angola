<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'campaign_id',
        'reporter_id',
        'reporter_name',
        'reporter_email',
        'reason',
        'description',
        'evidence_file_path',
        'status',
        'admin_notes',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function getReasonLabelAttribute(): string
    {
        return match ($this->reason) {
            'suspected_fraud' => 'Suspeita de Fraude',
            'false_information' => 'Informações Falsas / Incorretas',
            'misused_images' => 'Uso Indevido de Imagens / Identidade',
            'duplicate_campaign' => 'Campanha Duplicada',
            'other' => 'Outro Motivo',
            default => ucfirst($this->reason),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendente',
            'under_review' => 'Em Análise',
            'resolved' => 'Resolvida / Ação Tomada',
            'dismissed' => 'Arquivada / Improcedente',
            default => ucfirst($this->status),
        };
    }
}
