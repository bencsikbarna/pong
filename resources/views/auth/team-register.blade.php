@extends('layouts.app')
@section('title', 'Csapat Regisztráció')

@section('content')
<div style="max-width:480px; margin:3rem auto;">
    <div class="card">
        <div class="card-title">🏆 Csapat Regisztráció</div>
        <p class="text-muted mb-2">Regisztrálj csapatként, hogy mentse a rendszer a statisztikáitokat!</p>

        <form method="POST" action="{{ route('team.register') }}">
            @csrf

            <div class="form-group">
                <label>Csapat neve *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="pl. Sörtámadók">
                @error('name') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email cím *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="csapat@email.hu">
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jelszó *</label>
                <input type="password" name="password" required placeholder="Min. 6 karakter">
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jelszó megerősítése *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label>Kapcsolattartó neve</label>
                <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Opcionális">
            </div>

            <div class="form-group">
                <label>Telefonszám</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="Opcionális">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">Regisztráció</button>
        </form>

        <hr class="divider">
        <p class="text-center text-muted" style="font-size:0.9rem;">
            Már van fiókotok? <a href="{{ route('team.login') }}" class="text-orange">Bejelentkezés</a>
        </p>
    </div>
</div>
@endsection
