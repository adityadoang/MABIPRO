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
        'password',
        'role',
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

    // Method helper: Cek apakah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Method helper: Cek apakah produksi
    public function isProduksi(): bool
    {
        return $this->role === 'produksi';
    }

    // Method helper: Cek apakah marketing
    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    // Method helper: Cek apakah legalitas
    public function isLegalitas(): bool
    {
        return $this->role === 'legalitas';
    }
}