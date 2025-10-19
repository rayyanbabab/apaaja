{{-- CSS to ensure hamburger is hidden on desktop --}}
<style>
    @media (min-width: 768px) {
        .mobile-hamburger-container {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    }
    @media (max-width: 767px) {
        .mobile-hamburger-container {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    }
</style>

{{-- Mobile Hamburger Button - Only visible on mobile --}}
<div class="mobile-hamburger-container mobile-hamburger block md:hidden fixed top-4 left-4 z-50" 
     x-data="{ mobileMenuOpen: false }">
    <button @click="mobileMenuOpen = !mobileMenuOpen" 
            class="mobile-hamburger inline-flex items-center justify-center p-2 rounded-md text-gray-700 bg-white shadow-lg border border-gray-200 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
        <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    {{-- Mobile Sidebar Overlay --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"
         @click="mobileMenuOpen = false"></div>

    {{-- Mobile Sidebar --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl border-r border-gray-200 md:hidden"
         x-data="sidebarData()" x-init="init()">
        
        {{-- Mobile Header --}}
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 bg-white">
            <div class="flex items-center space-x-3">
                <img src="/inc.png" alt="CHEMI-CON Logo" class="h-10 w-auto">
                <div class="text-lg font-semibold text-gray-800">INVENTORY</div>
            </div>
            <button @click="mobileMenuOpen = false" class="p-2 rounded-md text-gray-400 hover:text-gray-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile Navigation --}}
        <div class="flex flex-col h-full">
            <nav class="px-4 py-6 space-y-1 flex-1 overflow-y-auto">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3 {{ Route::is('admin.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Master Data --}}
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
                    <a href="{{ route('admin.categories.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.categories.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Categories
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.suppliers.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.suppliers.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Suppliers
                    </a>
                </div>
            </div>

            {{-- Inventory --}}
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
                    <a href="{{ route('admin.inventory.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.inventory.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.inventory.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Inventory Overview
                    </a>
                    <a href="{{ route('admin.incoming.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.incoming.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.incoming.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                        Barang Masuk
                    </a>
                    <a href="{{ route('admin.outgoing.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.outgoing.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.outgoing.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                        Barang Keluar
                    </a>
                </div>
            </div>

            {{-- Borrowing --}}
            <div class="pt-4">
                <button @click="borrowingOpen = !borrowingOpen" 
                        class="flex items-center justify-between w-full px-4 py-2 text-sm font-semibold text-gray-500 transition-colors"
                        :class="borrowingOpen ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'">
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
                <div x-show="borrowingOpen" x-collapse class="mt-2 space-y-1">
                    <a href="{{ route('admin.borrowings.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.borrowings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.borrowings.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Kelola Peminjaman
                    </a>
                    <a href="{{ route('admin.borrowing-requests.index') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.borrowing-requests.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.borrowing-requests.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Permintaan Peminjaman
                    </a>
                </div>
            </div>

            {{-- Users --}}
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
                    <a href="{{ route('admin.content.listusers') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.content.listusers') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.content.listusers') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Manage Users
                    </a>
                    <a href="{{ route('admin.content.createusers') }}" @click="mobileMenuOpen = false"
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.content.createusers') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3 {{ Route::is('admin.content.createusers') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add New User
                    </a>
                </div>
            </div>

            {{-- Reports --}}
            <a href="{{ route('admin.reports.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.reports.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} mt-4">
                <svg class="w-5 h-5 mr-3 {{ Route::is('admin.reports.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Reports
            </a>

            {{-- Recent Activities --}}
            <a href="{{ route('admin.activities.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.activities.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3 {{ Route::is('admin.activities.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Recent Activities
            </a>

            {{-- Profile --}}
            <a href="{{ route('admin.profile.index') }}" @click="mobileMenuOpen = false"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ Route::is('admin.profile.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 mr-3 {{ Route::is('admin.profile.*') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile
            </a>

            </nav>
            
            {{-- Mobile Footer with Sign Out --}}
            <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" @click="mobileMenuOpen = false"
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
