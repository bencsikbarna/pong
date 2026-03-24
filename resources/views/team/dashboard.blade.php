@extends('layouts.app')
@section('title', 'Irányítópult')

@section('content')
<div class="page-header">
    <h1>🏆 {{ $team->name }}</h1>
    <p>Csapat irányítópult</p>
</div>

<div class="grid-3 mb-2">
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:#2ecc71;">{{ $team->total_wins }}</div>
        <div class="text-muted" style="font-size:0.9rem;">Győzelmek</div>
    </div>
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:#e74c3c;">{{ $team->total_losses }}</div>
        <div class="text-muted" style="font-size:0.9rem;">Vereségek</div>
    </div>
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:#95a5a6;">{{ $team->total_draws }}</div>
        <div class="text-muted" style="font-size:0.9rem;">Döntetlenek</div>
    </div>
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:#f39c12;">{{ $team->total_cups_scored }}</div>
        <div class="text-muted" style="font-size:0.9rem;">Poharak (szerzett)</div>
    </div>
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:#3498db;">{{ $team->total_cups_conceded }}</div>
        <div class="text-muted" style="font-size:0.9rem;">Poharak (kapott)</div>
    </div>
    <div class="card text-center">
        <div style="font-size:2rem; font-weight:800; color:{{ $team->total_cup_diff >= 0 ? '#2ecc71' : '#e74c3c' }}">
            {{ $team->total_cup_diff > 0 ? '+' : '' }}{{ $team->total_cup_diff }}
        </div>
        <div class="text-muted" style="font-size:0.9rem;">Pohárkülönbség</div>
    </div>
</div>

<div class="card">
    <div class="card-title">Esemény részvételek</div>
    @if($registrations->isEmpty())
        <p class="text-muted">Még nem vettetek részt eseményen.</p>
        <a href="{{ route('events.index') }}" class="btn btn-primary mt-2">Események megtekintése →</a>
    @else
        <table>
            <thead>
                <tr>
                    <th>Esemény</th>
                    <th>Dátum</th>
                    <th>Státusz</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr>
                    <td class="fw-bold">{{ $reg->event->name }}</td>
                    <td class="text-muted">{{ $reg->event->event_date->format('Y.m.d') }}</td>
                    <td><span class="badge status-{{ $reg->event->status }}">{{ $reg->event->status_label }}</span></td>
                    <td><a href="{{ route('events.show', $reg->event) }}" class="btn btn-secondary btn-sm">Megtekintés</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div class="text-right">
    <a href="{{ route('team.profile') }}" class="btn btn-secondary">Profil szerkesztése</a>
</div>
@endsection
