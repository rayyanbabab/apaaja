@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <!-- Summary Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Ringkasan Peminjaman</h2>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Barang</div>
                    <div class="font-medium text-gray-900 mt-1">{{ $borrowing->item->nama }}</div>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ 'ITM-' . str_pad($borrowing->item->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                        @if($borrowing->item->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $borrowing->item->category->nama }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Peminjam</div>
                    <div class="font-medium text-gray-900 mt-1">{{ $borrowing->user->name }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $borrowing->user->email }}</div>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="mt-1">
                        @if($borrowing->status === 'dipinjam')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                        @elseif($borrowing->status === 'dikembalikan')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                        @endif
                    </div>
                    @if($borrowing->isOverdue() && $borrowing->status !== 'dikembalikan')
                        <div class="mt-2 text-xs text-red-600 font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $borrowing->getDaysOverdue() }} hari terlambat
                        </div>
                    @endif
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="text-sm text-gray-500">Tanggal Kembali</div>
                    <div class="font-medium text-gray-900 mt-1">{{ $borrowing->tanggal_kembali_rencana->format('d F Y') }}</div>
                    @if($borrowing->tanggal_kembali_aktual)
                        <div class="text-sm text-green-600 mt-1">
                            Dikembalikan: {{ $borrowing->tanggal_kembali_aktual->format('d F Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap peminjaman barang</p>
                </div>
                <div class="flex space-x-2">
                    @if($borrowing->status !== 'dikembalikan')
                        <button type="button" onclick="openModal('ret-bor-{{ $borrowing->id }}')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kembalikan
                        </button>
                    @endif
                    <a href="{{ route($routePrefix . '.borrowings.edit', $borrowing) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route($routePrefix . '.borrowings.index') }}" 
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
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Detail Peminjaman</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jumlah</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->jumlah }} unit</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Pinjam</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->tanggal_pinjam->format('d F Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Rencana Kembali</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->tanggal_kembali_rencana->format('d F Y') }}</p>
                        </div>
                        
                        @if($borrowing->tanggal_kembali_aktual)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Kembali Aktual</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $borrowing->tanggal_kembali_aktual->format('d F Y H:i') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dicatat pada</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->created_at->format('d F Y H:i:s') }}</p>
                        </div>
                        
                        @if($borrowing->updated_at != $borrowing->created_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Terakhir Diubah</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $borrowing->updated_at->format('d F Y H:i:s') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Kondisi & Catatan</h3>
                </div>
                <div class="px-6 py-4">
                    @if($borrowing->kondisi_pinjam || $borrowing->kondisi_kembali || $borrowing->keterangan)
                        <div class="space-y-4">
                            @if($borrowing->kondisi_pinjam)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kondisi Saat Dipinjam</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $borrowing->kondisi_pinjam }}</p>
                                </div>
                            @endif
                            
                            @if($borrowing->kondisi_kembali)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kondisi Saat Dikembalikan</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $borrowing->kondisi_kembali }}</p>
                                </div>
                            @endif
                            
                            @if($borrowing->keterangan)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Keterangan</label>
                                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $borrowing->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">Tidak ada catatan kondisi atau keterangan.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Barang</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Barang</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $borrowing->item->nama }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kode Barang</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ 'ITM-' . str_pad($borrowing->item->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </p>
                    </div>
                    
                    @if($borrowing->item->category)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $borrowing->item->category->nama }}
                                </span>
                            </p>
                        </div>
                    @endif
                    
                    @if($borrowing->item->supplier)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Supplier</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->item->supplier->nama ?? 'N/A' }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Stok Saat Ini</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->item->stok }} unit</p>
                    </div>
                    
                    @if($borrowing->item->keterangan)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Deskripsi Barang</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->item->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Peminjam</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $borrowing->user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $borrowing->user->role }}</p>
                    </div>
                    
                    @if($borrowing->user->bio)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Bio</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $borrowing->user->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if($borrowing->status !== 'dikembalikan')
        <x-popup id="ret-bor-{{ $borrowing->id }}" title="Konfirmasi Pengembalian"
            message="Konfirmasi pengembalian barang ini?"
            formId="ret-bor-form-{{ $borrowing->id }}"
            confirmText="Kembalikan"
            confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
            cancelText="Batal" />
        <form id="ret-bor-form-{{ $borrowing->id }}" action="{{ route($routePrefix . '.borrowings.return', $borrowing) }}" method="POST" style="display: none;">
            @csrf
            @method('PATCH')
        </form>
    @endif
@endpush