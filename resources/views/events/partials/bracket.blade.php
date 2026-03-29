<div class="card" style="margin-top:1.5rem;">
    <div class="card-title">Egyenes kieséses szakasz</div>

    @php
        $allMatches = $event->knockoutMatches;
        $mainMatches = $allMatches->where('is_bronze', false)->sortByDesc('round')->groupBy('round');
        $bronzeMatch = $allMatches->where('is_bronze', true)->first();
        $rounds = $mainMatches->keys()->sortDesc()->values();
    @endphp

    <div class="bracket">
        <div class="bracket-rounds">
            @foreach($rounds as $round)
                @php $roundMatches = $mainMatches[$round]->sortBy('match_number'); @endphp
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
                    <div>
                        @if($match->table_number ?? false)
                        <div style="font-size:0.72rem; color:#555; text-align:center; margin-bottom:2px;">🎯 {{ $match->table_number }}. asztal</div>
                        @endif
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
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bronz mérkőzés --}}
    @if($bronzeMatch)
    <div style="margin-top:1.5rem; border-top:1px solid #2a2a4a; padding-top:1rem;">
        <div style="font-size:0.8rem; font-weight:700; color:#cd7f32; text-transform:uppercase; margin-bottom:0.6rem;">🥉 3. helyért – Bronz mérkőzés@if($bronzeMatch->table_number ?? false) &nbsp;<span style="font-weight:400; color:#555;">🎯 {{ $bronzeMatch->table_number }}. asztal</span>@endif</div>
        <div class="bracket-match" style="max-width:260px;">
            @php
                $bHomeWin = $bronzeMatch->is_played && $bronzeMatch->winner_registration_id == $bronzeMatch->home_registration_id;
                $bAwayWin = $bronzeMatch->is_played && $bronzeMatch->winner_registration_id == $bronzeMatch->away_registration_id;
            @endphp
            <div class="bracket-team {{ $bHomeWin ? 'winner' : '' }} {{ !$bronzeMatch->home_registration_id ? 'tbd' : '' }}">
                <span>{{ $bronzeMatch->homeRegistration?->team_name ?? 'TBD' }}</span>
                @if($bronzeMatch->is_played)<span class="bracket-score">{{ $bronzeMatch->home_score }}</span>@endif
            </div>
            <div class="bracket-team {{ $bAwayWin ? 'winner' : '' }} {{ !$bronzeMatch->away_registration_id ? 'tbd' : '' }}">
                <span>{{ $bronzeMatch->awayRegistration?->team_name ?? 'TBD' }}</span>
                @if($bronzeMatch->is_played)<span class="bracket-score">{{ $bronzeMatch->away_score }}</span>@endif
            </div>
        </div>
    </div>
    @endif

    @if($event->status === 'finished')
        @php
            $final = $allMatches->where('round', 2)->where('is_bronze', false)->first();
        @endphp
        @if($final && $final->winner)
        <div class="text-center mt-3">
            <div style="font-size:2rem; margin-bottom:0.5rem;">🏆</div>
            <div style="font-size:1.3rem; font-weight:700; color:#f39c12;">Győztes: {{ $final->winner->team_name }}</div>
        </div>
        @endif
    @endif
</div>
