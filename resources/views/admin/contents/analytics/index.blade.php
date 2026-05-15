@extends('admin.layouts.dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">


        <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-12 sm:h-14">
                    <nav class="hidden sm:flex items-center space-x-1 text-sm" aria-label="Breadcrumb">
                        <a href="{{ route($routePrefix . '.dashboard') }}"
                            class="flex items-center text-gray-500 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            Dashboard
                        </a>
                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 font-medium">Insights</span>
                    </nav>
                    <span class="sm:hidden text-sm font-semibold text-gray-700">Insights</span>
                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                        Live · {{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">


            <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-7">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span
                                class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            </span>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 leading-tight">Insight & Advanced
                                Analytics</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Analitik mendalam untuk stok, arus barang, dan performa
                                peminjaman.</p>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="inline-flex items-center gap-1.5 text-xs text-green-600 font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    Data Real-time
                                </span>
                                <span class="text-gray-300">|</span>
                                <span
                                    class="text-xs text-gray-400">{{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                                    WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="pl-16 sm:pl-0 sm:text-right">
                        <p class="text-xs text-gray-400 mb-1">Rentang Periode</p>
                        <div class="inline-flex rounded-xl bg-gray-100 p-1">
                            <a href="{{ route($routePrefix . '.insights.index', ['period' => '6m']) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $period === '6m' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                6 Bulan
                            </a>
                            <a href="{{ route($routePrefix . '.insights.index', ['period' => '12m']) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $period === '12m' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                12 Bulan
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Ringkasan Inventori
                    </h2>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></span>
                        Live Data
                    </span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4">

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Total</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($kpi['total_items']) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Total Item</div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-0.5 rounded-full">Stok</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($kpi['total_stock']) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Total Stok</div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Nilai</span>
                        </div>
                        <div class="text-xl font-bold text-gray-900">Rp
                            {{ number_format($kpi['inventory_value'], 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Nilai Inventory</div>
                    </div>

                    <div class="bg-amber-50/80 backdrop-blur-sm rounded-2xl border border-amber-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full">Rendah</span>
                        </div>
                        <div class="text-2xl font-bold text-amber-700">{{ number_format($kpi['low_stock_items']) }}</div>
                        <div class="text-xs text-amber-600 mt-0.5">Stok Rendah (≤ {{ $lowStockThreshold }})</div>
                    </div>

                    <div class="bg-red-50/80 backdrop-blur-sm rounded-2xl border border-red-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Kosong</span>
                        </div>
                        <div class="text-2xl font-bold text-red-700">{{ number_format($kpi['out_of_stock_items']) }}</div>
                        <div class="text-xs text-red-600 mt-0.5">Stok Habis</div>
                    </div>

                </div>
            </div>


            <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Aktivitas Hari Ini
                    </h2>
                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        {{ now()->setTimezone('Asia/Jakarta')->format('d M Y') }}
                    </span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Masuk</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($latestSignals['incoming_today']) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">Barang Masuk</div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Keluar</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($latestSignals['outgoing_today']) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">Barang Keluar</div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Aktif</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($latestSignals['active_borrowings']) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Peminjaman Aktif</div>
                    </div>

                    <div class="bg-red-50/60 backdrop-blur-sm rounded-2xl border border-red-100 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-rose-500 to-red-600 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Overdue</span>
                        </div>
                        <div class="text-2xl font-bold text-red-700">
                            {{ number_format($latestSignals['overdue_borrowings']) }}</div>
                        <div class="text-xs text-red-500 mt-0.5">Peminjaman Terlambat</div>
                    </div>

                </div>
            </div>


            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <div
                    class="xl:col-span-2 bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            Tren Arus Barang
                        </h2>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">Masuk vs Keluar vs
                            Net</span>
                    </div>
                    <div id="movementChart" class="h-80"></div>
                </div>

                <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-6">
                    <div class="mb-4">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                            Distribusi Status Peminjaman
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Proporsi berdasarkan status</p>
                    </div>
                    <div id="statusChart" class="h-64"></div>
                    <div
                        class="mt-3 flex items-center justify-between rounded-xl bg-indigo-50 border border-indigo-100 px-3 py-2">
                        <span class="text-xs text-indigo-600 font-medium">Completion Rate</span>
                        <span class="text-sm font-bold text-indigo-700">{{ $borrowing['completion_rate'] }}%</span>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Top 5 Barang Paling Sering Dipinjam
                        </h2>
                    </div>
                    <div class="p-5 sm:p-6 space-y-2.5">
                        @forelse($topBorrowedItems as $index => $row)
                            <div
                                class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white/60 hover:bg-indigo-50/40 hover:border-indigo-100 px-3 py-2.5 transition-colors">
                                <div class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full
                                        {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-gray-100 text-gray-500' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-indigo-50 text-indigo-500')) }}
                                        text-xs font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <span class="flex-1 text-sm text-gray-800 min-w-0 truncate">{{ $row->item_name }}</span>
                                <span
                                    class="flex-shrink-0 inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-full">
                                    {{ number_format($row->total_borrowed) }} unit
                                </span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="h-6 w-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Belum ada data peminjaman</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm p-5 sm:p-6">
                    <div class="mb-4">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Stok per Kategori
                        </h2>
                        <p class="text-xs text-gray-400 mt-0.5">Distribusi stok berdasarkan kategori</p>
                    </div>
                    <div id="categoryChart" class="h-80"></div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            @php
                $statusSeries = [
                    $borrowing['pending'],
                    $borrowing['approved'],
                    $borrowing['completed'],
                    $borrowing['rejected'],
                    $borrowing['cancelled'],
                    $borrowing['overdue'],
                ];
            @endphp

            const movementIncoming = @json($movementIncoming);
            const movementOutgoing = @json($movementOutgoing);
            const movementNet = @json($movementNet);
            const movementLabels = @json($movementLabels);
            const statusSeries = @json($statusSeries);
            const categoryTotals = @json($categoryTotals);
            const categoryLabels = @json($categoryLabels);

            new ApexCharts(document.querySelector("#movementChart"), {
                series: [
                    { name: 'Masuk', data: movementIncoming },
                    { name: 'Keluar', data: movementOutgoing },
                    { name: 'Net', data: movementNet },
                ],
                chart: {
                    type: 'line',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    background: 'transparent',
                    animations: { enabled: true, easing: 'easeinout', speed: 600 },
                    dropShadow: { enabled: true, top: 4, left: 0, blur: 8, color: ['#10B981', '#EF4444', '#4F46E5'], opacity: 0.1 }
                },
                stroke: { width: [3, 3, 2], curve: 'smooth', dashArray: [0, 0, 6] },
                colors: ['#10B981', '#EF4444', '#4F46E5'],
                xaxis: {
                    categories: movementLabels,
                    labels: { style: { colors: '#9CA3AF', fontSize: '11px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    min: 0,
                    labels: { style: { colors: '#9CA3AF', fontSize: '11px' } }
                },
                legend: { position: 'top', labels: { colors: '#6B7280' }, markers: { radius: 6 } },
                grid: { borderColor: '#F3F4F6', strokeDashArray: 5 },
                tooltip: { theme: 'light', y: { formatter: val => val + ' unit' } }
            }).render();

            new ApexCharts(document.querySelector("#statusChart"), {
                series: statusSeries,
                labels: ['Pending', 'Approved', 'Completed', 'Rejected', 'Cancelled', 'Overdue'],
                chart: { type: 'donut', height: 256, fontFamily: 'inherit', background: 'transparent' },
                colors: ['#F59E0B', '#3B82F6', '#10B981', '#EF4444', '#6B7280', '#7C3AED'],
                legend: { position: 'bottom', labels: { colors: '#6B7280' } },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '12px', fontWeight: 600, color: '#6B7280' } } } }
                },
                stroke: { width: 2, colors: ['#fff'] },
                tooltip: { theme: 'light' }
            }).render();

            new ApexCharts(document.querySelector("#categoryChart"), {
                series: [{ name: 'Stok', data: categoryTotals }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    background: 'transparent'
                },
                plotOptions: { bar: { horizontal: true, borderRadius: 6, dataLabels: { position: 'top' } } },
                colors: ['#6366F1'],
                xaxis: {
                    categories: categoryLabels,
                    labels: { style: { colors: '#9CA3AF', fontSize: '11px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: { labels: { style: { colors: '#6B7280', fontSize: '11px', fontWeight: 500 } } },
                dataLabels: { enabled: false },
                grid: { borderColor: '#F3F4F6', strokeDashArray: 5, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
                fill: { type: 'gradient', gradient: { type: 'horizontal', shadeIntensity: 0.4, opacityFrom: 1, opacityTo: 0.7 } },
                tooltip: { theme: 'light', y: { formatter: val => val + ' unit' } }
            }).render();
        </script>
    @endpush
@endsection
