<div class="desktop-sidebar hidden md:flex md:flex-shrink-0 md:w-64" x-data="sidebarData()" x-init="init()">
    <div class="sidebar-container w-64 bg-white fixed top-0 left-0 z-40 shadow-xl border-r border-gray-200 flex flex-col h-full">
        <div class="flex items-center h-16 px-6 border-b border-gray-200 bg-white flex-shrink-0">
            <div class="flex items-center space-x-3">
                <img src="/inc.png" alt="CHEMI-CON Logo" class="h-12 w-auto">
                <div class="text-lg font-semibold text-gray-800">
                   INVENTORY
                </div>
            </div>
        </div>
        <nav class="sidebar-navigation px-4 py-6 space-y-1 flex-1 overflow-y-auto">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3 {{ Route::is('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <div class="pt-4">
                <button @click="masterDataOpen = !masterDataOpen" 
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-semibold text-gray-500 transition-colors"
                        :class="masterDataOpen ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" :class="masterDataOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h10a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/>
                        </svg>
                        <span>Master Data</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-all duration-300 ease-in-out" 
                         :class="masterDataOpen ? 'rotate-90 text-blue-600' : 'rotate-0 text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="masterDataOpen" x-collapse class="mt-2 space-y-1">
                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.categories.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Categories
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.suppliers.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Suppliers
                    </a>
                </div>
            </div>

            <div class="pt-4">
                <button @click="inventoryOpen = !inventoryOpen" 
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-semibold text-gray-500 transition-colors"
                        :class="inventoryOpen ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" :class="inventoryOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Inventory</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-all duration-300 ease-in-out" 
                         :class="inventoryOpen ? 'rotate-90 text-blue-600' : 'rotate-0 text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="inventoryOpen" x-collapse class="mt-2 space-y-1">
                    <a href="{{ route('admin.inventory.index') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.inventory.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.inventory.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Inventory Overview
                    </a>
                    <a href="{{ route('admin.incoming.index') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.incoming.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.incoming.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Barang Masuk
                    </a>
                    <a href="{{ route('admin.outgoing.index') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.outgoing.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.outgoing.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                        Barang Keluar
                    </a>
                </div>
            </div>

            <div class="pt-4">
                <button @click="borrowingOpen = !borrowingOpen" 
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-semibold text-gray-500 transition-colors"
                        :class="borrowingOpen ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" :class="borrowingOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Borrowing</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-all duration-300 ease-in-out" 
                         :class="borrowingOpen ? 'rotate-90 text-blue-600' : 'rotate-0 text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="borrowingOpen" x-transition class="mt-2 space-y-1 pl-8">
                    <a href="{{ route('admin.borrowings.index') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.borrowings.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 {{ Route::is('admin.borrowings.index') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Peminjaman Barang</span>
                        </div>
                        @php
                            $activeBorrowingCount = \App\Models\Borrowing::whereIn('status', ['dipinjam', 'terlambat'])->count();
                        @endphp
                        @if($activeBorrowingCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-yellow-600 rounded-full">
                                {{ $activeBorrowingCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.borrowings.history') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.borrowings.history') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 mr-3 {{ Route::is('admin.borrowings.history') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        History Peminjaman
                    </a>
                    <a href="{{ route('admin.borrowing-requests.index') }}"
                       class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.borrowing-requests.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 {{ Route::is('admin.borrowing-requests.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Persetujuan Peminjaman</span>
                        </div>
                        @php
                            $pendingCount = \App\Models\BorrowingRequest::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full animate-pulse">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="pt-4">
                <button @click="usersOpen = !usersOpen" 
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-semibold text-gray-500 transition-colors"
                        :class="usersOpen ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" :class="usersOpen ? 'text-blue-600' : 'text-gray-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        <span>Users</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-all duration-300 ease-in-out" 
                         :class="usersOpen ? 'rotate-90 text-blue-600' : 'rotate-0 text-gray-400'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div x-show="usersOpen" x-collapse class="mt-2 space-y-1">
                    <a href="{{ route('admin.content.listusers') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.content.listusers') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.content.listusers') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.content.createusers') }}"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.content.createusers') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.content.createusers') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add New User
                    </a>
                </div>
            </div>

            <div class="pt-4">
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3 {{ Route::is('admin.reports.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Reports
                </a>
            </div>

            <div class="pt-4">
                <a href="{{ route('admin.activities.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.activities.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3 {{ Route::is('admin.activities.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Recent Activities
                </a>
            </div>

            <div class="pt-4">
                <a href="{{ route('admin.profile.index') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 mr-3 {{ Route::is('admin.profile.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>
            </div>
        </nav>

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
