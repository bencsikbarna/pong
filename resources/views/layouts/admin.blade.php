<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title', 'Sörpong')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0a0a14; color: #e0e0e0; min-height: 100vh; }

        /* ── TOPBAR (mobile) ── */
        .admin-topbar {
            display: none;
            background: #1a1a2e;
            border-bottom: 1px solid #2a2a4a;
            padding: 0.75rem 1rem;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 200;
        }
        .admin-topbar-brand { color: #9b59b6; font-weight: 700; font-size: 1rem; }
        .admin-menu-toggle { background: none; border: none; color: #9b59b6; font-size: 1.5rem; cursor: pointer; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 220px;
            background: linear-gradient(180deg, #1a1a2e 0%, #13132a 100%);
            border-right: 1px solid #2a2a4a;
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column; z-index: 150;
            transition: transform 0.25s ease;
        }
        .sidebar-brand {
            padding: 1.2rem 1rem;
            font-size: 1rem; font-weight: 700; color: #9b59b6;
            border-bottom: 1px solid #2a2a4a;
        }
        .sidebar-brand a { color: inherit; text-decoration: none; }
        .sidebar-nav { list-style: none; padding: 0.75rem 0; flex: 1; overflow-y: auto; }
        .sidebar-nav li a {
            display: block; padding: 0.6rem 1rem; color: #aaa;
            text-decoration: none; font-size: 0.88rem; transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav li a:hover, .sidebar-nav li a.active {
            background: rgba(155,89,182,0.1); color: #9b59b6; border-left-color: #9b59b6;
        }
        .sidebar-footer { padding: 0.75rem 1rem; border-top: 1px solid #2a2a4a; font-size: 0.82rem; }
        .sidebar-footer button { background: none; border: none; color: #888; cursor: pointer; font-family: inherit; font-size: 0.82rem; }

        /* ── MAIN ── */
        .admin-main { margin-left: 220px; min-height: 100vh; }
        .admin-content { padding: 1.5rem; max-width: 1200px; }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            .admin-topbar { display: flex; }
            .sidebar { transform: translateX(-100%); top: 0; }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.5); }
            .admin-main { margin-left: 0; }
            .admin-content { padding: 1rem; }
        }
        body { overflow-x: hidden; }
        .admin-main { overflow-x: hidden; }
        .card { overflow-wrap: anywhere; word-break: break-word; }
        @media (max-width: 640px) {
            .score-form { gap: 0.2rem; }
            .score-form input[type=number] { width: 2.6rem; font-size:0.85rem; }
            .flex-between, .flex-gap { gap: 0.4rem; }
            .page-header h1 { font-size: 1.2rem; }
            .admin-content { padding: 0.75rem; }
        }

        /* ── Shared styles ── */
        .alert { padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.92rem; }
        .alert-success { background: rgba(39,174,96,0.15); border: 1px solid #27ae60; color: #2ecc71; }
        .alert-error   { background: rgba(231,76,60,0.15);  border: 1px solid #e74c3c; color: #e74c3c; }
        .alert-warning  { background: rgba(243,156,18,0.15); border: 1px solid #f39c12; color: #f39c12; }

        .card { background: #1a1a2e; border: 1px solid #2a2a4a; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.25rem; }
        .card-title { font-size: 1.1rem; font-weight: 700; color: #9b59b6; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #2a2a4a; }

        .btn { display: inline-block; padding: 0.5rem 1.1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; font-family: inherit; text-align: center; }
        .btn-primary  { background: #9b59b6; color: #fff; }
        .btn-primary:hover { background: #8e44ad; }
        .btn-secondary { background: #2a2a4a; color: #ccc; border: 1px solid #3a3a6a; }
        .btn-secondary:hover { background: #3a3a6a; color: #fff; }
        .btn-danger   { background: rgba(231,76,60,0.15); color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-danger:hover { background: #e74c3c; color: #fff; }
        .btn-success  { background: rgba(39,174,96,0.15); color: #2ecc71; border: 1px solid #27ae60; }
        .btn-success:hover { background: #27ae60; color: #fff; }
        .btn-orange   { background: #f39c12; color: #0f0f1a; }
        .btn-orange:hover { background: #e67e22; color: #fff; }
        .btn-sm { padding: 0.28rem 0.65rem; font-size: 0.8rem; }
        .btn-block { width: 100%; display: block; }

        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.3rem; font-size: 0.88rem; color: #aaa; font-weight: 500; }
        input[type=text], input[type=email], input[type=password],
        input[type=number], input[type=datetime-local], select, textarea {
            width: 100%; padding: 0.55rem 0.85rem; background: #0a0a14;
            border: 1px solid #2a2a4a; border-radius: 8px; color: #e0e0e0;
            font-size: 0.95rem; font-family: inherit; transition: border-color 0.2s;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #9b59b6; }
        .field-error { color: #e74c3c; font-size: 0.8rem; margin-top: 0.25rem; }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; min-width: 400px; }
        th { background: #1e1e3a; color: #9b59b6; padding: 0.6rem 0.75rem; text-align: left; font-weight: 600; font-size: 0.82rem; white-space: nowrap; }
        td { padding: 0.55rem 0.75rem; border-bottom: 1px solid #1a1a30; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(155,89,182,0.04); }

        .badge { display: inline-block; padding: 0.18rem 0.55rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
        .badge-green  { background: rgba(39,174,96,0.2);  color: #2ecc71; }
        .badge-orange { background: rgba(243,156,18,0.2); color: #f39c12; }
        .badge-red    { background: rgba(231,76,60,0.2);  color: #e74c3c; }
        .badge-blue   { background: rgba(52,152,219,0.2); color: #3498db; }
        .badge-purple { background: rgba(155,89,182,0.2); color: #9b59b6; }
        .badge-gray   { background: rgba(149,165,166,0.2);color: #95a5a6; }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.2rem; }
        .page-header p  { color: #888; font-size: 0.92rem; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }
        @media (max-width: 768px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

        .status-registration_open   { background: rgba(39,174,96,0.2);  color: #2ecc71; }
        .status-registration_closed { background: rgba(243,156,18,0.2); color: #f39c12; }
        .status-group_stage         { background: rgba(52,152,219,0.2); color: #3498db; }
        .status-knockout_stage      { background: rgba(155,89,182,0.2); color: #9b59b6; }
        .status-finished            { background: rgba(149,165,166,0.2);color: #95a5a6; }

        .bracket { overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 0.5rem 0; }
        .bracket-rounds { display: flex; gap: 2rem; align-items: flex-start; min-width: max-content; }
        .bracket-round  { display: flex; flex-direction: column; gap: 1rem; min-width: 180px; }
        .bracket-round-title { text-align: center; font-size: 0.8rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.4rem; text-transform: uppercase; }
        .bracket-match  { background: #1e1e3a; border: 1px solid #2a2a4a; border-radius: 10px; overflow: hidden; }
        .bracket-team   { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.8rem; border-bottom: 1px solid #1a1a2e; font-size: 0.85rem; }
        .bracket-team:last-child { border-bottom: none; }
        .bracket-team.winner { background: rgba(155,89,182,0.15); color: #9b59b6; font-weight: 700; }
        .bracket-team.tbd    { color: #555; font-style: italic; }
        .bracket-score { font-weight: 700; min-width: 1.4rem; text-align: right; }

        .score-form { display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
        .score-form input[type=number] { width: 3.2rem; text-align: center; padding: 0.28rem; min-width: 0; }

        .text-center  { text-align: center; }
        .text-right   { text-align: right; }
        .mt-1 { margin-top: 0.5rem; } .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
        .flex-gap     { display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap; }
        .fw-bold  { font-weight: 700; }
        .text-muted   { color: #666; }
        .text-orange  { color: #f39c12; }
        .text-green   { color: #2ecc71; }
        .text-red     { color: #e74c3c; }
        .divider { border: none; border-top: 1px solid #2a2a4a; margin: 1.25rem 0; }
        .rank-1 { color: #f39c12; font-weight: 700; }
        .rank-2 { color: #95a5a6; font-weight: 700; }
        .rank-3 { color: #cd7f32; font-weight: 700; }
    </style>
</head>
<body>

<div class="admin-topbar">
    <span class="admin-topbar-brand">🍺 Admin</span>
    <button class="admin-menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>
</div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand"><a href="{{ route('admin.events.index') }}">🍺 Admin Panel</a></div>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">📅 Események</a></li>
        <li><a href="{{ route('events.index') }}" target="_blank">🌐 Nyilvános oldal</a></li>
    </ul>
    <div class="sidebar-footer">
        <div style="color:#666; margin-bottom:0.4rem;">{{ Auth::user()->name ?? '' }}</div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">Kilépés ↗</button>
        </form>
    </div>
</div>

<div class="admin-main">
    <div class="admin-content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)<div>✗ {{ $error }}</div>@endforeach
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    if (sidebar && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !e.target.closest('.admin-menu-toggle')) {
        sidebar.classList.remove('open');
    }
});
</script>
</body>
</html>
