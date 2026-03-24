<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'name', 'order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function groupTeams()
    {
        return $this->hasMany(GroupTeam::class)->orderByDesc('points')->orderByDesc('cup_diff')->orderByDesc('cups_scored');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class)->orderBy('round_number');
    }

    public function allRoundsPlayed(): bool
    {
        foreach ($this->rounds as $round) {
            foreach ($round->matches as $match) {
                if (!$match->is_played) {
                    return false;
                }
            }
        }
        return $this->rounds->count() > 0;
    }
}
