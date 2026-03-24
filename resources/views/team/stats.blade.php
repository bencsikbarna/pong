@extends('layouts.app')
@section('title', 'Csapat Statisztikák')

@section('content')
<div class="page-header">
    <h1>📊 Csapat Statisztikák</h1>
    <p>Összes regisztrált csapat eredményei</p>
</div>

<div class="card">
    @if($teams->isEmpty())
        <p class="text-muted text-center" style="padding:2rem;">Még nincs regisztrált csapat.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Csapat</th>
                    <th class="text-center">Események</th>
                    <th class="text-center">Gy</th>
                    <th class="text-center">D</th>
                    <th class="text-center">V</th>
                    <th class="text-center">P+</th>
                    <th class="text-center">P-</th>
                    <th class="text-center">+/-</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $i => $team)
                <tr>
                    <td class="{{ $i < 3 ? 'rank-' . ($i+1) : 'text-muted' }}">{{ $i + 1 }}.</td>
                    <td class="fw-bold">{{ $team->name }}</td>
                    <td class="text-center">{{ $team->total_events }}</td>
                    <td class="text-center text-green">{{ $team->total_wins }}</td>
                    <td class="text-center text-muted">{{ $team->total_draws }}</td>
                    <td class="text-center text-red">{{ $team->total_losses }}</td>
                    <td class="text-center">{{ $team->total_cups_scored }}</td>
                    <td class="text-center">{{ $team->total_cups_conceded }}</td>
                    <td class="text-center {{ $team->total_cup_diff > 0 ? 'text-green' : ($team->total_cup_diff < 0 ? 'text-red' : '') }}">
                        {{ $team->total_cup_diff > 0 ? '+' : '' }}{{ $team->total_cup_diff }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
