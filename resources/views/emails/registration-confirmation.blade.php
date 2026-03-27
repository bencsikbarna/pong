<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sikeres nevezés</title>
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f8; margin: 0; padding: 20px; color: #333; }
  .wrap { max-width: 560px; margin: 0 auto; background: #1a1a2e; border-radius: 12px; overflow: hidden; }
  .header { background: linear-gradient(135deg, #1a1a2e, #16213e); padding: 32px 32px 24px; text-align: center; border-bottom: 3px solid #f39c12; }
  .header h1 { color: #f39c12; margin: 0; font-size: 1.5rem; }
  .header p  { color: #aaa; margin: 6px 0 0; font-size: 0.9rem; }
  .body { padding: 28px 32px; color: #ddd; }
  .body h2 { color: #f39c12; font-size: 1.1rem; margin: 0 0 12px; }
  .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #2a2a4a; font-size: 0.9rem; }
  .detail-row:last-child { border-bottom: none; }
  .label { color: #888; }
  .value { color: #e0e0e0; font-weight: 600; }
  .btn { display: inline-block; margin: 20px 0 0; padding: 12px 28px; background: #f39c12; color: #0f0f1a; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.95rem; }
  .footer { background: #13132a; padding: 16px 32px; text-align: center; color: #555; font-size: 0.8rem; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🍺 Sörpong Bajnokság</h1>
    <p>Sikeres nevezési visszaigazolás</p>
  </div>
  <div class="body">
    <h2>Szia, {{ $registration->team_name }}!</h2>
    <p style="margin:0 0 20px; line-height:1.6;">Sikeresen neveztél a következő eseményre. Várunk szeretettel!</p>

    <div style="background:#13132a; border-radius:8px; padding:16px; margin-bottom:20px;">
      <div class="detail-row">
        <span class="label">Esemény</span>
        <span class="value">{{ $registration->event->name }}</span>
      </div>
      <div class="detail-row">
        <span class="label">Dátum</span>
        <span class="value">{{ $registration->event->event_date->format('Y. m. d. H:i') }}</span>
      </div>
      @if($registration->event->location)
      <div class="detail-row">
        <span class="label">Helyszín</span>
        <span class="value">{{ $registration->event->location }}</span>
      </div>
      @endif
      <div class="detail-row">
        <span class="label">Csapat</span>
        <span class="value">{{ $registration->team_name }}</span>
      </div>
    </div>

    <a href="{{ route('events.show', $registration->event) }}" class="btn">Esemény megtekintése →</a>
  </div>
  <div class="footer">
    Sörpong Bajnokság &mdash; <a href="{{ config('app.url') }}" style="color:#f39c12;">{{ config('app.url') }}</a><br>
    Ha nem te neveztél, kérjük hagyd figyelmen kívül ezt az emailt.
  </div>
</div>
</body>
</html>
