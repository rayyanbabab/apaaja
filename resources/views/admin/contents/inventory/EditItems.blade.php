@extends('admin.layouts.dashboard')

@section('content')
<div class="form mx-auto space-y-4 p-4 max-w-4xl">

    {{-- Header Section --}}
    <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Item</h1>
            <p class="text-sm text-gray-600">Update details for <span class="font-semibold text-gray-800">{{ $item->nama }}</span></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route($routePrefix . '.inventory.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Inventory
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Form --}}
    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <form id="update-form-{{ $item->id }}" method="POST"
              action="{{ route($routePrefix . '.inventory.update', $item->id) }}"
              enctype="multipart/form-data"
              x-data="editItemForm()">
            @csrf
            @method('PUT')
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            {{-- ── Section: Item Information ── --}}
            <div class="border-b border-gray-200 px-4 py-3">
                <h3 class="text-lg font-semibold text-gray-900">Item Information</h3>
            </div>

            <div class="space-y-4 p-4">

                {{-- Name & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama"
                            value="{{ old('nama', $item->nama) }}"
                            class="input-form @error('nama') border-red-300 bg-red-50 @enderror"
                            placeholder="e.g. Office Chair">
                        @error('nama')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Item Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" x-model="selectedType" @change="resetPriceOnTypeChange()" required
                            class="input-form">
                            <option value="stok"       {{ old('type', $item->type?->value) == 'stok'       ? 'selected' : '' }}>Stock (For Sale)</option>
                            <option value="peminjaman" {{ old('type', $item->type?->value) == 'peminjaman' ? 'selected' : '' }}>Borrowing (For Lending)</option>
                        </select>
                        @error('type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Category & Supplier --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- ── CATEGORY DROPDOWN ── --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Category
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-xs font-normal text-gray-400">(optional)</span>
                            </label>
                            <span x-show="catId" class="text-xs text-blue-600 font-medium cursor-pointer hover:text-blue-800"
                                  @click="catId = ''; catLabel = ''; catSearch = ''">Clear</span>
                        </div>
                        <input type="hidden" name="category_id" :value="catId">
                        <div class="relative">
                            <button type="button"
                                    @click="catOpen = !catOpen; supOpen = false"
                                    class="relative w-full flex items-center gap-2 px-3 py-2.5 text-sm bg-white border rounded-lg text-left transition-all duration-150
                                           {{ $errors->has('category_id') ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300' }}"
                                    :class="catOpen ? 'border-blue-500 ring-2 ring-blue-100 shadow-sm' : 'hover:border-gray-400 hover:shadow-sm'">
                                <span class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center"
                                      :class="catId ? 'bg-blue-50' : 'bg-gray-50'">
                                    <svg class="w-3.5 h-3.5" :class="catId ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </span>
                                <span class="flex-1 truncate" :class="catId ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="catLabel || 'Pilih kategori...'"></span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="catOpen ? 'rotate-180 text-blue-500' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="catOpen" @click.outside="catOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 w-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden"
                                 style="transform-origin: top;">
                                <div class="p-2 bg-gray-50 border-b border-gray-100">
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" x-model="catSearch"
                                               x-init="$watch('catOpen', v => v && $nextTick(() => $refs.catInput.focus()))"
                                               x-ref="catInput"
                                               placeholder="Cari kategori..."
                                               class="w-full pl-9 pr-8 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                        <button type="button" x-show="catSearch" @click="catSearch = ''"
                                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400 px-1"><span x-text="filteredCats.length"></span> kategori ditemukan</p>
                                </div>
                                <ul class="overflow-y-auto dropdown-scroll" style="max-height: 200px;">
                                    <li @click="catId = ''; catLabel = ''; catOpen = false; catSearch = ''"
                                        class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer border-b border-gray-50"
                                        :class="!catId ? 'bg-gray-50 text-gray-500' : 'text-gray-400 hover:bg-gray-50'">
                                        <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center bg-gray-100">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                        <span class="italic">Tidak ada</span>
                                    </li>
                                    <template x-for="cat in filteredCats" :key="cat.id">
                                        <li @click="catId = cat.id; catLabel = cat.name; catOpen = false; catSearch = ''"
                                            class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer transition-colors duration-100"
                                            :class="catId == cat.id ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'">
                                            <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center"
                                                  :class="catId == cat.id ? 'bg-blue-100' : 'bg-gray-100'">
                                                <svg x-show="catId == cat.id" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <svg x-show="catId != cat.id" class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1 truncate" x-text="cat.name"></span>
                                            <span x-show="catId == cat.id" class="text-xs font-semibold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded-full">Dipilih</span>
                                        </li>
                                    </template>
                                    <li x-show="filteredCats.length === 0" class="py-6 text-center">
                                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Tidak ditemukan</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @error('category_id')
                            <p class="flex items-center gap-1 text-xs text-red-600 mt-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- ── SUPPLIER DROPDOWN ── --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Supplier
                                <span x-show="selectedType === 'stok'" class="text-red-500">*</span>
                                <span x-show="selectedType === 'peminjaman'" class="text-xs font-normal text-gray-400">(optional)</span>
                            </label>
                            <span x-show="supId" class="text-xs text-blue-600 font-medium cursor-pointer hover:text-blue-800"
                                  @click="supId = ''; supLabel = ''; supSearch = ''">Clear</span>
                        </div>
                        <input type="hidden" name="supplier_id" :value="supId">
                        <div class="relative">
                            <button type="button"
                                    @click="supOpen = !supOpen; catOpen = false"
                                    class="relative w-full flex items-center gap-2 px-3 py-2.5 text-sm bg-white border rounded-lg text-left transition-all duration-150
                                           {{ $errors->has('supplier_id') ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300' }}"
                                    :class="supOpen ? 'border-blue-500 ring-2 ring-blue-100 shadow-sm' : 'hover:border-gray-400 hover:shadow-sm'">
                                <span class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center"
                                      :class="supId ? 'bg-blue-50' : 'bg-gray-50'">
                                    <svg class="w-3.5 h-3.5" :class="supId ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </span>
                                <span class="flex-1 truncate" :class="supId ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="supLabel || 'Pilih supplier...'"></span>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="supOpen ? 'rotate-180 text-blue-500' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="supOpen" @click.outside="supOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 w-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden"
                                 style="transform-origin: top;">
                                <div class="p-2 bg-gray-50 border-b border-gray-100">
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" x-model="supSearch"
                                               x-init="$watch('supOpen', v => v && $nextTick(() => $refs.supInput.focus()))"
                                               x-ref="supInput"
                                               placeholder="Cari supplier..."
                                               class="w-full pl-9 pr-8 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
                                        <button type="button" x-show="supSearch" @click="supSearch = ''"
                                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400 px-1"><span x-text="filteredSups.length"></span> supplier ditemukan</p>
                                </div>
                                <ul class="overflow-y-auto dropdown-scroll" style="max-height: 200px;">
                                    <li @click="supId = ''; supLabel = ''; supOpen = false; supSearch = ''"
                                        class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer border-b border-gray-50"
                                        :class="!supId ? 'bg-gray-50 text-gray-500' : 'text-gray-400 hover:bg-gray-50'">
                                        <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center bg-gray-100">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </span>
                                        <span class="italic">Tidak ada</span>
                                    </li>
                                    <template x-for="sup in filteredSups" :key="sup.id">
                                        <li @click="supId = sup.id; supLabel = sup.name; supOpen = false; supSearch = ''"
                                            class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer transition-colors duration-100"
                                            :class="supId == sup.id ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50'">
                                            <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center"
                                                  :class="supId == sup.id ? 'bg-blue-100' : 'bg-gray-100'">
                                                <svg x-show="supId == sup.id" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <svg x-show="supId != sup.id" class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </span>
                                            <span class="flex-1 truncate" x-text="sup.name"></span>
                                            <span x-show="supId == sup.id" class="text-xs font-semibold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded-full">Dipilih</span>
                                        </li>
                                    </template>
                                    <li x-show="filteredSups.length === 0" class="py-6 text-center">
                                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Tidak ditemukan</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @error('supplier_id')
                            <p class="flex items-center gap-1 text-xs text-red-600 mt-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Location (optional) --}}
                <div class="space-y-1.5" x-data="{
                    locOpen: false,
                    locId: {{ Js::from(old('location_id', $item->location_id ?? '')) }},
                    locLabel: {{ Js::from(old('location_id') ? ($locations->firstWhere('id', old('location_id'))?->full_label ?? '') : ($item->location?->full_label ?? '')) }},
                    locSearch: '',
                    locations: {{ Js::from($locations->map(fn($l) => ['id' => $l->id, 'name' => $l->full_label])->values()) }},
                    get filteredLocs() {
                        if (!this.locSearch) return this.locations;
                        return this.locations.filter(l => l.name.toLowerCase().includes(this.locSearch.toLowerCase()));
                    }
                }" @click.outside="locOpen = false">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            Lokasi Barang
                            <span class="text-xs font-normal text-gray-400">(opsional)</span>
                        </label>
                        <span x-show="locId" class="text-xs text-blue-600 font-medium cursor-pointer hover:text-blue-800"
                              @click="locId = ''; locLabel = ''; locSearch = ''">Clear</span>
                    </div>
                    <input type="hidden" name="location_id" :value="locId">
                    <div class="relative">
                        <button type="button" @click="locOpen = !locOpen"
                                class="relative w-full flex items-center gap-2 px-3 py-2.5 text-sm bg-white border rounded-lg text-left transition-all duration-150
                                       {{ $errors->has('location_id') ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300' }}"
                                :class="locOpen ? 'border-indigo-500 ring-2 ring-indigo-100 shadow-sm' : 'hover:border-gray-400 hover:shadow-sm'">
                            <span class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center" :class="locId ? 'bg-indigo-50' : 'bg-gray-50'">
                                <svg class="w-3.5 h-3.5" :class="locId ? 'text-indigo-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <span class="flex-1 truncate" :class="locId ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="locLabel || 'Pilih lokasi...'"></span>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="locOpen ? 'rotate-180 text-indigo-500' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="locOpen" @click.outside="locOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 w-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden"
                             style="transform-origin: top;">
                            <div class="p-2 bg-gray-50 border-b border-gray-100">
                                <input type="text" x-model="locSearch"
                                       x-init="$watch('locOpen', v => v && $nextTick(() => $el.focus()))"
                                       placeholder="Cari lokasi..."
                                       class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400">
                                <p class="mt-1.5 text-xs text-gray-400 px-1"><span x-text="filteredLocs.length"></span> lokasi ditemukan</p>
                            </div>
                            <ul class="overflow-y-auto dropdown-scroll" style="max-height: 200px;">
                                <li @click="locId = ''; locLabel = ''; locOpen = false; locSearch = ''"
                                    class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer border-b border-gray-50"
                                    :class="!locId ? 'bg-gray-50 text-gray-500' : 'text-gray-400 hover:bg-gray-50'">
                                    <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center bg-gray-100">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                    <span class="italic">Tidak ada</span>
                                </li>
                                <template x-for="loc in filteredLocs" :key="loc.id">
                                    <li @click="locId = loc.id; locLabel = loc.name; locOpen = false; locSearch = ''"
                                        class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer transition-colors duration-100"
                                        :class="locId == loc.id ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50'">
                                        <span class="w-5 h-5 rounded flex-shrink-0 flex items-center justify-center" :class="locId == loc.id ? 'bg-indigo-100' : 'bg-gray-100'">
                                            <svg x-show="locId == loc.id" class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <svg x-show="locId != loc.id" class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </span>
                                        <span class="flex-1 truncate" x-text="loc.name"></span>
                                        <span x-show="locId == loc.id" class="text-xs font-semibold text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded-full">Dipilih</span>
                                    </li>
                                </template>
                                <li x-show="filteredLocs.length === 0" class="py-6 text-center">
                                    <p class="text-sm text-gray-400">Lokasi tidak ditemukan</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @error('location_id')
                        <p class="flex items-center gap-1 text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price --}}
                <div x-show="selectedType === 'stok'" x-transition class="space-y-2">
                    <label for="harga" class="block text-sm font-medium text-gray-700">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-sm text-gray-500 font-medium">Rp</span>
                        </div>
                        <input type="number" id="harga" name="harga" min="0" max="999999999" step="1"
                            value="{{ old('harga', $item->harga ?? '') }}"
                            class="input-form pl-10 @error('harga') border-red-300 @enderror"
                            placeholder="0"
                            style="-moz-appearance:textfield;">
                    </div>
                    @error('harga')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">
                        Description <span class="text-gray-400 font-normal text-xs">(optional)</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="input-form resize-none"
                        placeholder="Describe the item...">{{ old('keterangan', $item->keterangan ?? '') }}</textarea>
                </div>

            </div>

            {{-- ── Section: Product Image ── --}}
            <div class="border-t border-b border-gray-200 px-4 py-3">
                <h3 class="text-lg font-semibold text-gray-900">Product Image</h3>
                <p class="text-sm text-gray-500 mt-0.5">Upload a new image to replace the current one</p>
            </div>

            <div class="p-4 space-y-3">
                <div class="flex items-start gap-5">
                    {{-- Current Image --}}
                    @if($item->gambar)
                        <div class="flex-shrink-0">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Current Image</p>
                            <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ asset($item->gambar) }}" alt="Current" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endif

                    <div class="flex-1 space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            {{ $item->gambar ? 'Replace with New Image' : 'Upload Image' }}
                        </p>
                        <div id="imagePreview" class="hidden">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img id="previewImg" src="" alt="New image" class="w-full h-full object-cover">
                                    <button type="button" onclick="clearImage()"
                                        class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">✕</button>
                                </div>
                                <p class="text-sm text-blue-600">New image selected</p>
                            </div>
                        </div>
                        <label for="gambarInput"
                               class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/20 transition-colors group">
                            <svg class="w-6 h-6 text-gray-300 group-hover:text-blue-400 flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500 group-hover:text-blue-600 font-medium transition-colors">Click to upload</p>
                                <p class="text-xs text-gray-400">PNG, JPG up to 10MB</p>
                            </div>
                            <input id="gambarInput" name="gambar" type="file" accept="image/*" onchange="previewImage(this)" class="sr-only">
                        </label>
                        @error('gambar')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form Footer --}}
            <div class="rounded-b-lg border-t border-gray-200 bg-gray-50 px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="{{ route($routePrefix . '.inventory.index') }}"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>

                    <button type="button" onclick="openModal('update-modal-{{ $item->id }}')" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearImage() {
        document.getElementById('gambarInput').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('previewImg').src = '';
    }

    function editItemForm() {
        return {
            selectedType: {{ Js::from(old('type', $item->type?->value ?? 'stok')) }},
            resetPriceOnTypeChange() {
                if (this.selectedType === 'peminjaman') {
                    const el = document.getElementById('harga');
                    if (el) el.value = '';
                }
            },

            catSearch: '',
            catOpen: false,
            catId: {{ Js::from(old('category_id', $item->category_id ?? '')) }},
            catLabel: {{ Js::from(old('category_id') ? ($categories->firstWhere('id', old('category_id'))?->name ?? '') : ($item->category?->name ?? '')) }},
            categories: {{ Js::from($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()) }},
            get filteredCats() {
                if (!this.catSearch) return this.categories;
                return this.categories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase()));
            },

            supSearch: '',
            supOpen: false,
            supId: {{ Js::from(old('supplier_id', $item->supplier_id ?? '')) }},
            supLabel: {{ Js::from(old('supplier_id') ? ($suppliers->firstWhere('id', old('supplier_id'))?->company_name ?? '') : ($item->supplier?->company_name ?? '')) }},
            suppliers: {{ Js::from($suppliers->map(fn($s) => ['id' => $s->id, 'name' => $s->company_name])->values()) }},
            get filteredSups() {
                if (!this.supSearch) return this.suppliers;
                return this.suppliers.filter(s => s.name.toLowerCase().includes(this.supSearch.toLowerCase()));
            },
        };
    }
</script>

<style>
    .dropdown-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .dropdown-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .dropdown-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .dropdown-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 999px;
    }
    .dropdown-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #94a3b8;
    }
</style>
@endsection

@push('scripts')
    <x-popup id="update-modal-{{ $item->id }}" title="Konfirmasi Update"
        message="Apakah Anda yakin ingin menyimpan perubahan data barang ini?"
        formId="update-form-{{ $item->id }}"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />
@endpush