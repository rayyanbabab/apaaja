@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Barang Masuk #{{ $incomingItem->id }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap barang masuk</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route($routePrefix . '.incoming.edit', $incomingItem) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route($routePrefix . '.incoming.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Item Information --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Barang</h3>
                </div>
                <div class="px-6 py-6">
                    @php
                        $itemType = $incomingItem->item->type;
                        if (is_object($itemType) && method_exists($itemType, 'value')) {
                            $typeValue = $itemType->value;
                        } else {
                            $typeValue = $itemType ?? 'stok';
                        }
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Barang</label>
                            <div class="flex items-center space-x-2">
                                <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->item->nama }}</p>
                                @if($typeValue === 'peminjaman')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        PEMINJAMAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        STOK
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kode Barang</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->item->id ? 'ITM-' . str_pad($incomingItem->item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->item->supplier->company_name ?? $incomingItem->item->supplier->nama ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->item->category->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Harga Satuan</label>
                            <p class="text-lg font-semibold text-green-600">Rp {{ number_format($incomingItem->item->harga, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Stok Saat Ini</label>
                            @if($typeValue === 'peminjaman')
                                <p class="text-lg font-semibold text-purple-600">{{ $incomingItem->item->stok_peminjaman ?? 0 }} unit (Peminjaman)</p>
                            @else
                                <p class="text-lg font-semibold text-blue-600">{{ $incomingItem->item->stok_reguler ?? 0 }} unit (Reguler)</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-1">Total: {{ $incomingItem->item->stok_total ?? 0 }} unit</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction Details --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Transaksi</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Masuk</label>
                            <p class="text-2xl font-bold text-green-600">{{ $incomingItem->jumlah }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Nilai</label>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($incomingItem->jumlah * $incomingItem->item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Masuk</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->created_at->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Waktu</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $incomingItem->created_at->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($incomingItem->keterangan)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Keterangan</h3>
                </div>
                <div class="px-6 py-6">
                    <p class="text-gray-700 leading-relaxed">{{ $incomingItem->keterangan }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Stats --}}
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-sm text-white">
                <div class="px-6 py-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium">Barang Masuk</h3>
                            <p class="text-green-100">ID: #{{ $incomingItem->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <a href="{{ route($routePrefix . '.incoming.edit', $incomingItem) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Data
                    </a>
                    
                    <button type="button" 
                            onclick="openModal('del-inc-{{ $incomingItem->id }}')"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Data
                    </button>
                </div>
            </div>

            {{-- Related Item Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Info Barang</h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Stok Minimum</span>
                        <span class="text-sm font-medium text-gray-900">{{ $incomingItem->item->stok_minimum ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Status Stok</span>
                        @if(($incomingItem->item->stok_total ?? 0) <= ($incomingItem->item->stok_minimum ?? 0))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Stok Rendah
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Stok Aman
                            </span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Terakhir Update</span>
                        <span class="text-sm font-medium text-gray-900">{{ $incomingItem->item->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Timeline</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Barang masuk dicatat
                                                    <time datetime="{{ $incomingItem->created_at->toISOString() }}" class="font-medium text-gray-900">
                                                        {{ $incomingItem->created_at->format('d F Y, H:i') }}
                                                    </time>
                                                </p>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-700">
                                                <p>{{ $incomingItem->jumlah }} unit {{ $incomingItem->item->nama }} ditambahkan ke inventory</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @if($incomingItem->updated_at != $incomingItem->created_at)
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Data diperbarui
                                                    <time datetime="{{ $incomingItem->updated_at->toISOString() }}" class="font-medium text-gray-900">
                                                        {{ $incomingItem->updated_at->format('d F Y, H:i') }}
                                                    </time>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <x-popup id="del-inc-{{ $incomingItem->id }}" title="Hapus Barang Masuk"
        message="Apakah Anda yakin ingin menghapus data ini? Stok barang akan dikurangi sesuai jumlah yang masuk."
        formId="del-inc-form-{{ $incomingItem->id }}"
        confirmText="Hapus"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal" />
    <form id="del-inc-form-{{ $incomingItem->id }}" action="{{ route($routePrefix . '.incoming.destroy', $incomingItem) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endpush
