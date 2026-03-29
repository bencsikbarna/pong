<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fordulók – {{ $event->name }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #111;
            font-size: 10pt;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .page-header h1 { font-size: 14pt; font-weight: 700; }
        .page-header .meta { font-size: 8pt; color: #555; text-align: right; }

        /* Groups layout: side by side in columns */
        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            align-items: start;
        }

        .group-block { break-inside: avoid; }
        .group-title {
            font-size: 11pt;
            font-weight: 700;
            background: #222;
            color: #fff;
            padding: 3px 8px;
            margin-bottom: 6px;
            border-radius: 3px;
        }

        .round-block { margin-bottom: 8px; break-inside: avoid; }
        .round-title {
            font-size: 8pt;
            font-weight: 700;
            color: #444;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .match-row {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 2px 0;
            border-bottom: 1px dotted #ddd;
            font-size: 9pt;
        }
        .match-row:last-child { border-bottom: none; }

        .table-badge {
            font-size: 7.5pt;
            font-weight: 700;
            background: #f39c12;
            color: #fff;
            border-radius: 3px;
            padding: 1px 4px;
            flex-shrink: 0;
            min-width: 26px;
            text-align: center;
        }
        .team-home {
            flex: 1;
            text-align: right;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .score-box {
            font-weight: 700;
            min-width: 28px;
            text-align: center;
            flex-shrink: 0;
            border: 1px solid #bbb;
            border-radius: 3px;
            padding: 1px 2px;
            font-size: 9pt;
            background: #fafafa;
        }
        .score-box.played { background: #e8f5e9; border-color: #4caf50; }
        .team-away {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* No-rounds message */
        .no-data { color: #888; font-style: italic; font-size: 9pt; padding: 4px 0; }

        /* Print button – hidden in print */
        .print-btn {
            position: fixed;
            top: 12px;
            right: 12px;
            background: #f39c12;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 10pt;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 10;
        }
        .print-btn:hover { background: #e67e22; }

        .footer {
            margin-top: 16px;
            border-top: 1px solid #ccc;
            padding-top: 4px;
            font-size: 7.5pt;
            color: #888;
            text-align: right;
        }

        @media print {
            .print-btn { display: none; }
            body { font-size: 9pt; }
            @page { margin: 1cm; size: A4 landscape; }

            /* Try to fit more columns in landscape */
            .groups-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; }
            .group-title { font-size: 10pt; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Nyomtatás / PDF mentés</button>

<div style="padding: 12px 16px;">

    <div class="page-header">
        <div>
            <h1>🍺 {{ $event->name }} – Csoportköri fordulók</h1>
            <div style="font-size:9pt; color:#555; margin-top:2px;">
                {{ $event->event_date->format('Y. m. d.') }}
                @if($event->location) · {{ $event->location }} @endif
                @if($event->tables_count) · {{ $event->tables_count }} asztal @endif
            </div>
        </div>
        <div class="meta">
            Nyomtatva: {{ now()->format('Y. m. d. H:i') }}<br>
            Csoportok: {{ $event->groups->count() }}
        </div>
    </div>

    @if($event->groups->isEmpty())
        <p class="no-data">Még nincsenek generált csoportok.</p>
    @else
        <div class="groups-grid">
            @foreach($event->groups as $group)
            <div class="group-block">
                <div class="group-title">{{ $group->name }}</div>

                @if($group->rounds->isEmpty())
                    <p class="no-data">Nincsenek fordulók.</p>
                @else
                    @foreach($group->rounds as $round)
                    <div class="round-block">
                        <div class="round-title">{{ $round->round_number }}. forduló</div>
                        @foreach($round->matches as $match)
                        <div class="match-row">
                            <span class="table-badge">{{ $match->table_number ?? 1 }}.</span>
                            <span class="team-home" title="{{ $match->homeRegistration->team_name ?? '?' }}">
                                {{ $match->homeRegistration->team_name ?? '?' }}
                            </span>
                            <span class="score-box {{ $match->is_played ? 'played' : '' }}">
                                @if($match->is_played)
                                    {{ $match->home_score }}&nbsp;–&nbsp;{{ $match->away_score }}
                                @else
                                    &nbsp;–&nbsp;
                                @endif
            </span>
                            <span class="team-away" title="{{ $match->awayRegistration->team_name ?? '?' }}">
                                {{ $match->awayRegistration->team_name ?? '?' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                @endif
            </div>
            @endforeach
        </div>

        {{-- Legend --}}
        <div style="margin-top:14px; font-size:8pt; color:#666; display:flex; gap:16px; flex-wrap:wrap;">
            <span><span style="background:#f39c12;color:#fff;padding:1px 4px;border-radius:3px;font-size:7.5pt;">N.</span> = asztal száma</span>
            <span><span style="background:#e8f5e9;border:1px solid #4caf50;padding:1px 4px;border-radius:3px;">0 – 0</span> = eredmény rögzítve</span>
            <span><span style="background:#fafafa;border:1px solid #bbb;padding:1px 4px;border-radius:3px;">&nbsp;–&nbsp;</span> = még nem játszott</span>
        </div>
    @endif

    <div class="footer">{{ $event->name }} · Sörpong Bajnokság · pong.baksa.hu</div>
</div>

</body>
</html>
