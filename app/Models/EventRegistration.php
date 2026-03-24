<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'team_id',
        'guest_team_name', 'guest_contact_name', 'guest_contact_email', 'guest_contact_phone',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function groupTeam()
    {
        return $this->hasOne(GroupTeam::class, 'registration_id');
    }

    public function getTeamNameAttribute(): string
    {
        if ($this->team_id) {
            return $this->team->name ?? 'Ismeretlen csapat';
        }
        return $this->guest_team_name ?? 'Ismeretlen csapat';
    }

    public function getContactEmailAttribute(): string
    {
        if ($this->team_id && $this->team) {
            return $this->team->email;
        }
        return $this->guest_contact_email ?? '';
    }
}
