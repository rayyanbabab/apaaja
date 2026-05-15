@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route($routePrefix . '.locations.index') }}" class="hover:text-blue-600 transition-colors">Manajemen Lokasi</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-600 font-medium">{{ $location->name }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Detail Lokasi</h1>
            <p class="text-sm text-gray-400 mt-0.5">Informasi dan daftar barang di lokasi ini</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route($routePrefix . '.locations.edit', $location) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Lokasi
            </a>
            <a href="{{ route($routePrefix . '.locations.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Location Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($location->kode)
                            <span class="text-xs font-bold font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md">{{ $location->kode }}</span>
                        @endif
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $location->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $location->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $location->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($location->parent)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                Induk: {{ $location->parent->name }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                Lokasi Utama
                            </span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $location->name }}</h2>
                    @if($location->deskripsi)
                        <p class="text-sm text-gray-500 mt-1.5">{{ $location->deskripsi }}</p>
                    @endif
                </div>

                {{-- Quick stats --}}
                <div class="hidden sm:flex items-center gap-4 flex-shrink-0">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $items->total() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Total Barang</p>
                    </div>
                    @if($location->children->count())
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ $location->children->count() }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">Sub-Lokasi</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sub-locations --}}
            @if($location->children->count())
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Sub-Lokasi ({{ $location->children->count() }})</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($location->children as $child)
                            <a href="{{ route($routePrefix . '.locations.show', $child) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors group">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $child->name }}
                                <span class="bg-indigo-200 text-indigo-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $child->items->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Barang di Lokasi Ini
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Total {{ $items->total() }} barang tersimpan di lokasi ini</p>
            </div>
        </div>

        @if($items->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Barang</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Kategori</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Stok</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if($item->gambar)
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}"
                                                 class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                        @else
                                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->nama }}</p>
                                            <p class="text-xs font-mono text-blue-600">{{ $item->kode ?? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 hidden sm:table-cell">
                                    @if($item->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                            {{ $item->category->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-300 italic">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-bold text-sm {{ $item->stok_total == 0 ? 'text-red-500' : 'text-gray-900' }}">
                                        {{ $item->stok_total }}
                                    </span>
                                    <span class="text-xs text-gray-400 ml-0.5">unit</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route($routePrefix . '.inventory.show', $item->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $items->links() }}
                </div>
            @else
                <div class="px-5 py-3 border-t border-gray-50">
                    <p class="text-xs text-gray-400">Menampilkan semua {{ $items->total() }} barang</p>
                </div>
            @endif
        @else
            <div class="py-14 text-center">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-600 mb-1">Belum ada barang</p>
                <p class="text-xs text-gray-400">Assign lokasi ini saat menambah atau mengedit barang inventaris.</p>
            </div>
        @endif
    </div>

</div>
@endsection
