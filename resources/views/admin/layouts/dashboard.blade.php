<!DOCTYPE html>
<html lang="en" class="h-full">
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
    <title>Dashboard — {{ $companyName ?? 'Artilia' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/artilia.png">

    <!-- Alpine.js Collapse Plugin (must load before Alpine) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Controller -->
    <script>
        function sidebarData() {
            @php
                $curRole = Auth::user()->role->value ?? 'user';
                $curRp   = $routePrefix ?? ($curRole === 'operator' ? 'staff' : 'admin');
            @endphp
            const isMasterActive = {{ Route::is($curRp . '.categories.*', $curRp . '.suppliers.*', $curRp . '.locations.*') ? 'true' : 'false' }};
            const isInventoryActive = {{ (
                Route::is($curRp . '.inventory.*') ||
                Route::is($curRp . '.incoming.*') ||
                Route::is($curRp . '.outgoing.*') ||
                Route::is($curRp . '.maintenance.*') ||
                Route::is($curRp . '.stock-opnames.*') ||
                Route::is($curRp . '.calibration.*') ||
                Route::is($curRp . '.logistics.*') ||
                Route::is($curRp . '.tooling-kits.*') ||
                Route::is($curRp . '.bap.*') ||
                Route::is($curRp . '.borrowing-requests.bap') ||
                Route::is($curRp . '.safety.*')
            ) ? 'true' : 'false' }};
            const isBorrowingActive = {{ (
                Route::is($curRp . '.borrowings.*') ||
                (Route::is($curRp . '.borrowing-requests.*') && !Route::is($curRp . '.borrowing-requests.bap'))
            ) ? 'true' : 'false' }};
            const isUsersActive = {{ Route::is($curRp . '.content.*') ? 'true' : 'false' }};

            return {
                // Dropdown hanya terbuka jika halaman saat ini berada di dalam dropdown tersebut
                masterDataOpen: isMasterActive,
                inventoryOpen: isInventoryActive,
                borrowingOpen: isBorrowingActive,
                usersOpen: isUsersActive,

                init() {
                    // Bersihkan cache localStorage sebelumnya agar dropdown tidak dipaksa terbuka saat navigasi halaman
                    ['artilia_sidebar_master', 'artilia_sidebar_inv', 'artilia_sidebar_borrow', 'artilia_sidebar_users'].forEach(k => {
                        localStorage.removeItem(k);
                    });

                    // Automatically scroll the active element into view inside the sidebar
                    this.$nextTick(() => {
                        const activeDesktop = document.querySelector('.desktop-sidebar [data-active="true"]');
                        if (activeDesktop) {
                            activeDesktop.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                        }
                    });
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

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

    @stack('styles')

    <style>
        /* ── Global Font ── */
        *, *::before, *::after { box-sizing: border-box; }
        body, html {
            font-family: 'Geist', ui-sans-serif, system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        .sidebar-navigation::-webkit-scrollbar { width: 3px; }
        .sidebar-navigation::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 99px; }

        /* ═══════════════════════════════════════
           DARK MODE — CSS Custom Properties
        ═══════════════════════════════════════ */
        :root {
            --dm-bg:           #ffffff;
            --dm-bg-secondary: #f9fafb;
            --dm-surface:      #ffffff;
            --dm-border:       #e5e7eb;
            --dm-text:         #111827;
            --dm-text-muted:   #6b7280;
            --dm-text-subtle:  #9ca3af;
            --dm-sidebar-bg:   #ffffff;
            --dm-header-bg:    rgba(255,255,255,0.95);
            --dm-input-bg:     #ffffff;
            --dm-hover:        #f9fafb;
            --dm-table-stripe: #f9fafb;
        }
        html.dark {
            --dm-bg:           #0f172a;
            --dm-bg-secondary: #1e293b;
            --dm-surface:      #1e293b;
            --dm-border:       #334155;
            --dm-text:         #f1f5f9;
            --dm-text-muted:   #94a3b8;
            --dm-text-subtle:  #64748b;
            --dm-sidebar-bg:   #1e293b;
            --dm-header-bg:    rgba(15,23,42,0.95);
            --dm-input-bg:     #0f172a;
            --dm-hover:        #334155;
            --dm-table-stripe: #1a2744;
        }

        /* ── Dark mode body & background ── */
        html.dark body {
            background-color: var(--dm-bg);
            color: var(--dm-text);
        }

        /* ── Sidebar ── */
        html.dark .sidebar-container {
            background-color: var(--dm-sidebar-bg) !important;
            border-color: var(--dm-border) !important;
        }
        html.dark .sidebar-navigation::-webkit-scrollbar-thumb { background: #334155; }
        html.dark ::-webkit-scrollbar-thumb { background: #475569; }
        html.dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* ── Header ── */
        html.dark .app-top-header {
            background: var(--dm-header-bg) !important;
            border-color: var(--dm-border) !important;
        }
        html.dark .app-top-header .text-gray-900 { color: var(--dm-text) !important; }
        html.dark .app-top-header .text-gray-500 { color: var(--dm-text-muted) !important; }
        html.dark .app-top-header .text-gray-400 { color: var(--dm-text-subtle) !important; }
        html.dark .app-top-header .bg-gray-50  { background-color: #334155 !important; }
        html.dark .app-top-header .bg-gray-100 { background-color: #475569 !important; }
        html.dark .app-top-header .ring-gray-200 { --tw-ring-color: #334155 !important; }
        html.dark .app-top-header .hover\:bg-gray-100:hover { background-color: #475569 !important; }
        html.dark .app-top-header .border-gray-100 { border-color: var(--dm-border) !important; }

        /* ── Dark Mode Toggle button in header ── */
        html:not(.dark) .dm-toggle-btn {
            background: #f3f4f6;
            color: #4b5563;
            ring: 1px solid #e5e7eb;
        }
        html:not(.dark) .dm-toggle-btn:hover { background: #e5e7eb; }
        html.dark .dm-toggle-btn {
            background: #334155;
            color: #e2e8f0;
        }
        html.dark .dm-toggle-btn:hover { background: #475569; }

        /* ── White cards / panels ── */
        html.dark .bg-white {
            background-color: var(--dm-surface) !important;
        }
        html.dark .bg-gray-50  { background-color: #1e293b !important; }
        html.dark .bg-gray-100 { background-color: #334155 !important; }
        html.dark .bg-gray-200 { background-color: #475569 !important; }

        /* ── Text colors ── */
        html.dark .text-gray-900 { color: var(--dm-text) !important; }
        html.dark .text-gray-800 { color: #e2e8f0 !important; }
        html.dark .text-gray-700 { color: #cbd5e1 !important; }
        html.dark .text-gray-600 { color: var(--dm-text-muted) !important; }
        html.dark .text-gray-500 { color: var(--dm-text-muted) !important; }
        html.dark .text-gray-400 { color: var(--dm-text-subtle) !important; }

        /* ── Borders ── */
        html.dark .border-gray-100 { border-color: var(--dm-border) !important; }
        html.dark .border-gray-200 { border-color: #334155 !important; }
        html.dark .divide-gray-100 > * + * { border-color: var(--dm-border) !important; }
        html.dark .divide-gray-200 > * + * { border-color: #334155 !important; }
        html.dark .divide-y > * + * { border-color: var(--dm-border) !important; }

        /* ── Tables ── */
        html.dark table { color: var(--dm-text); }
        html.dark thead th,
        html.dark thead td {
            background-color: #1e293b !important;
            color: var(--dm-text-muted) !important;
            border-color: var(--dm-border) !important;
        }
        html.dark tbody tr { border-color: var(--dm-border) !important; }
        html.dark tbody tr:hover { background-color: #334155 !important; }
        html.dark tbody tr:nth-child(even) { background-color: var(--dm-table-stripe) !important; }
        html.dark tbody td { border-color: var(--dm-border) !important; }
        html.dark .divide-y.divide-gray-50 > * + * { border-color: #334155 !important; }

        /* ── Forms & Inputs ── */
        html.dark input:not([type=checkbox]):not([type=radio]),
        html.dark textarea,
        html.dark select {
            background-color: var(--dm-input-bg) !important;
            color: var(--dm-text) !important;
            border-color: var(--dm-border) !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder { color: var(--dm-text-subtle) !important; }
        html.dark label { color: var(--dm-text-muted) !important; }
        html.dark .form-label { color: var(--dm-text-muted) !important; }

        /* ── Sidebar Nav items ── */
        html.dark .sidebar-container p.text-gray-400 { color: #475569 !important; }

        /* ── Notification dropdown ── */
        html.dark .bg-white.rounded-2xl.border { background-color: var(--dm-surface) !important; border-color: var(--dm-border) !important; }
        html.dark .hover\:bg-gray-50:hover { background-color: #334155 !important; }
        html.dark .bg-blue-50\/40 { background-color: rgba(30,64,175,0.2) !important; }

        /* ── Toasts ── */
        html.dark .toast-item.bg-white { background-color: #1e293b !important; }
        html.dark .toast-item { border-color: #334155 !important; }
        html.dark .toast-item .text-gray-900 { color: var(--dm-text) !important; }
        html.dark .toast-item .text-gray-500 { color: var(--dm-text-muted) !important; }

        /* ── Badges & Rings ── */
        html.dark .ring-gray-100 { --tw-ring-color: #334155 !important; }
        html.dark .ring-1.ring-gray-200 { --tw-ring-color: #334155 !important; }
        html.dark .ring-gray-50  { --tw-ring-color: #1e293b !important; }


        /* ── PWA banner ── */
        html.dark #pwa-banner-inner {
            background: rgba(30,41,59,0.97) !important;
            border-color: rgba(99,102,241,0.2) !important;
        }
        html.dark .pwa-banner-title { color: var(--dm-text) !important; }
        html.dark .pwa-banner-sub { color: var(--dm-text-muted) !important; }
        html.dark .pwa-btn-dismiss { border-color: #475569 !important; color: #94a3b8 !important; }
        html.dark .pwa-btn-dismiss:hover { background: #334155 !important; }

        /* ── Mobile Bottom Nav (dark) ── */
        html.dark .mobile-bottom-nav {
            background: rgba(15,23,42,0.92) !important;
            border-color: rgba(255,255,255,0.06) !important;
        }
        html.dark .bn-item { color: #64748b !important; }

        /* ── Sidebar skeleton shimmer (dark) ── */
        html.dark .ag-skeleton {
            background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%);
            background-size: 400% 100%;
            animation: ag-shimmer 1.6s ease-in-out infinite;
        }

        /* ══════════════════════════════════════════════════
           SIDEBAR DARK MODE — COMPREHENSIVE
        ══════════════════════════════════════════════════ */

        /* Sidebar container & border */
        html.dark .sidebar-container {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        /* Logo section border */
        html.dark .sidebar-container > div > div:first-child {
            border-color: #1f2937 !important;
        }

        /* Company name text */
        html.dark .sidebar-container .text-gray-900 { color: #f1f5f9 !important; }

        /* Section headers (Main, Master Data, etc.) */
        html.dark .sidebar-container p.text-gray-400 { color: #475569 !important; }

        /* ── Nav links default state ── */
        html.dark .sidebar-container a.text-gray-600,
        html.dark .sidebar-container button.text-gray-600 {
            color: #94a3b8 !important;
        }
        /* All nav link text that uses text-gray-600 */
        html.dark .sidebar-container a span,
        html.dark .sidebar-container button span { color: inherit; }

        /* ── Nav link hover state ── */
        html.dark .sidebar-container a:hover,
        html.dark .sidebar-container button:hover {
            background-color: rgba(255,255,255,0.06) !important;
            color: #e2e8f0 !important;
        }

        /* ── Icons: default (gray-400) → visible slate in dark ── */
        html.dark .sidebar-container svg.text-gray-400,
        html.dark .sidebar-container [class*="text-gray-400"] svg {
            color: #64748b !important;
        }
        /* When hovered, lighten icon */
        html.dark .sidebar-container a:hover svg,
        html.dark .sidebar-container button:hover svg {
            color: #94a3b8 !important;
        }

        /* ── hover:bg-gray-50 / hover:text-gray-900 overrides ── */
        html.dark .sidebar-container .hover\:bg-gray-50:hover {
            background-color: rgba(255,255,255,0.06) !important;
        }
        html.dark .sidebar-container .hover\:text-gray-900:hover {
            color: #e2e8f0 !important;
        }

        /* ── Sub-menu child items (pl-7) ── */
        html.dark .sidebar-container .pl-7 a {
            color: #94a3b8 !important;
        }
        html.dark .sidebar-container .pl-7 a:hover {
            background-color: rgba(255,255,255,0.06) !important;
            color: #e2e8f0 !important;
        }

        /* ── Bottom border (logout area) ── */
        html.dark .sidebar-container .border-t { border-color: #1f2937 !important; }

        /* Logout button */
        html.dark .sidebar-container button[type="submit"] {
            background-color: rgba(239,68,68,0.15) !important;
            color: #f87171 !important;
        }
        html.dark .sidebar-container button[type="submit"]:hover {
            background-color: rgba(239,68,68,0.25) !important;
        }

        /* Sidebar scrollbar */
        html.dark .sidebar-navigation::-webkit-scrollbar { width: 4px; }
        html.dark .sidebar-navigation::-webkit-scrollbar-track { background: transparent; }
        html.dark .sidebar-navigation::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        /* Header icons */
        html.dark .app-top-header svg { color: inherit; }

        /* Generic svg inside dark mode gets a safe fallback */
        html.dark svg[stroke="currentColor"] { color: inherit; }

        /* ── Active nav state fix (sidebar & mobile) ── */
        html.dark .sidebar-container .bg-blue-50   { background-color: rgba(59,130,246,0.28) !important; }
        html.dark .sidebar-container .bg-emerald-50 { background-color: rgba(16,185,129,0.28) !important; }
        html.dark .sidebar-container .text-blue-700 { color: #93c5fd !important; }
        html.dark .sidebar-container .text-emerald-700 { color: #6ee7b7 !important; }
        html.dark .sidebar-container .text-blue-600 { color: #60a5fa !important; }
        html.dark .sidebar-container .text-emerald-600 { color: #34d399 !important; }
        html.dark .sidebar-container .text-blue-500 { color: #60a5fa !important; }
        html.dark .sidebar-container .text-emerald-500 { color: #34d399 !important; }

        /* Mobile hamburger active states */
        html.dark .mobile-hamburger-container .bg-blue-50   { background-color: rgba(59,130,246,0.28) !important; }
        html.dark .mobile-hamburger-container .bg-emerald-50 { background-color: rgba(16,185,129,0.28) !important; }
        html.dark .mobile-hamburger-container .text-blue-700 { color: #93c5fd !important; }
        html.dark .mobile-hamburger-container .text-emerald-700 { color: #6ee7b7 !important; }
        html.dark .mobile-hamburger-container .text-blue-600 { color: #60a5fa !important; }
        html.dark .mobile-hamburger-container .text-emerald-600 { color: #34d399 !important; }

        /* ── Explicit Active Item Highlight [data-active="true"] ── */
        .sidebar-container a[data-active="true"],
        .sidebar-container .pl-7 a[data-active="true"],
        .mobile-hamburger-container a[data-active="true"],
        .mobile-hamburger-container .pl-7 a[data-active="true"] {
            color: #1d4ed8 !important;
            background-color: #eff6ff !important;
            font-weight: 600 !important;
            border-left: 3px solid #2563eb !important;
            padding-left: calc(0.75rem - 3px) !important;
        }
        .sidebar-container a[data-active="true"] svg,
        .mobile-hamburger-container a[data-active="true"] svg {
            color: #2563eb !important;
        }

        html.dark .sidebar-container a[data-active="true"],
        html.dark .sidebar-container .pl-7 a[data-active="true"],
        html.dark .mobile-hamburger-container a[data-active="true"],
        html.dark .mobile-hamburger-container .pl-7 a[data-active="true"] {
            color: #93c5fd !important;
            background-color: rgba(37, 99, 235, 0.22) !important;
            font-weight: 600 !important;
            border-left: 3px solid #60a5fa !important;
            padding-left: calc(0.75rem - 3px) !important;
        }
        html.dark .sidebar-container a[data-active="true"] svg,
        html.dark .mobile-hamburger-container a[data-active="true"] svg {
            color: #60a5fa !important;
        }

        /* User mobile hamburger active states */
        html.dark .user-mobile-hamburger .bg-blue-50   { background-color: rgba(59,130,246,0.28) !important; }
        html.dark .user-mobile-hamburger .bg-orange-50 { background-color: rgba(249,115,22,0.28) !important; }
        html.dark .user-mobile-hamburger .text-blue-700 { color: #93c5fd !important; }
        html.dark .user-mobile-hamburger .text-orange-700 { color: #fdba74 !important; }
        html.dark .user-mobile-hamburger .text-blue-600 { color: #60a5fa !important; }
        html.dark .user-mobile-hamburger .text-orange-600 { color: #fb923c !important; }

        /* ── Status badges (colored) — visible icon wrappers ── */
        html.dark .bg-green-100  { background-color: rgba(34,197,94,0.28)   !important; }
        html.dark .bg-red-100    { background-color: rgba(239,68,68,0.28)   !important; }
        html.dark .bg-yellow-100 { background-color: rgba(250,204,21,0.28)  !important; }
        html.dark .bg-blue-100   { background-color: rgba(59,130,246,0.30)  !important; }
        html.dark .bg-orange-100 { background-color: rgba(249,115,22,0.28)  !important; }
        html.dark .bg-purple-100 { background-color: rgba(139,92,246,0.30)  !important; }
        html.dark .bg-indigo-100 { background-color: rgba(99,102,241,0.30)  !important; }
        html.dark .bg-pink-100   { background-color: rgba(236,72,153,0.28)  !important; }
        html.dark .bg-emerald-100 { background-color: rgba(16,185,129,0.28) !important; }
        html.dark .bg-amber-100  { background-color: rgba(245,158,11,0.28)  !important; }
        html.dark .bg-slate-100  { background-color: #334155 !important; }

        /* Status badge text — keep colored */
        html.dark .text-green-700  { color: #6ee7b7 !important; }
        html.dark .text-red-700    { color: #fca5a5 !important; }
        html.dark .text-yellow-700 { color: #fde68a !important; }
        html.dark .text-orange-700 { color: #fdba74 !important; }
        html.dark .text-purple-700 { color: #c4b5fd !important; }
        html.dark .text-indigo-700 { color: #a5b4fc !important; }
        html.dark .text-pink-700   { color: #f9a8d4 !important; }
        html.dark .text-emerald-700 { color: #6ee7b7 !important; }
        html.dark .text-amber-700  { color: #fde68a !important; }
        html.dark .text-blue-700   { color: #93c5fd !important; }

        /* Solid colored badges — keep as is (white text on colored bg) */
        html.dark .bg-red-500,
        html.dark .bg-green-500,
        html.dark .bg-blue-500,
        html.dark .bg-orange-500,
        html.dark .bg-yellow-500,
        html.dark .bg-purple-500,
        html.dark .bg-indigo-500 { opacity: 0.9; }

        /* ── Main content area background ── */
        html.dark main { background-color: transparent !important; }
        html.dark .bg-gray-50.min-h-screen { background-color: transparent !important; }

        /* ── Hover states for nav items ── */
        html.dark .hover\:bg-gray-50:hover { background-color: rgba(255,255,255,0.06) !important; }
        html.dark .hover\:bg-gray-100:hover { background-color: #334155 !important; }
        html.dark .hover\:text-gray-900:hover { color: #f1f5f9 !important; }

        /* ── Ring white (avatar border) ── */
        html.dark .ring-2 { --tw-ring-opacity: 1; }
        html.dark .ring-white { --tw-ring-color: rgba(15,23,42,0.8) !important; }

        /* ── Top loading bar stays blue ── */
        html.dark #ag-topbar { opacity: 1; }

        /* ── bn-active item (bottom nav active) ── */
        html.dark .bn-item.bn-active { color: #60a5fa !important; }
        html.dark .bn-item.bn-active::after { background: #60a5fa !important; }

        /* ── Smooth color transitions ── */
        body, .sidebar-container, .app-top-header, .bg-white, .mobile-bottom-nav,
        input, textarea, select, table, thead, tbody, td, th {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease;
        }

        /* ══════════════════════════════════════════
           DARK MODE — Nuclear "No White Left" Patch
           Catches every possible white/light surface
        ══════════════════════════════════════════ */

        /* Body background */
        html.dark body { background-color: #0f172a !important; }

        /* Alpha/opacity bg-white variants */
        html.dark .bg-white\/95,
        html.dark .bg-white\/90,
        html.dark .bg-white\/80,
        html.dark .bg-white\/70,
        html.dark .bg-white\/60,
        html.dark .bg-white\/50 {
            background-color: rgba(15,23,42,0.92) !important;
        }

        /* Ring white (used on avatar, badge borders) */
        html.dark .ring-white { --tw-ring-color: #0f172a !important; }
        html.dark .ring-2.ring-white { --tw-ring-color: #1e293b !important; }

        /* Badge border over dark bg */
        html.dark .bn-badge { border-color: #0f172a !important; }

        /* Modals / overlays — be specific, avoid matching SVG/canvas inside charts */
        html.dark div[class*="bg-white"][class*="rounded"],
        html.dark section[class*="bg-white"][class*="rounded"],
        html.dark article[class*="bg-white"][class*="rounded"] {
            background-color: #1e293b !important;
        }

        /* Popup / modal dialog backgrounds */
        html.dark .fixed.inset-0 > div[class*="bg-white"],
        html.dark .z-50 > div[class*="bg-white"],
        html.dark .z-\[9999\] div[class*="bg-white"] {
            background-color: #1e293b !important;
        }

        /* ApexCharts dark mode — ONLY hide the white background rect, NOT the colored segments */
        html.dark .apexcharts-canvas .apexcharts-bg { fill: transparent !important; }
        /* Donut center label text — light for dark mode */
        html.dark .apexcharts-datalabels-group text { fill: #e2e8f0 !important; }
        html.dark .apexcharts-datalabels-group tspan { fill: #e2e8f0 !important; }
        html.dark .apexcharts-pie-label { fill: #f1f5f9 !important; }
        /* Donut stroke — use dark bg color instead of white */
        html.dark .apexcharts-pie path,
        html.dark .apexcharts-donut-slice-donut path { stroke: #1e293b !important; }

        /* <select> arrow & option list on webkit */
        html.dark select option {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }

        /* Flatpickr calendar (dark) */
        html.dark .flatpickr-calendar {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5) !important;
        }
        html.dark .flatpickr-day { color: #f1f5f9 !important; }
        html.dark .flatpickr-day:hover { background: #334155 !important; }
        html.dark .flatpickr-day.selected,
        html.dark .flatpickr-day.startRange,
        html.dark .flatpickr-day.endRange { background: #2563eb !important; border-color: #2563eb !important; }
        html.dark .flatpickr-day.flatpickr-disabled { color: #475569 !important; }
        html.dark .flatpickr-months,
        html.dark .flatpickr-weekdays { background: #0f172a !important; }
        html.dark .flatpickr-current-month,
        html.dark span.flatpickr-weekday { color: #94a3b8 !important; }
        html.dark .flatpickr-prev-month svg,
        html.dark .flatpickr-next-month svg { fill: #94a3b8 !important; }
        html.dark .flatpickr-prev-month:hover svg,
        html.dark .flatpickr-next-month:hover svg { fill: #f1f5f9 !important; }

        /* Scrollbar track */
        html.dark ::-webkit-scrollbar-track { background: #0f172a !important; }

        /* Empty state illustration containers */
        html.dark .bg-white.rounded-full { background-color: #334155 !important; }

        /* Prose / typography */
        html.dark .prose { color: #cbd5e1 !important; }
        html.dark .prose h1, html.dark .prose h2, html.dark .prose h3 { color: #f1f5f9 !important; }

        /* ApexCharts (dark) */
        html.dark .apexcharts-canvas .apexcharts-bg { fill: transparent !important; }
        html.dark .apexcharts-tooltip {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html.dark .apexcharts-tooltip-title { background: #0f172a !important; color: #94a3b8 !important; border-color: #334155 !important; }
        html.dark .apexcharts-xaxistooltip { background: #1e293b !important; border-color: #334155 !important; color: #f1f5f9 !important; }
        html.dark .apexcharts-legend-text { color: #94a3b8 !important; }
        html.dark .apexcharts-gridline { stroke: #334155 !important; }
        html.dark .apexcharts-text { fill: #94a3b8 !important; }

        :root {
            --app-header-height: 4rem;
            --app-content-top-gap: 0.1rem;
        }

        /* ── Content Layout ── */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
            padding-top: calc(var(--app-header-height) + var(--app-content-top-gap));
        }
        .app-top-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--app-header-height);
            z-index: 40;
            overflow: visible;
        }

        /* ── Modals, Drawers & Overlays Z-Index Fix (Selalu di atas .app-top-header z-40) ── */
        .z-\[9998\], .z-\[9999\], .z-\[10000\],
        .modal-overlay, .drawer-overlay, .ws-drawer-overlay, .ws-modal-overlay,
        [role="dialog"] {
            z-index: 99999 !important;
        }

        @media (min-width: 768px) {
            .main-content { margin-left: 16rem; }
            .app-top-header { left: 16rem; }
            #toast-container.admin-toast {
                left: calc(16rem + 1.25rem);
                right: 1.25rem;
                max-width: none !important;
                align-items: flex-end;
            }
        }
        @media (max-width: 767px) {
            .main-content { margin-left: 0; }
            .app-top-header {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            #toast-container.admin-toast {
                left: 0.75rem !important;
                right: 0.75rem !important;
                bottom: calc(4.5rem + env(safe-area-inset-bottom, 0px));
                max-width: none !important;
                align-items: stretch;
            }
            #toast-container.admin-toast .toast-item { max-width: 100%; }
            /* Push content above bottom nav */
            .main-content main {
                padding-bottom: calc(5rem + env(safe-area-inset-bottom, 0px)) !important;
            }
        }
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
        @media (max-width: 639px) { .form { padding: 0; } }

        /* ── Mobile Bottom Navigation Bar ── */
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
            border-radius: 0 0 3px 3px;
        }

        /* ══════════════════════════════════════════
           ARTILIA ANIMATION ENGINE
           Applies globally to every page
        ══════════════════════════════════════════ */

        /* 1 · Respect prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* 2 · Page enter transition */
        .main-content main {
            animation: ag-fadeUp 0.32s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes ag-fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }

        /* 3 · Top loading bar */
        #ag-topbar {
            position: fixed;
            top: 0; left: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #2563eb 0%, #7c3aed 60%, #06b6d4 100%);
            z-index: 99999;
            width: 0;
            opacity: 0;
            transition: width 0.25s ease, opacity 0.25s ease;
            border-radius: 0 2px 2px 0;
            box-shadow: 0 0 8px rgba(37, 99, 235, 0.5);
        }
        #ag-topbar.ag-loading { opacity: 1; }

        /* 4 · Scroll-reveal */
        .ag-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition:
                opacity  0.45s cubic-bezier(0.22, 1, 0.36, 1),
                transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .ag-reveal.ag-visible { opacity: 1; transform: translateY(0); }

        /* 5 · Table row stagger */
        @keyframes ag-rowIn {
            from { opacity: 0; transform: translateX(-6px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* 6 · Card hover lift */
        .ag-card-hover {
            transition: transform 0.2s cubic-bezier(0.22,1,0.36,1),
                        box-shadow 0.2s cubic-bezier(0.22,1,0.36,1) !important;
            will-change: transform;
        }
        .ag-card-hover:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 12px 28px -6px rgba(0,0,0,0.10), 0 4px 10px -4px rgba(0,0,0,0.06) !important;
        }

        /* 7 · Button / link press */
        .ag-btn-press { transition: transform 0.1s ease !important; }
        .ag-btn-press:active { transform: scale(0.96) !important; }

        /* 8 · Ripple effect on buttons */
        .ag-ripple-wrap { position: relative; overflow: hidden; }
        .ag-ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            background: rgba(255,255,255,0.35);
            animation: ag-rippleAnim 0.55s linear;
            pointer-events: none;
        }
        @keyframes ag-rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }

        /* 9 · Skeleton shimmer (utility) */
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

        /* 10 · Sidebar nav link underline sweep */
        .ag-nav-link { position: relative; }
        .ag-nav-link::after {
            content: '';
            position: absolute;
            left: 0; bottom: -1px;
            height: 2px; width: 0;
            background: #2563eb;
            border-radius: 1px;
            transition: width 0.25s ease;
        }
        .ag-nav-link:hover::after { width: 100%; }

        /* 11 · Bell icon badge — never clip */
        #admin-notif-bell { overflow: visible !important; }
    </style>
</head>

<body class="min-h-screen">

    <!-- Mobile Hamburger Menu (outside sidebar container) -->
    @include('admin.components.mobile-hamburger')

    <!-- Sidebar -->
    @include('admin.components.sidebar-new')

    <!-- Main Content -->
    <div class="main-content content-wrapper">
        @include('admin.components.header')
        <main class="px-3 pt-1 pb-6 sm:px-4 sm:pt-1 sm:pb-4 lg:px-6 lg:pt-1 lg:pb-6">
            @yield('content')
        </main>
    </div>

    {{-- ═══ Global Toast Notifications ═══ --}}
    @if(session('success') || session('error') || session('info') || session('warning'))
    <div id="toast-container"
         x-data="toastNotif()"
         x-init="init()"
         class="admin-toast fixed bottom-5 right-5 z-[9999] flex flex-col gap-2 pointer-events-none"
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
                 :style="`width: ${progress}%; transition-duration: ${duration}ms`"
                 x-ref="progressBar"></div>
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
        /* ── 1. Top navigation loading bar ── */
        var bar = document.createElement('div');
        bar.id = 'ag-topbar';
        document.body.prepend(bar);

        var barTimer, barW = 0;
        function barStart() {
            clearInterval(barTimer);
            barW = 0;
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

        /* ── 2. Scroll-reveal stagger for <main> top-level children ── */
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

        /* ── 3. Table row stagger ── */
        document.querySelectorAll('table tbody').forEach(function (tbody) {
            tbody.querySelectorAll('tr').forEach(function (tr, i) {
                tr.style.opacity = '0';
                tr.style.animation = 'ag-rowIn 0.3s cubic-bezier(0.22,1,0.36,1) ' +
                    Math.min(i * 0.045, 0.32) + 's both';
            });
        });

        /* ── 4. Card hover lift ── */
        document.querySelectorAll(
            '.bg-white.rounded-xl, .bg-white.rounded-2xl, .bg-white.rounded-lg, .chart-card'
        ).forEach(function (el) {
            if (!el.closest('table') && !el.closest('nav') && !el.closest('header')) el.classList.add('ag-card-hover');
        });

        /* ── 5. Button press + ripple ── */
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

        /* ── 6. Counter-up for stat numbers ── */
        document.querySelectorAll('[data-count]').forEach(function (el) {
            var target = parseInt(el.dataset.count, 10);
            if (isNaN(target)) return;
            var cur = 0, dur = 900, start = performance.now();
            function tick(now) {
                var pct = Math.min((now - start) / dur, 1);
                var ease = 1 - Math.pow(1 - pct, 3);
                cur = Math.round(ease * target);
                el.textContent = cur.toLocaleString('id-ID');
                if (pct < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
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

    {{-- ═══ Mobile Bottom Navigation Bar ═══ --}}
    @auth
    @php
        $bnRole   = Auth::user()->role->value ?? 'admin';
        $bnIsOp   = $bnRole === 'operator';
        $bnPrefix = $bnIsOp ? 'staff' : 'admin';
        $bnColor  = $bnIsOp ? '#059669' : '#2563eb';
        $bnBadge  = ($sidebarActiveBorrowingCount ?? 0) + ($sidebarPendingRequestCount ?? 0);
        $bnNotif  = $sidebarAdminUnreadCount ?? 0;
    @endphp
    <style>
        .bn-item.bn-active { color: {{ $bnColor }}; }
        .bn-item.bn-active::after { background: {{ $bnColor }}; }
    </style>
    <nav class="mobile-bottom-nav" aria-label="Mobile Navigation" x-data>

        {{-- Home --}}
        <a href="{{ route($bnPrefix . '.dashboard') }}"
           class="bn-item {{ Route::is($bnPrefix . '.dashboard') ? 'bn-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="bn-label">Home</span>
        </a>

        @if($bnIsOp)
        {{-- Operator: Maintenance --}}
        <a href="{{ route($bnPrefix . '.maintenance.index') }}"
           class="bn-item {{ Route::is($bnPrefix . '.maintenance.*') ? 'bn-active' : '' }}">
            @php $bnMaint = \App\Models\Maintenance::where('status','in_repair')->count(); @endphp
            @if($bnMaint > 0)<span class="bn-badge">{{ $bnMaint > 9 ? '9+' : $bnMaint }}</span>@endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="bn-label">Maintenance</span>
        </a>
        @else
        {{-- Admin: Inventory --}}
        <a href="{{ route($bnPrefix . '.inventory.index') }}"
           class="bn-item {{ Route::is($bnPrefix.'.inventory.*') || Route::is($bnPrefix.'.incoming.*') || Route::is($bnPrefix.'.outgoing.*') ? 'bn-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="bn-label">Inventory</span>
        </a>
        {{-- Admin: Borrowing --}}
        <a href="{{ route($bnPrefix . '.borrowing-requests.index') }}"
           class="bn-item {{ Route::is($bnPrefix.'.borrowings.*') || Route::is($bnPrefix.'.borrowing-requests.*') ? 'bn-active' : '' }}">
            @if($bnBadge > 0)<span class="bn-badge">{{ $bnBadge > 9 ? '9+' : $bnBadge }}</span>@endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <span class="bn-label">Borrowing</span>
        </a>
        @endif

        {{-- Notifications --}}
        <a href="{{ route($bnPrefix . '.notifications.index') }}"
           class="bn-item {{ Route::is($bnPrefix . '.notifications.*') ? 'bn-active' : '' }}">
            @if($bnNotif > 0)<span class="bn-badge">{{ $bnNotif > 9 ? '9+' : $bnNotif }}</span>@endif
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="bn-label">Notifikasi</span>
        </a>

        {{-- More (opens hamburger sidebar) --}}
        <button type="button" class="bn-item"
                @click="$dispatch('open-mobile-menu')" aria-label="More">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span class="bn-label">More</span>
        </button>

    </nav>
    @endauth
</body>
</html>