@extends('admin.layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap peminjaman barang</p>
                </div>
                <div class="flex space-x-2">
                    @if($borrowing->status !== 'dikembalikan')
                        <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Konfirmasi pengembalian barang?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Kembalikan
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.borrowings.edit', $borrowing) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.borrowings.index') }}" 
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Peminjaman</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status</label>
                    <div class="mt-1">
                        @if($borrowing->status === 'dipinjam')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Dipinjam</span>
                        @elseif($borrowing->status === 'dikembalikan')
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Dikembalikan</span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                        @endif
                    </div>
                </div>

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
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->tanggal_kembali_aktual->format('d F Y') }}</p>
                    </div>
                @endif

                @if($borrowing->isOverdue() && $borrowing->status !== 'dikembalikan')
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Keterlambatan</label>
                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $borrowing->getDaysOverdue() }} hari</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-500">Dicatat pada</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $borrowing->created_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
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
                    <label class="block text-sm font-medium text-gray-500">Supplier</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $borrowing->item->supplier->nama ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $borrowing->item->category->nama ?? 'N/A' }}</p>
                </div>

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

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Kondisi & Catatan</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                @if($borrowing->kondisi_pinjam)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kondisi Saat Dipinjam</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->kondisi_pinjam }}</p>
                    </div>
                @endif

                @if($borrowing->kondisi_kembali)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kondisi Saat Dikembalikan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->kondisi_kembali }}</p>
                    </div>
                @endif

                @if($borrowing->keterangan)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Keterangan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $borrowing->keterangan }}</p>
                    </div>
                @endif

                @if(!$borrowing->kondisi_pinjam && !$borrowing->kondisi_kembali && !$borrowing->keterangan)
                    <p class="text-sm text-gray-500 italic">Tidak ada catatan kondisi atau keterangan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
