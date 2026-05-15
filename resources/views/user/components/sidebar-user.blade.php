<div class="hidden md:flex md:flex-shrink-0 md:w-64" x-data="userSidebarData()" x-init="init()">
    <div class="sidebar-container w-64 bg-white fixed top-0 left-0 z-50 border-r border-gray-100 flex flex-col h-full shadow-sm">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-5 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-lg flex-shrink-0 overflow-hidden">
                    @if(!empty($companyLogo))
                        <img src="{{ asset($companyLogo) }}" alt="Logo" class="h-8 w-8 object-contain">
                    @else
                        <img src="/inc.png" alt="Logo" class="h-6 w-auto brightness-0 invert" onerror="this.style.display='none'">
                    @endif
                </div>
                <span class="text-[15px] font-bold text-gray-900 tracking-tight">{{ $companyName ?? 'Artilia' }}</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-navigation flex-1 px-3 py-4 overflow-y-auto space-y-0.5">

            {{-- Main --}}
            <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Main</p>

            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150
                      {{ Route::is('user.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-4.5 h-4.5 flex-shrink-0 {{ Route::is('user.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Borrowing Section --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Borrowing</p>

                <button @click="borrowingOpen = !borrowingOpen"
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150"
                        :class="borrowingOpen ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;" :class="borrowingOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Borrowing</span>
                    </div>
                    <svg style="width:14px;height:14px;" class="transform transition-transform duration-200"
                         :class="borrowingOpen ? 'rotate-90 text-blue-600' : 'text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="borrowingOpen" x-collapse class="mt-1 space-y-0.5 pl-7">
                    <a href="{{ route('user.borrowing.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all duration-150
                              {{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') || Route::is('user.borrowing.show-item') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') || Route::is('user.borrowing.show-item') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Item Borrowing
                    </a>

                    <a href="{{ route('user.borrowing.cart') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all duration-150
                              {{ Route::is('user.borrowing.cart') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <div class="flex items-center gap-2.5">
                            <svg style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.cart') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Cart
                        </div>
                        @if(($sidebarCartCount ?? 0) > 0)
                            <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full flex-shrink-0">{{ $sidebarCartCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('user.borrowing.my-requests') }}"
                       class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-all duration-150
                              {{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg style="width:15px;height:15px;" class="{{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        My Requests
                    </a>

                </div>
            </div>

            {{-- Pengadaan --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Pengadaan</p>

                <a href="{{ route('user.procurement.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150
                          {{ Route::is('user.procurement.*') ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;flex-shrink:0;" class="{{ Route::is('user.procurement.*') ? 'text-orange-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                        </svg>
                        <span>Permintaan Pengadaan</span>
                    </div>
                    @php $myPendingProcurement = \App\Models\ProcurementRequest::where('user_id', auth()->id())->where('status', 'pending')->count(); @endphp
                    @if($myPendingProcurement > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-orange-500 rounded-full flex-shrink-0">{{ $myPendingProcurement }}</span>
                    @endif
                </a>
            </div>

            {{-- Account Section --}}
            <div class="pt-4 flex flex-col gap-1">
                <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Account</p>

                <a href="{{ route('user.profile.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150
                          {{ Route::is('user.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg style="width:18px;height:18px;" class="{{ Route::is('user.profile.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>My Profile</span>
                </a>

                <a href="{{ route('user.notifications.index') }}"
                   class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-all duration-150
                          {{ Route::is('user.notifications.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg style="width:18px;height:18px;" class="{{ Route::is('user.notifications.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifikasi</span>
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
                <button type="submit" 
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