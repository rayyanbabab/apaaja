@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Item Details</h1>
            <p class="text-sm text-gray-400 mt-0.5">Detailed information about this inventory item</p>
        </div>
        <a href="{{ route($routePrefix . '.inventory.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @php
        $typeVal = $item->type?->value ?? $item->getRawOriginal('type') ?? 'stok';
        $isPeminjaman = $typeVal === 'peminjaman';
    @endphp

    {{-- Main Content --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">

            {{-- Left: Image --}}
            <div class="border-b lg:border-b-0 lg:border-r border-gray-100">
                @if($item->gambar)
                    <img src="{{ asset($item->gambar) }}"
                         alt="{{ $item->nama }}"
                         class="w-full h-72 lg:h-full object-cover">
                @else
                    <div class="w-full h-72 lg:h-full flex items-center justify-center bg-gray-50">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-gray-400">No image</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: Details --}}
            <div class="p-6 space-y-5">
                {{-- Name & Badges --}}
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md tracking-wide">
                            {{ $item->kode ?? 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        @if($item->type)
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-md
                                {{ $isPeminjaman ? 'bg-purple-50 text-purple-700' : 'bg-orange-50 text-orange-700' }}">
                                {{ $item->type->label() }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $item->nama }}</h2>
                </div>

                {{-- Stock --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Stock Information</p>
                    <div class="space-y-2">
                        @if($isPeminjaman)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Borrowing Stock</span>
                                <span class="text-base font-semibold text-purple-600">{{ $item->stok_peminjaman ?? 0 }} units</span>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Regular Stock</span>
                                <span class="text-base font-semibold text-green-600">{{ $item->stok_reguler ?? 0 }} units</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                            <span class="text-sm font-semibold text-gray-700">Total Stock</span>
                            @php $total = $item->stok_total ?? $item->stok ?? 0; @endphp
                            <span class="text-lg font-bold
                                {{ $total == 0 ? 'text-red-600' : ($total <= $lowStockThreshold ? 'text-yellow-600' : 'text-gray-900') }}">
                                {{ $total }} units
                                @if($total == 0)
                                    <span class="text-[10px] font-semibold bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md ml-1">OUT</span>
                                @elseif($total <= $lowStockThreshold)
                                    <span class="text-[10px] font-semibold bg-yellow-100 text-yellow-600 px-1.5 py-0.5 rounded-md ml-1">LOW</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Category</p>
                        @if($item->category)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700">
                                {{ $item->category->name }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400 italic">No category</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Supplier</p>
                        @if($item->supplier)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-green-50 text-green-700">
                                {{ $item->supplier->company_name }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400 italic">No supplier</span>
                        @endif
                    </div>
                </div>

                {{-- Location --}}
                @if($item->location)
                <div class="bg-indigo-50 rounded-xl p-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-indigo-400 uppercase tracking-wide">Lokasi Barang</p>
                        <p class="text-sm font-bold text-indigo-700">
                            @if($item->location->parent)
                                <span class="font-normal text-indigo-400">{{ $item->location->parent->name }} ›</span>
                            @endif
                            {{ $item->location->name }}
                            @if($item->location->kode)
                                <span class="ml-1 text-[10px] font-bold font-mono bg-indigo-200 text-indigo-700 px-1.5 py-0.5 rounded-md">{{ $item->location->kode }}</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route($routePrefix . '.locations.show', $item->location) }}"
                       class="ml-auto text-xs font-medium text-indigo-500 hover:text-indigo-700 hover:underline">
                        Lihat Lokasi
                    </a>
                </div>
                @endif

                {{-- Price --}}
                @if(!$isPeminjaman && $item->harga > 0)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Price</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </div>
                @endif

                {{-- Description --}}
                @if($item->keterangan)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Description</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $item->keterangan }}</p>
                    </div>
                @endif

                {{-- Timestamps --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-0.5">Created</p>
                        <p class="text-xs text-gray-600">{{ $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-0.5">Last Updated</p>
                        <p class="text-xs text-gray-600">{{ $item->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
            <button type="button" onclick="openModal('delete-modal-{{ $item->id }}')"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-200 hover:bg-red-50 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
            <div class="flex flex-wrap gap-2">
                {{-- Print Label --}}
                <a href="{{ route($routePrefix . '.inventory.print-label', $item->id) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Label QR
                </a>
                {{-- Quick Scan --}}
                <a href="{{ route('scanner.handle', $item->kode) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-violet-700 bg-violet-50 hover:bg-violet-100 border border-violet-200 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Quick Scan
                </a>
                {{-- Edit --}}
                <a href="{{ route($routePrefix . '.inventory.edit', $item->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Item
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<x-popup id="delete-modal-{{ $item->id }}" title="Delete Item"
    message="Are you sure you want to delete '{{ $item->nama }}'? This action cannot be undone."
    :formId="'delete-form-' . $item->id" confirmText="Delete"
    confirmClass="text-sm link-primary delete-btn-color" cancelText="Cancel" />

<form id="delete-form-{{ $item->id }}" method="POST" action="{{ route($routePrefix . '.inventory.destroy', $item->id) }}">
    @csrf
    @method('DELETE')
</form>

{{-- Maintenance History --}}
@php $maintenanceHistory = $item->maintenances()->with('user')->latest('started_at')->get(); @endphp
@if($maintenanceHistory->count() > 0)
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800">Riwayat Maintenance</h3>
            </div>
            <span class="text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $maintenanceHistory->count() }} record</span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($maintenanceHistory as $mhist)
            <div class="px-6 py-4 flex items-start justify-between gap-4 hover:bg-gray-50 transition-colors">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $mhist->statusColor() }}">
                            {{ $mhist->statusLabel() }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $mhist->jumlah }} unit</span>
                    </div>
                    @if($mhist->catatan)
                        <p class="text-sm text-gray-600 truncate">{{ $mhist->catatan }}</p>
                    @endif
                    @if($mhist->catatan_selesai)
                        <p class="text-xs text-gray-400 mt-0.5 truncate">Hasil: {{ $mhist->catatan_selesai }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">oleh {{ $mhist->user?->name ?? '-' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-500">{{ $mhist->started_at?->setTimezone('Asia/Jakarta')->format('d M Y') }}</p>
                    @if($mhist->completed_at)
                        <p class="text-xs text-gray-400">→ {{ $mhist->completed_at->setTimezone('Asia/Jakarta')->format('d M Y') }}</p>
                    @endif
                    <a href="{{ route($routePrefix . '.maintenance.show', $mhist) }}"
                       class="text-xs text-blue-600 hover:underline mt-1 inline-block">Detail →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
