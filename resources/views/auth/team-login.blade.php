@extends('layouts.app')
@section('title', 'Csapat Bejelentkezés')

@section('content')
<div style="max-width:440px; margin:3rem auto;">
    <div class="card">
        <div class="card-title">🍺 Csapat Bejelentkezés</div>

        <form method="POST" action="{{ route('team.login') }}">
            @csrf

            <div class="form-group">
                <label>Email cím</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jelszó</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width:auto">
                <label for="remember" style="margin:0; font-size:0.88rem;">Emlékezz rám</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">Bejelentkezés</button>
        </form>

        <hr class="divider">
        <p class="text-center text-muted" style="font-size:0.9rem;">
            Még nincs fiókotok? <a href="{{ route('team.register') }}" class="text-orange">Regisztráció</a>
        </p>
        <p class="text-center text-muted mt-1" style="font-size:0.88rem;">
            <a href="{{ route('team.password.forgot.form') }}" style="color:#888;">Elfelejtett jelszó?</a>
        </p>
        <p class="text-center text-muted mt-1" style="font-size:0.88rem;">
            <a href="{{ route('admin.login') }}" style="color:#9b59b6">Admin bejelentkezés →</a>
        </p>
    </div>
</div>
@endsection
