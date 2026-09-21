@extends('admin.layouts.dashboard')

@section('title', 'Detail Permintaan Peminjaman')

@section('content')
<style>
/* ══ Borrowing Request Show — Dark Mode ══ */

/* Main cards */
html.dark .br-card { background-color: #1e293b !important; border-color: #334155 !important; }

/* Card header gradients → dark tinted */
html.dark .br-header-blue   { background: linear-gradient(to right, rgba(59,130,246,0.15), rgba(99,102,241,0.15)) !important; border-color: #334155 !important; }
html.dark .br-header-green  { background: linear-gradient(to right, rgba(16,185,129,0.15), rgba(5,150,105,0.15)) !important; border-color: #334155 !important; }
html.dark .br-header-purple { background: linear-gradient(to right, rgba(139,92,246,0.15), rgba(236,72,153,0.15)) !important; border-color: #334155 !important; }
html.dark .br-header-amber  { background: linear-gradient(to right, rgba(245,158,11,0.15), rgba(249,115,22,0.15)) !important; border-color: #334155 !important; }
html.dark .br-header-gray   { background: linear-gradient(to right, rgba(75,85,99,0.3), rgba(55,65,81,0.3)) !important; border-color: #334155 !important; }

/* Card header titles & subtitles */
html.dark .br-card-title { color: #e2e8f0 !important; }
html.dark .br-card-sub   { color: #64748b !important; }

/* Row labels (key) and values */
html.dark .br-row-key { color: #64748b !important; }
html.dark .br-row-val { color: #e2e8f0 !important; }
html.dark .br-row-val-sm { color: #94a3b8 !important; }

/* Row dividers */
html.dark .br-row-divider { border-color: #1f2937 !important; }

/* Page header */
html.dark .br-page-title { color: #f1f5f9 !important; }
html.dark .br-page-sub   { color: #64748b !important; }

/* Status badges */
html.dark .br-badge-yellow { background-color: rgba(234,179,8,0.2) !important; color: #fde047 !important; }
html.dark .br-badge-green  { background-color: rgba(16,185,129,0.2) !important; color: #6ee7b7 !important; }
html.dark .br-badge-red    { background-color: rgba(239,68,68,0.2) !important; color: #fca5a5 !important; }
html.dark .br-badge-blue   { background-color: rgba(59,130,246,0.2) !important; color: #93c5fd !important; }
html.dark .br-badge-gray   { background-color: rgba(75,85,99,0.3) !important; color: #9ca3af !important; }

/* Info boxes inside modals */
html.dark .br-info-green { background-color: rgba(16,185,129,0.1) !important; border-color: rgba(16,185,129,0.3) !important; }
html.dark .br-info-green p { color: #6ee7b7 !important; }
html.dark .br-info-blue  { background-color: rgba(59,130,246,0.1) !important; border-color: rgba(59,130,246,0.3) !important; }
html.dark .br-info-blue h4, html.dark .br-info-blue span { color: #93c5fd !important; }
html.dark .br-info-blue .br-row-divider-inner { border-color: rgba(59,130,246,0.2) !important; }
html.dark .br-info-red   { background-color: rgba(239,68,68,0.1) !important; border-color: rgba(239,68,68,0.3) !important; }
html.dark .br-info-red p { color: #fca5a5 !important; }

/* Admin notes box */
html.dark .br-admin-notes { background-color: rgba(37,99,235,0.12) !important; border-color: rgba(37,99,235,0.3) !important; }
html.dark .br-admin-notes h4 { color: #93c5fd !important; }
html.dark .br-admin-notes p  { color: #bfdbfe !important; }

/* Textarea in modals */
html.dark .br-textarea {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}
html.dark .br-textarea::placeholder { color: #475569 !important; }
html.dark .br-textarea:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 2px rgba(59,130,246,0.3) !important; }

/* Modal cancel button */
html.dark .br-btn-cancel { background-color: #1e293b !important; border-color: #334155 !important; color: #94a3b8 !important; }
html.dark .br-btn-cancel:hover { background-color: #334155 !important; }

/* Modal labels */
html.dark .br-modal-label { color: #94a3b8 !important; }
html.dark .br-modal-hint  { color: #64748b !important; }

/* Modal background */
html.dark .br-modal-bg { background-color: #1e293b !important; }

/* Lokasi indigo text */
html.dark .br-loc-text { color: #a5b4fc !important; }
html.dark .br-loc-badge { background-color: rgba(99,102,241,0.2) !important; color: #a5b4fc !important; }

/* Stok badge */
html.dark .br-stok-badge { background-color: rgba(59,130,246,0.2) !important; color: #93c5fd !important; }
</style>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h1 class="br-page-title text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail Permintaan #{{ $request->id }}
                </h1>
                <p class="br-page-sub text-gray-600 mt-2">Informasi lengkap permintaan peminjaman dari {{ $request->user->name }}</p>
            </div>
            <div>
                <a href="{{ route($routePrefix . '.borrowing-requests.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <div class="mb-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        @switch($request->status)
            @case('pending')
                <div class="br-badge-yellow inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Menunggu Persetujuan
                </div>
                @break
            @case('approved')
                <div class="br-badge-green inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Disetujui
                </div>
                @break
            @case('rejected')
                <div class="br-badge-red inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Ditolak
                </div>
                @break
            @case('completed')
                <div class="br-badge-blue inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Selesai
                </div>
                @break
            @case('cancelled')
                <div class="br-badge-gray inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Dibatalkan
                </div>
                @break
        @endswitch
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Request Information Card -->
        <div class="br-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="br-header-blue bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h3 class="br-card-title text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Permintaan
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">ID Permintaan</span>
                        <span class="br-row-val text-sm font-bold text-gray-900">#{{ $request->id }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Pemohon</span>
                        <div class="text-right">
                            <div class="br-row-val text-sm font-bold text-gray-900">{{ $request->user->name }}</div>
                            <div class="br-row-val-sm text-xs text-gray-500">{{ $request->user->email }}</div>
                        </div>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Tanggal Ajukan</span>
                        <span class="br-row-val text-sm text-gray-900">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Jumlah</span>
                        <span class="br-row-val text-sm font-bold text-gray-900">{{ $request->jumlah }} pcs</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Tanggal Pinjam</span>
                        <span class="br-row-val text-sm text-gray-900">{{ $request->tanggal_pinjam->format('d/m/Y') }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Rencana Kembali</span>
                        <span class="br-row-val text-sm text-gray-900">{{ $request->tanggal_kembali_rencana->format('d/m/Y') }}</span>
                    </div>
                    @if($request->approved_by)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Diproses oleh</span>
                        <span class="text-sm text-gray-900">{{ $request->approvedBy->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-600">Tanggal Diproses</span>
                        <span class="text-sm text-gray-900">{{ $request->approved_at ? $request->approved_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Item Information Card -->
        <div class="br-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="br-header-green bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                <h3 class="br-card-title text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Informasi Barang
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Nama Barang</span>
                        <span class="br-row-val text-sm font-bold text-gray-900">{{ $request->item->nama }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Supplier</span>
                        <span class="br-row-val text-sm text-gray-900">{{ $request->item->supplier->nama ?? 'N/A' }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Kategori</span>
                        <span class="br-row-val text-sm text-gray-900">{{ $request->item->category->nama ?? 'N/A' }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Kode Barang</span>
                        <span class="br-row-val text-sm font-bold text-gray-900">{{ $request->item->id ? 'ITM-' . str_pad($request->item->id, 4, '0', STR_PAD_LEFT) : 'N/A' }}</span>
                    </div>
                    <div class="br-row-divider flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Stok Peminjaman</span>
                        <span class="br-stok-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $request->item->stok_peminjaman }} tersedia
                        </span>
                    </div>
                    @if($request->item->location)
                    <div class="flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Lokasi Barang
                        </span>
                        <div class="text-right">
                            <div class="br-loc-text text-sm font-bold text-indigo-700">
                                @if($request->item->location->parent)
                                    {{ $request->item->location->parent->name }} &rsaquo; {{ $request->item->location->name }}
                                @else
                                    {{ $request->item->location->name }}
                                @endif
                            </div>
                            @if($request->item->location->kode)
                            <span class="br-loc-badge inline-block mt-0.5 text-[10px] font-bold font-mono bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded">
                                {{ $request->item->location->kode }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($request->item->keterangan)
                    <div class="pt-2 border-t border-gray-100">
                        <span class="br-row-key text-sm font-medium text-gray-600">Deskripsi</span>
                        <p class="br-row-val text-sm text-gray-900 mt-1">{{ $request->item->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    @if($request->keterangan || $request->kondisi_pinjam)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @if($request->keterangan)
        <div class="br-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="br-header-purple bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                <h3 class="br-card-title text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Keterangan/Tujuan Peminjaman
                </h3>
            </div>
            <div class="p-6">
                <p class="br-row-val text-gray-700 leading-relaxed">{{ $request->keterangan }}</p>
            </div>
        </div>
        @endif

        @if($request->kondisi_pinjam)
        <div class="br-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="br-header-amber bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-100">
                <h3 class="br-card-title text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kondisi Barang Saat Dipinjam
                </h3>
            </div>
            <div class="p-6">
                <p class="br-row-val text-gray-700 leading-relaxed">{{ $request->kondisi_pinjam }}</p>
            </div>
        </div>
        @endif
    </div>
    @endif

    @if($request->admin_notes)
    <div class="mb-8">
        <div class="br-admin-notes bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-blue-900 mb-2">Catatan Admin</h4>
                    <p class="text-blue-800 leading-relaxed">{{ $request->admin_notes }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    @if($request->status === 'pending')
    <div class="br-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="br-header-gray bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-100">
            <h3 class="br-card-title text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                </svg>
                Tindakan Admin
            </h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button type="button" 
                        onclick="openApproveModal({{ $request->id }}, '{{ $request->user->name }}', '{{ $request->item->nama }}')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Setujui Permintaan
                </button>
                <button type="button" 
                        onclick="openRejectModal({{ $request->id }}, '{{ $request->user->name }}', '{{ $request->item->nama }}')"
                        class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Permintaan
                </button>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-lg shadow-2xl">
            <div class="br-modal-bg bg-white rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Setujui Permintaan</h3>
                                <p class="text-green-100 text-sm">Konfirmasi persetujuan peminjaman</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeApproveModal()" class="text-white hover:text-gray-200 transition-colors p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <form id="approveForm" method="POST">
                        @csrf
                        <div class="mb-6">
                            <div class="br-info-green bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-green-800 mb-2">Konfirmasi Persetujuan</p>
                                        <p id="approveText" class="text-sm text-green-700"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stock Information -->
                            <div class="br-info-blue bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    Informasi Stok
                                </h4>
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                        <span class="text-sm text-blue-700">Stok Peminjaman Saat Ini</span>
                                        <span class="text-sm font-bold text-blue-900">{{ $request->item->stok_peminjaman }} pcs</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                        <span class="text-sm text-blue-700">Jumlah Diminta</span>
                                        <span class="text-sm font-bold text-blue-900">{{ $request->jumlah }} pcs</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-sm text-blue-700">Sisa Stok Setelah Disetujui</span>
                                        <span class="text-sm font-bold {{ ($request->item->stok_peminjaman - $request->jumlah) >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $request->item->stok_peminjaman - $request->jumlah }} pcs</span>
                                    </div>
                                </div>
                            </div>
                            
                            <label for="approve_admin_notes" class="br-modal-label block text-sm font-bold text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Catatan Admin <span class="text-red-500 font-bold">*</span>
                            </label>
                            <textarea id="approve_admin_notes" name="admin_notes" rows="4" required
                                      class="br-textarea w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="Tambahkan catatan atau instruksi khusus untuk peminjam..."></textarea>
                            <p class="br-modal-hint text-xs text-gray-500 mt-2">Catatan ini akan dikirimkan kepada peminjam sebagai notifikasi.</p>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeApproveModal()" 
                                    class="br-btn-cancel px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-lg shadow-2xl">
            <div class="br-modal-bg bg-white rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Tolak Permintaan</h3>
                                <p class="text-red-100 text-sm">Berikan alasan penolakan</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition-colors p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="mb-6">
                            <div class="br-info-red bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-800 mb-2">Konfirmasi Penolakan</p>
                                        <p id="rejectText" class="text-sm text-red-700"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <label for="reject_admin_notes" class="br-modal-label block text-sm font-bold text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                Alasan Penolakan <span class="text-red-500 font-bold">*</span>
                            </label>
                            <textarea id="reject_admin_notes" name="admin_notes" rows="4" required
                                      class="br-textarea w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none"
                                      placeholder="Jelaskan dengan detail alasan penolakan permintaan ini..."></textarea>
                            <p class="br-modal-hint text-xs text-gray-500 mt-2">Alasan ini akan dikirimkan kepada peminjam sebagai notifikasi.</p>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRejectModal()" 
                                    class="br-btn-cancel px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @elseif($request->status === 'approved')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tindakan Lanjutan
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-800">Status: Permintaan Disetujui</p>
                        <p class="text-sm text-green-700">User dapat mengambil barang sesuai jadwal yang telah ditentukan.</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-3 flex-wrap">
                {{-- BAP Digital Signature Button --}}
                <a href="{{ route($routePrefix . '.borrowing-requests.bap', $request->id) }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Buka BAP Digital
                </a>
                <button type="button" onclick="openModal('complete-req-{{ $request->id }}')"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Selesaikan Peminjaman
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- BAP Summary Card (for completed/signed requests) --}}
    @if(in_array($request->status, ['completed', 'approved']) && $request->isSigned())
    <div class="br-card mt-6 bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50/80 to-blue-50/80 px-6 py-4 border-b border-indigo-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-indigo-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Berita Acara Peminjaman (BAP) Digital Ditandatangani
            </h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                Terverifikasi
            </span>
        </div>
        <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="space-y-1 text-sm">
                <p class="text-gray-900">No. BAP: <strong class="font-mono text-indigo-700 font-bold">{{ $request->bap_number }}</strong></p>
                <p class="text-gray-600 text-xs">Ditandatangani oleh <strong class="text-gray-800">{{ $request->signed_by_name }}</strong> pada {{ $request->signed_at?->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
            </div>
            <a href="{{ route($routePrefix . '.borrowing-requests.bap', $request->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat & Cetak BAP
            </a>
        </div>
    </div>
    @endif

</div>
</div>

@endsection

@push('scripts')
    @if($request->status === 'approved')
        <x-popup id="complete-req-{{ $request->id }}" title="Selesaikan Peminjaman"
            message="Yakin ingin menyelesaikan peminjaman ini? Stok akan dikembalikan."
            formId="complete-req-form-{{ $request->id }}"
            confirmText="Selesaikan"
            confirmClass="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg"
            cancelText="Batal" />
        <form id="complete-req-form-{{ $request->id }}" action="{{ route($routePrefix . '.borrowing-requests.complete', $request->id) }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endif
<script>
function openApproveModal(id, userName, itemName) {
    const form = document.getElementById('approveForm');
    form.action = `/admin/borrowing-requests/${id}/approve`;
    document.getElementById('approveText').textContent = `Setujui permintaan peminjaman dari ${userName} untuk ${itemName}?`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approve_admin_notes').value = '';
}

function openRejectModal(id, userName, itemName) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/borrowing-requests/${id}/reject`;
    document.getElementById('rejectText').textContent = `Tolak permintaan peminjaman dari ${userName} untuk ${itemName}?`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('reject_admin_notes').value = '';
}
</script>
@endpush