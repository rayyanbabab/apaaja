@extends('user.layouts.dashboard-user')

@section('title', 'Peminjaman Barang')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Peminjaman Barang</h1>
                <p class="text-gray-600 mt-2">Pilih barang yang ingin Anda pinjam</p>
            </div>
            <a href="{{ route('user.borrowing.my-requests') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Status peminjaman</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            @if($items->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $item)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                            {{-- Image Section --}}
                            <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                                @if ($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}"
                                        class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gray-100">
                                        <div class="rounded-full border-2 border-gray-200 p-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                                stroke-linecap="round" stroke-linejoin="round" class="text-gray-300">
                                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                                <circle cx="9" cy="9" r="2" />
                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Stock Badge --}}
                                <div class="absolute top-3 right-3">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full shadow-sm">
                                        {{ $item->stok_peminjaman }} tersedia
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $item->nama }}</h3>
                                </div>
                                
                                
                                @if($item->keterangan)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($item->keterangan, 100) }}</p>
                                @endif
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('user.borrowing.show-item', $item->id) }}" 
                                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-center inline-block transition-colors duration-200">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('user.borrowing.create', $item->id) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-center inline-block transition-colors duration-200">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Pinjam
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada barang tersedia</h3>
                    <p class="text-gray-500">Saat ini tidak ada barang yang tersedia untuk dipinjam. Silakan cek kembali nanti atau hubungi admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
