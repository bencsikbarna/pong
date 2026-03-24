@extends('layouts.app')
@section('title', 'Admin Bejelentkezés')

@section('content')
<div style="max-width:440px; margin:3rem auto;">
    <div class="card" style="border-color:#9b59b6;">
        <div class="card-title" style="color:#9b59b6;">🔒 Admin Bejelentkezés</div>

        <form method="POST" action="{{ route('admin.login') }}">
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

            <button type="submit" class="btn" style="width:100%; background:#9b59b6; color:#fff;">Admin Bejelentkezés</button>
        </form>

        <hr class="divider">
        <p class="text-center text-muted" style="font-size:0.88rem;">
            <a href="{{ route('team.login') }}" class="text-orange">← Csapat bejelentkezés</a>
        </p>
    </div>
</div>
@endsection
