<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignVerification extends Model
{
    protected $fillable = [
        'campaign_id',
        'verifier_id',
        'action',
        'internal_notes',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
