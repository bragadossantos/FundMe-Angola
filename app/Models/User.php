<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'avatar',
        'province',
        'municipality',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerifier(): bool
    {
        return $this->role === 'verifier' || $this->role === 'admin';
    }

    public function isApplicant(): bool
    {
        return $this->role === 'applicant';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
