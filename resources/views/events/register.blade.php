@extends('layouts.app')
@section('title', 'Nevezés - ' . $event->name)

@section('content')
<div style="max-width:520px; margin:2rem auto;">
    <a href="{{ route('events.show', $event) }}" class="text-muted" style="font-size:0.88rem; text-decoration:none;">← Vissza az eseményhez</a>

    <div class="card" style="margin-top:1rem;">
        <div class="card-title">Nevezés: {{ $event->name }}</div>
        <p class="text-muted mb-2" style="font-size:0.9rem;">{{ $event->event_date->format('Y. m. d. H:i') }}@if($event->location) · {{ $event->location }}@endif</p>

        <div class="alert alert-warning" style="font-size:0.88rem; margin-bottom:1.5rem;">
            Vendégként nevezel. Ha van csapatfiókotok, <a href="{{ route('team.login') }}" class="text-orange">jelentkezzetek be</a> a statisztikák mentéséhez!
        </div>

        <form method="POST" action="{{ route('events.register', $event) }}">
            @csrf

            <div class="form-group">
                <label>Csapat neve *</label>
                <input type="text" name="guest_team_name" value="{{ old('guest_team_name') }}" required placeholder="pl. Sörtámadók">
                @error('guest_team_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Kapcsolattartó neve *</label>
                <input type="text" name="guest_contact_name" value="{{ old('guest_contact_name') }}" required>
                @error('guest_contact_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email cím *</label>
                <input type="email" name="guest_contact_email" value="{{ old('guest_contact_email') }}" required>
                @error('guest_contact_email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Telefonszám</label>
                <input type="text" name="guest_contact_phone" value="{{ old('guest_contact_phone') }}" placeholder="Opcionális">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">Nevezés →</button>
        </form>
    </div>
</div>
@endsection
