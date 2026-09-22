@php
    $sRole     = Auth::user()->role->value ?? 'user';
    $isOp      = $sRole === 'operator';
    $rp        = $routePrefix ?? ($isOp ? 'staff' : 'admin');

    $logoAccent = $isOp ? 'bg-emerald-600'                            : 'bg-blue-600';
    $navActive  = $isOp ? 'bg-emerald-50 text-emerald-700 font-semibold border-l-[3px] border-emerald-600' : 'bg-blue-50 text-blue-700 font-semibold border-l-[3px] border-blue-600';
    $navActiveM = $isOp ? 'bg-emerald-50 text-emerald-700 font-semibold border-l-[3px] border-emerald-600' : 'bg-blue-50 text-blue-700 font-semibold border-l-[3px] border-blue-600';
    $ic6        = $isOp ? 'text-emerald-600'                           : 'text-blue-600';
    $ic5        = $isOp ? 'text-emerald-500'                           : 'text-blue-500';
    $icRot      = $isOp ? 'rotate-90 text-emerald-600'                 : 'rotate-90 text-blue-600';

    // Submenu active child detection
    $isMasterChildActive = Route::is($rp . '.categories.*', $rp . '.suppliers.*', $rp . '.locations.*');
    $isInvChildActive    = Route::is(
        $rp . '.inventory.*',
        $rp . '.incoming.*',
        $rp . '.outgoing.*',
        $rp . '.maintenance.*',
        $rp . '.stock-opnames.*',
        $rp . '.calibration.*',
        $rp . '.logistics.*',
        $rp . '.tooling-kits.*',
        $rp . '.bap.*',
        $rp . '.borrowing-requests.bap',
        $rp . '.safety.*'
    );
    $isBorrowChildActive = Route::is($rp . '.borrowings.*') ||
                           (Route::is($rp . '.borrowing-requests.*') && !Route::is($rp . '.borrowing-requests.bap'));
    $isUsersChildActive  = Route::is($rp . '.content.*');
@endphp

<style>
    @media (min-width: 768px) { .mobile-hamburger-container { display: none !important; } }
    @media (max-width: 767px) { .mobile-hamburger-container { display: block !important; } }

    /* ══════════════════════════════════════════════════
       MOBILE SIDEBAR DARK MODE — COMPREHENSIVE
    ══════════════════════════════════════════════════ */

    /* ── Hamburger toggle button ── */
    html.dark .mobile-hamburger-container button.inline-flex {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
    html.dark .mobile-hamburger-container button.inline-flex:hover {
        background-color: #334155 !important;
        color: #e2e8f0 !important;
    }

    /* ── Slide-over panel ── */
    html.dark .mobile-hamburger-container .fixed.inset-y-0 {
        background-color: #111827 !important;
        border-color: #1f2937 !important;
        box-shadow: 4px 0 24px rgba(0,0,0,0.5) !important;
    }

    /* ── All borders ── */
    html.dark .mobile-hamburger-container .border-b,
    html.dark .mobile-hamburger-container .border-t,
    html.dark .mobile-hamburger-container .border-r { border-color: #1f2937 !important; }

    /* ── Text colors ── */
    html.dark .mobile-hamburger-container .text-gray-900 { color: #f1f5f9 !important; }
    html.dark .mobile-hamburger-container .text-gray-600 { color: #94a3b8 !important; }
    html.dark .mobile-hamburger-container .text-gray-500 { color: #cbd5e1 !important; }
    html.dark .mobile-hamburger-container .text-gray-400 { color: #64748b !important; }

    /* ── Section headers ── */
    html.dark .mobile-hamburger-container p.text-gray-400 { color: #475569 !important; }

    /* ── Nav links default ── */
    html.dark .mobile-hamburger-container a.text-gray-600,
    html.dark .mobile-hamburger-container button.text-gray-600 {
        color: #94a3b8 !important;
    }

    /* ── Nav links hover ── */
    html.dark .mobile-hamburger-container a:hover,
    html.dark .mobile-hamburger-container nav button:hover {
        background-color: rgba(255,255,255,0.06) !important;
        color: #e2e8f0 !important;
    }
    html.dark .mobile-hamburger-container .hover\:bg-gray-50:hover,
    html.dark .mobile-hamburger-container .hover\:bg-gray-100:hover {
        background-color: rgba(255,255,255,0.06) !important;
    }
    html.dark .mobile-hamburger-container .hover\:text-gray-900:hover { color: #e2e8f0 !important; }
    html.dark .mobile-hamburger-container .hover\:text-gray-600:hover { color: #94a3b8 !important; }

    /* ── Icons: default (gray-400) → visible in dark ── */
    html.dark .mobile-hamburger-container svg.text-gray-400 { color: #64748b !important; }
    html.dark .mobile-hamburger-container a:hover svg,
    html.dark .mobile-hamburger-container nav button:hover svg { color: #94a3b8 !important; }

    /* ── Sub-menu items (pl-7) ── */
    html.dark .mobile-hamburger-container .pl-7 a { color: #94a3b8 !important; }
    html.dark .mobile-hamburger-container .pl-7 a:hover {
        background-color: rgba(255,255,255,0.06) !important;
        color: #e2e8f0 !important;
    }

    /* ── Active indicator override in dark mode ── */
    html.dark .mobile-hamburger-container a[data-active="true"],
    html.dark .mobile-hamburger-container .pl-7 a[data-active="true"] {
        color: #93c5fd !important;
        background-color: rgba(37, 99, 235, 0.22) !important;
        font-weight: 600 !important;
        border-left: 3px solid #60a5fa !important;
    }
    html.dark .mobile-hamburger-container a[data-active="true"] svg {
        color: #60a5fa !important;
    }

    /* ── Close button ── */
    html.dark .mobile-hamburger-container button[\@click="mobileMenuOpen = false"].rounded-lg {
        color: #64748b !important;
    }

    /* ── Logout button ── */
    html.dark .mobile-hamburger-container button[type="submit"] {
        background-color: rgba(239,68,68,0.15) !important;
        color: #f87171 !important;
    }
    html.dark .mobile-hamburger-container button[type="submit"]:hover {
        background-color: rgba(239,68,68,0.25) !important;
    }

    /* ── Nav scrollbar ── */
    html.dark .mobile-hamburger-container nav::-webkit-scrollbar { width: 4px; }
    html.dark .mobile-hamburger-container nav::-webkit-scrollbar-track { background: transparent; }
    html.dark .mobile-hamburger-container nav::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 4px;
    }
</style>

<div class="mobile-hamburger-container" x-data="{ mobileMenuOpen: false }" @open-mobile-menu.window="mobileMenuOpen = true">

    {{-- Backdrop --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/75 backdrop-blur-md"
         @click="mobileMenuOpen = false"
         style="display:none;"></div>

    {{-- Slide-over Sidebar --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-250 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white border-r border-gray-100 flex flex-col shadow-2xl"
         x-data="sidebarData()" x-init="init()"
         data-role="{{ $sRole }}"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between h-16 px-5 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="flex items-center justify-center w-9 h-9 {{ $logoAccent }} rounded-xl flex-shrink-0 overflow-hidden shadow-sm">
                    @if(!empty($companyLogo ?? null))
                        <img src="{{ asset($companyLogo) }}" alt="Logo" class="h-9 w-9 object-contain">
                    @else
                        <img src="/inc.png" alt="Logo" class="h-5 w-auto brightness-0 invert" onerror="this.style.display='none'">
                    @endif
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="text-[14px] font-extrabold text-gray-900 tracking-tight leading-tight">{{ $companyName ?? 'Artilia' }}</span>
                    <span class="text-[10px] text-gray-400 font-medium leading-tight">{{ $isOp ? 'Operator' : 'Admin Panel' }}</span>
                </div>
            </div>
            <button @click="mobileMenuOpen = false" class="flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex-shrink-0" aria-label="Tutup navigasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto flex flex-col gap-1">

        @if($isOp)
        {{-- ════════════ OPERATOR MOBILE NAV ════════════ --}}

            <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Main</p>

            @php $isAct = Route::is($rp . '.dashboard'); @endphp
            <a href="{{ route($rp . '.dashboard') }}" @click="mobileMenuOpen = false"
               @if($isAct) data-active="true" @endif
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                      {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </div>
                @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
            </a>

            @php $isAct = Route::is('scanner.*'); @endphp
            <a href="{{ route('scanner.index') }}" @click="mobileMenuOpen = false"
               @if($isAct) data-active="true" @endif
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                      {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span>Quick Scan</span>
                </div>
                @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
            </a>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Maintenance</p>

                @php $isAct = Route::is($rp . '.maintenance.*'); @endphp
                <a href="{{ route($rp . '.maintenance.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Daftar Maintenance</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @php $mCount = \App\Models\Maintenance::where('status','in_repair')->count(); @endphp
                        @if($mCount > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full animate-pulse">{{ $mCount }}</span>
                        @endif
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>
            </div>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Akun</p>

                @php $isAct = Route::is($rp . '.profile.*'); @endphp
                <a href="{{ route($rp . '.profile.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is($rp . '.notifications.*'); @endphp
                <a href="{{ route($rp . '.notifications.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifikasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @if(($sidebarAdminUnreadCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $sidebarAdminUnreadCount }}</span>
                        @endif
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>
            </div>

        @else
        {{-- ════════════ ADMIN MOBILE NAV ════════════ --}}

            <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Main</p>

            @php $isAct = Route::is($rp . '.dashboard'); @endphp
            <a href="{{ route($rp . '.dashboard') }}" @click="mobileMenuOpen = false"
               @if($isAct) data-active="true" @endif
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                      {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </div>
                @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
            </a>

            @php $isAct = Route::is('scanner.*'); @endphp
            <a href="{{ route('scanner.index') }}" @click="mobileMenuOpen = false"
               @if($isAct) data-active="true" @endif
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                      {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span>Quick Scan</span>
                </div>
                @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
            </a>

            {{-- Master Data (Admin only) --}}
            @if($sRole === 'admin')
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Master Data</p>

                <button @click="masterDataOpen = !masterDataOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                               {{ $isMasterChildActive ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        :class="masterDataOpen ? 'bg-blue-50/70 text-blue-700' : ''">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isMasterChildActive ? $ic6 : 'text-gray-400' }}" :class="masterDataOpen ? '{{ $ic6 }}' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14"/>
                        </svg>
                        <span>Master Data</span>
                        @if($isMasterChildActive)
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300">Aktif</span>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="masterDataOpen ? '{{ $icRot }}' : '{{ $isMasterChildActive ? $ic6 : 'text-gray-400' }}'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="masterDataOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    @php $isAct = Route::is($rp . '.categories.*'); @endphp
                    <a href="{{ route($rp . '.categories.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Categories</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.suppliers.*'); @endphp
                    <a href="{{ route($rp . '.suppliers.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Suppliers</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.locations.*'); @endphp
                    <a href="{{ route($rp . '.locations.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Lokasi Barang</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>
                </div>
            </div>
            @endif

            {{-- Inventory --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Inventory</p>

                <button @click="inventoryOpen = !inventoryOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                               {{ $isInvChildActive ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        :class="inventoryOpen ? 'bg-blue-50/70 text-blue-700' : ''">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isInvChildActive ? $ic6 : 'text-gray-400' }}" :class="inventoryOpen ? '{{ $ic6 }}' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Inventory</span>
                        @if($isInvChildActive)
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300">Aktif</span>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="inventoryOpen ? '{{ $icRot }}' : '{{ $isInvChildActive ? $ic6 : 'text-gray-400' }}'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="inventoryOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    @php $isAct = Route::is($rp . '.inventory.*'); @endphp
                    <a href="{{ route($rp . '.inventory.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Inventory Overview</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.incoming.*'); @endphp
                    <a href="{{ route($rp . '.incoming.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Incoming Items</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.outgoing.*'); @endphp
                    <a href="{{ route($rp . '.outgoing.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                            </svg>
                            <span>Outgoing Items</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.maintenance.*'); @endphp
                    <a href="{{ route($rp . '.maintenance.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Maintenance</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @php $inRepairCount = \App\Models\Maintenance::where('status', 'in_repair')->count(); @endphp
                            @if($inRepairCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full">{{ $inRepairCount }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>

                    @php $isAct = Route::is($rp . '.stock-opnames.*'); @endphp
                    <a href="{{ route($rp . '.stock-opnames.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Stock Opname</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.calibration.*'); @endphp
                    <a href="{{ route($rp . '.calibration.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                            <span>Kalibrasi & Tooling</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @php 
                                $expiredCalibCount = \App\Models\Item::where('tool_type', 'measuring_tool')->where('calibration_status', 'expired')->count();
                            @endphp
                            @if($expiredCalibCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse">{{ $expiredCalibCount }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>

                    @php $isAct = Route::is($rp . '.logistics.*'); @endphp
                    <a href="{{ route($rp . '.logistics.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <span>Smart Logistics (EOQ/ROP)</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    {{-- Tooling Kit --}}
                    @php $isAct = Route::is($rp . '.tooling-kits.*'); @endphp
                    <a href="{{ route($rp . '.tooling-kits.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Tooling Kit SPK</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    {{-- BAP Digital Signature --}}
                    @php $isAct = Route::is($rp . '.bap.*') || Route::is($rp . '.borrowing-requests.bap'); @endphp
                    <a href="{{ route($rp . '.bap.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            <span>BAP Digital Signature</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @php
                                $unsignedBapCount = \App\Models\BorrowingRequest::whereIn('status', ['approved','completed'])->whereNull('signed_at')->count();
                            @endphp
                            @if($unsignedBapCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-purple-500 rounded-full flex-shrink-0">{{ $unsignedBapCount }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>

                    {{-- K3 Safety & APD Induction (PIMNAS Feature) --}}
                    @php $isAct = Route::is($rp . '.safety.*'); @endphp
                    <a href="{{ route($rp . '.safety.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>K3 & Keselamatan Lab</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @php
                                $pendingK3Clearance = \App\Models\BorrowingRequest::whereHas('item', fn($q) => $q->whereIn('safety_risk_level', ['medium', 'high']))
                                    ->whereIn('status', ['pending', 'approved'])
                                    ->whereNull('safety_verified_at')
                                    ->count();
                            @endphp
                            @if($pendingK3Clearance > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-amber-500 rounded-full flex-shrink-0">{{ $pendingK3Clearance }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>
                </div>
            </div>

            {{-- Borrowing --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Borrowing</p>

                <button @click="borrowingOpen = !borrowingOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                               {{ $isBorrowChildActive ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        :class="borrowingOpen ? 'bg-blue-50/70 text-blue-700' : ''">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isBorrowChildActive ? $ic6 : 'text-gray-400' }}" :class="borrowingOpen ? '{{ $ic6 }}' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Borrowing</span>
                        @if($isBorrowChildActive)
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300">Aktif</span>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="borrowingOpen ? '{{ $icRot }}' : '{{ $isBorrowChildActive ? $ic6 : 'text-gray-400' }}'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="borrowingOpen" x-transition class="mt-1 space-y-0.5 pl-7">
                    @php $isAct = Route::is($rp . '.borrowings.*') && !Route::is($rp . '.borrowings.history'); @endphp
                    <a href="{{ route($rp . '.borrowings.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Item Borrowing</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if(($sidebarActiveBorrowingCount ?? 0) > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-yellow-500 rounded-full flex-shrink-0">{{ $sidebarActiveBorrowingCount }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>

                    @php $isAct = Route::is($rp . '.borrowing-requests.*') && !Route::is($rp . '.borrowing-requests.history') && !Route::is($rp . '.borrowing-requests.bap'); @endphp
                    <a href="{{ route($rp . '.borrowing-requests.index') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Borrowing Approval</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if(($sidebarPendingRequestCount ?? 0) > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0 animate-pulse">{{ $sidebarPendingRequestCount }}</span>
                            @endif
                            @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                        </div>
                    </a>

                    @php $isAct = Route::is($rp . '.borrowing-requests.history'); @endphp
                    <a href="{{ route($rp . '.borrowing-requests.history') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Approval History</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.borrowings.history'); @endphp
                    <a href="{{ route($rp . '.borrowings.history') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Borrowing History</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>
                </div>
            </div>

            {{-- Pengadaan --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Pengadaan</p>

                @php $isAct = Route::is($rp . '.procurement-requests.*'); @endphp
                <a href="{{ route($rp . '.procurement-requests.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                        </svg>
                        <span>Permintaan Pengadaan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @php $procurementPending = \App\Models\ProcurementRequest::where('status', 'pending')->count(); @endphp
                        @if($procurementPending > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full flex-shrink-0 animate-pulse">{{ $procurementPending }}</span>
                        @endif
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>
            </div>

            {{-- Management (Admin only) --}}
            @if($sRole === 'admin')
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Management</p>

                <button @click="usersOpen = !usersOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                               {{ $isUsersChildActive ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300 font-semibold' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                        :class="usersOpen ? 'bg-blue-50/70 text-blue-700' : ''">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isUsersChildActive ? $ic6 : 'text-gray-400' }}" :class="usersOpen ? '{{ $ic6 }}' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span>Users</span>
                        @if($isUsersChildActive)
                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300">Aktif</span>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="usersOpen ? '{{ $icRot }}' : '{{ $isUsersChildActive ? $ic6 : 'text-gray-400' }}'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="usersOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    @php 
                        $isAct = Route::is($rp . '.content.listusers') || 
                                 Route::is($rp . '.content.showusers*') || 
                                 Route::is($rp . '.content.editusers*') || 
                                 Route::is($rp . '.content.updateusers*') ||
                                 Route::is($rp . '.content.deleteusers') ||
                                 Route::is($rp . '.content.bulkdeleteusers') ||
                                 Route::is($rp . '.content.togglestatususers');
                    @endphp
                    <a href="{{ route($rp . '.content.listusers') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Manage Users</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>

                    @php $isAct = Route::is($rp . '.content.createusers') || Route::is($rp . '.content.savedatausers'); @endphp
                    <a href="{{ route($rp . '.content.createusers') }}" @click="mobileMenuOpen = false"
                       @if($isAct) data-active="true" @endif
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all border-l-[3px]
                              {{ $isAct ? $navActiveM : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ $isAct ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span>Add New User</span>
                        </div>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </a>
                </div>
            </div>
            @endif

            {{-- Digital Twin & AI Lab Tools --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Digital Twin & AI Tools</p>

                @php $isAct = Route::is($rp . '.workshop.*'); @endphp
                <a href="{{ route($rp . '.workshop.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        <span>Denah Bengkel 2D</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300 tracking-wide">LIVE</span>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>

                @php $isAct = Route::is($rp . '.defect-scanner.*'); @endphp
                <a href="{{ route($rp . '.defect-scanner.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.867V15.13a1 1 0 01-1.447.898L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>AI Defect Scanner</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase bg-violet-100 text-violet-700 dark:bg-violet-900/60 dark:text-violet-300 tracking-wide">CV</span>
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>
            </div>

            {{-- System --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">System</p>

                @php $isAct = Route::is($rp . '.reports.*'); @endphp
                <a href="{{ route($rp . '.reports.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Reports</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is($rp . '.insights.*'); @endphp
                <a href="{{ route($rp . '.insights.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a1 1 0 112 0v8.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L11 11.586V3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4z"/>
                        </svg>
                        <span>Insights</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is($rp . '.activities.*'); @endphp
                <a href="{{ route($rp . '.activities.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Recent Activities</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is($rp . '.profile.*'); @endphp
                <a href="{{ route($rp . '.profile.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profile</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is($rp . '.notifications.*'); @endphp
                <a href="{{ route($rp . '.notifications.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifikasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @if(($sidebarAdminUnreadCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarAdminUnreadCount }}</span>
                        @endif
                        @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                    </div>
                </a>

                @if($sRole === 'admin')
                @php $isAct = Route::is($rp . '.settings.*'); @endphp
                <a href="{{ route($rp . '.settings.index') }}" @click="mobileMenuOpen = false"
                   @if($isAct) data-active="true" @endif
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all border-l-[3px]
                          {{ $isAct ? $navActive : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Settings</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full {{ $ic6 }} flex-shrink-0 animate-pulse"></span>@endif
                </a>
                @endif
            </div>

        @endif
        </nav>

        {{-- User Info & Sign Out --}}
        <div class="flex-shrink-0 border-t border-gray-100 p-3 flex flex-col gap-3">
            <div class="flex items-center gap-3 px-2">
                <img src="{{ Auth::user()->profil ? asset(Auth::user()->profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=4F76F6&background=EEF2FF&size=40' }}"
                    alt="{{ Auth::user()->name }}"
                    class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100 dark:ring-slate-800"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=4F76F6&background=EEF2FF&size=40'" />
                <div class="flex flex-col min-w-0">
                    <span class="text-[13px] font-bold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" @click="mobileMenuOpen = false"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 active:bg-red-200 transition-colors duration-150 group">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" class="group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>
