@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Barang Keluar</h1>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap barang yang keluar dari inventory</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route($routePrefix . '.outgoing.edit', $outgoingItem->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route($routePrefix . '.outgoing.index') }}" 
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

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Item Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Barang</h3>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Barang</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $outgoingItem->item->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kode Barang</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $outgoingItem->item->id ? 'ITM-' . str_pad($outgoingItem->item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Supplier</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $outgoingItem->item->supplier->company_name ?? $outgoingItem->item->supplier->nama ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $outgoingItem->item->category->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Harga Satuan</dt>
                            <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($outgoingItem->item->harga, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipe Barang</dt>
                            <dd class="mt-1">
                                @php
                                    $itemType = $outgoingItem->item->type;
                                    if (is_object($itemType) && method_exists($itemType, 'value')) {
                                        $typeValue = $itemType->value;
                                    } else {
                                        $typeValue = $itemType ?? 'stok';
                                    }
                                @endphp
                                @if($typeValue === 'peminjaman')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        PEMINJAMAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        STOK
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Transaction Details --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Transaksi</h3>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Keluar</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $outgoingItem->created_at->format('d F Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jumlah Keluar</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $outgoingItem->jumlah }} unit</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($outgoingItem->status === 'sold') bg-green-100 text-green-800
                                    @elseif($outgoingItem->status === 'damaged') bg-red-100 text-red-800
                                    @elseif($outgoingItem->status === 'expired') bg-orange-100 text-orange-800
                                    @elseif($outgoingItem->status === 'lost') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($outgoingItem->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Nilai</dt>
                            <dd class="mt-1 text-lg font-semibold text-green-600">
                                Rp {{ number_format($outgoingItem->item->harga * $outgoingItem->jumlah, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Item Description --}}
            @if($outgoingItem->item->keterangan)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Deskripsi Barang</h3>
                </div>
                <div class="px-6 py-6">
                    <p class="text-sm text-gray-700">{{ $outgoingItem->item->keterangan }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Item Image --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Gambar Barang</h3>
                </div>
                <div class="px-6 py-6">
                    @if($outgoingItem->item->gambar)
                        <img src="{{ asset($outgoingItem->item->gambar) }}" 
                             alt="{{ $outgoingItem->item->nama }}"
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-center text-sm text-gray-500 mt-2">No image available</p>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik Barang</h3>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Stok Saat Ini</span>
                        <span class="text-sm font-medium text-gray-900">{{ $outgoingItem->item->stok_total ?? 0 }} unit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Masuk</span>
                        <span class="text-sm font-medium text-green-600">12 unit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Keluar</span>
                        <span class="text-sm font-medium text-red-600">2 unit</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <a href="{{ route($routePrefix . '.outgoing.edit', $outgoingItem->id) }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Data
                    </a>
                    
                    <button type="button" 
                            onclick="openModal('del-out-{{ $outgoingItem->id }}')"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <x-popup id="del-out-{{ $outgoingItem->id }}" title="Hapus Barang Keluar"
        message="Are you sure you want to delete this outgoing item? This will restore the stock."
        formId="del-out-form-{{ $outgoingItem->id }}"
        confirmText="Hapus"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal" />
    <form id="del-out-form-{{ $outgoingItem->id }}" action="{{ route($routePrefix . '.outgoing.destroy', $outgoingItem->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endpush
