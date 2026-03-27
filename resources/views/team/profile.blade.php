@extends('layouts.app')
@section('title', 'Profil')

@section('content')
<div style="max-width:480px; margin:2rem auto;">
    <div class="page-header">
        <h1>Profil szerkesztése</h1>
    </div>

    <div class="card">
        <div class="card-title">{{ $team->name }}</div>
        <p class="text-muted mb-2" style="font-size:0.9rem;">Email: {{ $team->email }}</p>

        <form method="POST" action="{{ route('team.profile.update') }}">
            @csrf

            <div class="form-group">
                <label>Kapcsolattartó neve</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $team->contact_name) }}">
            </div>

            <div class="form-group">
                <label>Telefonszám</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $team->contact_phone) }}">
            </div>

            <button type="submit" class="btn btn-primary">Mentés</button>
            <a href="{{ route('team.dashboard') }}" class="btn btn-secondary">Vissza</a>
        </form>

        <hr class="divider">
        <p style="font-size:0.9rem; color:#888; margin-bottom:0.75rem;">Jelszó módosítása</p>
        <a href="{{ route('team.password.change.form') }}" class="btn btn-secondary">🔒 Jelszóváltoztatás</a>
    </div>
</div>
@endsection
