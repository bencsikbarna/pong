<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Round extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'round_number'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function matches()
    {
        return $this->hasMany(\App\Models\Match::class);
    }

    public function isFullyPlayed(): bool
    {
        return $this->matches->every(fn($m) => $m->is_played);
    }
}
