<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Sörpong Bajnokság')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a14;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #1a1a2e 0%, #13132a 100%);
            border-right: 1px solid #2a2a4a;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 1.5rem 1.2rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: #9b59b6;
            border-bottom: 1px solid #2a2a4a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand a {
            color: inherit;
            text-decoration: none;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1rem 0;
            flex: 1;
        }

        .sidebar-nav li a {
            display: block;
            padding: 0.65rem 1.2rem;
            color: #aaa;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav li a:hover, .sidebar-nav li a.active {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
            border-left-color: #9b59b6;
        }

        .sidebar-footer {
            padding: 1rem 1.2rem;
            border-top: 1px solid #2a2a4a;
            font-size: 0.85rem;
        }

        .sidebar-footer a {
            color: #888;
            text-decoration: none;
        }

        /* MAIN */
        .admin-main {
            margin-left: 240px;
            flex: 1;
            min-height: 100vh;
        }

        .admin-topbar {
            background: #1a1a2e;
            border-bottom: 1px solid #2a2a4a;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-topbar h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ccc;
        }

        .admin-content {
            padding: 2rem;
            max-width: 1200px;
        }

        /* Reuse styles from app.blade.php */
        .alert { padding: 0.9rem 1.2rem; border-radius: 8px; margin-bottom: 1.2rem; font-size: 0.95rem; }
        .alert-success { background: rgba(39,174,96,0.2); border: 1px solid #27ae60; color: #2ecc71; }
        .alert-error { background: rgba(231,76,60,0.2); border: 1px solid #e74c3c; color: #e74c3c; }
        .alert-warning { background: rgba(243,156,18,0.2); border: 1px solid #f39c12; color: #f39c12; }

        .card { background: #1a1a2e; border: 1px solid #2a2a4a; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-title { font-size: 1.2rem; font-weight: 700; color: #9b59b6; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #2a2a4a; }

        .btn { display: inline-block; padding: 0.55rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; font-family: inherit; }
        .btn-primary { background: #9b59b6; color: #fff; }
        .btn-primary:hover { background: #8e44ad; }
        .btn-secondary { background: #2a2a4a; color: #ccc; border: 1px solid #3a3a6a; }
        .btn-secondary:hover { background: #3a3a6a; color: #fff; }
        .btn-danger { background: rgba(231,76,60,0.2); color: #e74c3c; border: 1px solid #e74c3c; }
        .btn-danger:hover { background: #e74c3c; color: #fff; }
        .btn-success { background: rgba(39,174,96,0.2); color: #2ecc71; border: 1px solid #27ae60; }
        .btn-success:hover { background: #27ae60; color: #fff; }
        .btn-orange { background: #f39c12; color: #0f0f1a; }
        .btn-orange:hover { background: #e67e22; color: #fff; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.82rem; }

        .form-group { margin-bottom: 1.1rem; }
        label { display: block; margin-bottom: 0.35rem; font-size: 0.9rem; color: #aaa; font-weight: 500; }
        input, select, textarea { width: 100%; padding: 0.6rem 0.9rem; background: #0a0a14; border: 1px solid #2a2a4a; border-radius: 8px; color: #e0e0e0; font-size: 0.95rem; font-family: inherit; transition: border-color 0.2s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #9b59b6; }
        .field-error { color: #e74c3c; font-size: 0.82rem; margin-top: 0.3rem; }

        table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
        th { background: #1e1e3a; color: #9b59b6; padding: 0.7rem 0.9rem; text-align: left; font-weight: 600; font-size: 0.85rem; letter-spacing: 0.03em; }
        td { padding: 0.65rem 0.9rem; border-bottom: 1px solid #1a1a30; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(155,89,182,0.04); }

        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-green { background: rgba(39,174,96,0.2); color: #2ecc71; }
        .badge-orange { background: rgba(243,156,18,0.2); color: #f39c12; }
        .badge-red { background: rgba(231,76,60,0.2); color: #e74c3c; }
        .badge-blue { background: rgba(52,152,219,0.2); color: #3498db; }
        .badge-purple { background: rgba(155,89,182,0.2); color: #9b59b6; }
        .badge-gray { background: rgba(149,165,166,0.2); color: #95a5a6; }

        .page-header { margin-bottom: 2rem; }
        .page-header h1 { font-size: 1.8rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.3rem; }
        .page-header p { color: #888; font-size: 0.95rem; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }

        .status-registration_open { background: rgba(39,174,96,0.2); color: #2ecc71; }
        .status-registration_closed { background: rgba(243,156,18,0.2); color: #f39c12; }
        .status-group_stage { background: rgba(52,152,219,0.2); color: #3498db; }
        .status-knockout_stage { background: rgba(155,89,182,0.2); color: #9b59b6; }
        .status-finished { background: rgba(149,165,166,0.2); color: #95a5a6; }

        .score-form { display: flex; align-items: center; gap: 0.4rem; }
        .score-form input[type=number] { width: 3.5rem; text-align: center; padding: 0.3rem; }

        .bracket { overflow-x: auto; padding: 1rem 0; }
        .bracket-rounds { display: flex; gap: 3rem; align-items: stretch; min-width: max-content; }
        .bracket-round { display: flex; flex-direction: column; gap: 1rem; min-width: 220px; }
        .bracket-round-title { text-align: center; font-size: 0.85rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .bracket-match { background: #1e1e3a; border: 1px solid #2a2a4a; border-radius: 10px; overflow: hidden; }
        .bracket-team { display: flex; justify-content: space-between; align-items: center; padding: 0.55rem 0.9rem; border-bottom: 1px solid #1a1a2e; font-size: 0.88rem; }
        .bracket-team:last-child { border-bottom: none; }
        .bracket-team.winner { background: rgba(155,89,182,0.15); color: #9b59b6; font-weight: 700; }
        .bracket-team.tbd { color: #555; font-style: italic; }
        .bracket-score { font-weight: 700; min-width: 1.5rem; text-align: right; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .flex { display: flex; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .flex-gap { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .fw-bold { font-weight: 700; }
        .text-muted { color: #666; }
        .text-orange { color: #f39c12; }
        .text-green { color: #2ecc71; }
        .text-red { color: #e74c3c; }
        .divider { border: none; border-top: 1px solid #2a2a4a; margin: 1.5rem 0; }
        .rank-1 { color: #f39c12; font-weight: 700; }
        .rank-2 { color: #95a5a6; font-weight: 700; }
        .rank-3 { color: #cd7f32; font-weight: 700; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .admin-main { margin-left: 0; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        🍺 <a href="{{ route('admin.events.index') }}">Admin Panel</a>
    </div>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">📅 Események</a></li>
        <li><a href="{{ route('events.index') }}" target="_blank">🌐 Nyilvános oldal</a></li>
    </ul>
    <div class="sidebar-footer">
        <div style="color:#666; margin-bottom:0.5rem; font-size:0.82rem;">Admin: {{ Auth::user()->name ?? '' }}</div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#888;cursor:pointer;font-family:inherit;font-size:0.85rem;padding:0;">Kilépés ↗</button>
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
                @foreach($errors->all() as $error)
                    <div>✗ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>
