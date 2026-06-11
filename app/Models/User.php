<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'address', 'street',
        'push_token', 'role', 'telephone', 'points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['reports_count'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'points'            => 'integer',
        ];
    }

    // ── Attribut calculé — inclus automatiquement dans le JSON ────────────────
    public function getReportsCountAttribute(): int
    {
        return $this->reports()->count();
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}