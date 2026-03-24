@extends('layouts.admin')
@section('title', 'Új esemény')

@section('content')
<div class="page-header">
    <h1>Új esemény létrehozása</h1>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="{{ route('admin.events.store') }}">
        @csrf

        <div class="form-group">
            <label>Esemény neve *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="pl. Tavasz Kupa 2025">
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Leírás</label>
            <textarea name="description" rows="3" placeholder="Opcionális leírás...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Helyszín</label>
            <input type="text" name="location" value="{{ old('location') }}" placeholder="pl. Budapest, XY Bár">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Esemény dátuma *</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required>
                @error('event_date') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Nevezési határidő</label>
                <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Maximum csapatszám *</label>
            <input type="number" name="max_teams" value="{{ old('max_teams', 16) }}" required min="2" max="128">
            @error('max_teams') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="flex-gap mt-2">
            <button type="submit" class="btn btn-primary">Esemény létrehozása</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Mégse</a>
        </div>
    </form>
</div>
@endsection
