@extends('layouts.admin')
@section('title', 'Esemény szerkesztése')

@section('content')
<div class="page-header">
    <h1>Esemény szerkesztése</h1>
    <p>{{ $event->name }}</p>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="{{ route('admin.events.update', $event) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Esemény neve *</label>
            <input type="text" name="name" value="{{ old('name', $event->name) }}" required>
        </div>

        <div class="form-group">
            <label>Leírás</label>
            <textarea name="description" rows="3">{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Helyszín</label>
            <input type="text" name="location" value="{{ old('location', $event->location) }}">
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Esemény dátuma *</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label>Nevezési határidő</label>
                <input type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}">
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label>Maximum csapatszám *</label>
                <input type="number" name="max_teams" value="{{ old('max_teams', $event->max_teams) }}" required min="2">
            </div>
            <div class="form-group">
                <label>Státusz</label>
                <select name="status">
                    @foreach(['registration_open' => 'Nevezés nyitva', 'registration_closed' => 'Nevezés lezárva', 'group_stage' => 'Csoportkör', 'knockout_stage' => 'Egyenes kiesés', 'finished' => 'Befejezett'] as $val => $label)
                        <option value="{{ $val }}" {{ $event->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex-gap mt-2">
            <button type="submit" class="btn btn-primary">Mentés</button>
            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary">Mégse</a>
        </div>
    </form>
</div>
@endsection
