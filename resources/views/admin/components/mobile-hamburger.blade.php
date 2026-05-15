@php
    $sRole     = Auth::user()->role->value ?? 'user';
    $isOp      = $sRole === 'operator';

    $logoAccent = $isOp ? 'bg-emerald-600'                            : 'bg-blue-600';
    $navActive  = $isOp ? 'bg-emerald-50 text-emerald-700'             : 'bg-blue-50 text-blue-700';
    $navActiveM = $isOp ? 'bg-emerald-50 text-emerald-700 font-medium' : 'bg-blue-50 text-blue-700 font-medium';
    $ic6        = $isOp ? 'text-emerald-600'                           : 'text-blue-600';
    $ic5        = $isOp ? 'text-emerald-500'                           : 'text-blue-500';
    $icRot      = $isOp ? 'rotate-90 text-emerald-600'                 : 'rotate-90 text-blue-600';
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

    /* ── Close button ── */
    html.dark .mobile-hamburger-container button[\\@click="mobileMenuOpen = false"].rounded-lg {
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

<div class="mobile-hamburger-container fixed top-3.5 left-4 z-[60]"
     x-data="{ mobileMenuOpen: false }"
     @open-mobile-menu.window="mobileMenuOpen = true">

    {{-- Hamburger Button --}}
    <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 bg-white shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

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
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col shadow-2xl"
         x-data="sidebarData()" x-init="init()"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between h-16 px-5 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 {{ $logoAccent }} rounded-lg flex-shrink-0 overflow-hidden">
                    @if(!empty($companyLogo ?? null))
                        <img src="{{ asset($companyLogo) }}" alt="Logo" class="h-8 w-8 object-contain">
                    @else
                        <img src="/inc.png" alt="Logo" class="h-6 w-auto brightness-0 invert" onerror="this.style.display='none'">
                    @endif
                </div>
                <span class="text-[15px] font-bold text-gray-900 tracking-tight">{{ $companyName ?? 'Artilia' }}</span>
            </div>
            <button @click="mobileMenuOpen = false" class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors flex-shrink-0">
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

            <a href="{{ route($routePrefix . '.dashboard') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                      {{ Route::is($routePrefix . '.dashboard') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.dashboard') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('scanner.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                      {{ Route::is('scanner.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('scanner.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Quick Scan
            </a>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Maintenance</p>

                <a href="{{ route($routePrefix . '.maintenance.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.maintenance.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.maintenance.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Daftar Maintenance
                    </div>
                    @php $mCount = \App\Models\Maintenance::where('status','in_repair')->count(); @endphp
                    @if($mCount > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full animate-pulse">{{ $mCount }}</span>
                    @endif
                </a>
            </div>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Akun</p>

                <a href="{{ route($routePrefix . '.profile.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.profile.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.profile.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                <a href="{{ route($routePrefix . '.notifications.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.notifications.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.notifications.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifikasi
                    </div>
                    @if(($sidebarAdminUnreadCount ?? 0) > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $sidebarAdminUnreadCount }}</span>
                    @endif
                </a>
            </div>

        @else
        {{-- ════════════ ADMIN MOBILE NAV ════════════ --}}

            <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Main</p>

            <a href="{{ route($routePrefix . '.dashboard') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                      {{ Route::is($routePrefix . '.dashboard') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.dashboard') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('scanner.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                      {{ Route::is('scanner.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('scanner.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Quick Scan
            </a>

            {{-- Master Data (Admin only) --}}
            @if($sRole === 'admin')
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Master Data</p>

                <button @click="masterDataOpen = !masterDataOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all"
                        :class="masterDataOpen ? '{{ $navActive }}' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" :class="masterDataOpen ? '{{ $ic6 }}' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14"/>
                        </svg>
                        <span>Master Data</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="masterDataOpen ? '{{ $icRot }}' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="masterDataOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route($routePrefix . '.categories.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.categories.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.categories.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Categories
                    </a>
                    <a href="{{ route($routePrefix . '.suppliers.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.suppliers.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.suppliers.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Suppliers
                    </a>
                    <a href="{{ route($routePrefix . '.locations.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.locations.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.locations.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lokasi Barang
                    </a>
                </div>
            </div>
            @endif

            {{-- Inventory --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Inventory</p>

                <button @click="inventoryOpen = !inventoryOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all"
                        :class="inventoryOpen ? '{{ $navActive }}' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" :class="inventoryOpen ? '{{ $ic6 }}' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Inventory</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="inventoryOpen ? '{{ $icRot }}' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="inventoryOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route($routePrefix . '.inventory.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.inventory.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.inventory.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Inventory Overview
                    </a>
                    <a href="{{ route($routePrefix . '.incoming.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.incoming.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.incoming.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Incoming Items
                    </a>
                    <a href="{{ route($routePrefix . '.outgoing.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.outgoing.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.outgoing.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                        </svg>
                        Outgoing Items
                    </a>
                    <a href="{{ route($routePrefix . '.maintenance.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.maintenance.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.maintenance.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Maintenance
                        </div>
                        @php $inRepairCount = \App\Models\Maintenance::where('status', 'in_repair')->count(); @endphp
                        @if($inRepairCount > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full">{{ $inRepairCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route($routePrefix . '.stock-opnames.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.stock-opnames.*') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.stock-opnames.*') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Stock Opname
                    </a>
                </div>
            </div>

            {{-- Borrowing --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Borrowing</p>

                <button @click="borrowingOpen = !borrowingOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all"
                        :class="borrowingOpen ? '{{ $navActive }}' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" :class="borrowingOpen ? '{{ $ic6 }}' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Borrowing</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="borrowingOpen ? '{{ $icRot }}' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="borrowingOpen" x-transition class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route($routePrefix . '.borrowings.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.borrowings.index') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.borrowings.index') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Item Borrowing
                        </div>
                        @if(($sidebarActiveBorrowingCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-yellow-500 rounded-full flex-shrink-0">{{ $sidebarActiveBorrowingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route($routePrefix . '.borrowing-requests.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.borrowing-requests.*') && !Route::is($routePrefix . '.borrowing-requests.history') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.borrowing-requests.*') && !Route::is($routePrefix . '.borrowing-requests.history') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Borrowing Approval
                        </div>
                        @if(($sidebarPendingRequestCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0 animate-pulse">{{ $sidebarPendingRequestCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route($routePrefix . '.borrowing-requests.history') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.borrowing-requests.history') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.borrowing-requests.history') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approval History
                    </a>
                    <a href="{{ route($routePrefix . '.borrowings.history') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.borrowings.history') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.borrowings.history') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Borrowing History
                    </a>
                </div>
            </div>

            {{-- Pengadaan --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Pengadaan</p>

                <a href="{{ route($routePrefix . '.procurement-requests.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.procurement-requests.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.procurement-requests.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                        </svg>
                        <span>Permintaan Pengadaan</span>
                    </div>
                    @php $procurementPending = \App\Models\ProcurementRequest::where('status', 'pending')->count(); @endphp
                    @if($procurementPending > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full flex-shrink-0 animate-pulse">{{ $procurementPending }}</span>
                    @endif
                </a>
            </div>

            {{-- Management (Admin only) --}}
            @if($sRole === 'admin')
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Management</p>

                <button @click="usersOpen = !usersOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all"
                        :class="usersOpen ? '{{ $navActive }}' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" :class="usersOpen ? '{{ $ic6 }}' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span>Users</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="usersOpen ? '{{ $icRot }}' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="usersOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route($routePrefix . '.content.listusers') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.content.listusers') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.content.listusers') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Manage Users
                    </a>
                    <a href="{{ route($routePrefix . '.content.createusers') }}" @click="mobileMenuOpen = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is($routePrefix . '.content.createusers') ? $navActiveM : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is($routePrefix . '.content.createusers') ? $ic5 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add New User
                    </a>
                </div>
            </div>
            @endif

            {{-- System --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">System</p>

                <a href="{{ route($routePrefix . '.reports.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.reports.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.reports.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Reports
                </a>

                <a href="{{ route($routePrefix . '.insights.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.insights.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.insights.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a1 1 0 112 0v8.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L11 11.586V3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Insights
                </a>

                <a href="{{ route($routePrefix . '.activities.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.activities.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.activities.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Recent Activities
                </a>

                <a href="{{ route($routePrefix . '.profile.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.profile.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.profile.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                <a href="{{ route($routePrefix . '.notifications.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.notifications.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.notifications.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifikasi
                    </div>
                    @if(($sidebarAdminUnreadCount ?? 0) > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarAdminUnreadCount }}</span>
                    @endif
                </a>

                @if($sRole === 'admin')
                <a href="{{ route($routePrefix . '.settings.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is($routePrefix . '.settings.*') ? $navActive : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is($routePrefix . '.settings.*') ? $ic6 : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
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
