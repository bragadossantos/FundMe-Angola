<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundDisbursement extends Model
{
    protected $fillable = [
        'campaign_id',
        'payment_destination_id',
        'admin_id',
        'amount',
        'transaction_reference',
        'disbursement_date',
        'proof_document_path',
        'public_summary_update',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'disbursement_date' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function paymentDestination()
    {
        return $this->belongsTo(PaymentDestination::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
