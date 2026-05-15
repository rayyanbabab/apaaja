<div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4 xl:grid-cols-4">
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-violet-100 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                            <path d="M12 22V12" />
                            <polyline points="3.29 7 12 12 20.71 7" />
                            <path d="m7.5 4.27 9 5.15" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Items</p>
                        <span class="hidden sm:inline-flex items-center rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-medium text-violet-700 flex-shrink-0">
                            {{ $jumlahJenisBarang }}
                        </span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($jumlahJenisBarang) }}</p>
                    <p class="hidden sm:block text-sm text-gray-500">Total Inventory Items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 7h6v6" />
                            <path d="m22 7-8.5 8.5-5-5L2 17" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Items In</p>
                        <span class="hidden sm:inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 flex-shrink-0">
                            +{{ $totalMasuk }}
                        </span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalMasuk) }}</p>
                    <p class="hidden sm:block text-sm text-gray-500">Total incoming items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-red-100 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 17h6v-6" />
                            <path d="m22 17-8.5-8.5-5 5L2 7" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Items Out</p>
                        <span class="hidden sm:inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 flex-shrink-0">
                            -{{ $totalKeluar }}
                        </span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($totalKeluar) }}</p>
                    <p class="hidden sm:block text-sm text-gray-500">Total outgoing items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-5 w-5 sm:h-8 sm:w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Stock Value</p>
                        <span class="hidden sm:inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 flex-shrink-0">
                            IDR
                        </span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 truncate">{{ number_format($totalValue, 0, ',', '.') }}</p>
                    <p class="hidden sm:block text-sm text-gray-500">Total inventory value</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Users</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($userCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 18H3c-.6 0-1-.4-1-1V7c0-.6.4-1 1-1h10c.6 0 1 .4 1 1v11"/><path d="M14 9h4l4 4v4h-8v-4l-4-4Z"/><circle cx="7.5" cy="18.5" r="2.5"/><circle cx="17.5" cy="18.5" r="2.5"/></svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Suppliers</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($supplierCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-pink-100 text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H2v7l6.29 6.29c.94.94 2.48.94 3.42 0l3.58-3.58c.94-.94.94-2.48 0-3.42L9 5Z"/><path d="M6 9.01V9"/><path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19"/></svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Categories</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($categoryCount) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="flex h-10 w-10 sm:h-14 sm:w-14 items-center justify-center rounded-full bg-teal-100 text-teal-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Borrowable Items</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($borrowableItemsCount) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
