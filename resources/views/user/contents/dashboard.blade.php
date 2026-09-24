@extends('user.layouts.dashboard-user')

@push('styles')
<style>

    .stat-card-blue   { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); }
    .stat-card-amber  { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
    .stat-card-violet { background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%); }
    .stat-card-emerald{ background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .stat-card-rose   { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
    .stat-card-teal   { background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); }

    .stat-card-icon-wrap {
        background: rgba(255,255,255,0.18);
        border-radius: 9px;
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-card-inner {
        border-radius: 1.15rem;
        padding: 0.95rem 1.1rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s cubic-bezier(0.22,1,0.36,1), box-shadow 0.18s;
        box-shadow: 0 4px 16px -4px rgba(0,0,0,0.16), inset 0 1px 0 rgba(255,255,255,0.16);
        text-decoration: none;
        display: block;
    }
    .stat-card-inner:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px -4px rgba(0,0,0,0.22), inset 0 1px 0 rgba(255,255,255,0.20);
    }
    .stat-card-blob {
        position: absolute;
        right: -16px; top: -16px;
        width: 76px; height: 76px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.10);
        pointer-events: none;
    }
    .stat-card-blob2 {
        position: absolute;
        right: 16px; bottom: -20px;
        width: 54px; height: 54px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    .qa-card {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        padding: 1rem 1.1rem;
        background: #fafafa;
        border: 1.5px solid #f3f4f6;
        border-radius: 1.15rem;
        text-decoration: none;
        transition: background 0.15s, box-shadow 0.15s, border-color 0.15s, transform 0.15s;
    }
    .qa-card:hover {
        background: #fff;
        border-color: #e5e7eb;
        box-shadow: 0 6px 18px -4px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .qa-icon-wrap {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    html.dark .qa-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark .qa-card:hover {
        background: #334155 !important;
        border-color: #475569 !important;
        box-shadow: 0 6px 20px -4px rgba(0,0,0,0.4) !important;
    }
    html.dark .qa-card .text-gray-900 { color: #f1f5f9 !important; }
    html.dark .qa-card .text-gray-500 { color: #94a3b8 !important; }
</style>
@endpush

@section('user')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-full px-3 py-1">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-pulse"></span>
                    Portal Peminjaman
                </span>
                <span class="text-xs text-gray-400 dark:text-slate-400">
                    {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y, H:i') }} WIB
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Selamat Datang, {{ $user->name }} 👋</h1>
            <p class="text-sm text-gray-400 dark:text-slate-400 mt-1">Pantau status peminjaman, keranjang alat, dan pengajuan barang kamu secara langsung</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            <a href="{{ route('user.workshop.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                Denah Bengkel 2D
            </a>
            <a href="{{ route('user.borrowing.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Pinjam Alat Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-3.5">

        <a href="{{ route('user.borrowing.my-requests') }}" class="stat-card-inner stat-card-blue">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Unit</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['total_borrowed'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Total Dipinjam</p>
            <p class="text-[10px] text-white/60 mt-0.5">unit disetujui</p>
        </a>

        <a href="{{ route('user.borrowing.my-requests') }}" class="stat-card-inner stat-card-amber">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Aktif</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['unreturned_items'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Belum Kembali</p>
            <p class="text-[10px] text-white/60 mt-0.5">harus dikembalikan</p>
        </a>

        <a href="{{ route('user.borrowing.my-requests') }}" class="stat-card-inner stat-card-violet">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Pending</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['pending_requests'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Menunggu Acc</p>
            <p class="text-[10px] text-white/60 mt-0.5">dalam antrean</p>
        </a>

        <a href="{{ route('user.borrowing.my-requests') }}" class="stat-card-inner stat-card-emerald">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Total</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['total_requests'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Total Pengajuan</p>
            <p class="text-[10px] text-white/60 mt-0.5">semua transaksi</p>
        </a>

        <a href="{{ route('user.borrowing.my-requests') }}" class="stat-card-inner stat-card-rose">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Batal</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['rejected_requests'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Ditolak</p>
            <p class="text-[10px] text-white/60 mt-0.5">tidak disetujui</p>
        </a>

        <a href="{{ route('user.borrowing.index') }}" class="stat-card-inner stat-card-teal">
            <div class="stat-card-blob"></div><div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <span class="text-[9px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-1.5 py-0.5 rounded-full">Ready</span>
            </div>
            <p class="text-2xl font-extrabold text-white tracking-tight">{{ $borrowingStats['available_items'] }}</p>
            <p class="text-[11px] font-semibold text-white/80 mt-1 truncate">Jenis Alat Ready</p>
            <p class="text-[10px] text-white/60 mt-0.5">siap dipinjam</p>
        </a>

    </div>

    <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-5 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Aksi Cepat Peminjaman</h2>
                <p class="text-xs text-gray-400 dark:text-slate-400">Pintasan praktis untuk kebutuhan aktivitas kerja kamu</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Navigasi Utama</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

            <a href="{{ route('user.borrowing.index') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Katalog Alat</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Cari &amp; pinjam barang</p>
                </div>
            </a>

            <a href="{{ route('user.workshop.index') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">Denah Bengkel</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Visual 2D lokasi rak</p>
                </div>
            </a>

            <a href="{{ route('user.borrowing.cart') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors">Keranjang</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Item siap checkout</p>
                </div>
            </a>

            <a href="{{ route('user.borrowing.my-requests') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-amber-600 transition-colors">Status Pinjam</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Lacak persetujuan</p>
                </div>
            </a>

            <a href="{{ route('user.procurement.index') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-rose-600 transition-colors">Pengadaan</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Request alat baru</p>
                </div>
            </a>

            <a href="{{ route('user.profile.index') }}" class="qa-card group">
                <div class="qa-icon-wrap bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-purple-600 transition-colors">Profil Saya</p>
                    <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate mt-0.5">Pengaturan akun</p>
                </div>
            </a>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Aktivitas Terakhir</h2>
                </div>
                <a href="{{ route('user.borrowing.my-requests') }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors">
                    Lihat semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="p-5 flex-1 flex flex-col justify-center">
                @if($recent_activities->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($recent_activities as $activity)
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-gray-50/75 dark:bg-slate-800/50 border border-gray-100/80 dark:border-slate-700/50 hover:bg-gray-100/80 dark:hover:bg-slate-800 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-2 ring-white dark:ring-slate-700 shadow-sm"
                                         src="{{ $activity['user_photo'] }}"
                                         alt="{{ $activity['user_name'] }}"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($activity['user_name']) }}&color=4F76F6&background=EEF2FF&size=40'">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $activity['action'] }}</p>
                                        <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-0.5">{{ $activity['timestamp'] }}</p>
                                    </div>
                                </div>
                                <span class="ml-2 flex-shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $activity['status_class'] }}">
                                    {{ $activity['status'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 mb-3 text-blue-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 dark:text-slate-200">Belum Ada Aktivitas</h3>
                        <p class="mt-1 text-[11px] text-gray-400 dark:text-slate-400">Mulai dengan mengajukan pinjaman alat pertamamu.</p>
                        <a href="{{ route('user.borrowing.index') }}" class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Mulai Pinjam Alat
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Pemberitahuan</h2>
                </div>
                @if($notifications->count() > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">
                        {{ $notifications->count() }} terbaru
                    </span>
                @endif
            </div>

            <div class="p-5 flex-1 flex flex-col justify-center">
                @if($notifications->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($notifications as $notification)
                            @php
                                $notifData = $notification->data;
                                $notifMsg  = $notifData['message'] ?? 'Notification';
                                if (($notifData['type'] ?? '') === 'overdue' && isset($notifData['due_date'])) {
                                    try {
                                        $nd      = \Carbon\Carbon::parse($notifData['due_date']);
                                        $ndFixed = max(1, (int) ceil($nd->diffInDays(now(), true)));
                                        $notifMsg = preg_replace('/terlambat\s+[\-\d\.]+\s+hari/i', 'terlambat ' . $ndFixed . ' hari', $notifMsg);
                                    } catch (\Throwable $e) {}
                                }
                            @endphp
                            <div class="p-3.5 rounded-xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/40">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5 flex items-center justify-center w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-slate-200 leading-relaxed">{{ $notifMsg }}</p>
                                        <p class="text-[10px] text-gray-400 dark:text-slate-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if(isset($notification->data['admin_notes']))
                                            <div class="mt-2 text-[11px] text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700">
                                                <strong class="font-bold text-amber-600 dark:text-amber-400">Catatan Admin:</strong> {{ $notification->data['admin_notes'] }}
                                            </div>
                                        @endif
                                        <a href="{{ $notification->data['url'] ?? '#' }}"
                                           class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 transition-colors">
                                            Buka Rincian
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gray-100 dark:bg-slate-800 mb-3 text-gray-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-xs font-bold text-gray-800 dark:text-slate-200">Tidak Ada Notifikasi Baru</h3>
                        <p class="mt-1 text-[11px] text-gray-400 dark:text-slate-400">Semua notifikasi pengajuanmu sudah dibaca.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
