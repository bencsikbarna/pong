<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\Round;
use App\Models\GameMatch;
use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    public function generateForm(Event $event)
    {
        if ($event->status !== 'registration_closed') {
            return back()->with('error', 'Először zárd le a nevezést!');
        }

        $teamCount = $event->confirmedRegistrations()->count();
        if ($teamCount < 2) {
            return back()->with('error', 'Legalább 2 csapat szükséges.');
        }

        return view('admin.groups.generate', compact('event', 'teamCount'));
    }

    public function generate(Request $request, Event $event)
    {
        if ($event->status !== 'registration_closed') {
            return back()->with('error', 'Először zárd le a nevezést!');
        }

        $request->validate([
            'group_size' => 'required|integer|min:2|max:20',
            'tables_count' => 'required|integer|min:1|max:20',
        ], [
            'group_size.required' => 'A csoportméret kötelező.',
            'tables_count.required' => 'Az asztalok száma kötelező.',
        ]);

        // Töröljük a meglévő csoportokat ha vannak
        $event->groups()->each(function($group) {
            $group->rounds()->each(function($round) {
                $round->matches()->delete();
            });
            $group->rounds()->delete();
            $group->groupTeams()->delete();
        });
        $event->groups()->delete();

        $registrations = $event->confirmedRegistrations()->inRandomOrder()->get();
        $groupSize = (int) $request->group_size;
        $tablesCount = (int) $request->tables_count;

        // Csoportokra osztás
        $chunks = $registrations->chunk($groupSize);
        $groupLetters = range('A', 'Z');

        foreach ($chunks as $index => $chunk) {
            $group = Group::create([
                'event_id' => $event->id,
                'name' => $groupLetters[$index] . ' csoport',
                'order' => $index,
            ]);

            foreach ($chunk as $registration) {
                GroupTeam::create([
                    'group_id' => $group->id,
                    'registration_id' => $registration->id,
                ]);
            }

            // Fordulók generálása round-robin módszerrel
            $groupTeams = $group->groupTeams()->get();
            $this->generateRounds($group, $groupTeams, $tablesCount);
        }

        $event->update([
            'status' => 'group_stage',
            'tables_count' => $tablesCount,
        ]);

        return redirect()->route('admin.events.show', $event)->with('success', 'Csoportok és fordulók sikeresen generálva!');
    }

    private function generateRounds(Group $group, $teams, int $tablesCount): void
    {
        $teamCount = $teams->count();

        if ($teamCount < 2) return;

        // Round-robin algoritmus
        // Ha páratlan számú csapat, adj hozzá egy "bye" (pihenő) csapatot
        $teamIds = $teams->pluck('id')->toArray();
        $hasBye = false;

        if ($teamCount % 2 !== 0) {
            $teamIds[] = null; // null = bye
            $teamCount++;
            $hasBye = true;
        }

        $rounds = $teamCount - 1;
        $half = $teamCount / 2;

        // Minden fordulóban $half mérkőzés van elméletileg, de max $tablesCount játszható egyszerre
        // Ezért ha $half > $tablesCount, a fordulót több "sub-fordulóra" kell bontani
        // Az egyszerűség kedvéért: egy "forduló" = az összes mérkőzés amit egy adott körben le kell játszani
        // és ezeket az admin egymás után tölti ki

        $roundNumber = 1;

        for ($r = 0; $r < $rounds; $r++) {
            // Mérkőzések ebben a fordulóban
            $matchesInRound = [];
            for ($i = 0; $i < $half; $i++) {
                $home = $teamIds[$i];
                $away = $teamIds[$teamCount - 1 - $i];
                if ($home !== null && $away !== null) {
                    $matchesInRound[] = [$home, $away];
                }
            }

            if (empty($matchesInRound)) {
                // Rotáljuk és folytassuk
                $this->rotateTeams($teamIds);
                continue;
            }

            // Ha $tablesCount korlátoz, a fordulóba kerülő meccseket sub-fordulókra bontjuk
            $subRounds = array_chunk($matchesInRound, $tablesCount);
            foreach ($subRounds as $subMatchSet) {
                $round = Round::create([
                    'group_id' => $group->id,
                    'round_number' => $roundNumber++,
                ]);

                foreach ($subMatchSet as $tableIdx => [$homeId, $awayId]) {
                    // homeId és awayId itt GroupTeam id-k
                    $homeGroupTeam = GroupTeam::find($homeId);
                    $awayGroupTeam = GroupTeam::find($awayId);

                    GameMatch::create([
                        'round_id'              => $round->id,
                        'table_number'          => $tableIdx + 1,
                        'home_registration_id'  => $homeGroupTeam->registration_id,
                        'away_registration_id'  => $awayGroupTeam->registration_id,
                    ]);
                }
            }

            // Rotáljuk a csapatokat (az első rögzített marad)
            $this->rotateTeams($teamIds);
        }
    }

    private function rotateTeams(array &$teams): void
    {
        // Az első elem rögzített, a többit rotáljuk
        $count = count($teams);
        if ($count <= 1) return;

        $last = $teams[$count - 1];
        for ($i = $count - 1; $i > 1; $i--) {
            $teams[$i] = $teams[$i - 1];
        }
        $teams[1] = $last;
    }

    public function show(Event $event, Group $group)
    {
        $group->load(['groupTeams.registration', 'rounds.matches.homeRegistration', 'rounds.matches.awayRegistration']);
        return view('admin.groups.show', compact('event', 'group'));
    }

    public function advanceTeams(Request $request, Event $event)
    {
        $request->validate([
            'teams_to_advance' => 'required|integer|min:1|max:10',
        ]);

        $advanceCount = (int) $request->teams_to_advance;
        $groups = $event->groups()->with(['groupTeams' => function($q) {
            $q->orderByDesc('points')->orderByDesc('cup_diff')->orderByDesc('cups_scored');
        }, 'groupTeams.registration'])->get();

        // Ellenőrzés: minden csoport összes fordulója lejátszva?
        foreach ($groups as $group) {
            if (!$group->allRoundsPlayed()) {
                return back()->with('error', 'Nem minden meccs lejátszott a "' . $group->name . '" csoportban!');
            }
        }

        // Összegyűjtjük a továbbjutókat csoportonként
        $advancingRegistrations = [];
        foreach ($groups as $group) {
            $topTeams = $group->groupTeams->take($advanceCount);
            foreach ($topTeams as $gt) {
                $advancingRegistrations[] = $gt->registration_id;
            }
        }

        if (count($advancingRegistrations) < 2) {
            return back()->with('error', 'Legalább 2 csapatnak tovább kell jutnia.');
        }

        // Egyenes kieséses szakasz generálása
        $this->generateKnockout($event, $advancingRegistrations);

        $event->update(['status' => 'knockout_stage']);

        return redirect()->route('admin.events.show', $event)->with('success', 'Egyenes kieséses szakasz generálva!');
    }

    private function generateKnockout(Event $event, array $registrationIds): void
    {
        // Töröljük a meglévő knockout meccseket
        $event->knockoutMatches()->delete();

        $count = count($registrationIds);
        // Kerekítés felfelé a legközelebbi 2 hatványra
        $bracketSize = 1;
        while ($bracketSize < $count) {
            $bracketSize *= 2;
        }

        // Shuffle a csapatok sorrendjét
        shuffle($registrationIds);

        // Pad with null (bye) ha szükséges
        while (count($registrationIds) < $bracketSize) {
            $registrationIds[] = null;
        }

        // Az első kör mérkőzéseinek generálása
        $matchNumber = 1;
        for ($i = 0; $i < $bracketSize; $i += 2) {
            $home = $registrationIds[$i];
            $away = $registrationIds[$i + 1];

            $winner = null;
            $isPlayed = false;

            // Ha az egyik csapat bye (null), a másik automatikusan továbbjut
            if ($home === null || $away === null) {
                $winner = $home ?? $away;
                $isPlayed = true;
            }

            \App\Models\KnockoutMatch::create([
                'event_id' => $event->id,
                'round' => $bracketSize,
                'match_number' => $matchNumber++,
                'home_registration_id' => $home,
                'away_registration_id' => $away,
                'winner_registration_id' => $winner,
                'is_played' => $isPlayed,
            ]);
        }

        // Generáljuk a közbülső köröket (elődöntőtől felfelé, döntő nélkül)
        // A loop csak round=4-ig megy, a döntőt külön kezeljük
        $currentRound = $bracketSize;
        while ($currentRound > 4) {
            $nextRound = $currentRound / 2;
            $matchesInRound = $nextRound / 2;
            for ($i = 1; $i <= $matchesInRound; $i++) {
                \App\Models\KnockoutMatch::create([
                    'event_id'              => $event->id,
                    'round'                 => $nextRound,
                    'match_number'          => $i,
                    'home_registration_id'  => null,
                    'away_registration_id'  => null,
                    'is_played'             => false,
                ]);
            }
            $currentRound = $nextRound;
        }

        // Döntő
        \App\Models\KnockoutMatch::create([
            'event_id'              => $event->id,
            'round'                 => 2,
            'match_number'          => 1,
            'home_registration_id'  => null,
            'away_registration_id'  => null,
            'is_played'             => false,
        ]);

        // Bronz mérkőzés (3. helyért) – csak ha van elődöntő
        if ($bracketSize >= 4) {
            \App\Models\KnockoutMatch::create([
                'event_id'              => $event->id,
                'round'                 => 2,
                'match_number'          => 2,
                'is_bronze'             => true,
                'home_registration_id'  => null,
                'away_registration_id'  => null,
                'is_played'             => false,
            ]);
        }

        // Ha van bye-ból nyertes, propagáljuk őket a következő körbe
        $this->propagateByeWinners($event);
    }

    private function propagateByeWinners(Event $event): void
    {
        $firstRoundMatches = $event->knockoutMatches()
            ->orderBy('round', 'desc')
            ->orderBy('match_number')
            ->where('is_played', true)
            ->get();

        foreach ($firstRoundMatches as $match) {
            if ($match->winner_registration_id) {
                $this->propagateWinner($event, $match);
            }
        }
    }

    private function propagateWinner(Event $event, $match): void
    {
        $nextRound = $match->round / 2;
        if ($nextRound < 1) return;

        $nextMatchNumber = (int) ceil($match->match_number / 2);
        $nextMatch = $event->knockoutMatches()
            ->where('round', $nextRound)
            ->where('match_number', $nextMatchNumber)
            ->first();

        if (!$nextMatch) return;

        if ($match->match_number % 2 === 1) {
            $nextMatch->update(['home_registration_id' => $match->winner_registration_id]);
        } else {
            $nextMatch->update(['away_registration_id' => $match->winner_registration_id]);
        }

        // Ha mindkét csapat be van töltve és az egyik bye volt, nem kell tovább propagálni
    }
}
