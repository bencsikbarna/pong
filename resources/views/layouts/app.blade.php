<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sörpong Bajnokság')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f0f1a;
            color: #e0e0e0;
            min-height: 100vh;
        }

        /* NAVBAR */
        nav {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-bottom: 2px solid #f39c12;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(243, 156, 18, 0.3);
        }

        .nav-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #f39c12;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-brand span { font-size: 1.6rem; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }

        .nav-links a, .nav-links button {
            color: #ccc;
            text-decoration: none;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            transition: all 0.2s;
            font-size: 0.9rem;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .nav-links a:hover, .nav-links button:hover {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .nav-links .btn-primary {
            background: #f39c12;
            color: #0f0f1a;
            font-weight: 600;
        }

        .nav-links .btn-primary:hover {
            background: #e67e22;
            color: #fff;
        }

        .nav-user {
            color: #f39c12;
            font-weight: 600;
            padding: 0.4rem 0.9rem;
            background: rgba(243, 156, 18, 0.1);
            border-radius: 6px;
            font-size: 0.9rem;
        }

        /* MAIN CONTAINER */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* ALERTS */
        .alert {
            padding: 0.9rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            font-size: 0.95rem;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.2);
            border: 1px solid #27ae60;
            color: #2ecc71;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .alert-warning {
            background: rgba(243, 156, 18, 0.2);
            border: 1px solid #f39c12;
            color: #f39c12;
        }

        /* CARDS */
        .card {
            background: #1a1a2e;
            border: 1px solid #2a2a4a;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #f39c12;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #2a2a4a;
        }

        /* BUTTONS */
        .btn {
            display: inline-block;
            padding: 0.55rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-primary {
            background: #f39c12;
            color: #0f0f1a;
        }

        .btn-primary:hover { background: #e67e22; color: #fff; }

        .btn-secondary {
            background: #2a2a4a;
            color: #ccc;
            border: 1px solid #3a3a6a;
        }

        .btn-secondary:hover { background: #3a3a6a; color: #fff; }

        .btn-danger {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .btn-danger:hover { background: #e74c3c; color: #fff; }

        .btn-success {
            background: rgba(39, 174, 96, 0.2);
            color: #2ecc71;
            border: 1px solid #27ae60;
        }

        .btn-success:hover { background: #27ae60; color: #fff; }

        .btn-sm {
            padding: 0.3rem 0.7rem;
            font-size: 0.82rem;
        }

        /* FORMS */
        .form-group {
            margin-bottom: 1.1rem;
        }

        label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.9rem;
            color: #aaa;
            font-weight: 500;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.6rem 0.9rem;
            background: #0f0f1a;
            border: 1px solid #2a2a4a;
            border-radius: 8px;
            color: #e0e0e0;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #f39c12;
        }

        .field-error {
            color: #e74c3c;
            font-size: 0.82rem;
            margin-top: 0.3rem;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }

        th {
            background: #1e1e3a;
            color: #f39c12;
            padding: 0.7rem 0.9rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.03em;
        }

        td {
            padding: 0.65rem 0.9rem;
            border-bottom: 1px solid #1a1a30;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td { background: rgba(243, 156, 18, 0.04); }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .badge-green { background: rgba(39,174,96,0.2); color: #2ecc71; }
        .badge-orange { background: rgba(243,156,18,0.2); color: #f39c12; }
        .badge-red { background: rgba(231,76,60,0.2); color: #e74c3c; }
        .badge-blue { background: rgba(52,152,219,0.2); color: #3498db; }
        .badge-gray { background: rgba(149,165,166,0.2); color: #95a5a6; }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #f39c12;
            margin-bottom: 0.3rem;
        }

        .page-header p {
            color: #888;
            font-size: 0.95rem;
        }

        /* GRID */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            nav { padding: 0 1rem; }
            .nav-links { gap: 0.25rem; }
        }

        /* STANDINGS TABLE RANK */
        .rank-1 { color: #f39c12; font-weight: 700; }
        .rank-2 { color: #95a5a6; font-weight: 700; }
        .rank-3 { color: #cd7f32; font-weight: 700; }

        /* KNOCKOUT BRACKET */
        .bracket {
            overflow-x: auto;
            padding: 1rem 0;
        }

        .bracket-rounds {
            display: flex;
            gap: 3rem;
            align-items: stretch;
            min-width: max-content;
        }

        .bracket-round {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-width: 200px;
        }

        .bracket-round-title {
            text-align: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #f39c12;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .bracket-match {
            background: #1e1e3a;
            border: 1px solid #2a2a4a;
            border-radius: 10px;
            overflow: hidden;
            flex: 1;
        }

        .bracket-team {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0.9rem;
            border-bottom: 1px solid #1a1a2e;
            font-size: 0.88rem;
        }

        .bracket-team:last-child { border-bottom: none; }

        .bracket-team.winner {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
            font-weight: 700;
        }

        .bracket-team.tbd { color: #555; font-style: italic; }

        .bracket-score {
            font-weight: 700;
            min-width: 1.5rem;
            text-align: right;
        }

        /* Inline score form */
        .score-form {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .score-form input[type=number] {
            width: 3.5rem;
            text-align: center;
            padding: 0.3rem;
        }

        /* Status badge colors */
        .status-registration_open { background: rgba(39,174,96,0.2); color: #2ecc71; }
        .status-registration_closed { background: rgba(243,156,18,0.2); color: #f39c12; }
        .status-group_stage { background: rgba(52,152,219,0.2); color: #3498db; }
        .status-knockout_stage { background: rgba(155,89,182,0.2); color: #9b59b6; }
        .status-finished { background: rgba(149,165,166,0.2); color: #95a5a6; }

        /* Beer icon decoration */
        .hero-section {
            text-align: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 16px;
            margin-bottom: 2rem;
            border: 1px solid #2a2a4a;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #f39c12;
            margin-bottom: 0.5rem;
        }

        .hero-section p {
            color: #888;
            font-size: 1.05rem;
        }

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
        .gap-1 { gap: 0.5rem; }
        .fw-bold { font-weight: 700; }
        .text-muted { color: #666; }
        .text-orange { color: #f39c12; }
        .text-green { color: #2ecc71; }
        .text-red { color: #e74c3c; }
        .divider { border: none; border-top: 1px solid #2a2a4a; margin: 1.5rem 0; }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('events.index') }}" class="nav-brand">
        <span>🍺</span> Sörpong Bajnokság
    </a>
    <ul class="nav-links">
        <li><a href="{{ route('events.index') }}">Események</a></li>
        <li><a href="{{ route('teams.stats') }}">Csapatok</a></li>

        @auth('team')
            <li><span class="nav-user">🏆 {{ Auth::guard('team')->user()->name }}</span></li>
            <li><a href="{{ route('team.dashboard') }}">Irányítópult</a></li>
            <li>
                <form method="POST" action="{{ route('team.logout') }}" style="display:inline">
                    @csrf
                    <button type="submit">Kilépés</button>
                </form>
            </li>
        @else
            <li><a href="{{ route('team.login') }}">Bejelentkezés</a></li>
            <li><a href="{{ route('team.register') }}" class="btn-primary">Regisztráció</a></li>
        @endauth

        @auth
            @if(Auth::user()->is_admin)
                <li><a href="{{ route('admin.events.index') }}" style="color:#9b59b6">Admin</a></li>
            @endif
        @endauth
    </ul>
</nav>

<div class="container">
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

</body>
</html>
