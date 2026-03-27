<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jelszó visszaállítás</title>
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f8; margin: 0; padding: 20px; color: #333; }
  .wrap { max-width: 560px; margin: 0 auto; background: #1a1a2e; border-radius: 12px; overflow: hidden; }
  .header { background: linear-gradient(135deg, #1a1a2e, #16213e); padding: 32px 32px 24px; text-align: center; border-bottom: 3px solid #f39c12; }
  .header h1 { color: #f39c12; margin: 0; font-size: 1.5rem; }
  .header p  { color: #aaa; margin: 6px 0 0; font-size: 0.9rem; }
  .body { padding: 28px 32px; color: #ddd; line-height: 1.6; }
  .body h2 { color: #f39c12; font-size: 1.1rem; margin: 0 0 12px; }
  .btn { display: inline-block; margin: 20px 0; padding: 13px 30px; background: #f39c12; color: #0f0f1a; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.95rem; }
  .url-box { background: #13132a; border-radius: 8px; padding: 12px 16px; word-break: break-all; font-size: 0.8rem; color: #888; margin-top: 8px; }
  .footer { background: #13132a; padding: 16px 32px; text-align: center; color: #555; font-size: 0.8rem; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🍺 Sörpong Bajnokság</h1>
    <p>Jelszó visszaállítás</p>
  </div>
  <div class="body">
    <h2>Szia, {{ $teamName }}!</h2>
    <p>Jelszó visszaállítást kértél a fiókodhoz. Kattints az alábbi gombra az új jelszó beállításához.</p>
    <p>A link <strong>60 percig</strong> érvényes.</p>

    <a href="{{ $resetUrl }}" class="btn">Jelszó visszaállítása →</a>

    <p style="margin-top:16px; color:#888; font-size:0.85rem;">Ha a gomb nem működik, másold be ezt a linket a böngésződbe:</p>
    <div class="url-box">{{ $resetUrl }}</div>
  </div>
  <div class="footer">
    Ha nem te kérted a jelszó visszaállítást, hagyd figyelmen kívül ezt az emailt.<br>
    Sörpong Bajnokság &mdash; <a href="{{ config('app.url') }}" style="color:#f39c12;">{{ config('app.url') }}</a>
  </div>
</div>
</body>
</html>
