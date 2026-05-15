<!DOCTYPE html>
<html lang="en">

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
            if (typeof updateDarkModeIcons === 'function') updateDarkModeIcons(isDark);
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/artilia.png">

    {{-- PWA: Manifest & Theme --}}
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#2563eb">

    {{-- PWA: Apple / iOS --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Artilia">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-192x192.png">

    {{-- PWA: Microsoft --}}
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-TileImage" content="/icons/icon-192x192.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function userSidebarData() {
            return {
                borrowingOpen: {{ Route::is('user.borrowing.*') || Route::is('user.procurement.*') ? 'true' : 'false' }},
                init() {
                    console.log("✅ User Sidebar Alpine.js ready");
                }
            }
        }
    </script>
    @stack('styles')
    @livewireStyles
    <style>
        @media (min-width: 768px) {
            .main-content {
                margin-left: 16rem;
                /* 256px = w-64 */
            }
        }

        /* Mobile: no sidebar offset, but add top padding for fixed header */
        @media (max-width: 767px) {
            .main-content {
                margin-left: 0 !important;
            }

            /* Push header content right to avoid hamburger overlap */
            header {
                padding-left: 3.5rem !important;
                padding-right: 0.75rem !important;
            }

            /* Toast full-width on mobile, above bottom nav */
            #toast-container {
                left: 0.75rem !important;
                right: 0.75rem !important;
                bottom: calc(4.5rem + env(safe-area-inset-bottom, 0px));
                max-width: none !important;
                align-items: stretch;
            }

            /* Push content above bottom nav */
            main {
                padding-bottom: calc(5rem + env(safe-area-inset-bottom, 0px)) !important;
            }
        }

        /* ══════════════════════════════════════════
           DARK MODE — User Layout
        ══════════════════════════════════════════ */
        html.dark body {
            background: linear-gradient(145deg,
                #0f172a 0%, #1a1035 25%,
                #0f1f3a 55%, #0d1f2d 100%) !important;
            color: #f1f5f9;
        }
        /* Liquid Glass orbs — dark tinted */
        html.dark .lg-orb { opacity: 0.35 !important; }

        /* Sidebar Glass (dark) */
        html.dark .sidebar-container {
            background: rgba(15,23,42,0.80) !important;
            border-right: 1px solid rgba(255,255,255,0.06) !important;
            box-shadow: 4px 0 28px rgba(0,0,0,0.4) !important;
        }
        /* Header Glass (dark) */
        html.dark header {
            background: rgba(15,23,42,0.75) !important;
            border-bottom: 1px solid rgba(255,255,255,0.06) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;
        }
        /* Card Glass (dark) */
        html.dark .lg-card {
            background: rgba(30,41,59,0.75) !important;
            border: 1px solid rgba(255,255,255,0.08) !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.07) !important;
        }
        html.dark .lg-card::before {
            background: linear-gradient(90deg,
                transparent,
                rgba(255,255,255,0.07) 25%,
                rgba(150,120,255,0.12) 50%,
                rgba(100,170,255,0.12) 75%,
                rgba(255,255,255,0.07) 88%,
                transparent) !important;
        }
        /* White cards */
        html.dark .bg-white { background-color: #1e293b !important; }
        html.dark .bg-gray-50 { background-color: #0f172a !important; }
        html.dark .bg-gray-100 { background-color: #334155 !important; }
        html.dark .bg-gray-200 { background-color: #475569 !important; }
        /* Main content bg */
        html.dark main.bg-gray-50 { background-color: transparent !important; }
        /* Text */
        html.dark .text-gray-900 { color: #f1f5f9 !important; }
        html.dark .text-gray-800 { color: #e2e8f0 !important; }
        html.dark .text-gray-700 { color: #cbd5e1 !important; }
        html.dark .text-gray-600 { color: #94a3b8 !important; }
        html.dark .text-gray-500 { color: #94a3b8 !important; }
        html.dark .text-gray-400 { color: #64748b !important; }
        /* Borders */
        html.dark .border-gray-100 { border-color: rgba(255,255,255,0.08) !important; }
        html.dark .border-gray-200 { border-color: #334155 !important; }
        html.dark .divide-y > * + * { border-color: rgba(255,255,255,0.06) !important; }
        html.dark .divide-gray-50 > * + * { border-color: rgba(255,255,255,0.05) !important; }
        /* Sidebar nav */
        html.dark .sidebar-container .text-gray-600 { color: #94a3b8 !important; }
        html.dark .sidebar-container .text-gray-400 { color: #475569 !important; }
        html.dark .sidebar-container .hover\:bg-gray-50:hover { background-color: rgba(255,255,255,0.06) !important; }
        html.dark .sidebar-container .hover\:text-gray-900:hover { color: #f1f5f9 !important; }
        html.dark .sidebar-container .border-t { border-color: rgba(255,255,255,0.06) !important; }
        html.dark .sidebar-container .border-b { border-color: rgba(255,255,255,0.06) !important; }
        /* Header elements */
        html.dark header .text-gray-900 { color: #f1f5f9 !important; }
        html.dark header .text-gray-500 { color: #94a3b8 !important; }
        html.dark header .text-gray-400 { color: #64748b !important; }
        html.dark header .bg-gray-50 { background-color: rgba(255,255,255,0.08) !important; }
        html.dark header .bg-gray-100 { background-color: rgba(255,255,255,0.12) !important; }
        html.dark header .ring-gray-200 { --tw-ring-color: rgba(255,255,255,0.10) !important; }
        html.dark header .hover\:bg-gray-100:hover { background-color: rgba(255,255,255,0.12) !important; }
        html.dark header .border-gray-100 { border-color: rgba(255,255,255,0.08) !important; }
        /* Notification dropdown (user) */
        html.dark header .bg-white.rounded-2xl {
            background-color: rgba(15,23,42,0.95) !important;
            border-color: rgba(255,255,255,0.10) !important;
            backdrop-filter: blur(20px) !important;
        }
        html.dark header .hover\:bg-gray-50:hover { background-color: rgba(255,255,255,0.06) !important; }
        html.dark header .bg-blue-50\/40 { background-color: rgba(30,64,175,0.25) !important; }
        /* Forms */
        html.dark input:not([type=checkbox]):not([type=radio]),
        html.dark textarea,
        html.dark select {
            background-color: rgba(15,23,42,0.8) !important;
            color: #f1f5f9 !important;
            border-color: rgba(255,255,255,0.12) !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder { color: #64748b !important; }
        html.dark label { color: #94a3b8 !important; }
        /* Tables */
        html.dark table { color: #f1f5f9; }
        html.dark thead th, html.dark thead td {
            background-color: rgba(30,41,59,0.8) !important;
            color: #94a3b8 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        html.dark tbody tr { border-color: rgba(255,255,255,0.06) !important; }
        html.dark tbody tr:hover { background-color: rgba(255,255,255,0.05) !important; }
        html.dark tbody td { border-color: rgba(255,255,255,0.06) !important; }
        /* Toasts */
        html.dark .toast-item { background-color: rgba(15,23,42,0.95) !important; border-color: rgba(255,255,255,0.10) !important; }
        html.dark .toast-item .text-gray-900 { color: #f1f5f9 !important; }
        html.dark .toast-item .text-gray-500 { color: #94a3b8 !important; }
        html.dark .bg-gray-100.rounded-full { background-color: rgba(255,255,255,0.10) !important; }
        /* Skeleton */
        html.dark .ag-skeleton {
            background: linear-gradient(90deg, rgba(30,41,59,0.8) 25%, rgba(51,65,85,0.8) 50%, rgba(30,41,59,0.8) 75%);
            background-size: 400% 100%;
        }
        /* Ring */
        html.dark .ring-gray-100 { --tw-ring-color: rgba(255,255,255,0.08) !important; }
        html.dark .ring-gray-200 { --tw-ring-color: rgba(255,255,255,0.10) !important; }
        /* Scroll */
        html.dark ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); }
        html.dark .sidebar-navigation::-webkit-scrollbar-track { background: transparent; }
        html.dark .sidebar-navigation::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); }
        /* Smooth transitions */
        body, .sidebar-container, header, .bg-white, .mobile-bottom-nav,
        input, textarea, select, table, thead, tbody, td, th {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease;
        }

        /* ═══════════════════════════════════════
           DARK MODE — Nuclear "No White Left"
        ═══════════════════════════════════════ */
        html.dark .bg-white\/95,
        html.dark .bg-white\/90,
        html.dark .bg-white\/80,
        html.dark .bg-white\/70,
        html.dark .bg-white\/60,
        html.dark .bg-white\/50 { background-color: rgba(15,23,42,0.92) !important; }
        html.dark .ring-white { --tw-ring-color: #0f172a !important; }
        html.dark .ring-2.ring-white { --tw-ring-color: #1e293b !important; }
        html.dark .bn-badge { border-color: #0f172a !important; }
        html.dark select option { background-color: #1e293b !important; color: #f1f5f9 !important; }
        html.dark ::-webkit-scrollbar-track { background: #0f172a !important; }
        /* Flatpickr */
        html.dark .flatpickr-calendar { background: #1e293b !important; border-color: #334155 !important; box-shadow: 0 8px 32px rgba(0,0,0,0.5) !important; }
        html.dark .flatpickr-day { color: #f1f5f9 !important; }
        html.dark .flatpickr-day:hover { background: #334155 !important; }
        html.dark .flatpickr-day.selected { background: #2563eb !important; border-color: #2563eb !important; }
        html.dark .flatpickr-months, html.dark .flatpickr-weekdays { background: #0f172a !important; }
        html.dark .flatpickr-current-month, html.dark span.flatpickr-weekday { color: #94a3b8 !important; }

        /* ── Mobile Bottom Navigation Bar (User) ── */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 55;
            height: calc(3.75rem + env(safe-area-inset-bottom, 0px));
            padding-bottom: env(safe-area-inset-bottom, 0px);
            display: none;
            align-items: stretch;
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(28px) saturate(180%) brightness(108%);
            -webkit-backdrop-filter: blur(28px) saturate(180%) brightness(108%);
            border-top: 1px solid rgba(0,0,0,0.07);
            box-shadow: 0 -4px 24px rgba(0,0,0,0.06), inset 0 1px 0 rgba(255,255,255,0.9);
        }
        /* Bottom nav dark */
        html.dark .mobile-bottom-nav {
            background: rgba(15,23,42,0.90) !important;
            border-top: 1px solid rgba(255,255,255,0.06) !important;
            box-shadow: 0 -4px 24px rgba(0,0,0,0.4) !important;
        }
        html.dark .bn-item { color: #64748b !important; }
        @media (max-width: 767px)  { .mobile-bottom-nav { display: flex; } }
        @media (min-width: 768px)  { .mobile-bottom-nav { display: none !important; } }
        .bn-item {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 3px; padding: 0.3rem 0.25rem 0;
            color: #6b7280; text-decoration: none;
            transition: color 0.18s ease, transform 0.12s ease;
            position: relative; background: none; border: none;
            cursor: pointer; -webkit-tap-highlight-color: transparent;
            min-height: 44px;
        }
        .bn-item:active { transform: scale(0.88); }
        .bn-item.bn-active { color: #2563eb; }
        .bn-item svg { width: 22px; height: 22px; flex-shrink: 0; transition: transform 0.15s ease; }
        .bn-item.bn-active svg { transform: scale(1.1); }
        .bn-label { font-size: 9.5px; font-weight: 600; letter-spacing: 0.01em; line-height: 1; white-space: nowrap; }
        .bn-badge {
            position: absolute; top: 4px; left: calc(50% + 4px);
            min-width: 15px; height: 15px;
            background: #ef4444; color: white;
            font-size: 8.5px; font-weight: 700;
            border-radius: 99px; display: flex;
            align-items: center; justify-content: center;
            padding: 0 3px; border: 1.5px solid rgba(255,255,255,0.95);
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .bn-item.bn-active::after {
            content: ''; position: absolute;
            top: 0; left: 22%; right: 22%; height: 2.5px;
            background: #2563eb;
            border-radius: 0 0 3px 3px;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar-navigation {
            height: calc(100vh - 64px - 80px);
            overflow-y: scroll !important;
            overflow-x: hidden;
            max-height: calc(100vh - 144px);
        }

        .sidebar-navigation::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-navigation::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .sidebar-navigation::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .sidebar-navigation::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .sidebar-navigation {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .sidebar-container {
            height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* ════════════════════════════════════════
           ARTILIA ANIMATION ENGINE — USER LAYOUT
        ════════════════════════════════════════ */

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Page enter */
        main { animation: ag-fadeUp 0.32s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes ag-fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Top loading bar */
        #ag-topbar {
            position: fixed; top: 0; left: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #2563eb 0%, #7c3aed 60%, #06b6d4 100%);
            z-index: 99999; width: 0; opacity: 0;
            transition: width 0.25s ease, opacity 0.25s ease;
            border-radius: 0 2px 2px 0;
            box-shadow: 0 0 8px rgba(37,99,235,0.5);
        }
        #ag-topbar.ag-loading { opacity: 1; }

        /* Scroll-reveal */
        .ag-reveal {
            opacity: 0; transform: translateY(18px);
            transition: opacity 0.45s cubic-bezier(0.22,1,0.36,1),
                        transform 0.45s cubic-bezier(0.22,1,0.36,1);
        }
        .ag-reveal.ag-visible { opacity: 1; transform: translateY(0); }

        /* Table row stagger */
        @keyframes ag-rowIn {
            from { opacity: 0; transform: translateX(-6px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Card hover lift */
        .ag-card-hover {
            transition: transform 0.2s cubic-bezier(0.22,1,0.36,1),
                        box-shadow 0.2s cubic-bezier(0.22,1,0.36,1) !important;
            will-change: transform;
        }
        .ag-card-hover:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 28px -6px rgba(0,0,0,0.10), 0 4px 10px -4px rgba(0,0,0,0.06) !important;
        }

        /* Button press */
        .ag-btn-press { transition: transform 0.1s ease !important; }
        .ag-btn-press:active { transform: scale(0.96) !important; }

        /* Ripple */
        .ag-ripple-wrap { position: relative; overflow: hidden; }
        .ag-ripple {
            position: absolute; border-radius: 50%;
            transform: scale(0);
            background: rgba(255,255,255,0.35);
            animation: ag-rippleAnim 0.55s linear;
            pointer-events: none;
        }
        @keyframes ag-rippleAnim { to { transform: scale(4); opacity: 0; } }

        /* Skeleton shimmer utility */
        @keyframes ag-shimmer {
            from { background-position: -400% 0; }
            to   { background-position:  400% 0; }
        }
        .ag-skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 400% 100%;
            animation: ag-shimmer 1.6s ease-in-out infinite;
            border-radius: 6px;
        }

        /* ══════════════════════════════════════
           LIQUID GLASS — iOS 26 Inspired
        ══════════════════════════════════════ */

        body {
            background: linear-gradient(145deg,
                #eef2ff 0%, #f5f0ff 25%,
                #eff6ff 55%, #f0fdf9 100%) !important;
        }

        .lg-orb {
            position: fixed; border-radius: 50%;
            filter: blur(90px); pointer-events: none; z-index: 0;
            animation: lg-drift linear infinite;
        }
        @keyframes lg-drift {
            0%   { transform: translate(0,0) scale(1); }
            33%  { transform: translate(28px,-18px) scale(1.06); }
            66%  { transform: translate(-18px,28px) scale(0.94); }
            100% { transform: translate(0,0) scale(1); }
        }

        /* Keep content above orbs */
        .hidden.md\:flex, .main-content, header, .content-wrapper { position: relative; z-index: 1; }

        /* Sidebar Glass */
        .sidebar-container {
            background: rgba(255,255,255,0.65) !important;
            backdrop-filter: blur(32px) saturate(180%) brightness(110%) !important;
            -webkit-backdrop-filter: blur(32px) saturate(180%) brightness(110%) !important;
            border-right: 1px solid rgba(255,255,255,0.55) !important;
            box-shadow: inset 1px 0 0 rgba(255,255,255,0.7), 4px 0 28px rgba(0,0,0,0.05) !important;
        }

        /* Header Glass */
        header {
            background: rgba(255,255,255,0.70) !important;
            backdrop-filter: blur(28px) saturate(160%) brightness(108%) !important;
            -webkit-backdrop-filter: blur(28px) saturate(160%) brightness(108%) !important;
            border-bottom: 1px solid rgba(255,255,255,0.55) !important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.90), 0 4px 20px rgba(0,0,0,0.04) !important;
        }

        /* Card Glass */
        .lg-card {
            background: rgba(255,255,255,0.58) !important;
            backdrop-filter: blur(20px) saturate(150%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(150%) !important;
            border: 1px solid rgba(255,255,255,0.55) !important;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.85),
                0 8px 32px rgba(0,0,0,0.06),
                0 2px 8px rgba(0,0,0,0.03) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        .lg-card::before {
            content: '';
            position: absolute;
            top: 0; left: 8%; right: 8%; height: 1px;
            background: linear-gradient(90deg,
                transparent,
                rgba(255,255,255,0.90) 25%,
                rgba(210,200,255,0.75) 50%,
                rgba(200,225,255,0.75) 75%,
                rgba(255,255,255,0.90) 88%,
                transparent);
            z-index: 2;
        }
        .lg-card::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg,
                rgba(255,255,255,0.13) 0%,
                rgba(180,200,255,0.05) 40%,
                rgba(255,200,240,0.04) 70%,
                rgba(255,255,255,0.08) 100%);
            pointer-events: none; z-index: 0;
        }
        .lg-card > * { position: relative; z-index: 1; }
    </style>
</head>

<body class="min-h-screen">
    {{-- Mobile Hamburger (User) --}}
    @include('user.components.mobile-hamburger-user')

    {{-- Desktop Sidebar --}}
    @include('user.components.sidebar-user')

    <div class="main-content content-wrapper">
        <x-header-user />
        <main class="px-3 py-4 sm:px-4 sm:py-5 lg:px-6 lg:py-6 bg-gray-50 min-h-screen">
            @yield('user')
        </main>
    </div>

    {{-- ═══ Global Toast Notifications ═══ --}}
    @if(session('success') || session('error') || session('info') || session('warning'))
    <div id="toast-container"
         x-data="toastNotif()"
         x-init="init()"
         class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none"
         style="max-width: 360px;">

        @if(session('success'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 px-4 py-3.5 bg-white border border-green-100 rounded-xl shadow-lg ring-1 ring-green-50"
             x-show="visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">Berhasil!</p>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button @click="hide()" class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 px-4 py-3.5 bg-white border border-red-100 rounded-xl shadow-lg ring-1 ring-red-50"
             x-show="visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">Gagal!</p>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ session('error') }}</p>
            </div>
            <button @click="hide()" class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('info'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 px-4 py-3.5 bg-white border border-blue-100 rounded-xl shadow-lg ring-1 ring-blue-50"
             x-show="visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">Info</p>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ session('info') }}</p>
            </div>
            <button @click="hide()" class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('warning'))
        <div class="toast-item pointer-events-auto flex items-start gap-3 px-4 py-3.5 bg-white border border-amber-100 rounded-xl shadow-lg ring-1 ring-amber-50"
             x-show="visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center mt-0.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900">Perhatian</p>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ session('warning') }}</p>
            </div>
            <button @click="hide()" class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- Progress bar --}}
        <div class="pointer-events-auto h-1 bg-gray-100 rounded-full overflow-hidden -mt-1" x-show="visible" style="max-width:360px;">
            <div class="h-full bg-green-400 rounded-full transition-all ease-linear"
                 :style="`width: ${progress}%; transition-duration: ${duration}ms`"></div>
        </div>
    </div>
    @endif

    <script>
    function toastNotif() {
        return {
            visible: false,
            progress: 100,
            duration: 4000,
            timer: null,
            init() {
                this.$nextTick(() => {
                    this.visible = true;
                    this.startTimer();
                });
            },
            startTimer() {
                this.progress = 0;
                this.timer = setTimeout(() => this.hide(), this.duration);
            },
            hide() {
                clearTimeout(this.timer);
                this.visible = false;
            }
        };
    }
    </script>

    @stack('scripts')

    <!-- ═══ Artilia Global Animation Engine ═══ -->
    <script>
    (function () {
        /* Top navigation loading bar */
        var bar = document.createElement('div');
        bar.id = 'ag-topbar';
        document.body.prepend(bar);
        var barTimer, barW = 0;
        function barStart() {
            clearInterval(barTimer); barW = 0;
            bar.style.width = '0%';
            bar.classList.add('ag-loading');
            barTimer = setInterval(function () {
                barW = Math.min(barW + Math.random() * 10 + 3, 88);
                bar.style.width = barW + '%';
            }, 140);
        }
        function barFinish() {
            clearInterval(barTimer);
            bar.style.width = '100%';
            setTimeout(function () {
                bar.classList.remove('ag-loading');
                setTimeout(function () { bar.style.width = '0%'; }, 50);
            }, 280);
        }
        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (!a || e.metaKey || e.ctrlKey || e.shiftKey || a.target) return;
            try {
                var u = new URL(a.href, location.origin);
                if (u.origin === location.origin && u.pathname !== location.pathname) barStart();
            } catch (_) {}
        });
        window.addEventListener('pageshow', barFinish);

        /* Scroll-reveal stagger */
        var main = document.querySelector('main');
        if (main) {
            var kids = Array.from(main.children);
            kids.forEach(function (el, i) {
                el.classList.add('ag-reveal');
                el.style.transitionDelay = Math.min(i * 0.07, 0.35) + 's';
            });
            var ro = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('ag-visible');
                        ro.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.06, rootMargin: '0px 0px -16px 0px' });
            kids.forEach(function (el) { ro.observe(el); });
        }

        /* Table row stagger */
        document.querySelectorAll('table tbody').forEach(function (tbody) {
            tbody.querySelectorAll('tr').forEach(function (tr, i) {
                tr.style.opacity = '0';
                tr.style.animation = 'ag-rowIn 0.3s cubic-bezier(0.22,1,0.36,1) ' +
                    Math.min(i * 0.045, 0.32) + 's both';
            });
        });

        /* Card hover lift */
        document.querySelectorAll(
            '.bg-white.rounded-xl, .bg-white.rounded-2xl, .bg-white.rounded-lg'
        ).forEach(function (el) {
            if (!el.closest('table') && !el.closest('nav')) el.classList.add('ag-card-hover');
        });

        /* Button press + ripple */
        document.querySelectorAll('button, [type="submit"]').forEach(function (btn) {
            btn.classList.add('ag-btn-press', 'ag-ripple-wrap');
            btn.addEventListener('click', function (e) {
                var r = document.createElement('span');
                r.className = 'ag-ripple';
                var rect = btn.getBoundingClientRect();
                var size = Math.max(rect.width, rect.height);
                r.style.cssText = 'width:' + size + 'px;height:' + size + 'px;' +
                    'left:' + (e.clientX - rect.left - size / 2) + 'px;' +
                    'top:'  + (e.clientY - rect.top  - size / 2) + 'px;';
                btn.appendChild(r);
                setTimeout(function () { r.remove(); }, 600);
            });
        });

        /* Counter-up */
        document.querySelectorAll('[data-count]').forEach(function (el) {
            var target = parseInt(el.dataset.count, 10);
            if (isNaN(target)) return;
            var dur = 900, start = performance.now();
            function tick(now) {
                var pct  = Math.min((now - start) / dur, 1);
                var ease = 1 - Math.pow(1 - pct, 3);
                el.textContent = Math.round(ease * target).toLocaleString('id-ID');
                if (pct < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });

        /* Liquid Glass background orbs */
        var orbData = [
            { w:700, h:600, l:'-6%',  t:'-12%', c:'rgba(99,102,241,0.16)',  d:'22s', delay:'0s'   },
            { w:550, h:700, l:'68%',  t:'-8%',  c:'rgba(14,165,233,0.13)',  d:'28s', delay:'-7s'  },
            { w:750, h:480, l:'28%',  t:'58%',  c:'rgba(168,85,247,0.11)',  d:'19s', delay:'-4s'  },
            { w:480, h:560, l:'80%',  t:'52%',  c:'rgba(16,185,129,0.10)',  d:'25s', delay:'-11s' },
        ];
        orbData.forEach(function (o) {
            var el = document.createElement('div');
            el.className = 'lg-orb';
            el.style.cssText =
                'width:' + o.w + 'px;height:' + o.h + 'px;' +
                'left:' + o.l + ';top:' + o.t + ';' +
                'background:' + o.c + ';' +
                'animation-duration:' + o.d + ';' +
                'animation-delay:' + o.delay + ';';
            document.body.appendChild(el);
        });

        /* Apply liquid glass to cards */
        document.querySelectorAll(
            '.bg-white.rounded-xl, .bg-white.rounded-2xl, .bg-white.rounded-lg'
        ).forEach(function (el) {
            if (!el.closest('table') && !el.closest('nav') &&
                !el.classList.contains('sidebar-container')) {
                el.classList.add('lg-card');
            }
        });
    })();
    </script>

    {{-- ═══ PWA: Service Worker + Install Banner ═══ --}}

    {{-- Install Banner HTML --}}
    <div id="pwa-install-banner" style="display:none;" aria-live="polite">
        <div id="pwa-banner-inner">
            <div class="pwa-banner-logo">
                <img src="/artilia.png" alt="Artilia" onerror="this.style.display='none'">
            </div>
            <div class="pwa-banner-text">
                <p class="pwa-banner-title">Install Artilia</p>
                <p class="pwa-banner-sub" id="pwa-banner-sub">Akses cepat dari Home Screen, bahkan saat offline</p>
            </div>
            <div class="pwa-banner-actions">
                <button id="pwa-install-btn" class="pwa-btn-install">Install</button>
                <button id="pwa-dismiss-btn" class="pwa-btn-dismiss">Nanti</button>
            </div>
        </div>
        {{-- iOS instructions (shown separately) --}}
        <div id="pwa-ios-hint" style="display:none;">
            <p class="pwa-ios-text">
                Tap <strong>Bagikan</strong>
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display:inline;vertical-align:middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4m0 0L8 6m4-4v13"/></svg>
                lalu <strong>Tambahkan ke Layar Utama</strong>
            </p>
        </div>
    </div>

    <style>
    /* ── PWA Install Banner ── */
    #pwa-install-banner {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 99998;
        padding: 0 0.75rem 0.75rem;
        pointer-events: none;
    }
    #pwa-banner-inner {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255,255,255,0.96);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1.5px solid rgba(37,99,235,0.12);
        border-radius: 18px;
        padding: 0.85rem 1rem;
        box-shadow:
            0 -2px 0 rgba(37,99,235,0.06),
            0 8px 32px -4px rgba(0,0,0,0.14),
            0 2px 8px -2px rgba(0,0,0,0.08);
        pointer-events: all;
        transform: translateY(120%);
        transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }
    #pwa-install-banner.pwa-visible #pwa-banner-inner {
        transform: translateY(0);
    }
    #pwa-install-banner.pwa-hiding #pwa-banner-inner {
        transform: translateY(120%);
    }
    .pwa-banner-logo img {
        width: 40px; height: 40px;
        border-radius: 10px;
        object-fit: contain;
        flex-shrink: 0;
        box-shadow: 0 2px 8px -2px rgba(37,99,235,0.18);
    }
    .pwa-banner-text { flex: 1; min-width: 0; }
    .pwa-banner-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
        line-height: 1.2;
    }
    .pwa-banner-sub {
        font-size: 0.72rem;
        color: #6b7280;
        margin: 2px 0 0;
        line-height: 1.35;
    }
    .pwa-banner-actions {
        display: flex;
        gap: 0.4rem;
        flex-shrink: 0;
    }
    .pwa-btn-install {
        padding: 0.45rem 0.9rem;
        background: #2563eb;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
        box-shadow: 0 2px 8px -2px rgba(37,99,235,0.5);
        white-space: nowrap;
    }
    .pwa-btn-install:hover  { background: #1d4ed8; }
    .pwa-btn-install:active { transform: scale(0.95); }
    .pwa-btn-dismiss {
        padding: 0.45rem 0.7rem;
        background: transparent;
        color: #9ca3af;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
        white-space: nowrap;
    }
    .pwa-btn-dismiss:hover { background: #f3f4f6; color: #6b7280; }

    /* iOS hint variant */
    #pwa-ios-hint {
        padding: 0 0.5rem;
        pointer-events: all;
    }
    .pwa-ios-text {
        font-size: 0.78rem;
        color: #4b5563;
        text-align: center;
        line-height: 1.5;
        margin: 0;
    }

    /* Only show on small screens (mobile) */
    @media (min-width: 768px) {
        #pwa-install-banner { display: none !important; }
    }
    </style>

    <script>
    (function () {
        'use strict';

        /* ── Service Worker ── */
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function (reg) {
                        console.log('[Artilia PWA] Service Worker registered, scope:', reg.scope);

                        // Check for SW updates
                        reg.addEventListener('updatefound', function () {
                            var newWorker = reg.installing;
                            if (newWorker) {
                                newWorker.addEventListener('statechange', function () {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        // New version available — silent update
                                        console.log('[Artilia PWA] New version cached.');
                                    }
                                });
                            }
                        });
                    })
                    .catch(function (err) {
                        console.warn('[Artilia PWA] SW registration failed:', err);
                    });
            });
        }

        /* ── Install Banner Logic ── */
        var DISMISS_KEY = 'artilia_pwa_dismissed';
        var DISMISS_TTL = 7 * 24 * 60 * 60 * 1000; // 7 days
        var deferredPrompt = null;
        var banner = document.getElementById('pwa-install-banner');
        var bannerInner = document.getElementById('pwa-banner-inner');
        var iosHint = document.getElementById('pwa-ios-hint');

        if (!banner) return;

        /* Helper: check if dismissed recently */
        function wasDismissed() {
            try {
                var ts = localStorage.getItem(DISMISS_KEY);
                return ts && (Date.now() - parseInt(ts, 10)) < DISMISS_TTL;
            } catch (_) { return false; }
        }

        /* Helper: check if already installed */
        function isInstalled() {
            return window.matchMedia('(display-mode: standalone)').matches ||
                   window.navigator.standalone === true;
        }

        /* Show banner with slide-up animation */
        function showBanner() {
            if (wasDismissed() || isInstalled()) return;
            banner.style.display = 'block';
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    banner.classList.add('pwa-visible');
                });
            });
        }

        /* Hide banner with slide-down animation */
        function hideBanner() {
            banner.classList.add('pwa-hiding');
            setTimeout(function () {
                banner.style.display = 'none';
                banner.classList.remove('pwa-visible', 'pwa-hiding');
            }, 450);
        }

        /* ── Android/Chrome: beforeinstallprompt ── */
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredPrompt = e;
            setTimeout(showBanner, 2500); // show after 2.5s
        });

        /* Install button */
        var installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            installBtn.addEventListener('click', function () {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function (choice) {
                    console.log('[Artilia PWA] Install choice:', choice.outcome);
                    deferredPrompt = null;
                    hideBanner();
                    if (choice.outcome === 'accepted') {
                        try { localStorage.setItem(DISMISS_KEY, Date.now().toString()); } catch (_) {}
                    }
                });
            });
        }

        /* Dismiss button */
        var dismissBtn = document.getElementById('pwa-dismiss-btn');
        if (dismissBtn) {
            dismissBtn.addEventListener('click', function () {
                try { localStorage.setItem(DISMISS_KEY, Date.now().toString()); } catch (_) {}
                hideBanner();
            });
        }

        /* Installed event */
        window.addEventListener('appinstalled', function () {
            console.log('[Artilia PWA] App installed!');
            hideBanner();
            deferredPrompt = null;
        });

        /* ── iOS / Safari: Show manual instructions ── */
        var isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
        var isSafari = /safari/i.test(navigator.userAgent) && !/chrome/i.test(navigator.userAgent);
        if (isIOS && isSafari && !isInstalled() && !wasDismissed()) {
            bannerInner.style.flexDirection = 'column';
            bannerInner.style.gap = '0.5rem';
            iosHint.style.display = 'block';
            var subEl = document.getElementById('pwa-banner-sub');
            if (subEl) subEl.textContent = 'Ikuti langkah berikut untuk install:';
            var installBtnEl = document.getElementById('pwa-install-btn');
            if (installBtnEl) installBtnEl.style.display = 'none';
            setTimeout(showBanner, 3000);
        }

    })();
    </script>

    {{-- ═══ Mobile Bottom Navigation Bar (User) ═══ --}}
    @auth
    @php
        $uCartBadge = $sidebarCartCount ?? 0;
        $uNotifBadge = $sidebarUserUnreadCount ?? 0;
    @endphp
    <nav class="mobile-bottom-nav" aria-label="User Mobile Navigation" x-data>

        {{-- Home --}}
        <a href="{{ route('user.dashboard') }}"
           class="bn-item {{ Route::is('user.dashboard') ? 'bn-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="bn-label">Home</span>
        </a>

        {{-- Browse --}}
        <a href="{{ route('user.borrowing.index') }}"
           class="bn-item {{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') ? 'bn-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="bn-label">Browse</span>
        </a>

        {{-- Cart --}}
        <a href="{{ route('user.borrowing.cart') }}"
           class="bn-item {{ Route::is('user.borrowing.cart') ? 'bn-active' : '' }}">
            @if($uCartBadge > 0)<span class="bn-badge">{{ $uCartBadge > 9 ? '9+' : $uCartBadge }}</span>@endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="bn-label">Cart</span>
        </a>

        {{-- Notifications --}}
        <a href="{{ route('user.notifications.index') }}"
           class="bn-item {{ Route::is('user.notifications.*') ? 'bn-active' : '' }}">
            @if($uNotifBadge > 0)<span class="bn-badge">{{ $uNotifBadge > 9 ? '9+' : $uNotifBadge }}</span>@endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="bn-label">Notifikasi</span>
        </a>

        {{-- More (opens hamburger) --}}
        <button type="button" class="bn-item"
                @click="$dispatch('open-mobile-menu')" aria-label="More">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span class="bn-label">More</span>
        </button>

    </nav>
    @endauth
    @livewireScripts
</body>

</html>