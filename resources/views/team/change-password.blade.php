@extends('layouts.app')
@section('title', 'Jelszóváltoztatás')

@section('content')
<div style="max-width:480px; margin:2rem auto;">
    <div class="page-header">
        <h1>Jelszóváltoztatás</h1>
    </div>

    <div class="card">
        <div class="card-title">{{ $team->name }}</div>

        <form method="POST" action="{{ route('team.password.change') }}">
            @csrf
            <div class="form-group">
                <label>Jelenlegi jelszó</label>
                <input type="password" name="current_password" required autofocus>
                @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Új jelszó</label>
                <input type="password" name="password" required>
                @error('password') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Új jelszó megerősítése</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <div class="flex-gap">
                <button type="submit" class="btn btn-primary">Jelszó megváltoztatása</button>
                <a href="{{ route('team.profile') }}" class="btn btn-secondary">Vissza</a>
            </div>
        </form>
    </div>
</div>
@endsection
