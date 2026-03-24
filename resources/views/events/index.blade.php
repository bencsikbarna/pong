@extends('layouts.app')
@section('title', 'Események')

@section('content')
<div class="hero-section">
    <div style="font-size:3rem; margin-bottom:0.5rem;">🍺</div>
    <h1>Sörpong Bajnokság</h1>
    <p>Nevezz be a közelgő eseményekre, és mutasd meg ki a legjobb csapat!</p>
    <div class="mt-2 flex-gap" style="justify-content:center;">
        <a href="{{ route('teams.stats') }}" class="btn btn-secondary">📊 Csapat statisztikák</a>
        @guest('team')
            <a href="{{ route('team.register') }}" class="btn btn-primary">🏆 Csapat regisztráció</a>
        @endguest
    </div>
</div>

<div class="flex-between mb-2">
    <h2 style="color:#f39c12;">Közelgő és aktív események</h2>
</div>

@if($events->isEmpty())
    <div class="card text-center" style="padding:3rem;">
        <div style="font-size:2.5rem; margin-bottom:1rem;">😕</div>
        <p style="color:#888;">Jelenleg nincs aktív esemény. Gyere vissza hamarosan!</p>
    </div>
@else
    <div class="grid-2">
        @foreach($events as $event)
        <div class="card" style="cursor:pointer;" onclick="location.href='{{ route('events.show', $event) }}'">
            <div class="flex-between mb-1">
                <span class="badge badge status-{{ $event->status }}">{{ $event->status_label }}</span>
                <span class="text-muted" style="font-size:0.85rem;">{{ $event->event_date->format('Y.m.d H:i') }}</span>
            </div>

            <h3 style="font-size:1.2rem; color:#e0e0e0; margin-bottom:0.4rem;">{{ $event->name }}</h3>

            @if($event->location)
                <p style="color:#888; font-size:0.88rem; margin-bottom:0.5rem;">📍 {{ $event->location }}</p>
            @endif

            @if($event->description)
                <p style="color:#999; font-size:0.88rem; margin-bottom:0.8rem;">{{ Str::limit($event->description, 100) }}</p>
            @endif

            <div class="flex-gap" style="font-size:0.85rem; color:#888;">
                <span>👥 {{ $event->confirmed_registrations_count ?? 0 }} / {{ $event->max_teams }} csapat</span>
                @if($event->registration_deadline)
                    <span>⏰ Határidő: {{ $event->registration_deadline->format('m.d H:i') }}</span>
                @endif
            </div>

            @if($event->isRegistrationOpen() && !$event->isFull())
                <div class="mt-2">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">Nevezés →</a>
                </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-2">{{ $events->links() }}</div>
@endif
@endsection
