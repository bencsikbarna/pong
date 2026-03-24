<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'location', 'event_date',
        'registration_deadline', 'max_teams', 'tables_count', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function confirmedRegistrations()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'confirmed');
    }

    public function groups()
    {
        return $this->hasMany(Group::class)->orderBy('order');
    }

    public function knockoutMatches()
    {
        return $this->hasMany(KnockoutMatch::class);
    }

    public function isRegistrationOpen(): bool
    {
        return $this->status === 'registration_open';
    }

    public function isFull(): bool
    {
        return $this->confirmedRegistrations()->count() >= $this->max_teams;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registration_open' => 'Nevezés nyitva',
            'registration_closed' => 'Nevezés lezárva',
            'group_stage' => 'Csoportkör',
            'knockout_stage' => 'Egyenes kiesés',
            'finished' => 'Befejezett',
            default => $this->status,
        };
    }
}
