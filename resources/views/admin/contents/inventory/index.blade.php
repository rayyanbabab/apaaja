@extends('admin.layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Clean Header Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Inventory Management</h1>
                    <p class="text-sm text-gray-600">Manage and organize your inventory items efficiently</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    {{-- Scan Barcode Button --}}
                    <button onclick="openScannerModal()"
                        class="inline-flex items-center px-3 py-2 sm:px-4 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        Scan Barcode
                    </button>
                    @if(auth()->user()->role->value === 'admin')
                    <button onclick="openImportItemsModal()"
                            class="inline-flex items-center px-3 py-2 sm:px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Import CSV
                    </button>
                    @endif
                    <a href="{{ route($routePrefix . '.inventory.add') }}"
                        class="inline-flex items-center px-3 py-2 sm:px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Item
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Message Banner --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 5000)"
             class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3.5 rounded-xl mb-6 shadow-sm">
            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold">Berhasil!</p>
                <p class="text-xs text-green-700 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- Import Errors (from CSV import) --}}
        @if(session('import_errors') && count(session('import_errors')) > 0)
        <div x-data="{ show: true, expanded: false }" x-show="show"
             class="flex flex-col gap-1 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-3.5 rounded-xl mb-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold">{{ count(session('import_errors')) }} baris dilewati saat import</p>
                    <button @click="expanded = !expanded" class="text-xs text-amber-700 underline" x-text="expanded ? 'Sembunyikan detail' : 'Lihat detail'"></button>
                </div>
                <button @click="show = false" class="text-amber-400 hover:text-amber-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <ul x-show="expanded" class="mt-2 pl-11 space-y-1">
                @foreach(session('import_errors') as $err)
                <li class="text-xs text-amber-700">• {{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 6000)"
             class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl mb-6 shadow-sm">
            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold">Gagal!</p>
                <p class="text-xs text-red-700 mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('warning'))
        <div x-data="{ show: true }" x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 6000)"
             class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-3.5 rounded-xl mb-6 shadow-sm">
            <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold">Perhatian!</p>
                <p class="text-xs text-amber-700 mt-0.5">{{ session('warning') }}</p>
            </div>
            <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- Inventory Overview Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
            {{-- Total Items Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Items</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['total_items'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Stock Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Stock</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($statistics['total_stock']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Low Stock Items Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Low Stock</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['low_stock'] }}</p>
                    </div>
                </div>
            </div>

        {{-- Out of Stock Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Out of Stock</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $statistics['out_of_stock'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Under Maintenance Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">In Maintenance</p>
                        <a href="{{ route($routePrefix . '.maintenance.index', ['status' => 'in_repair']) }}" class="text-xl sm:text-2xl font-bold text-orange-700 hover:underline">{{ $statistics['under_maintenance'] }}</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Search Section --}}
        <div x-data="inventoryFilter()" class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-visible">

            {{-- Filter Header --}}
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Filter & Pencarian</h3>
                            <p class="text-xs text-gray-500">Temukan item dengan cepat</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            {{ $items->total() }} item
                        </span>
                        {{-- Active filter indicators --}}
                        @if(request('search') || request('category_id') || request('supplier_id') || request('type') || request('availability'))
                            <a href="{{ route($routePrefix . '.inventory.index') }}"
                               class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 px-2.5 py-1 rounded-full border border-red-100 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Filter Body --}}
            <div class="p-5 overflow-visible">
                <form method="GET" action="{{ route($routePrefix . '.inventory.index') }}" id="filterForm" class="overflow-visible">

                    {{-- Search Bar --}}
                    <div class="relative mb-3">

                        <input type="text" name="search" id="search"
                               value="{{ request('search') }}"
                               class="w-full pl-5 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 bg-gray-50 focus:bg-white transition-all"
                               placeholder="Cari berdasarkan nama, kode, atau ITM-XXXX...">
                        @if(request('search'))
                        <a href="{{ route($routePrefix . '.inventory.index', array_merge(request()->except('search', 'page'), [])) }}"
                           class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                        @endif
                    </div>

                    {{-- Dropdown Filters --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4 overflow-visible">

                        {{-- ── Category Searchable Dropdown ── --}}
                        <div class="space-y-1 overflow-visible">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</label>
                            <input type="hidden" name="category_id" :value="catId">
                            <div class="relative overflow-visible">
                                <button type="button"
                                        x-ref="catBtn"
                                        @click="const r=$refs.catBtn.getBoundingClientRect(); catPos={top:r.bottom+window.scrollY+4,left:r.left+window.scrollX,width:r.width}; catOpen=!catOpen; supOpen=false"
                                        class="w-full flex items-center gap-2 px-3 py-2.5 text-sm bg-white border rounded-lg text-left transition-all duration-150 border-gray-200 hover:border-gray-300"
                                        :class="catOpen ? 'border-blue-500 ring-2 ring-blue-100' : ''">
                                    <span class="flex-shrink-0 w-6 h-6 rounded flex items-center justify-center" :class="catId ? 'bg-blue-50' : 'bg-gray-100'">
                                        <svg class="w-3.5 h-3.5" :class="catId ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </span>
                                    <span class="flex-1 truncate" :class="catId ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="catLabel || 'Semua Kategori'"></span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="catOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <template x-teleport="body">
                                    <div x-show="catOpen" @click.outside="catOpen = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         :style="`position:absolute;top:${catPos.top}px;left:${catPos.left}px;width:${catPos.width}px;z-index:9999`"
                                         class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden"
                                         style="transform-origin: top;">
                                    <div class="p-2 bg-gray-50 border-b border-gray-100">
                                        <div class="relative">
                                           
                                            <input type="text" x-model="catSearch"
                                                   x-init="$watch('catOpen', v => v && $nextTick(() => $refs.catInput.focus()))"
                                                   x-ref="catInput"
                                                   placeholder="Cari kategori..."
                                                   class="w-full pl-8 pr-3 py-1.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400 px-1"><span x-text="filteredCats.length"></span> ditemukan</p>
                                    </div>
                                    <ul class="overflow-y-auto dropdown-scroll" style="max-height: 180px;">
                                        <li @click="catId = ''; catLabel = ''; catOpen = false; catSearch = ''; submitForm()"
                                            class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer border-b border-gray-50"
                                            :class="!catId ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-500 hover:bg-gray-50'">
                                            <span class="w-4 h-4 flex-shrink-0 flex items-center justify-center">
                                                <svg x-show="!catId" class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                            <span class="italic">Semua Kategori</span>
                                        </li>
                                        <template x-for="cat in filteredCats" :key="cat.id">
                                            <li @click="catId = cat.id; catLabel = cat.name; catOpen = false; catSearch = ''; submitForm()"
                                                class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer transition-colors"
                                                :class="catId == cat.id ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'">
                                                <span class="w-4 h-4 flex-shrink-0 flex items-center justify-center">
                                                    <svg x-show="catId == cat.id" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                                <span class="flex-1 truncate" x-text="cat.name"></span>
                                            </li>
                                        </template>
                                        <li x-show="filteredCats.length === 0" class="py-5 text-center">
                                            <p class="text-sm text-gray-400">Tidak ditemukan</p>
                                        </li>
                                    </ul>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- ── Supplier Searchable Dropdown ── --}}
                        <div class="space-y-1 overflow-visible">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Supplier</label>
                            <input type="hidden" name="supplier_id" :value="supId">
                            <div class="relative overflow-visible">
                                <button type="button"
                                        x-ref="supBtn"
                                        @click="const r=$refs.supBtn.getBoundingClientRect(); supPos={top:r.bottom+window.scrollY+4,left:r.left+window.scrollX,width:r.width}; supOpen=!supOpen; catOpen=false"
                                        class="w-full flex items-center gap-2 px-3 py-2.5 text-sm bg-white border rounded-lg text-left transition-all duration-150 border-gray-200 hover:border-gray-300"
                                        :class="supOpen ? 'border-blue-500 ring-2 ring-blue-100' : ''">
                                    <span class="flex-shrink-0 w-6 h-6 rounded flex items-center justify-center" :class="supId ? 'bg-blue-50' : 'bg-gray-100'">
                                        <svg class="w-3.5 h-3.5" :class="supId ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </span>
                                    <span class="flex-1 truncate" :class="supId ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="supLabel || 'Semua Supplier'"></span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="supOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <template x-teleport="body">
                                    <div x-show="supOpen" @click.outside="supOpen = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         :style="`position:absolute;top:${supPos.top}px;left:${supPos.left}px;width:${supPos.width}px;z-index:9999`"
                                         class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden"
                                         style="transform-origin: top;">
                                    <div class="p-2 bg-gray-50 border-b border-gray-100">
                                        <div class="relative">
                                            
                                            <input type="text" x-model="supSearch"
                                                   x-init="$watch('supOpen', v => v && $nextTick(() => $refs.supInput.focus()))"
                                                   x-ref="supInput"
                                                   placeholder="Cari supplier..."
                                                   class="w-full pl-8 pr-3 py-1.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400 px-1"><span x-text="filteredSups.length"></span> ditemukan</p>
                                    </div>
                                    <ul class="overflow-y-auto dropdown-scroll" style="max-height: 180px;">
                                        <li @click="supId = ''; supLabel = ''; supOpen = false; supSearch = ''; submitForm()"
                                            class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer border-b border-gray-50"
                                            :class="!supId ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-500 hover:bg-gray-50'">
                                            <span class="w-4 h-4 flex-shrink-0 flex items-center justify-center">
                                                <svg x-show="!supId" class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            </span>
                                            <span class="italic">Semua Supplier</span>
                                        </li>
                                        <template x-for="sup in filteredSups" :key="sup.id">
                                            <li @click="supId = sup.id; supLabel = sup.name; supOpen = false; supSearch = ''; submitForm()"
                                                class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer transition-colors"
                                                :class="supId == sup.id ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'">
                                                <span class="w-4 h-4 flex-shrink-0 flex items-center justify-center">
                                                    <svg x-show="supId == sup.id" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                                <span class="flex-1 truncate" x-text="sup.name"></span>
                                            </li>
                                        </template>
                                        <li x-show="filteredSups.length === 0" class="py-5 text-center">
                                            <p class="text-sm text-gray-400">Tidak ditemukan</p>
                                        </li>
                                    </ul>
                                    </div>
                                </template>
                            </div>
                        </div>

                    {{-- ── Item Type Pill Select ── --}}
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipe Item</label>
                            <input type="hidden" name="type" :value="typeVal">
                            <div class="flex items-center gap-1.5 flex-wrap pt-1">
                                <button type="button" @click="typeVal = ''; submitForm()"
                                        class="flex-1 px-3 py-2.5 text-sm rounded-lg border transition-all font-medium text-center"
                                        :class="typeVal === '' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'">
                                    Semua
                                </button>
                                <button type="button" @click="typeVal = 'stok'; submitForm()"
                                        class="flex-1 px-3 py-2.5 text-sm rounded-lg border transition-all font-medium text-center"
                                        :class="typeVal === 'stok' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'">
                                    Stok
                                </button>
                                <button type="button" @click="typeVal = 'peminjaman'; submitForm()"
                                        class="flex-1 px-3 py-2.5 text-sm rounded-lg border transition-all font-medium text-center"
                                        :class="typeVal === 'peminjaman' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'">
                                    Pinjam
                                </button>
                            </div>
                        </div>

                    </div>



                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-400">
                            Menampilkan <span class="font-semibold text-gray-600">{{ $items->total() }}</span> item
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route($routePrefix . '.inventory.index') }}"
                               class="px-4 py-2 text-sm border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-lg font-medium transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </a>
                            <button type="submit"
                                    class="px-5 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .dropdown-scroll { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
            .dropdown-scroll::-webkit-scrollbar { width: 4px; }
            .dropdown-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 999px; }
        </style>

        <script>
        function inventoryFilter() {
            return {
                catOpen: false, catSearch: '', catPos: {top:0,left:0,width:0},
                catId: '{{ request('category_id') }}',
                catLabel: '{{ $categories->firstWhere('id', request('category_id'))?->name ?? '' }}',
                categories: {!! json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()) !!},
                get filteredCats() {
                    if (!this.catSearch) return this.categories;
                    return this.categories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase()));
                },

                supOpen: false, supSearch: '', supPos: {top:0,left:0,width:0},
                supId: '{{ request('supplier_id') }}',
                supLabel: '{{ $suppliers->firstWhere('id', request('supplier_id'))?->company_name ?? '' }}',
                suppliers: {!! json_encode($suppliers->map(fn($s) => ['id' => $s->id, 'name' => $s->company_name])->values()) !!},
                get filteredSups() {
                    if (!this.supSearch) return this.suppliers;
                    return this.suppliers.filter(s => s.name.toLowerCase().includes(this.supSearch.toLowerCase()));
                },

                typeVal: '{{ request('type', '') }}',
                availVal: '{{ request('availability', '') }}',

                submitForm() {
                    this.$nextTick(() => document.getElementById('filterForm').submit());
                }
            }
        }
        </script>

        {{-- Clean Items Grid --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Inventory Items</h3>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $items->total() }} items</span>
                </div>
            </div>

            @if($items->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                    @foreach($items as $item)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                            {{-- Item Image --}}
                            <div class="relative h-48 bg-gray-50">
                                @if($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" 
                                         alt="{{ $item->nama }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Type Badge --}}
                                @if($item->type)
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-medium {{ $item->type->value === 'stok' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $item->type->label() }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Stock Status --}}
                                <div class="absolute top-3 left-3 flex flex-col gap-1">
                                    @if($item->stok_total == 0)
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($item->stok_total <= $lowStockThreshold)
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $item->stok_total }} units (Low)
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                            {{ $item->stok_total }} units
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Item Details --}}
                            <div class="p-4">
                                <div class="mb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">
                                            ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $item->nama }}</h4>
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <span class="truncate">{{ $item->category->name ?? 'No Category' }}</span>
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="truncate">{{ $item->supplier->company_name ?? 'No Supplier' }}</span>
                                    </div>

                                    {{-- Unit Price Display --}}
                                    @if($item->type->value === 'stok' && $item->harga > 0)
                                        <div class="flex items-center text-sm text-green-600 font-medium">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span>Rp {{ number_format($item->harga, 0, '.', '.') }}</span>
                                        </div>
                                    @elseif($item->type->value === 'peminjaman')
                                        <div class="flex items-center text-sm text-purple-600 font-medium">
                                            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                            <span>Borrowing</span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span>No price</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="space-y-2">
                                    {{-- Print Label (full width, always visible) --}}
                                    <a href="{{ route($routePrefix . '.inventory.print-label', $item->id) }}" target="_blank"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Print Label QR
                                    </a>
                                    {{-- View / Edit / Delete --}}
                                    <div class="flex gap-2">
                                        <a href="{{ route($routePrefix . '.inventory.show', $item->id) }}"
                                            class="flex-1 px-3 py-2 text-center text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                                            View
                                        </a>
                                        <a href="{{ route($routePrefix . '.inventory.edit', $item->id) }}"
                                            class="flex-1 px-3 py-2 text-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
                                            Edit
                                        </a>
                                        @if($item->stok_total == 0)
                                        <button type="button" onclick="openModal('del-item-{{ $item->id }}')" title="Hapus item"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 flex items-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Clean Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    @if ($items->hasPages())
                        {{ $items->appends(request()->query())->links() }}
                    @else
                        <div class="text-sm text-gray-500">
                            Showing <span class="font-medium">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $items->total() }}</span> results
                        </div>
                    @endif
                </div>
            @else
                {{-- Clean Empty State --}}
                <div class="text-center py-16 px-6">
                    <div class="max-w-sm mx-auto">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Items Found</h3>
                        <p class="text-gray-600 mb-6">Your inventory is empty or no items match your current filters.</p>
                        <div class="space-y-3">
                            <a href="{{ route($routePrefix . '.inventory.add') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Add Your First Item
                            </a>
                            <div>
                                <a href="{{ route($routePrefix . '.inventory.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                                    or clear all filters
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@if(auth()->user()->role->value === 'admin')
    @include('admin.components.partials.import-items-modal')
@endif

{{-- Barcode Scanner Modal --}}
@include('admin.components.partials.barcode-scanner-modal')

@push('scripts')
    @foreach($items as $item)
        @if($item->stok_total == 0)
            <x-popup id="del-item-{{ $item->id }}" title="Hapus Item"
                message="Apakah Anda yakin ingin menghapus item '{{ $item->nama }}'? Tindakan ini tidak dapat dibatalkan."
                formId="del-item-form-{{ $item->id }}"
                confirmText="Hapus"
                confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
                cancelText="Batal" />
            <form id="del-item-form-{{ $item->id }}" action="{{ route($routePrefix . '.inventory.destroy', $item->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    @endforeach
@endpush

@endsection
