@php
    $companyName = \App\Models\Setting::get('company_name', 'Artilia');
    $companyLogo = \App\Models\Setting::get('company_logo', null);
    $companyTagline = \App\Models\Setting::get('company_tagline', 'Inventory Management System');
@endphp
<!DOCTYPE html>
<html lang="en" >
<head>
    <!-- Dark Mode Anti-Flash Script (must be first) -->
    <script>
        (function () {
            var saved = localStorage.getItem('artilia_dark_mode');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (saved === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
        function toggleDarkMode() {
            var html = document.documentElement;
            var isDark = html.classList.toggle('dark');
            localStorage.setItem('artilia_dark_mode', isDark ? 'dark' : 'light');
            var sun  = document.getElementById('login-dm-sun');
            var moon = document.getElementById('login-dm-moon');
            if (sun && moon) {
                sun.classList.toggle('hidden', !isDark);
                moon.classList.toggle('hidden', isDark);
            }
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName }} — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ─── Reset & Base ─── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { height: 100%; }
body {
    font-family: 'Inter', system-ui, sans-serif;
    min-height: 100vh;
    background: #f1f5f9;
    display: flex;
    flex-direction: column;
    -webkit-font-smoothing: antialiased;
}

/* ─── Mobile Hero Banner ─── */
.mobile-hero {
    position: relative;
    padding: 36px 24px 48px;
    overflow: hidden;
    background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 35%, #1d4ed8 65%, #3b82f6 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
.mobile-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
}
.hero-glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(50px);
    opacity: 0.25;
    pointer-events: none;
}
.hero-glow-1 { width: 200px; height: 200px; background: #60a5fa; top: -40px; right: -40px; }
.hero-glow-2 { width: 160px; height: 160px; background: #a78bfa; bottom: -20px; left: -30px; }

.mobile-logo-ring {
    position: relative;
    width: 72px;
    height: 72px;
    margin-bottom: 16px;
    z-index: 10;
}
.mobile-logo-ring::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.05));
    animation: spinSlow 8s linear infinite;
}
@keyframes spinSlow {
    to { transform: rotate(360deg); }
}
.mobile-logo-inner {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.mobile-logo-inner img {
    width: 44px;
    height: 44px;
    object-fit: contain;
}

.mobile-hero-title {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.5px;
    z-index: 10;
    position: relative;
}
.mobile-hero-sub {
    font-size: 12px;
    color: rgba(255,255,255,0.55);
    margin-top: 4px;
    z-index: 10;
    position: relative;
    letter-spacing: 0.3px;
}

/* Status pill */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 100px;
    padding: 4px 12px;
    font-size: 10px;
    font-weight: 600;
    color: rgba(255,255,255,0.8);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 14px;
    z-index: 10;
    position: relative;
}
.status-dot {
    width: 6px; height: 6px;
    background: #34d399;
    border-radius: 50%;
    animation: blink 2s ease-in-out infinite;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* ─── Card Container ─── */
.card-container {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 0 16px 32px;
    margin-top: -24px;
    position: relative;
    z-index: 20;
}

.form-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06);
    overflow: hidden;
    animation: riseUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
}
@keyframes riseUp {
    from { opacity: 0; transform: translateY(24px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.card-body {
    padding: 28px 28px 24px;
}

/* ─── Form Elements ─── */
.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.4px;
    margin-bottom: 4px;
}
.form-sub {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 22px;
}

.field {
    margin-bottom: 16px;
}
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin-bottom: 7px;
}
.field-wrap {
    position: relative;
}
.field-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    display: flex;
    transition: color 0.2s;
}
.field-input {
    width: 100%;
    height: 46px;
    padding: 0 14px 0 40px !important; /* !important: Tailwind Preflight resets input padding to 0 */
    font-size: 14px;
    font-family: inherit;
    border: 1.5px solid #e2e8f0;
    border-radius: 11px;
    background: #f8fafc;
    color: #0f172a;
    outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.field-input:focus {
    border-color: #3b82f6;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
    padding: 0 14px 0 40px !important;
}
.field-input:focus ~ .field-icon,
.field-wrap:focus-within .field-icon  {
    color: #3b82f6;
}
.field-input::placeholder { color: #94a3b8; }
.field-input.with-toggle { padding-right: 44px !important; }

.pw-toggle {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    padding: 4px;
    border-radius: 6px;
    transition: color 0.15s, background 0.15s;
    display: flex;
    align-items: center;
}
.pw-toggle:hover { color: #475569; background: #f1f5f9; }

/* Remember row */
.meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    margin-top: -4px;
}
.check-label {
    display: flex;
    align-items: center;
    gap: 7px;
    cursor: pointer;
    user-select: none;
}
.check-label input[type="checkbox"] {
    width: 15px; height: 15px;
    accent-color: #2563eb;
    border-radius: 4px;
    cursor: pointer;
}
.check-label span { font-size: 12px; color: #64748b; }

/* Submit */
.btn-submit {
    width: 100%;
    height: 48px;
    background: linear-gradient(135deg, #1d4ed8, #2563eb, #3b82f6);
    background-size: 200% 200%;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    font-family: inherit;
    border: none;
    border-radius: 11px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    letter-spacing: 0.1px;
    box-shadow: 0 4px 16px rgba(37,99,235,0.35), 0 1px 4px rgba(37,99,235,0.2);
    transition: transform 0.15s, box-shadow 0.15s, background-position 0.4s;
}
.btn-submit:hover {
    background-position: right center;
    box-shadow: 0 8px 24px rgba(37,99,235,0.4);
    transform: translateY(-1px);
}
.btn-submit:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37,99,235,0.3);
}

/* Error */
.error-box {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 11px 13px;
    background: #fff1f2;
    border: 1.5px solid #fecdd3;
    border-radius: 10px;
    margin-bottom: 18px;
    animation: shake 0.4s ease both;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-4px); }
    40%, 80% { transform: translateX(4px); }
}
.error-box svg { flex-shrink: 0; margin-top: 1px; }
.error-box p { font-size: 12.5px; color: #be123c; line-height: 1.5; }

/* Card footer */
.card-footer {
    padding: 14px 28px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
}

/* ─── DESKTOP LAYOUT (lg+) ─── */
/* Hide desktop right panel on mobile by default */
.desktop-right { display: none; }

@media (min-width: 1024px) {
    body {
        flex-direction: row;
        background: #fff;
    }

    /* Hide mobile hero on desktop */
    .mobile-hero { display: none; }
    /* Form panel — goes to the RIGHT */
    .card-container {
        order: 2;
        align-items: center;
        padding: 0;
        margin-top: 0;
        width: 440px;
        flex-shrink: 0;
        background: #fff;
        border-left: 1px solid #f0f4ff;
        min-height: 100vh;
    }
    .form-card {
        max-width: 100%;
        border-radius: 0;
        box-shadow: none;
        animation: none;
        border: 0;
    }
    .card-body { padding: 40px 44px 32px; }
    .card-footer { padding: 16px 44px; }

    /* Hero panel — goes to the LEFT */
    .desktop-right {
        order: 1;
        flex: 1;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, #0f172a 0%, #1e3a8a 30%, #1d4ed8 60%, #3b82f6 100%);
        padding: 48px 52px;
    }
    .desktop-right::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }

    /* Animated orbs */
    .orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.2;
    }
    .orb-a { width: 400px; height: 400px; background: #60a5fa; top: -100px; right: -100px; animation: floatA 10s ease-in-out infinite; }
    .orb-b { width: 280px; height: 280px; background: #a78bfa; bottom: 60px; left: -80px; animation: floatB 12s ease-in-out infinite; }
    .orb-c { width: 200px; height: 200px; background: #34d399; top: 45%; right: 80px; animation: floatC 8s ease-in-out infinite; }
    @keyframes floatA { 0%, 100% { transform: translate(0,0); }  50% { transform: translate(-20px, 20px); } }
    @keyframes floatB { 0%, 100% { transform: translate(0,0); }  50% { transform: translate(20px,-20px); } }
    @keyframes floatC { 0%, 100% { transform: translate(0,0); }  50% { transform: translate(-10px, 15px); } }

    .d-brand {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .d-logo-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .d-logo-wrap img {
        width: 36px;
        height: 36px;
        object-fit: contain;
    }
    .d-brand-text {}
    .d-brand-name { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
    .d-brand-tag  { font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 1px; }

    .d-hero {
        position: relative;
        z-index: 10;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 48px 0;
    }
    .d-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 100px;
        padding: 5px 14px;
        font-size: 10px;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 22px;
        width: fit-content;
    }
    .d-badge-dot {
        width: 6px; height: 6px;
        background: #34d399;
        border-radius: 50%;
        animation: blink 2s ease-in-out infinite;
    }
    .d-title {
        font-size: 44px;
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 16px;
        text-align: justify
    }
    .d-title em {
        font-style: normal;
        background: linear-gradient(135deg, #93c5fd, #c4b5fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .d-desc {
        font-size: 15px;
        color: rgba(255,255,255,0.6);
        line-height: 1.75;
        max-width: 380px;
        margin-bottom: 40px;
    }

    /* Stats */
    .d-stats {
        display: flex;
        gap: 0;
        margin-bottom: 36px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        overflow: hidden;
        width: fit-content;
    }
    .d-stat {
        padding: 14px 24px;
        text-align: center;
    }
    .d-stat + .d-stat {
        border-left: 1px solid rgba(255,255,255,0.1);
    }
    .d-stat-num { font-size: 22px; font-weight: 800; color: #fff; }
    .d-stat-lbl { font-size: 10px; color: rgba(255,255,255,0.45); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

    /* Feature pills */
    .d-features {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .d-feature {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .d-feat-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .d-feat-text { font-size: 13px; color: rgba(255,255,255,0.7); font-weight: 500; }

    .d-footer {
        position: relative;
        z-index: 10;
        font-size: 12px;
        color: rgba(255,255,255,0.3);
    }
}

/* ─── Floating label interaction ─── */
.field-wrap:focus-within .field-label {
    color: #2563eb;
}

/* ══════════════════════════════════════════════
   DARK MODE — Login Page
══════════════════════════════════════════════ */

/* Toggle button — light mode: dark button on light bg */
.dm-login-btn {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 9999;
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(15,23,42,0.12);
    border: 1px solid rgba(15,23,42,0.18);
    backdrop-filter: blur(12px);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #334155;
    transition: background 0.2s, transform 0.15s, color 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.dm-login-btn:hover {
    background: rgba(15,23,42,0.2);
    color: #0f172a;
    transform: scale(1.08);
}

html.dark .dm-login-btn {
    background: rgba(30,41,59,0.85);
    border-color: rgba(255,255,255,0.12);
    color: #94a3b8;
    box-shadow: 0 2px 12px rgba(0,0,0,0.4);
}
html.dark .dm-login-btn:hover {
    background: rgba(51,65,85,0.9);
    color: #e2e8f0;
}

/* Body & backgrounds */
html.dark body {
    background: linear-gradient(145deg, #060a14 0%, #0c1525 40%, #0f1e3d 70%, #0d1b35 100%) !important;
    color: #f1f5f9;
}

/* Mobile hero — deeper, premium dark */
html.dark .mobile-hero {
    background: linear-gradient(145deg, #050810 0%, #091440 35%, #0e2580 65%, #1535b5 100%);
}

/* Form card — premium glass dark */
html.dark .form-card {
    background: linear-gradient(160deg, rgba(22,32,52,0.97) 0%, rgba(15,23,42,0.99) 100%) !important;
    box-shadow:
        0 0 0 1px rgba(255,255,255,0.07),
        0 24px 64px rgba(0,0,0,0.8),
        0 8px 24px rgba(0,0,0,0.5),
        inset 0 1px 0 rgba(255,255,255,0.07),
        inset 0 -1px 0 rgba(0,0,0,0.2) !important;
}

/* Welcome text */
html.dark .form-title {
    color: #f1f5f9 !important;
}
html.dark .form-sub { color: #475569 !important; }

/* Field labels */
html.dark .field-label {
    color: #475569 !important;
}
html.dark .field-wrap:focus-within .field-label { color: #60a5fa !important; }

/* Inputs */
html.dark .field-input {
    background: rgba(8,13,25,0.85) !important;
    border: 1.5px solid rgba(255,255,255,0.08) !important;
    color: #e2e8f0 !important;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.4) !important;
    /* Preserve icon spacing — prevents icon from overlapping text */
    padding: 0 14px 0 40px !important;
}
html.dark .field-input.with-toggle {
    padding-right: 44px !important;
}
html.dark .field-input:hover {
    border-color: rgba(255,255,255,0.12) !important;
}
html.dark .field-input:focus {
    border-color: #3b82f6 !important;
    background: rgba(5,10,20,0.95) !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.18), inset 0 2px 4px rgba(0,0,0,0.4) !important;
    padding: 0 14px 0 40px !important;
}
html.dark .field-input.with-toggle:focus {
    padding-right: 44px !important;
}

/* ── Webkit Autofill Override (dark mode) ──────────────────────────
   Browser autofill ignores `background` — only box-shadow inset works.
   The 1000px inset shadow "paints over" the browser's autofill color.
─────────────────────────────────────────────────────────────────── */
html.dark .field-input:-webkit-autofill,
html.dark .field-input:-webkit-autofill:hover,
html.dark .field-input:-webkit-autofill:focus,
html.dark .field-input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 1000px rgba(8,13,25,0.97) inset !important;
    box-shadow: 0 0 0 1000px rgba(8,13,25,0.97) inset !important;
    -webkit-text-fill-color: #e2e8f0 !important;
    caret-color: #e2e8f0 !important;
    border: 1.5px solid rgba(255,255,255,0.08) !important;
    padding: 0 14px 0 40px !important;
}
html.dark .field-input.with-toggle:-webkit-autofill,
html.dark .field-input.with-toggle:-webkit-autofill:focus {
    padding-right: 44px !important;
}

/* ── Webkit Autofill Override (light mode) — remove yellow tint ── */
.field-input:-webkit-autofill,
.field-input:-webkit-autofill:hover,
.field-input:-webkit-autofill:focus,
.field-input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 1000px #f8fafc inset !important;
    box-shadow: 0 0 0 1000px #f8fafc inset !important;
    -webkit-text-fill-color: #0f172a !important;
    caret-color: #0f172a !important;
    border: 1.5px solid #e2e8f0 !important;
    padding: 0 14px 0 40px !important;
}
.field-input.with-toggle:-webkit-autofill,
.field-input.with-toggle:-webkit-autofill:focus {
    padding-right: 44px !important;
}
html.dark .field-input::placeholder { color: #334155 !important; }
html.dark .field-icon { color: #2d3f5f !important; }
html.dark .field-wrap:focus-within .field-icon { color: #3b82f6 !important; }

/* Password toggle */
html.dark .pw-toggle { color: #2d3f5f !important; }
html.dark .pw-toggle:hover {
    color: #64748b !important;
    background: rgba(255,255,255,0.05) !important;
}

/* Remember me */
html.dark .check-label span { color: #64748b !important; }

/* Card footer */
html.dark .card-footer {
    background: rgba(0,0,0,0.4) !important;
    border-top: 1px solid rgba(255,255,255,0.05) !important;
    color: #475569 !important;
}

/* Error box */
html.dark .error-box {
    background: rgba(244,63,94,0.12) !important;
    border-color: rgba(244,63,94,0.3) !important;
}
html.dark .error-box p { color: #fca5a5 !important; }

/* Desktop: left card panel */
@media (min-width: 1024px) {
    html.dark body {
        background: linear-gradient(145deg, #060a14 0%, #0c1525 40%, #0f1e3d 70%, #0d1b35 100%) !important;
    }
    html.dark .card-container {
        background: linear-gradient(180deg, rgba(5,9,18,0.99) 0%, rgba(8,13,25,0.99) 100%) !important;
        border-left: 1px solid rgba(255,255,255,0.05) !important;
    }
    html.dark .form-card {
        background: transparent !important;
        box-shadow: none !important;
    }
    html.dark .card-footer {
        background: rgba(0,0,0,0.5) !important;
        border-top: 1px solid rgba(255,255,255,0.04) !important;
        color: #475569 !important;
    }
    html.dark .field-input {
        background: rgba(3,6,15,0.9) !important;
        padding: 0 14px 0 40px !important;
    }
    html.dark .field-input.with-toggle {
        padding-right: 44px !important;
    }
    html.dark .field-input:focus {
        background: rgba(1,3,10,0.98) !important;
        padding: 0 14px 0 40px !important;
    }
    html.dark .field-input.with-toggle:focus {
        padding-right: 44px !important;
    }
    html.dark .desktop-right {
        background: linear-gradient(145deg, #040710 0%, #07123a 30%, #0b2080 60%, #1030b5 100%);
    }
}
</style>
</head>
<body>

    {{-- Dark Mode Toggle (fixed, top-right) --}}
    <button onclick="toggleDarkMode()" class="dm-login-btn" aria-label="Toggle dark mode" title="Toggle Dark Mode">
        <svg id="login-dm-sun" class="hidden" width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <svg id="login-dm-moon" width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>

    <script>
        // Set icon state immediately
        (function() {
            var sun = document.getElementById('login-dm-sun');
            var moon = document.getElementById('login-dm-moon');
            if (document.documentElement.classList.contains('dark')) {
                if (sun) sun.classList.remove('hidden');
                if (moon) moon.classList.add('hidden');
            }
        })();
    </script>

    {{-- ═══════════════════════════ MOBILE HERO ═══════════════════════════ --}}
    <div class="mobile-hero">
        <div class="hero-glow hero-glow-1"></div>
        <div class="hero-glow hero-glow-2"></div>

        <div class="status-pill">
            <span class="status-dot"></span>
            System Online
        </div>

        <div class="mobile-logo-ring">
            <div class="mobile-logo-inner">
                @if($companyLogo)
                    <img src="{{ asset($companyLogo) }}"
                         alt="{{ $companyName }}"
                         onerror="this.style.display='none';document.getElementById('mob-icon').style.display='flex'">
                    <span id="mob-icon" style="display:none;align-items:center;justify-content:center;width:100%;height:100%;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </span>
                @else
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                @endif
            </div>
        </div>

        <h1 class="mobile-hero-title">{{ $companyName }}</h1>
        <p class="mobile-hero-sub">{{ $companyTagline }}</p>
    </div>

    {{-- ═══════════════════════════ FORM CARD ═══════════════════════════ --}}
    <div class="card-container">
        <div class="form-card">
            <div class="card-body">

                <h2 class="form-title">Selamat datang 👋</h2>
                <p class="form-sub">Masukkan kredensial Anda untuk melanjutkan</p>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="error-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    {{-- Email --}}
                    <div class="field">
                        <label for="email" class="field-label">Alamat Email</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   class="field-input"
                                   placeholder="email@contoh.com"
                                   required
                                   autocomplete="email"
                                   autocapitalize="none"
                                   autocorrect="off"
                                   spellcheck="false">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="field">
                        <label for="password" class="field-label">Password</label>
                        <div class="field-wrap">
                            <span class="field-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password"
                                   class="field-input with-toggle"
                                   placeholder="••••••••"
                                   required
                                   autocomplete="current-password"
                                   autocapitalize="none"
                                   autocorrect="off"
                                   spellcheck="false">
                            <button type="button" class="pw-toggle" onclick="togglePw()" aria-label="Toggle password">
                                <svg id="pw-eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="meta-row">
                        <label class="check-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M14 12H3"/>
                        </svg>
                        Masuk
                    </button>
                </form>
            </div>

            <div class="card-footer">
                &copy; {{ date('Y') }} {{ $companyName }} &mdash; {{ $companyTagline }}
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════ DESKTOP RIGHT PANEL ═══════════════════════════ --}}
    <div class="desktop-right">
        <div class="orb orb-a"></div>
        <div class="orb orb-b"></div>
        <div class="orb orb-c"></div>

        {{-- Brand --}}
        <div class="d-brand">
            <div class="d-logo-wrap">
                @if($companyLogo)
                    <img src="{{ asset($companyLogo) }}"
                         alt="{{ $companyName }}"
                         onerror="this.style.display='none';document.getElementById('d-icon').style.display='flex'">
                    <span id="d-icon" style="display:none;align-items:center;justify-content:center;width:36px;height:36px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </span>
                @else
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                @endif
            </div>
            <div class="d-brand-text">
                <div class="d-brand-name">{{ $companyName }}</div>
                <div class="d-brand-tag">{{ $companyTagline }}</div>
            </div>
        </div>

        {{-- Hero --}}
        <div class="d-hero">
            <div class="d-badge">
                <span class="d-badge-dot"></span>
                Real-time System
            </div>

            <h1 class="d-title">
                Kelola inventory<br>lebih <em>cerdas</em>
            </h1>

            <p class="d-desc">
                Pantau stok, kelola supplier, dan dapatkan laporan real-time — semuanya dalam satu platform yang powerful.
            </p>

            {{-- Stats --}}
            <div class="d-stats">
                <div class="d-stat">
                    <div class="d-stat-num">∞</div>
                    <div class="d-stat-lbl">Real-time</div>
                </div>
                <div class="d-stat">
                    <div class="d-stat-num">PWA</div>
                    <div class="d-stat-lbl">Mobile</div>
                </div>
                <div class="d-stat">
                    <div class="d-stat-num">3</div>
                    <div class="d-stat-lbl">Roles</div>
                </div>
            </div>

            {{-- Features --}}
            <div class="d-features">
                <div class="d-feature">
                    <div class="d-feat-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="d-feat-text">Tracking stok & transaksi secara komplet</span>
                </div>
                <div class="d-feature">
                    <div class="d-feat-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="d-feat-text">Dashboard analitik & grafik live</span>
                </div>
                <div class="d-feature">
                    <div class="d-feat-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <span class="d-feat-text">Role-based access: Admin, Operator & User</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="d-footer">
            &copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.
        </div>
    </div>

<script>
    // Password toggle
    function togglePw() {
        const input = document.getElementById('password');
        const eye   = document.getElementById('pw-eye');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        eye.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>`
            : `<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }

    // Submit loading state
    document.querySelector('form').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 0.7s linear infinite">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Memproses...
        `;
    });
</script>
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
</body>
</html>