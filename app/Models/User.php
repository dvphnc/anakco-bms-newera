<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'role',
    'password',
    'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    public function issuedDocuments()
    {
        return $this->hasMany(Document::class, 'issued_by');
    }

    public function filedBlotterCases()
    {
        return $this->hasMany(BlotterCase::class, 'filed_by');
    }

    public function issuedBusinessPermits()
    {
        return $this->hasMany(Business::class, 'issued_by');
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isSecretary(): bool
    {
        return $this->role === 'Secretary';
    }

    public function isCommittee(): bool
    {
        return $this->role === 'Committee';
    }
}