@extends('layouts.admin')
@section('title', 'Csoportok generálása')

@section('content')
<div class="page-header">
    <a href="{{ route('admin.events.show', $event) }}" class="text-muted" style="font-size:0.88rem; text-decoration:none;">← Vissza</a>
    <h1 style="margin-top:0.3rem;">Csoportok generálása</h1>
    <p>{{ $event->name }} · {{ $teamCount }} nevezett csapat</p>
</div>

<div class="card" style="max-width:540px;">
    <form method="POST" action="{{ route('admin.groups.generate', $event) }}">
        @csrf

        <div class="form-group">
            <label>Csoportméret (hány csapat legyen egy csoportban) *</label>
            <input type="number" name="group_size" value="{{ old('group_size', 4) }}" required min="2" max="{{ $teamCount }}">
            <div class="text-muted mt-1" style="font-size:0.82rem;">
                {{ $teamCount }} csapatból kb. {{ old('group_size', 4) > 0 ? ceil($teamCount / old('group_size', 4)) : '?' }} csoport lesz.
                Ha nem osztható egyenlően, az utolsó csoport kisebb lesz.
            </div>
            @error('group_size') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Asztalok száma (hány meccs futhat egyszerre) *</label>
            <input type="number" name="tables_count" id="tables_count" value="{{ old('tables_count', 2) }}" required min="1" max="20">
            <div class="text-muted mt-1" style="font-size:0.82rem;" id="tables-hint">
                Adott fordulóban maximum ennyi mérkőzés zajlik egyszerre.
            </div>
            @error('tables_count') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div id="tables-warning" style="display:none;" class="alert alert-warning" style="font-size:0.88rem;"></div>

        <div class="alert alert-warning" style="font-size:0.88rem;">
            <strong>Figyelem:</strong> A generálás véletlenszerűen osztja be a csapatokat. Ha már vannak csoportok, azok törlődnek.
        </div>

        <div class="flex-gap mt-2">
            <button type="submit" class="btn btn-primary">Csoportok generálása</button>
            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary">Mégse</a>
        </div>
    </form>
</div>

<script>
const teamCount = {{ $teamCount }};

function updateHint() {
    const groupSize = parseInt(document.querySelector('[name=group_size]').value) || 0;
    const tables = parseInt(document.getElementById('tables_count').value) || 0;
    const numGroups = groupSize > 0 ? Math.ceil(teamCount / groupSize) : 0;
    const warning = document.getElementById('tables-warning');

    if (numGroups > 0 && tables > 0 && tables % numGroups !== 0) {
        const perGroup = Math.floor(tables / numGroups);
        warning.style.display = 'block';
        warning.innerHTML = '<strong>Figyelem:</strong> ' + tables + ' asztal nem osztható egyenlően ' + numGroups + ' csoportra. Csoportonként ' + perGroup + ' asztal jut, ' + (tables % numGroups) + ' asztal kihasználatlan lesz. Ajánlott asztalszám: <strong>' + (perGroup * numGroups) + '</strong> vagy <strong>' + ((perGroup + 1) * numGroups) + '</strong>.';
    } else {
        warning.style.display = 'none';
    }
}

document.querySelector('[name=group_size]').addEventListener('input', updateHint);
document.getElementById('tables_count').addEventListener('input', updateHint);
updateHint();
</script>
@endsection
