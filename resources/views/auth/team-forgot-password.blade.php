@extends('layouts.app')
@section('title', 'Elfelejtett jelszó')

@section('content')
<div style="max-width:440px; margin:3rem auto;">
    <div class="card">
        <div class="card-title">🔑 Elfelejtett jelszó</div>
        <p class="text-muted mb-2" style="font-size:0.9rem;">Add meg a csapat email-címét és küldünk egy visszaállító linket.</p>

        <form method="POST" action="{{ route('team.password.forgot') }}">
            @csrf
            <div class="form-group">
                <label>Email cím</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block">Link küldése</button>
        </form>

        <hr class="divider">
        <p class="text-center text-muted" style="font-size:0.88rem;">
            <a href="{{ route('team.login') }}" class="text-orange">← Vissza a bejelentkezéshez</a>
        </p>
    </div>
</div>
@endsection
