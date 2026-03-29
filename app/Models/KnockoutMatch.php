<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnockoutMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'round', 'match_number', 'table_number', 'is_bronze',
        'home_registration_id', 'away_registration_id',
        'home_score', 'away_score', 'winner_registration_id', 'is_played',
    ];

    protected function casts(): array
    {
        return [
            'is_played' => 'boolean',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function homeRegistration()
    {
        return $this->belongsTo(EventRegistration::class, 'home_registration_id');
    }

    public function awayRegistration()
    {
        return $this->belongsTo(EventRegistration::class, 'away_registration_id');
    }

    public function winner()
    {
        return $this->belongsTo(EventRegistration::class, 'winner_registration_id');
    }

    public function getRoundLabelAttribute(): string
    {
        return match($this->round) {
            2 => 'Döntő',
            4 => 'Elődöntő',
            8 => 'Negyeddöntő',
            16 => 'Nyolcaddöntő',
            default => $this->round . ' csapatos kör',
        };
    }
}
