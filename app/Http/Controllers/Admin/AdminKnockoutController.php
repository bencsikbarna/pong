<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KnockoutMatch;
use Illuminate\Http\Request;

class AdminKnockoutController extends Controller
{
    public function updateResult(Request $request, Event $event, KnockoutMatch $knockoutMatch)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0|max:10',
            'away_score' => 'required|integer|min:0|max:10',
        ]);

        $homeScore = (int) $request->home_score;
        $awayScore = (int) $request->away_score;

        if ($homeScore === $awayScore) {
            return back()->withErrors(['away_score' => 'Egyenes kiesésnél nem lehet döntetlen! Adj meg különböző eredményeket.']);
        }

        $winnerId = $homeScore > $awayScore
            ? $knockoutMatch->home_registration_id
            : $knockoutMatch->away_registration_id;

        $knockoutMatch->update([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'winner_registration_id' => $winnerId,
            'is_played' => true,
        ]);

        // Továbbjutó beállítása a következő körben
        $this->propagateWinner($event, $knockoutMatch, $winnerId);

        // Ellenőrzés: ha ez volt a döntő, az esemény befejezett
        if ($knockoutMatch->round === 2) {
            $event->update(['status' => 'finished']);

            // Statisztikák frissítése a regisztrált csapatoknál
            $this->updateTeamStats($event);

            return redirect()->route('admin.events.show', $event)->with('success', 'Döntő eredménye rögzítve! Az esemény befejezett.');
        }

        return back()->with('success', 'Eredmény rögzítve, továbbjutó beállítva!');
    }

    private function propagateWinner(Event $event, KnockoutMatch $match, int $winnerId): void
    {
        $nextRound = $match->round / 2;
        if ($nextRound < 2) return;

        $nextMatchNumber = (int) ceil($match->match_number / 2);
        $nextMatch = $event->knockoutMatches()
            ->where('round', $nextRound)
            ->where('match_number', $nextMatchNumber)
            ->first();

        if (!$nextMatch) return;

        if ($match->match_number % 2 === 1) {
            $nextMatch->update(['home_registration_id' => $winnerId]);
        } else {
            $nextMatch->update(['away_registration_id' => $winnerId]);
        }
    }

    private function updateTeamStats(Event $event): void
    {
        $registrations = $event->confirmedRegistrations()->with('team')->get();

        foreach ($registrations as $registration) {
            if (!$registration->team_id || !$registration->team) continue;

            $team = $registration->team;

            // Csoport mérkőzések
            $groupTeam = $registration->groupTeam;
            if ($groupTeam) {
                $team->increment('total_events');
                $team->increment('total_wins', $groupTeam->wins);
                $team->increment('total_losses', $groupTeam->losses);
                $team->increment('total_draws', $groupTeam->draws);
                $team->increment('total_cups_scored', $groupTeam->cups_scored);
                $team->increment('total_cups_conceded', $groupTeam->cups_conceded);
            }
        }
    }
}
