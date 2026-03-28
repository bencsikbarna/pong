<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'round_id', 'table_number', 'home_registration_id', 'away_registration_id',
        'home_score', 'away_score', 'result', 'is_played',
    ];

    protected function casts(): array
    {
        return [
            'is_played' => 'boolean',
        ];
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function homeRegistration()
    {
        return $this->belongsTo(EventRegistration::class, 'home_registration_id');
    }

    public function awayRegistration()
    {
        return $this->belongsTo(EventRegistration::class, 'away_registration_id');
    }
}
