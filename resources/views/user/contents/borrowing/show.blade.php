@extends('user.layouts.dashboard-user')

@section('title', 'Detail Permintaan Peminjaman')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.borrowing.my-requests') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Permintaan Peminjaman</h1>
                <p class="text-gray-600 mt-2">Informasi lengkap tentang permintaan peminjaman Anda</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Request Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h3>
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <svg class="w-4 h-4 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Menunggu Persetujuan
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Disetujui
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Ditolak
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Selesai
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ ucfirst($request->status) }}
                                </span>
                        @endswitch
                    </div>
                </div>
                <div class="p-6">
                    <!-- Item Info -->
                    <div class="flex items-start space-x-4 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $request->item->nama }}</h4>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ $request->jumlah }} unit</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Stok tersedia: {{ $request->item->stok_peminjaman }} unit</span>
                                </div>
                            </div>
                            @if($request->item->keterangan)
                                <p class="mt-3 text-sm text-gray-600">{{ $request->item->keterangan }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">ID Permintaan</span>
                            <p class="mt-1 text-sm font-semibold text-gray-900">#{{ $request->id }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_pinjam->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Kembali</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_kembali_rencana->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_pinjam->diffInDays($request->tanggal_kembali_rencana) }} hari</p>
                        </div>
                        @if($request->status === 'completed' && $request->completed_at)
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->completed_at->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        @endif
                        @if($request->status !== 'pending' && $request->approved_at)
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Persetujuan</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->approved_at->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Item Additional Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-3">Informasi Barang</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</span>
                                <p class="mt-1 text-sm text-gray-900">{{ $request->item->category->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</span>
                                <p class="mt-1 text-sm text-gray-900">{{ $request->item->supplier->nama ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Total</span>
                                <p class="mt-1 text-sm text-gray-900">{{ $request->item->stok_total }} unit</p>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Reguler</span>
                                <p class="mt-1 text-sm text-gray-900">{{ $request->item->stok_reguler }} unit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Timeline Peminjaman
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Request Submitted -->
                            <li>
                                <div class="relative pb-8">
                                    @if($request->approved_at || $request->status !== 'pending')
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Permintaan Diajukan</p>
                                                <p class="mt-0.5 text-xs text-gray-500">Permintaan peminjaman telah dikirim</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->created_at->toIso8601String() }}">{{ $request->created_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                                <p class="text-xs text-gray-400">{{ $request->created_at->format('H:i') }} WIB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Approved/Rejected -->
                            @if($request->approved_at)
                            <li>
                                <div class="relative pb-8">
                                    @if($request->status === 'completed')
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($request->status === 'approved' || $request->status === 'completed')
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    @if($request->status === 'approved' || $request->status === 'completed')
                                                        Permintaan Disetujui
                                                    @else
                                                        Permintaan Ditolak
                                                    @endif
                                                </p>
                                                <p class="mt-0.5 text-xs text-gray-500">Oleh {{ $request->approvedBy->name ?? 'Admin' }}</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->approved_at->toIso8601String() }}">{{ $request->approved_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                                <p class="text-xs text-gray-400">{{ $request->approved_at->format('H:i') }} WIB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <!-- Completed -->
                            @if($request->status === 'completed' && $request->completed_at)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Peminjaman Selesai</p>
                                                <p class="mt-0.5 text-xs text-gray-500">Barang telah dikembalikan</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->completed_at->toIso8601String() }}">{{ $request->completed_at->locale('id')->isoFormat('D MMMM YYYY') }}</time>
                                                <p class="text-xs text-gray-400">{{ $request->completed_at->format('H:i') }} WIB</p>
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

            @if($request->keterangan)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Keterangan/Tujuan Peminjaman</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $request->keterangan }}</p>
                </div>
            </div>
            @endif

            @if($request->kondisi_pinjam)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kondisi Saat Dipinjam</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $request->kondisi_pinjam }}</p>
                </div>
            </div>
            @endif

            @if($request->admin_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Catatan Admin</h3>
                            <p class="mt-1 text-sm text-blue-700">{{ $request->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- User Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Peminjam</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">{{ substr($request->user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $request->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $request->user->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Pinjaman</span>
                            <span class="text-sm font-medium text-gray-900">{{ $request->user->borrowingRequests()->count() }}</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-sm text-gray-500">Pinjaman Aktif</span>
                            <span class="text-sm font-medium text-gray-900">{{ $request->user->borrowingRequests()->whereIn('status', ['approved', 'completed'])->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status Permintaan</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">Status saat ini</span>
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Selesai
                                </span>
                                @break
                        @endswitch
                    </div>
                    
                    @if($request->status === 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Menunggu Persetujuan</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Permintaan Anda sedang dalam proses persetujuan oleh admin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($request->status === 'approved')
                    <div class="bg-green-50 border border-green-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Permintaan Disetujui</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>Permintaan peminjaman Anda telah disetujui. Silakan ambil barang pada tanggal peminjaman.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($request->status === 'rejected')
                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Permintaan Ditolak</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Permintaan peminjaman Anda ditolak. Silakan periksa kembali kebutuhan Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($request->status === 'completed')
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Peminjaman Selesai</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Peminjaman telah selesai. Terima kasih telah mengembalikan barang tepat waktu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tindakan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($request->status === 'pending')
                        <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Batalkan Permintaan
                        </button>
                        @endif
                        
                        <button type="button" onclick="window.print()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak Detail
                        </button>
                        
                        <a href="{{ route('user.borrowing.my-requests') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Lihat Semua Permintaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection