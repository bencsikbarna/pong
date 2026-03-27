@extends('layouts.app')
@section('title', 'Jelszó visszaállítás')

@section('content')
<div style="max-width:440px; margin:3rem auto;">
    <div class="card">
        <div class="card-title">🔒 Új jelszó beállítása</div>

        <form method="POST" action="{{ route('team.password.reset') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label>Email cím</label>
                <input type="email" value="{{ $email }}" disabled style="opacity:0.6;">
            </div>
            <div class="form-group">
                <label>Új jelszó</label>
                <input type="password" name="password" required autofocus>
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Jelszó megerősítése</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Jelszó beállítása</button>
        </form>
    </div>
</div>
@endsection
