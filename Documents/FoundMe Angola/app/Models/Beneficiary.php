<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'age_range',
        'relation_to_applicant',
        'location_province',
        'location_municipality',
        'is_identity_hidden',
    ];

    protected $casts = [
        'is_identity_hidden' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Display name based on identity privacy preference
     */
    public function getPublicDisplayNameAttribute(): string
    {
        if ($this->is_identity_hidden) {
            return 'Paciente (Identidade Protegida)';
        }
        return $this->full_name;
    }
}
