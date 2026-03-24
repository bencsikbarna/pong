<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Team extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'contact_name', 'contact_phone',
        'total_events', 'total_wins', 'total_losses', 'total_draws',
        'total_cups_scored', 'total_cups_conceded',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    public function getTotalCupDiffAttribute(): int
    {
        return $this->total_cups_scored - $this->total_cups_conceded;
    }
}
