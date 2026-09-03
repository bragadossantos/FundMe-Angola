<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name',
        'province',
        'municipality',
        'address',
        'contact_phone',
        'contact_email',
        'bank_name',
        'bank_account_number',
        'iban',
        'swift_bic',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * Hide private financial data from public JSON arrays
     */
    protected $hidden = [
        'bank_name',
        'bank_account_number',
        'iban',
        'swift_bic'
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
