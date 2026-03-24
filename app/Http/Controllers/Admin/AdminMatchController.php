<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\Match as PongMatch;
use App\Models\GroupTeam;
use Illuminate\Http\Request;

class AdminMatchController extends Controller
{
    public function updateResult(Request $request, Event $event, PongMatch $match)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0|max:10',
            'away_score' => 'required|integer|min:0|max:10',
        ], [
            'home_score.required' => 'A hazai eredmény kötelező.',
            'away_score.required' => 'Az idegenbeli eredmény kötelező.',
            'home_score.min' => 'Az eredmény 0 és 10 között kell legyen.',
            'away_score.min' => 'Az eredmény 0 és 10 között kell legyen.',
            'home_score.max' => 'Az eredmény 0 és 10 között kell legyen.',
            'away_score.max' => 'Az eredmény 0 és 10 között kell legyen.',
        ]);

        $homeScore = (int) $request->home_score;
        $awayScore = (int) $request->away_score;

        // Ha már volt eredmény, visszavonáljuk a régi statisztikákat
        if ($match->is_played) {
            $this->revertMatchStats($match);
        }

        // Eredmény meghatározása
        $result = 'draw';
        if ($homeScore > $awayScore) $result = 'home_win';
        elseif ($awayScore > $homeScore) $result = 'away_win';

        $match->update([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'result' => $result,
            'is_played' => true,
        ]);

        // Statisztikák frissítése
        $this->updateMatchStats($match, $homeScore, $awayScore, $result);

        return back()->with('success', 'Eredmény rögzítve!');
    }

    private function revertMatchStats(PongMatch $match): void
    {
        $homeGT = GroupTeam::where('registration_id', $match->home_registration_id)
            ->whereHas('group', fn($q) => $q->where('event_id', $match->round->group->event_id))
            ->first();
        $awayGT = GroupTeam::where('registration_id', $match->away_registration_id)
            ->whereHas('group', fn($q) => $q->where('event_id', $match->round->group->event_id))
            ->first();

        if (!$homeGT || !$awayGT) return;

        $oldHome = $match->home_score;
        $oldAway = $match->away_score;

        // Visszavonás
        $homePoints = match($match->result) { 'home_win' => 3, 'draw' => 1, default => 0 };
        $awayPoints = match($match->result) { 'away_win' => 3, 'draw' => 1, default => 0 };

        $homeGT->decrement('played');
        $homeGT->decrement('cups_scored', $oldHome);
        $homeGT->decrement('cups_conceded', $oldAway);
        $homeGT->decrement('points', $homePoints);
        if ($match->result === 'home_win') $homeGT->decrement('wins');
        elseif ($match->result === 'away_win') $homeGT->decrement('losses');
        else $homeGT->decrement('draws');

        $awayGT->decrement('played');
        $awayGT->decrement('cups_scored', $oldAway);
        $awayGT->decrement('cups_conceded', $oldHome);
        $awayGT->decrement('points', $awayPoints);
        if ($match->result === 'away_win') $awayGT->decrement('wins');
        elseif ($match->result === 'home_win') $awayGT->decrement('losses');
        else $awayGT->decrement('draws');

        $homeGT->update(['cup_diff' => $homeGT->cups_scored - $homeGT->cups_conceded]);
        $awayGT->update(['cup_diff' => $awayGT->cups_scored - $awayGT->cups_conceded]);
    }

    private function updateMatchStats(PongMatch $match, int $homeScore, int $awayScore, string $result): void
    {
        $homeGT = GroupTeam::where('registration_id', $match->home_registration_id)
            ->whereHas('group', fn($q) => $q->where('event_id', $match->round->group->event_id))
            ->first();
        $awayGT = GroupTeam::where('registration_id', $match->away_registration_id)
            ->whereHas('group', fn($q) => $q->where('event_id', $match->round->group->event_id))
            ->first();

        if (!$homeGT || !$awayGT) return;

        $homePoints = match($result) { 'home_win' => 3, 'draw' => 1, default => 0 };
        $awayPoints = match($result) { 'away_win' => 3, 'draw' => 1, default => 0 };

        // Hazai csapat
        $homeGT->increment('played');
        $homeGT->increment('cups_scored', $homeScore);
        $homeGT->increment('cups_conceded', $awayScore);
        $homeGT->increment('points', $homePoints);
        if ($result === 'home_win') $homeGT->increment('wins');
        elseif ($result === 'away_win') $homeGT->increment('losses');
        else $homeGT->increment('draws');
        $homeGT->update(['cup_diff' => $homeGT->fresh()->cups_scored - $homeGT->fresh()->cups_conceded]);

        // Vendég csapat
        $awayGT->increment('played');
        $awayGT->increment('cups_scored', $awayScore);
        $awayGT->increment('cups_conceded', $homeScore);
        $awayGT->increment('points', $awayPoints);
        if ($result === 'away_win') $awayGT->increment('wins');
        elseif ($result === 'home_win') $awayGT->increment('losses');
        else $awayGT->increment('draws');
        $awayGT->update(['cup_diff' => $awayGT->fresh()->cups_scored - $awayGT->fresh()->cups_conceded]);
    }
}
