<div class="card" style="margin-top:1.5rem;">
    <div class="card-title">Egyenes kieséses szakasz</div>

    @php
        $matchesByRound = $event->knockoutMatches->sortByDesc('round')->groupBy('round');
        $rounds = $matchesByRound->keys()->sortDesc()->values();
    @endphp

    <div class="bracket">
        <div class="bracket-rounds">
            @foreach($rounds as $round)
                @php $roundMatches = $matchesByRound[$round]->sortBy('match_number'); @endphp
                <div class="bracket-round">
                    <div class="bracket-round-title">
                        @if($round == 2) Döntő
                        @elseif($round == 4) Elődöntő
                        @elseif($round == 8) Negyeddöntő
                        @elseif($round == 16) Nyolcaddöntő
                        @else {{ $round }} csapatos kör
                        @endif
                    </div>

                    @foreach($roundMatches as $match)
                    <div class="bracket-match">
                        @php
                            $homeWin = $match->is_played && $match->winner_registration_id == $match->home_registration_id;
                            $awayWin = $match->is_played && $match->winner_registration_id == $match->away_registration_id;
                        @endphp
                        <div class="bracket-team {{ $homeWin ? 'winner' : '' }} {{ !$match->home_registration_id ? 'tbd' : '' }}">
                            <span>{{ $match->homeRegistration?->team_name ?? 'TBD' }}</span>
                            @if($match->is_played)<span class="bracket-score">{{ $match->home_score }}</span>@endif
                        </div>
                        <div class="bracket-team {{ $awayWin ? 'winner' : '' }} {{ !$match->away_registration_id ? 'tbd' : '' }}">
                            <span>{{ $match->awayRegistration?->team_name ?? 'TBD' }}</span>
                            @if($match->is_played)<span class="bracket-score">{{ $match->away_score }}</span>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    @if($event->status === 'finished')
        @php
            $final = $event->knockoutMatches->where('round', 2)->first();
        @endphp
        @if($final && $final->winner)
        <div class="text-center mt-3">
            <div style="font-size:2rem; margin-bottom:0.5rem;">🏆</div>
            <div style="font-size:1.3rem; font-weight:700; color:#f39c12;">Győztes: {{ $final->winner->team_name }}</div>
        </div>
        @endif
    @endif
</div>
