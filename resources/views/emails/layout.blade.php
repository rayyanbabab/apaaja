<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailTitle ?? 'Notifikasi' }} — {{ $companyName ?? config('app.name') }}</title>
    <style>
        /* ===== Reset ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; font-size: 14px; color: #374151; line-height: 1.6; }
        table { border-collapse: collapse; }
        img { border: 0; display: block; }
        a { color: inherit; text-decoration: none; }

        /* ===== Wrapper ===== */
        .wrapper { width: 100%; background-color: #f3f4f6; padding: 32px 16px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }

        /* ===== Header ===== */
        .header { background: linear-gradient(135deg, {{ $headerColorFrom ?? '#4f46e5' }} 0%, {{ $headerColorTo ?? '#6366f1' }} 100%); padding: 32px 40px; text-align: center; }
        .header-logo { height: 40px; width: auto; margin: 0 auto 12px; }
        .header-app-name { color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: -0.3px; }
        .header-tagline { color: rgba(255,255,255,0.75); font-size: 12px; margin-top: 2px; }

        /* ===== Hero Badge ===== */
        .hero { padding: 28px 40px 0; text-align: center; }
        .hero-icon { width: 56px; height: 56px; border-radius: 16px; background: {{ $iconBg ?? '#eff6ff' }}; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; font-size: 26px; line-height: 1; }
        .hero-title { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 6px; }
        .hero-subtitle { font-size: 13px; color: #6b7280; }

        /* ===== Body ===== */
        .body { padding: 24px 40px 32px; }

        /* ===== Info Card ===== */
        .info-card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .info-label { font-size: 12px; color: #6b7280; font-weight: 500; }
        .info-value { font-size: 13px; color: #111827; font-weight: 600; text-align: right; max-width: 60%; }

        /* ===== Message Box ===== */
        .message-box { background: {{ $msgBoxBg ?? '#eff6ff' }}; border-left: 3px solid {{ $msgBoxBorder ?? '#3b82f6' }}; border-radius: 0 8px 8px 0; padding: 14px 16px; margin: 20px 0; font-size: 13px; color: #374151; line-height: 1.65; }

        /* ===== CTA Button ===== */
        .cta-wrapper { text-align: center; margin: 28px 0 8px; }
        .cta-btn { display: inline-block; background: {{ $ctaColor ?? '#4f46e5' }}; color: #ffffff !important; font-size: 14px; font-weight: 600; padding: 13px 32px; border-radius: 10px; text-decoration: none; letter-spacing: 0.2px; }

        /* ===== Divider ===== */
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }

        /* ===== Footer ===== */
        .footer { background: #f9fafb; border-top: 1px solid #f1f5f9; padding: 20px 40px; text-align: center; }
        .footer-text { font-size: 11px; color: #9ca3af; line-height: 1.7; }
        .footer-text a { color: #6b7280; text-decoration: underline; }
        .footer-company { font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 4px; }

        /* ===== Responsive ===== */
        @media only screen and (max-width: 600px) {
            .header { padding: 24px 24px; }
            .hero { padding: 20px 24px 0; }
            .body { padding: 20px 24px 24px; }
            .footer { padding: 16px 24px; }
            .info-row { flex-direction: column; gap: 2px; }
            .info-value { text-align: left; max-width: 100%; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">

        {{-- ── HEADER ── --}}
        <div class="header">
            @if($companyLogo ?? null)
                <img src="{{ $companyLogo }}" alt="{{ $companyName }}" class="header-logo">
            @endif
            <div class="header-app-name">{{ $companyName ?? config('app.name') }}</div>
            @if($companyTagline ?? null)
                <div class="header-tagline">{{ $companyTagline }}</div>
            @endif
        </div>

        {{-- ── HERO ── --}}
        <div class="hero">
            <div class="hero-icon">{{ $heroEmoji ?? '🔔' }}</div>
            <div class="hero-title">{{ $heroTitle ?? 'Notifikasi' }}</div>
            @if($heroSubtitle ?? null)
                <div class="hero-subtitle">{{ $heroSubtitle }}</div>
            @endif
        </div>

        {{-- ── BODY ── --}}
        <div class="body">
            @yield('content')
        </div>

        {{-- ── FOOTER ── --}}
        <div class="footer">
            <div class="footer-company">{{ $companyName ?? config('app.name') }}</div>
            <div class="footer-text">
                Email ini dikirim secara otomatis oleh sistem Artilia.<br>
                @if($companyEmail ?? null)
                    Hubungi kami: <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a><br>
                @endif
                @if($companyAddress ?? null)
                    {{ $companyAddress }}<br>
                @endif
                <br>
                <a href="{{ $appUrl ?? config('app.url') }}">{{ $appUrl ?? config('app.url') }}</a>
            </div>
        </div>

    </div>
</div>
</body>
</html>
