<style>
    @media (min-width: 768px) { .user-mobile-hamburger { display: none !important; } }
    @media (max-width: 767px) { .user-mobile-hamburger { display: block !important; } }

    /* ── User Mobile Hamburger Button (dark) ── */
    html.dark .user-mobile-hamburger button.inline-flex {
        background-color: rgba(15,23,42,0.85) !important;
        border-color: rgba(255,255,255,0.10) !important;
        color: #94a3b8 !important;
    }

    /* ── User Mobile Slide-over Sidebar (dark) ── */
    html.dark .user-mobile-hamburger .fixed.inset-y-0 {
        background-color: rgba(15,23,42,0.95) !important;
        border-color: rgba(255,255,255,0.06) !important;
        backdrop-filter: blur(32px) !important;
    }
    html.dark .user-mobile-hamburger .border-b { border-color: rgba(255,255,255,0.06) !important; }
    html.dark .user-mobile-hamburger .border-t { border-color: rgba(255,255,255,0.06) !important; }
    html.dark .user-mobile-hamburger .text-gray-900 { color: #f1f5f9 !important; }
    html.dark .user-mobile-hamburger .text-gray-600 { color: #94a3b8 !important; }
    html.dark .user-mobile-hamburger .text-gray-500 { color: #cbd5e1 !important; }
    html.dark .user-mobile-hamburger .text-gray-400 { color: #64748b !important; }
    html.dark .user-mobile-hamburger .hover\:bg-gray-50:hover,
    html.dark .user-mobile-hamburger .hover\:bg-gray-100:hover { background-color: rgba(255,255,255,0.06) !important; }
    html.dark .user-mobile-hamburger .hover\:text-gray-900:hover { color: #f1f5f9 !important; }
</style>


<div class="user-mobile-hamburger fixed top-3.5 left-4 z-[60]"
     x-data="{ open: false }"
     @open-mobile-menu.window="open = true">

    {{-- Hamburger Button --}}
    <button @click="open = !open"
            class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 bg-white shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{ 'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{ 'block': open, 'hidden': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/75 backdrop-blur-md"
         @click="open = false"
         style="display:none;"></div>

    {{-- Slide-over Sidebar --}}
    <div x-show="open"
         x-transition:enter="transition ease-in-out duration-250 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 flex flex-col shadow-xl"
         x-data="userSidebarData()" x-init="init()"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between h-16 px-5 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-lg flex-shrink-0 overflow-hidden">
                    @if(!empty($companyLogo ?? null))
                        <img src="{{ asset($companyLogo) }}" alt="Logo" class="h-8 w-8 object-contain">
                    @else
                        <img src="/inc.png" alt="Logo" class="h-6 w-auto brightness-0 invert" onerror="this.style.display='none'">
                    @endif
                </div>
                <span class="text-[15px] font-bold text-gray-900 tracking-tight">{{ $companyName ?? 'Artilia' }}</span>
            </div>
            <button @click="open = false" class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto flex flex-col gap-1">

            {{-- Main --}}
            <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Main</p>

            <a href="{{ route('user.dashboard') }}" @click="open = false"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                      {{ Route::is('user.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('user.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Borrowing --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Borrowing</p>

                <button @click="borrowingOpen = !borrowingOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all"
                        :class="borrowingOpen ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" :class="borrowingOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Borrowing</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" class="transform transition-transform duration-200 flex-shrink-0"
                         :class="borrowingOpen ? 'rotate-90 text-blue-600' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="borrowingOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route('user.borrowing.index') }}" @click="open = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Item Borrowing
                    </a>

                    <a href="{{ route('user.borrowing.cart') }}" @click="open = false"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is('user.borrowing.cart') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.cart') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Cart
                        </div>
                        @if(($sidebarCartCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarCartCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('user.borrowing.my-requests') }}" @click="open = false"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all
                              {{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        My Requests
                    </a>
                </div>
            </div>

            {{-- Pengadaan --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Pengadaan</p>

                <a href="{{ route('user.procurement.index') }}" @click="open = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is('user.procurement.*') ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('user.procurement.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                        </svg>
                        <span>Permintaan Pengadaan</span>
                    </div>
                    @php $mobilePending = \App\Models\ProcurementRequest::where('user_id', auth()->id())->where('status', 'pending')->count(); @endphp
                    @if($mobilePending > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full flex-shrink-0">{{ $mobilePending }}</span>
                    @endif
                </a>
            </div>

            {{-- Account --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Account</p>

                <a href="{{ route('user.profile.index') }}" @click="open = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is('user.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('user.profile.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    My Profile
                </a>

                <a href="{{ route('user.notifications.index') }}" @click="open = false"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all
                          {{ Route::is('user.notifications.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('user.notifications.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifikasi
                    </div>
                    @if(($sidebarUserUnreadCount ?? 0) > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarUserUnreadCount }}</span>
                    @endif
                </a>
            </div>
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
                <button type="submit" @click="open = false"
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
