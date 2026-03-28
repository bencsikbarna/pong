@extends('layouts.app')
@section('title', $event->name)

@section('content')
<div class="flex-between mb-2">
    <div>
        <a href="{{ route('events.index') }}" class="text-muted" style="font-size:0.88rem; text-decoration:none;">← Vissza</a>
        <h1 style="font-size:1.8rem; color:#f39c12; margin-top:0.3rem;">{{ $event->name }}</h1>
    </div>
    <span class="badge status-{{ $event->status }}">{{ $event->status_label }}</span>
</div>

<div class="grid-2 mb-2">
    <div class="card">
        <div class="card-title">Esemény adatok</div>
        <div class="table-wrap">
        <table>
            <tr>
                <td class="text-muted" style="width:45%">Dátum</td>
                <td>{{ $event->event_date->format('Y. m. d. H:i') }}</td>
            </tr>
            @if($event->location)
            <tr>
                <td class="text-muted">Helyszín</td>
                <td>{{ $event->location }}</td>
            </tr>
            @endif
            <tr>
                <td class="text-muted">Csapatok</td>
                <td>{{ $registrations->count() }} / {{ $event->max_teams }}</td>
            </tr>
            @if($event->registration_deadline)
            <tr>
                <td class="text-muted">Nevezési határidő</td>
                <td>{{ $event->registration_deadline->format('Y. m. d. H:i') }}</td>
            </tr>
            @endif
            @if($event->tables_count && $event->status !== 'registration_open')
            <tr>
                <td class="text-muted">Asztalok száma</td>
                <td>{{ $event->tables_count }}</td>
            </tr>
            @endif
        </table>
        </div>

        @if($event->description)
            <hr class="divider">
            <p style="color:#999; font-size:0.9rem; word-break:break-word;">{{ $event->description }}</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Nevezés</div>

        @if($event->isRegistrationOpen() && !$event->isFull())
            @auth('team')
                @if($teamRegistration)
                    <div class="alert alert-success">✓ Csapatod már nevezve van!</div>
                    <form method="POST" action="{{ route('events.cancel', $event) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Biztosan visszalépsz?')">Visszalépés</button>
                    </form>
                @else
                    <p class="text-muted mb-2" style="font-size:0.9rem;">Bejelentkezve mint: <strong class="text-orange">{{ Auth::guard('team')->user()->name }}</strong></p>
                    <form method="POST" action="{{ route('events.register', $event) }}">
                        @csrf
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label style="font-size:0.88rem;">Mit isztok? 🍺</label>
                            <div style="display:flex; gap:1rem; margin-top:0.3rem; flex-wrap:wrap;">
                                <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-weight:normal; font-size:0.9rem;">
                                    <input type="radio" name="drink_preference" value="sor" checked style="width:auto;">
                                    🍺 Sör
                                </label>
                                <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-weight:normal; font-size:0.9rem;">
                                    <input type="radio" name="drink_preference" value="froccs" style="width:auto;">
                                    🥂 Fröccs
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">🏆 Nevezés!</button>
                    </form>
                @endif
            @else
                <p class="text-muted mb-2" style="font-size:0.9rem;">Nevezhetsz regisztráció nélkül is, vagy <a href="{{ route('team.login') }}" class="text-orange">jelentkezz be</a> a statisztikák mentéséhez.</p>
                <a href="{{ route('events.register.form', $event) }}" class="btn btn-primary">Nevezés →</a>
            @endauth
        @elseif($event->isFull())
            <div class="alert alert-warning">Az esemény betelt.</div>
        @else
            <div class="alert alert-warning">A nevezés lezárult.</div>
            @auth('team')
                @if($teamRegistration)
                    <div class="alert alert-success mt-1">✓ Csapatod be van nevezve!</div>
                @endif
            @endauth
        @endif
    </div>
</div>

{{-- Nevezett csapatok --}}
<div class="card">
    <div class="card-title">Nevezett csapatok ({{ $registrations->count() }})</div>
    @if($registrations->isEmpty())
        <p class="text-muted">Még nincs nevezett csapat.</p>
    @else
        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Csapat</th>
                    <th>Státusz</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $i => $reg)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>{{ $reg->team_name }}</td>
                    <td><span class="badge badge-green">Megerősített</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

{{-- Csoportkör --}}
@if(in_array($event->status, ['group_stage', 'knockout_stage', 'finished']) && $event->groups->isNotEmpty())
    <div class="card-title" style="font-size:1.4rem; color:#f39c12; margin-bottom:1rem;">Csoportkör</div>

    @foreach($event->groups as $group)
    <div class="card">
        <div class="card-title">{{ $group->name }}</div>

        {{-- Csoport tabella --}}
        <div class="table-wrap" style="margin-bottom:1.5rem;">
        <table>
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
        </div>

        {{-- Fordulók --}}
        <div class="card-title" style="font-size:0.95rem; color:#888;">Mérkőzések</div>
        @foreach($group->rounds as $round)
        <div style="margin-bottom:1.2rem;">
            <div style="color:#888; font-size:0.85rem; margin-bottom:0.4rem;">{{ $round->round_number }}. forduló</div>
            @foreach($round->matches as $match)
            <div style="display:flex; align-items:center; gap:0.5rem; padding:0.4rem 0; border-bottom:1px solid #1a1a30; font-size:0.88rem; flex-wrap:wrap;">
                <span style="font-size:0.75rem; color:#555; white-space:nowrap;">🎯{{ $match->table_number ?? 1 }}</span>
                <span style="flex:1; text-align:right; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; {{ $match->result === 'home_win' ? 'color:#f39c12;font-weight:700;' : '' }}">
                    {{ $match->homeRegistration->team_name ?? '?' }}
                </span>
                <span style="min-width:2.8rem; text-align:center; font-weight:700; color:{{ $match->is_played ? '#e0e0e0' : '#555' }}; flex-shrink:0;">
                    @if($match->is_played)
                        {{ $match->home_score }} - {{ $match->away_score }}
                    @else
                        vs
                    @endif
                </span>
                <span style="flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; {{ $match->result === 'away_win' ? 'color:#f39c12;font-weight:700;' : '' }}">
                    {{ $match->awayRegistration->team_name ?? '?' }}
                </span>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endforeach
@endif

{{-- Egyenes kiesés --}}
@if(in_array($event->status, ['knockout_stage', 'finished']) && $event->knockoutMatches->isNotEmpty())
    @include('events.partials.bracket', ['event' => $event])
@endif

@endsection
