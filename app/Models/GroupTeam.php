<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'registration_id',
        'points', 'wins', 'draws', 'losses',
        'cups_scored', 'cups_conceded', 'cup_diff', 'played',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }

    public function getTeamNameAttribute(): string
    {
        return $this->registration ? $this->registration->team_name : 'Ismeretlen';
    }
}
