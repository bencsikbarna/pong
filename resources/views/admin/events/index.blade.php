@extends('layouts.admin')
@section('title', 'Események')

@section('content')
<div class="flex-between page-header">
    <div>
        <h1>Események</h1>
        <p>Összes verseny esemény kezelése</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">+ Új esemény</a>
</div>

<div class="card">
    @if($events->isEmpty())
        <p class="text-muted text-center" style="padding:2rem;">Még nincs létrehozva esemény.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Esemény neve</th>
                    <th>Dátum</th>
                    <th>Csapatok</th>
                    <th>Státusz</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td class="fw-bold">{{ $event->name }}</td>
                    <td class="text-muted">{{ $event->event_date->format('Y.m.d H:i') }}</td>
                    <td>{{ $event->confirmed_registrations_count }} / {{ $event->max_teams }}</td>
                    <td><span class="badge status-{{ $event->status }}">{{ $event->status_label }}</span></td>
                    <td class="text-right">
                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary btn-sm">Kezelés</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
