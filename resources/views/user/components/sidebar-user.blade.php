<div class="hidden md:flex md:flex-shrink-0 md:w-64" x-data="userSidebarData()" x-init="init()">
    <div class="sidebar-container w-64 bg-white dark:bg-slate-900 fixed top-0 left-0 z-50 border-r border-gray-100 dark:border-slate-800 flex flex-col h-full shadow-sm">

        <div class="flex items-center h-16 px-5 border-b border-gray-100 dark:border-slate-800 flex-shrink-0">
            <div class="flex items-center gap-3 w-full">
                <div class="flex items-center justify-center w-9 h-9 bg-blue-600 rounded-xl flex-shrink-0 overflow-hidden shadow-sm">
                    @if(!empty($companyLogo))
                        <img src="{{ asset($companyLogo) }}" alt="Logo" class="h-9 w-9 object-contain">
                    @else
                        <img src="/inc.png" alt="Logo" class="h-5 w-auto brightness-0 invert" onerror="this.style.display='none'">
                    @endif
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[14px] font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">{{ $companyName ?? 'Artilia' }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-slate-400 font-medium leading-tight">Portal User</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-navigation flex-1 px-3 py-4 overflow-y-auto flex flex-col gap-1">

            <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Menu Utama</p>

            @php $isAct = Route::is('user.dashboard'); @endphp
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                      {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </div>
                @if($isAct)
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>
                @endif
            </a>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Peminjaman Alat</p>

                @php $isAct = Route::is('user.workshop.*'); @endphp
                <a href="{{ route('user.workshop.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        <span>Denah Bengkel</span>
                    </div>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">2D</span>
                </a>

                @php $isAct = Route::is('user.borrowing.index') || Route::is('user.borrowing.create') || Route::is('user.borrowing.show-item'); @endphp
                <a href="{{ route('user.borrowing.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Katalog Alat</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is('user.borrowing.cart'); @endphp
                <a href="{{ route('user.borrowing.cart') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Keranjang Pinjam</span>
                    </div>
                    @if(($sidebarCartCount ?? 0) > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarCartCount }}</span>
                    @elseif($isAct)
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>
                    @endif
                </a>

                @php $isAct = Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show'); @endphp
                <a href="{{ route('user.borrowing.my-requests') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Status Pinjaman</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is('user.borrowing.history'); @endphp
                <a href="{{ route('user.borrowing.history') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Riwayat Pinjam</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>@endif
                </a>

            </div>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Pengadaan Barang</p>

                @php $isAct = Route::is('user.procurement.*'); @endphp
                <a href="{{ route('user.procurement.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-orange-50 dark:bg-orange-950/30 text-orange-700 dark:text-orange-300 font-semibold border-orange-500' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-orange-600 dark:text-orange-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                        </svg>
                        <span>Request Pengadaan</span>
                    </div>
                    @php $myPendingProcurement = \App\Models\ProcurementRequest::where('user_id', auth()->id())->where('status', 'pending')->count(); @endphp
                    @if($myPendingProcurement > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full flex-shrink-0">{{ $myPendingProcurement }}</span>
                    @elseif($isAct)
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 flex-shrink-0 animate-pulse"></span>
                    @endif
                </a>
            </div>

            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Akun &amp; Sistem</p>

                @php $isAct = Route::is('user.profile.*'); @endphp
                <a href="{{ route('user.profile.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Profil Saya</span>
                    </div>
                    @if($isAct)<span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>@endif
                </a>

                @php $isAct = Route::is('user.notifications.*'); @endphp
                <a href="{{ route('user.notifications.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150 border-l-[3px]
                          {{ $isAct ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border-blue-600' : 'border-transparent text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ $isAct ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Pemberitahuan</span>
                    </div>
                    @if(($sidebarUserUnreadCount ?? 0) > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarUserUnreadCount }}</span>
                    @elseif($isAct)
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0 animate-pulse"></span>
                    @endif
                </a>
            </div>
        </nav>

        <div class="flex-shrink-0 border-t border-gray-100 dark:border-slate-800 p-3.5 flex flex-col gap-3 bg-gray-50/50 dark:bg-slate-900/50">
            <div class="flex items-center gap-3 px-1">
                <img src="{{ Auth::user()->profil ? asset(Auth::user()->profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=4F76F6&background=EEF2FF&size=40' }}"
                    alt="{{ Auth::user()->name }}"
                    class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900 shadow-sm"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=4F76F6&background=EEF2FF&size=40'" />
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</span>
                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">User</span>
                    </div>
                    <span class="text-[10px] text-gray-400 dark:text-slate-400 truncate">{{ Auth::user()->email }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full px-3.5 py-2 text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-xl transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </div>
</div>
