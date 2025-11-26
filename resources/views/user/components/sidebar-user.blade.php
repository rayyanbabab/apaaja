<div class="hidden md:flex md:flex-shrink-0 md:w-64" x-data="userSidebarData()" x-init="init()">
    <div class="sidebar-container w-64 bg-white fixed top-0 left-0 z-50 shadow-xl border-r border-gray-200">
        
        {{-- Header --}}
        <div class="flex items-center h-16 px-6 border-b border-gray-200 bg-white flex-shrink-0">
            <div class="flex items-center space-x-3">
                <img src="/inc.png" alt="CHEMI-CON Logo" class="h-12 w-auto">
                <div class="text-lg font-semibold text-gray-800">
                    INVENTORY
                </div>
            </div>
        </div>

        {{-- Scrollable Navigation --}}
        <nav class="sidebar-navigation px-4 py-6 space-y-1">

            {{-- Dashboard --}}
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('user.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3 {{ Route::is('user.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>


            {{-- Borrowing --}}
            <div class="mt-4">
                <button @click="borrowingOpen = !borrowingOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Borrowing</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-all duration-300 ease-in-out" 
                         :class="borrowingOpen ? 'rotate-90 text-blue-600' : 'rotate-0 text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="borrowingOpen" x-collapse class="mt-1 ml-4 space-y-1">
                    <a href="{{ route('user.borrowing.index') }}"
                       class="flex items-center px-3 py-2 text-sm rounded-lg {{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') || Route::is('user.borrowing.show-item') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Route::is('user.borrowing.index') || Route::is('user.borrowing.create') || Route::is('user.borrowing.show-item') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Peminjaman Barang
                    </a>
                    
                    <a href="{{ route('user.borrowing.cart') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm rounded-lg {{ Route::is('user.borrowing.cart') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 {{ Route::is('user.borrowing.cart') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Keranjang
                        </div>
                        @php
                            $cartCount = \App\Models\BorrowingCart::where('user_id', auth()->id())->count();
                        @endphp
                        @if($cartCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $cartCount }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('user.borrowing.my-requests') }}"
                       class="flex items-center px-3 py-2 text-sm rounded-lg {{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 mr-2 {{ Route::is('user.borrowing.my-requests') || Route::is('user.borrowing.show') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Status Peminjaman
                    </a>
                </div>
            </div>

            {{-- Profile --}}
            <div class="pt-4">
                <a href="{{ route('user.profile.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('user.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3 {{ Route::is('user.profile.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
            </div>
        </nav>

        {{-- Footer with Sign Out --}}
        <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
        </div>
    </div>
</div>
