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
    .badge-special {
        background: rgba(155,89,182,0.15);
        color: #9b59b6;
        border: 1px solid rgba(155,89,182,0.3);
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
    .cup-diagram {
        text-align: center;
    }
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
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(243,156,18,0.3);
    }
    .cup.water {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border-color: rgba(52,152,219,0.6);
        box-shadow: 0 2px 8px rgba(52,152,219,0.3);
    }
    .cup.empty {
        background: transparent;
        border: 2px dashed #333;
        box-shadow: none;
    }

    .table-visual {
        background: #1a1a2e;
        border: 2px solid #2a2a4a;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }
    .table-side { text-align: center; }
    .table-divider {
        font-size: 1.5rem;
        color: #444;
        font-weight: 700;
    }
    .table-label {
        font-size: 0.75rem;
        color: #888;
        margin-top: 0.4rem;
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
    .highlight-box.purple {
        background: rgba(155,89,182,0.08);
        border-left-color: #9b59b6;
    }
    .highlight-box.green {
        background: rgba(46,204,113,0.08);
        border-left-color: #2ecc71;
    }
    .highlight-box.red {
        background: rgba(231,76,60,0.08);
        border-left-color: #e74c3c;
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
        .cup { width: 28px; height: 28px; font-size: 0.6rem; }
        .cup-diagrams { gap: 1.2rem; }
        .table-visual { justify-content: center; }
    }
</style>

{{-- Pohárállás vizuális --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-title">🥤 Pohárfelállások</div>

    <div class="table-visual">
        <div class="table-side">
            <div class="cup-row"><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="table-label">Csapat A – 10 pohár</div>
        </div>
        <div class="table-divider">⟵ asztal ⟶</div>
        <div class="table-side">
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div><div class="cup"></div></div>
            <div class="cup-row"><div class="cup"></div></div>
            <div class="table-label">Csapat B – 10 pohár</div>
        </div>
    </div>

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

{{-- Alapszabályok + Dobás --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">📐 Alapfelállás</div>
        <ul class="rule-list">
            <li>Csapatonként <strong>10 pohár</strong>, háromszög alakban felállítva (4-3-2-1)</li>
            <li>A poharak csúcsa az ellenfél felé mutat</li>
            <li>Minden pohárba <strong>egyforma mennyiség</strong> kerül (sör vagy víz)</li>
            <li>Mérkőzésenként <strong>2 db vizes pohár</strong> a labda öblítéséhez (nem számítanak célpontnak)</li>
            <li>A poharak érintkeznek egymással – nem lehet köztük rés az alapfelállásnál</li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">🎯 Dobás szabályai</div>
        <ul class="rule-list">
            <li>Köpenként <strong>csapatonként 2 dobás</strong> (fejenként 1)</li>
            <li>Dobás közben a könyök <strong>nem lóghat át</strong> az asztal széle fölé</li>
            <li>A labdát <strong>legalább egy ívben</strong> kell dobni – nem szabad vízszintesen hajítani</li>
            <li>A labda <strong>kipattintható</strong> az asztalról – sikeres kipattintás <span class="badge-rule">2 pohár</span></li>
            <li>A védekező fél az asztaltól pattanó labdát <strong>elütheti</strong></li>
            <li>Ha mindkét játékos talál, a labdák visszajárnak <span class="badge-rule">visszadobás</span></li>
        </ul>
    </div>
</div>

{{-- Átrakás + Különleges szabályok --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">🔄 Átrakás (Re-rack)</div>
        <ul class="rule-list">
            <li>Minden csapat <strong>2 átrakást</strong> kérhet egy meccs során</li>
            <li>Átrakást a saját kör <strong>elején</strong> lehet kérni</li>
            <li>Átrakás lehetséges: <strong>6, 3, vagy 1</strong> pohár esetén</li>
            <li>6 pohárból: háromszög (3-2-1) felállás</li>
            <li>3 pohárból: sor vagy háromszög felállás</li>
            <li>1 pohárnál: középre kerül az asztalon</li>
        </ul>

        <div class="highlight-box" style="margin-top:0.75rem;">
            Az átrakás után a poharak újra érintkeznek egymással.
        </div>
    </div>

    <div class="rule-section card">
        <div class="card-title">✨ Különleges szabályok</div>
        <ul class="rule-list">
            <li><span class="badge-special">Visszadobás</span> Ha mindkét csapattag talál, extra kör jár</li>
            <li><span class="badge-special">Tűzben van</span> Ha valaki egymás után 3-szor talál, folytathatja a dobást amíg hibázik</li>
            <li><span class="badge-special">Halálpohár</span> Ha egy labda olyan pohárba esik amibe már korábban is ment, azonnali vereség</li>
            <li><span class="badge-special">Island cup</span> Ha egy pohár teljesen el van szigetelve, arra lehet "sziget" dobást kérni – találat esetén 2 pohár</li>
            <li><span class="badge-special">Trick shot</span> Visszadobásnál trükk dobást kell végrehajtani (hát mögül, könyökkel, stb.)</li>
        </ul>
    </div>
</div>

{{-- A meccs menete + hosszabbító --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">⚔️ A meccs menete</div>
        <ul class="rule-list">
            <li>A feldobással döntik el, melyik csapat kezd – mindkét csapat 1 játékosa dob, szemkontaktust tartva a céllal</li>
            <li>A kezdő csapat <strong>1 dobást</strong> kap az első körben</li>
            <li>Ezután minden kör <strong>2 dobás</strong> csapatonként</li>
            <li>Amelyik csapat az ellenfél összes poharát kiüti, <strong>nyer</strong></li>
            <li>Az utolsó pohár kiütése után a vesztes csapatnak jár a <strong>visszavágó</strong></li>
            <li>Ha a visszavágón is kiütik az összes maradék pohárt, <strong>hosszabbítás</strong> következik</li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">⏱️ Hosszabbítás (Overtime)</div>
        <ul class="rule-list">
            <li>Hosszabbításban mindkét csapat <strong>3 pohárral</strong> játszik</li>
            <li>A 3 pohár sorban áll (sor felállás)</li>
            <li>Ugyanazok a szabályok érvényesek mint az alap meccsben</li>
            <li>Az átrakási lehetőségek <strong>nullázódnak</strong> – 1-1 új átrakás jár csapatonként</li>
            <li>Ha ismét döntetlen, újabb hosszabbítás következik <strong>1 pohárral</strong></li>
            <li>Az 1 pohár az asztal közepére kerül</li>
        </ul>
    </div>
</div>

{{-- Higieniai + Versenyszabályok --}}
<div class="rules-grid">
    <div class="rule-section card">
        <div class="card-title">🧼 Higiénia & fair play</div>
        <ul class="rule-list">
            <li>A labdát minden dobás előtt <strong>vizes pohárban meg kell öblíteni</strong></li>
            <li>Tilos a labdát befújni vagy megnyalni</li>
            <li>Tilos az ellenfél zavarása dobás közben (integetés, kiabálás a labda közvetlen közelébe)</li>
            <li>Az asztalhoz nem szabad hozzáérni a dobás megakadályozása érdekében, kivéve pattintott labdánál</li>
            <li>A poharak nem tologathatók szándékosan dobás közben</li>
        </ul>
    </div>

    <div class="rule-section card">
        <div class="card-title">🏆 Versenyszabályok</div>
        <ul class="rule-list">
            <li>A mérkőzések <strong>csoportkörös</strong> rendszerben zajlanak, majd egyenes kieséssel folytatódnak</li>
            <li>A csoportkörben minden csapat <strong>minden csoporttársával</strong> játszik egyszer</li>
            <li>Pontrendszer: <strong>győzelem = 3 pont</strong>, döntetlen = 1 pont, vereség = 0 pont</li>
            <li>Holtverseny esetén a pohárkülönbség dönt</li>
            <li>Az egyenes kieséses szakasz <strong>egygémes</strong> meccsekből áll</li>
            <li>A döntő előtt <strong>bronzmeccs</strong> is zajlik a 3. helyért</li>
        </ul>

        <div class="highlight-box green" style="margin-top:0.75rem;">
            <strong>Menetrend:</strong> Minden mérkőzés max. 15 percig tart. Ha az idő lejár, az aktuális állás számít.
        </div>
    </div>
</div>

{{-- Pontszámítás táblázat --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-title">📊 Pontozási rendszer</div>
    <div class="table-wrap">
    <table class="scoring-table">
        <thead>
            <tr>
                <th>Esemény</th>
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
                <td>A védekezők elüthetik</td>
            </tr>
            <tr>
                <td>Mindkét játékos talál</td>
                <td><span class="badge-rule">visszadobás</span></td>
                <td>Extra kör jár a csapatnak</td>
            </tr>
            <tr>
                <td>Halálpohár</td>
                <td><span style="color:#e74c3c; font-weight:700;">azonnali vereség</span></td>
                <td>A labda olyan pohárba esik, amibe már ment</td>
            </tr>
            <tr>
                <td>Sziget dobás</td>
                <td><span class="badge-rule">2 pohár</span></td>
                <td>Csak ha a pohár valóban el van szigetelve</td>
            </tr>
        </tbody>
    </table>
    </div>
</div>

{{-- Figyelmeztetések --}}
<div class="highlight-box red" style="margin-bottom:1.5rem;">
    <strong>⚠️ Fontos:</strong> A versenyen való részvétel feltétele a <strong>18. életév betöltése</strong>. A szabályok megszegése vagy sportszerűtlen viselkedés a csapat kizárásával járhat. A szervezők döntése minden vitás kérdésben végleges.
</div>

<div class="highlight-box" style="margin-bottom:2rem;">
    <strong>📞 Kérdés esetén</strong> fordulj a szervezőkhöz a helyszínen, vagy írj nekünk az esemény oldalán keresztül. Jó játékot! 🍺
</div>

@endsection
