@extends('layouts.admin')
@section('title', $event->name)

@section('content')
<div class="flex-between page-header">
    <div>
        <a href="{{ route('admin.events.index') }}" class="text-muted" style="font-size:0.88rem; text-decoration:none;">← Vissza</a>
        <h1 style="margin-top:0.3rem;">{{ $event->name }}</h1>
        <p>{{ $event->event_date->format('Y. m. d. H:i') }}@if($event->location) · {{ $event->location }}@endif</p>
    </div>
    <div class="flex-gap">
        <span class="badge status-{{ $event->status }}">{{ $event->status_label }}</span>
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">Szerkesztés</a>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-sm" target="_blank">Nyilvános nézet ↗</a>
    </div>
</div>

{{-- Admin akciók sávja --}}
<div class="card">
    <div class="card-title">Admin műveletek</div>
    <div class="flex-gap">
        @if($event->status === 'registration_open')
            <form method="POST" action="{{ route('admin.events.close-registration', $event) }}">
                @csrf
                <button type="submit" class="btn btn-orange" onclick="return confirm('Biztosan lezárod a nevezést?')">Nevezés lezárása</button>
            </form>
        @endif

        @if($event->status === 'registration_closed')
            <a href="{{ route('admin.groups.generate.form', $event) }}" class="btn btn-primary">Csoportok generálása →</a>
        @endif

        @if($event->status === 'group_stage')
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <form method="POST" action="{{ route('admin.groups.advance', $event) }}" style="display:flex; gap:0.5rem; align-items:center;">
                    @csrf
                    <label style="margin:0; white-space:nowrap;">Továbbjutók csoportonként:</label>
                    <input type="number" name="teams_to_advance" value="2" min="1" max="8" style="width:4rem;">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Generálod az egyenes kiesést?')">Egyenes kiesés generálása →</button>
                </form>
            </div>
        @endif
    </div>
</div>

{{-- Nevezések --}}
<div class="card">
    <div class="flex-between mb-2">
        <div class="card-title" style="margin:0;">Nevezett csapatok ({{ $event->confirmedRegistrations->count() }} / {{ $event->max_teams }})</div>
    </div>

    @if($event->confirmedRegistrations->isEmpty())
        <p class="text-muted">Még nincs nevezett csapat.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Csapat</th>
                    <th>Típus</th>
                    <th>Ital</th>
                    <th>Kapcsolattartó</th>
                    @if(in_array($event->status, ['registration_open', 'registration_closed']))
                        <th></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($event->confirmedRegistrations as $i => $reg)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td class="fw-bold">{{ $reg->team_name }}</td>
                    <td>
                        @if($reg->team_id)
                            <span class="badge badge-blue">Regisztrált</span>
                        @else
                            <span class="badge badge-gray">Vendég</span>
                        @endif
                    </td>
                    <td style="font-size:0.9rem;">
                        @if($reg->drink_preference === 'froccs')
                            🥂 Fröccs
                        @else
                            🍺 Sör
                        @endif
                    </td>
                    <td class="text-muted" style="font-size:0.85rem;">
                        {{ $reg->team_id ? $reg->team?->contact_name : $reg->guest_contact_name }}
                        @if($reg->contact_email) <br>{{ $reg->contact_email }} @endif
                    </td>
                    @if(in_array($event->status, ['registration_open', 'registration_closed']))
                    <td>
                        <form method="POST" action="{{ route('admin.events.remove-registration', [$event, $reg->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Törli a nevezést?')">Törlés</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Csoportkör --}}
@if(in_array($event->status, ['group_stage', 'knockout_stage', 'finished']) && $event->groups->isNotEmpty())
<div class="card-title" style="font-size:1.3rem; color:#9b59b6; margin-bottom:1rem;">Csoportkör</div>

@foreach($event->groups as $group)
<div class="card">
    <div class="card-title">{{ $group->name }}</div>

    {{-- Csoport tabella --}}
    <table style="margin-bottom:1.5rem;">
        <thead>
            <tr>
                <th>#</th>
                <th>Csapat</th>
                <th class="text-center">M</th>
                <th class="text-center">Gy</th>
                <th class="text-center">D</th>
                <th class="text-center">V</th>
                <th class="text-center">P+</th>
                <th class="text-center">P-</th>
                <th class="text-center">+/-</th>
                <th class="text-center">Pont</th>
            </tr>
        </thead>
        <tbody>
            @foreach($group->groupTeams as $i => $gt)
            <tr>
                <td class="rank-{{ $i + 1 }}">{{ $i + 1 }}.</td>
                <td class="fw-bold">{{ $gt->team_name }}</td>
                <td class="text-center">{{ $gt->played }}</td>
                <td class="text-center text-green">{{ $gt->wins }}</td>
                <td class="text-center text-muted">{{ $gt->draws }}</td>
                <td class="text-center text-red">{{ $gt->losses }}</td>
                <td class="text-center">{{ $gt->cups_scored }}</td>
                <td class="text-center">{{ $gt->cups_conceded }}</td>
                <td class="text-center {{ $gt->cup_diff > 0 ? 'text-green' : ($gt->cup_diff < 0 ? 'text-red' : '') }}">
                    {{ $gt->cup_diff > 0 ? '+' : '' }}{{ $gt->cup_diff }}
                </td>
                <td class="text-center fw-bold text-orange">{{ $gt->points }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Fordulók eredményrögzítéssel --}}
    @if($event->status === 'group_stage')
    @foreach($group->rounds as $round)
    <div style="margin-bottom:1.5rem;">
        <div style="color:#888; font-size:0.85rem; margin-bottom:0.5rem; font-weight:600;">{{ $round->round_number }}. forduló</div>
        @foreach($round->matches as $match)
        <div style="background:#1e1e3a; border-radius:8px; padding:0.8rem 1rem; margin-bottom:0.5rem; display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <span style="flex:1; text-align:right; font-weight:{{ $match->result === 'home_win' ? '700' : '400' }}; color:{{ $match->result === 'home_win' ? '#f39c12' : '#e0e0e0' }}; min-width:120px;">
                {{ $match->homeRegistration->team_name ?? '?' }}
            </span>

            @if($match->is_played)
                <span style="font-size:1.1rem; font-weight:700; color:#e0e0e0; min-width:3rem; text-align:center;">
                    {{ $match->home_score }} - {{ $match->away_score }}
                </span>
            @else
                <span style="color:#555; min-width:3rem; text-align:center;">vs</span>
            @endif

            <span style="flex:1; font-weight:{{ $match->result === 'away_win' ? '700' : '400' }}; color:{{ $match->result === 'away_win' ? '#f39c12' : '#e0e0e0' }}; min-width:120px;">
                {{ $match->awayRegistration->team_name ?? '?' }}
            </span>

            <form method="POST" action="{{ route('admin.matches.result', [$event, $match->id]) }}" class="score-form">
                @csrf
                <input type="number" name="home_score" value="{{ $match->home_score }}" min="0" max="10" placeholder="0" style="width:3.5rem;">
                <span class="text-muted">-</span>
                <input type="number" name="away_score" value="{{ $match->away_score }}" min="0" max="10" placeholder="0" style="width:3.5rem;">
                <button type="submit" class="btn btn-success btn-sm">{{ $match->is_played ? 'Frissít' : 'Rögzít' }}</button>
            </form>
        </div>
        @endforeach
    </div>
    @endforeach
    @else
    {{-- Csak megtekintés --}}
    @foreach($group->rounds as $round)
    <div style="margin-bottom:1rem;">
        <div style="color:#888; font-size:0.85rem; margin-bottom:0.4rem;">{{ $round->round_number }}. forduló</div>
        @foreach($round->matches as $match)
        <div style="display:flex; align-items:center; gap:0.8rem; padding:0.4rem 0; border-bottom:1px solid #1a1a30; font-size:0.9rem;">
            <span style="flex:1; text-align:right;">{{ $match->homeRegistration->team_name ?? '?' }}</span>
            <span style="font-weight:700; min-width:3rem; text-align:center;">
                @if($match->is_played) {{ $match->home_score }} - {{ $match->away_score }} @else vs @endif
            </span>
            <span style="flex:1;">{{ $match->awayRegistration->team_name ?? '?' }}</span>
        </div>
        @endforeach
    </div>
    @endforeach
    @endif
</div>
@endforeach
@endif

{{-- Egyenes kiesés --}}
@if(in_array($event->status, ['knockout_stage', 'finished']) && $event->knockoutMatches->isNotEmpty())
<div class="card">
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
                        @else {{ $round }} csapatos kör @endif
                    </div>

                    @foreach($roundMatches as $match)
                    <div style="margin-bottom:1rem;">
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

                        @if($event->status === 'knockout_stage' && $match->home_registration_id && $match->away_registration_id && !$match->is_played)
                        <form method="POST" action="{{ route('admin.knockout.result', [$event, $match]) }}" class="score-form" style="margin-top:0.4rem; justify-content:center;">
                            @csrf
                            <input type="number" name="home_score" min="0" max="10" placeholder="0" style="width:3rem;">
                            <span class="text-muted">-</span>
                            <input type="number" name="away_score" min="0" max="10" placeholder="0" style="width:3rem;">
                            <button type="submit" class="btn btn-success btn-sm">Rögzít</button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    @if($event->status === 'finished')
        @php $final = $event->knockoutMatches->where('round', 2)->first(); @endphp
        @if($final && $final->winner)
        <div class="text-center mt-3">
            <div style="font-size:2rem; margin-bottom:0.5rem;">🏆</div>
            <div style="font-size:1.3rem; font-weight:700; color:#f39c12;">Győztes: {{ $final->winner->team_name }}</div>
        </div>
        @endif
    @endif
</div>
@endif

@endsection
