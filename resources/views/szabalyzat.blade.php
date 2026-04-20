@extends('layouts.app')
@section('title', 'Versenyszabályzat')

@section('content')
<div class="page-header">
    <h1>📋 Versenyszabályzat</h1>
    <p>Sörpong bajnokság – hivatalos szabályok</p>
</div>

<style>
    .rules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .rule-section { margin-bottom: 0; }
    .rule-section .card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.05rem;
    }
    .rule-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .rule-list li {
        padding: 0.45rem 0;
        border-bottom: 1px solid #1e1e3a;
        font-size: 0.92rem;
        line-height: 1.5;
        display: flex;
        gap: 0.5rem;
    }
    .rule-list li:last-child { border-bottom: none; }
    .rule-list li::before {
        content: '▸';
        color: #f39c12;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .badge-rule {
        background: rgba(243,156,18,0.15);
        color: #f39c12;
        border: 1px solid rgba(243,156,18,0.3);
        border-radius: 4px;
        padding: 0.1rem 0.45rem;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
        align-self: flex-start;
        margin-top: 2px;
    }

    /* ── Pohárállás diagramok ── */
    .cup-diagrams {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: space-around;
        padding: 1rem 0;
    }
    .cup-diagram { text-align: center; }
    .cup-diagram-label {
        font-size: 0.82rem;
        color: #888;
        margin-top: 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .cup-row {
        display: flex;
        justify-content: center;
        gap: 6px;
        margin-bottom: 6px;
    }
    .cup {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        border: 2px solid rgba(243,156,18,0.6);
        box-shadow: 0 2px 8px rgba(243,156,18,0.3);
    }

    /* ── Asztal nézet (felülről) ── */
    .table-visual {
        background: #1a1a2e;
        border: 2px solid #2a2a4a;
        border-radius: 12px;
        padding: 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.2rem;
    }
    .table-team-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.3rem;
    }
    .table-divider-line {
        width: 100%;
        max-width: 260px;
        border: none;
        border-top: 2px dashed #2a2a4a;
        margin: 0.4rem 0;
        position: relative;
    }
    .table-divider-text {
        font-size: 0.75rem;
        color: #444;
        font-weight: 600;
        letter-spacing: 0.05em;
        margin: 0.2rem 0;
    }

    .highlight-box {
        background: rgba(243,156,18,0.08);
        border-left: 3px solid #f39c12;
        border-radius: 0 8px 8px 0;
        padding: 0.7rem 1rem;
        margin: 0.75rem 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }
    .highlight-box.green {
        background: rgba(46,204,113,0.08);
        border-left-color: #2ecc71;
    }
    .highlight-box.red {
        background: rgba(231,76,60,0.08);
        border-left-color: #e74c3c;
    }
    .highlight-box.blue {
        background: rgba(52,152,219,0.08);
        border-left-color: #3498db;
    }

    .scoring-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }
    .scoring-table th {
        background: #1a1a2e;
        padding: 0.5rem 0.75rem;
        text-align: left;
        color: #f39c12;
        border-bottom: 2px solid #2a2a4a;
    }
    .scoring-table td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #1a1a2e;
        vertical-align: top;
    }
    .scoring-table tr:last-child td { border-bottom: none; }

    @media (max-width: 640px) {
        .rules-grid { grid-template-columns: 1fr; }
        .cup { width: 28px; height: 28px; }
        .cup-diagrams { gap: 1.2rem; }
    }
</style>

{{-- Pohárállás vizuális: felülről nézve, csúcsok egymás felé --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-title">🥤 Pohárfelállások</div>

    {{-- Asztal felülnézete --}}
    <div class="table-visual">
        <div class="table-team-label">Csapat A</div>
        {{-- A: talp az asztal szélénél, csúcs a közép felé (csúcs alul) --}}
        <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div></div>

        <div class="table-divider-text">· · · · · · asztal közepe · · · · · ·</div>

        {{-- B: csúcs a közép felé (csúcs felül), talp az asztal szélénél --}}
        <div class="cup-row"><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
        <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
        <div class="table-team-label" style="margin-top:0.3rem;">Csapat B</div>
    </div>

    <p class="text-muted" style="font-size:0.82rem; text-align:center; margin-bottom:1rem;">
        Mindkét háromszög csúcsa az asztal közepe felé mutat (egymással szemben).
    </p>

    {{-- Átrakási formációk --}}
    <div class="cup-diagrams">
        <div class="cup-diagram">
            <div class="cup-row"><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-diagram-label">Alap (10 pohár)</div>
        </div>
        <div class="cup-diagram">
            <div class="cup-row"><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-diagram-label">Átrakás (6 pohár)</div>
        </div>
        <div class="cup-diagram">
            <div class="cup-row"><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-diagram-label">Átrakás (3 pohár)</div>
        </div>
        <div class="cup-diagram">
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-diagram-label">Átrakás (sor)</div>
        </div>
        <div class="cup-diagram">
            <div class="cup-row"><div class="cup"></div></div>
            <div class="cup-diagram-label">Utolsó pohár</div>
        </div>
    </div>
</div>

{{-- Alapfelállás + Dobás --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">📐 Alapfelállás</div>
        <ul class="rule-list">
            <li>Csapatonként <strong>10 pohár</strong>, háromszög alakban felállítva (4-3-2-1)</li>
            <li>A háromszög csúcsa az <strong>asztal közepe felé</strong> mutat (az ellenfél irányába)</li>
            <li>Minden pohárba <strong>egyforma mennyiség</strong> kerül (sör vagy víz)</li>
            <li>Mérkőzésenként <strong>2 db vizes pohár</strong> a labda öblítéséhez (nem számítanak célpontnak)</li>
            <li>A poharak érintkeznek egymással – nem lehet köztük rés az alapfelállásnál</li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">🎯 Dobás szabályai</div>
        <ul class="rule-list">
            <li>Körenként <strong>csapatonként 2 dobás</strong> (fejenként 1)</li>
            <li>A csapatok <strong>szabadon döntik el</strong>, melyikük kezd</li>
            <li>Dobás közben a könyök <strong>nem lóghat át</strong> az asztal széle fölé</li>
            <li>A labdát <strong>legalább egy ívben</strong> kell dobni – nem szabad vízszintesen hajítani</li>
            <li>Ha mindkét játékos talál ugyanabban a körben, a labdák <strong>visszajárnak</strong> <span class="badge-rule">extra kör</span></li>
        </ul>
    </div>
</div>

{{-- Átrakás + Különleges szabályok --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">🔄 Átrakás (Re-rack)</div>
        <ul class="rule-list">
            <li>Minden csapat <strong>2 átrakást</strong> kérhet egy meccs során</li>
            <li>Átrakást a saját kör <strong>elején</strong> lehet kérni, bármennyi pohár esetén</li>
            <li>Az átrakás formáját a kérő csapat szabadon választhatja meg</li>
            <li>Az átrakás után a poharak érintkeznek egymással</li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">✨ Különleges dobások</div>
        <ul class="rule-list">
            <li><span class="badge-rule">Pattintott dobás</span> Ha a labda az asztalon pattan, majd pohárba esik → <strong>2 pohár</strong> esik ki</li>
            <li>Pattintott labdát a védekező csapat <strong>elütheti</strong>, mielőtt pohárba esne</li>
            <li>Ha mindkét játékos talál (<strong>visszadobás</strong>), a csapatnak extra kör jár – ilyenkor <strong>trükk dobást</strong> kell végrehajtani (pl. hát mögül, könyökkel)</li>
        </ul>
    </div>
</div>

{{-- A meccs menete --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">⚔️ A meccs menete</div>
        <ul class="rule-list">
            <li>A csapatok <strong>megegyeznek</strong>, ki kezd – minden körben <strong>2 dobás</strong> jár csapatonként</li>
            <li>Amelyik csapat az ellenfél összes poharát kiüti, <strong>nyer</strong></li>
            <li>Az utolsó pohár kiütése után a vesztes csapat mindkét tagja kap <strong>1-1 reváns dobást</strong></li>
            <li>Ha mindkét reváns talál, a vesztes csapat kap még 1-1 dobást – ez addig folytatódik, amíg valamelyikük hibázik</li>
            <li>Ha a reváns sikertelen (valaki hibázik), a meccs véget ér</li>
            <li>A meccs végén a <strong>győztes csapat</strong> jelenti be az eredményt a <strong>pultnál</strong></li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">🧼 Higiénia & fair play</div>
        <ul class="rule-list">
            <li>A labdát minden dobás előtt <strong>vizes pohárban meg kell öblíteni</strong></li>
            <li>Tilos a labdát befújni vagy megnyalni</li>
            <li>Tilos az ellenfél zavarása dobás közben</li>
            <li>Az asztalhoz nem szabad hozzáérni a dobás megakadályozása érdekében, kivéve pattintott labdánál</li>
            <li>A poharak nem tologathatók szándékosan dobás közben</li>
        </ul>
    </div>
</div>

{{-- Versenyszabályok --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-title">🏆 Versenyszabályok</div>
    <ul class="rule-list">
        <li>A mérkőzések <strong>csoportkörös</strong> rendszerben zajlanak, majd egyenes kieséssel folytatódnak</li>
        <li>A csoportkörben minden csapat <strong>minden csoporttársával</strong> játszik egyszer</li>
        <li>Pontrendszer: <strong>győzelem = 3 pont</strong>, döntetlen = 1 pont, vereség = 0 pont</li>
        <li>Holtverseny esetén a pohárkülönbség dönt</li>
        <li>Az egyenes kieséses szakasz <strong>egygémes</strong> meccsekből áll</li>
        <li>A döntő előtt <strong>bronzmeccs</strong> is zajlik a 3. helyért</li>
        <li>Minden meccs után a <strong>győztes csapat jelenti be az eredményt</strong> a pultnál</li>
    </ul>
</div>

{{-- Pontozási táblázat --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-title">📊 Dobások összefoglalója</div>
    <div class="table-wrap">
    <table class="scoring-table">
        <thead>
            <tr>
                <th>Dobás típusa</th>
                <th>Eredmény</th>
                <th>Megjegyzés</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Normál találat</td>
                <td><span class="badge-rule">1 pohár</span></td>
                <td>A kiütött pohár lekerül az asztalról</td>
            </tr>
            <tr>
                <td>Pattintott dobás</td>
                <td><span class="badge-rule">2 pohár</span></td>
                <td>A védekező elütheti, mielőtt pohárba esik</td>
            </tr>
            <tr>
                <td>Visszadobás (mindkét játékos talál)</td>
                <td><span class="badge-rule">extra kör</span></td>
                <td>Trükk dobást kell végrehajtani</td>
            </tr>
        </tbody>
    </table>
    </div>
</div>

<div class="highlight-box red" style="margin-bottom:1.5rem;">
    <strong>⚠️ Fontos:</strong> A versenyen való részvétel feltétele a <strong>18. életév betöltése</strong>. A szabályok megszegése vagy sportszerűtlen viselkedés a csapat kizárásával járhat. A szervezők döntése minden vitás kérdésben végleges.
</div>

<div class="highlight-box" style="margin-bottom:2rem;">
    <strong>📞 Kérdés esetén</strong> fordulj a szervezőkhöz a helyszínen, vagy írj nekünk az esemény oldalán keresztül. Jó játékot! 🍺
</div>

@endsection
