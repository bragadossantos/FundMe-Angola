<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignFundPlan extends Model
{
    protected $fillable = [
        'campaign_id',
        'item_name',
        'estimated_amount',
        'notes',
    ];

    protected $casts = [
        'estimated_amount' => 'decimal:2',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
