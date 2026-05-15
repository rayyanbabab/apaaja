@extends('admin.layouts.dashboard')

@push('styles')
<style>
    /* ── Stat Card Gradients ── */
    .stat-card-blue   { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); }
    .stat-card-emerald{ background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .stat-card-rose   { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
    .stat-card-violet { background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%); }

    .stat-card-icon-wrap {
        background: rgba(255,255,255,0.18);
        border-radius: 9px;
        width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-card-inner {
        border-radius: 1rem;
        padding: 0.85rem 1rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s cubic-bezier(0.22,1,0.36,1), box-shadow 0.18s;
        box-shadow: 0 3px 14px -4px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.16);
        text-decoration: none;
        display: block;
    }
    .stat-card-inner:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px -4px rgba(0,0,0,0.24), inset 0 1px 0 rgba(255,255,255,0.20);
    }
    .stat-card-blob {
        position: absolute;
        right: -14px; top: -14px;
        width: 72px; height: 72px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.09);
        pointer-events: none;
    }
    .stat-card-blob2 {
        position: absolute;
        right: 14px; bottom: -18px;
        width: 52px; height: 52px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.06);
        pointer-events: none;
    }

    /* ── Section Headers ── */
    .section-title { font-size: 0.9rem; font-weight: 700; color: #111827; }
    .section-sub   { font-size: 0.72rem; color: #9ca3af; margin-top: 1px; }

    /* ── Secondary stat mini cards ── */
    .mini-stat {
        background: #fff;
        border-radius: 1rem;
        border: 1.5px solid #f3f4f6;
        padding: 0.9rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: box-shadow 0.18s, border-color 0.18s, transform 0.18s;
    }
    .mini-stat:hover {
        box-shadow: 0 4px 16px -4px rgba(0,0,0,0.1);
        border-color: #e5e7eb;
        transform: translateY(-1px);
    }

    /* ── Quick Action Cards ── */
    .qa-card {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        padding: 1rem;
        background: #fafafa;
        border: 1.5px solid #f3f4f6;
        border-radius: 1rem;
        text-decoration: none;
        transition: background 0.15s, box-shadow 0.15s, border-color 0.15s, transform 0.15s;
    }
    .qa-card:hover {
        background: #fff;
        border-color: #e5e7eb;
        box-shadow: 0 4px 14px -4px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .qa-icon-wrap {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── Chart Card ── */
    .chart-card {
        background: #fff;
        border-radius: 1.25rem;
        border: 1.5px solid #f3f4f6;
        box-shadow: 0 2px 16px -4px rgba(0,0,0,0.07);
        padding: 1.4rem 1.6rem;
    }

    /* ── Chart Legend Dot ── */
    .chart-legend-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    /* ── Activity Table ── */
    .activity-table th {
        font-size: 0.67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #9ca3af;
        padding: 0.7rem 1.25rem;
        background: #f9fafb;
        border-bottom: 1.5px solid #f3f4f6;
    }
    .activity-table td {
        padding: 0.85rem 1.25rem;
        font-size: 0.82rem;
        border-bottom: 1px solid #f9fafb;
    }
    .activity-table tr:last-child td { border-bottom: none; }
    .activity-table tr:hover td { background: #f9fafb; }

    /* ── Pending Alert Banner ── */
    .pending-banner {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        border-radius: 1rem;
        background: linear-gradient(135deg, #fffbeb 0%, #fff7ed 100%);
        border: 1.5px solid #fde68a;
        padding: 1rem 1.25rem;
    }
    @media (min-width: 640px) {
        .pending-banner { flex-direction: row; align-items: center; }
    }

    /* ── Chart Period Buttons ── */
    .period-btn {
        padding: 0.3rem 0.85rem;
        font-size: 0.71rem;
        font-weight: 600;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        letter-spacing: 0.01em;
    }
    .period-btn.active {
        background: #2563eb;
        color: #fff;
        box-shadow: 0 2px 8px -2px rgba(37,99,235,0.4);
    }
    .period-btn:not(.active) { background: transparent; color: #6b7280; }
    .period-btn:not(.active):hover { background: #e5e7eb; color: #374151; }

    /* ══════════════════════════════════════════
       DARK MODE — Dashboard Specific Classes
    ══════════════════════════════════════════ */
    html.dark .section-title { color: #f1f5f9 !important; }
    html.dark .section-sub   { color: #64748b !important; }

    /* Mini stat cards */
    html.dark .mini-stat {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 16px -4px rgba(0,0,0,0.3) !important;
    }
    html.dark .mini-stat:hover {
        background: #334155 !important;
        border-color: #475569 !important;
    }
    html.dark .mini-stat .text-gray-900 { color: #f1f5f9 !important; }
    html.dark .mini-stat .text-gray-400 { color: #64748b !important; }

    /* Icon bg in mini stat */
    html.dark .mini-stat .bg-blue-50   { background-color: rgba(37,99,235,0.18) !important; }
    html.dark .mini-stat .bg-amber-50  { background-color: rgba(245,158,11,0.18) !important; }
    html.dark .mini-stat .bg-pink-50   { background-color: rgba(236,72,153,0.18) !important; }
    html.dark .mini-stat .bg-emerald-50 { background-color: rgba(5,150,105,0.18) !important; }

    /* Chart cards */
    html.dark .chart-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 2px 16px -4px rgba(0,0,0,0.3) !important;
    }
    html.dark .chart-card .border-b,
    html.dark .chart-card .border-gray-100 { border-color: #334155 !important; }
    html.dark .chart-card .text-gray-900 { color: #f1f5f9 !important; }
    html.dark .chart-card .text-gray-800 { color: #e2e8f0 !important; }
    html.dark .chart-card .text-gray-600 { color: #94a3b8 !important; }
    html.dark .chart-card .text-gray-500 { color: #94a3b8 !important; }
    html.dark .chart-card .text-gray-400 { color: #64748b !important; }

    /* Period buttons dark */
    html.dark .bg-gray-100 { background-color: #334155 !important; }
    html.dark .period-btn:not(.active) { background: transparent; color: #94a3b8 !important; }
    html.dark .period-btn:not(.active):hover { background: #475569 !important; color: #f1f5f9 !important; }

    /* Progress bar bg in legend */
    html.dark .h-1\.5.rounded-full { background-color: rgba(255,255,255,0.06) !important; }

    /* Quick action cards */
    html.dark .qa-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark .qa-card:hover {
        background: #334155 !important;
        border-color: #475569 !important;
        box-shadow: 0 4px 14px -4px rgba(0,0,0,0.4) !important;
    }
    html.dark .qa-card .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .qa-card .text-gray-400 { color: #64748b !important; }

    /* Icon bg in quick actions */
    html.dark .qa-icon-wrap.bg-blue-50    { background-color: rgba(37,99,235,0.18) !important; }
    html.dark .qa-icon-wrap.bg-emerald-50 { background-color: rgba(5,150,105,0.18) !important; }
    html.dark .qa-icon-wrap.bg-rose-50    { background-color: rgba(244,63,94,0.18) !important; }
    html.dark .qa-icon-wrap.bg-amber-50   { background-color: rgba(245,158,11,0.18) !important; }
    html.dark .qa-icon-wrap.bg-indigo-50  { background-color: rgba(99,102,241,0.18) !important; }
    html.dark .qa-icon-wrap.bg-purple-50  { background-color: rgba(139,92,246,0.18) !important; }
    html.dark .qa-icon-wrap.bg-slate-50   { background-color: rgba(100,116,139,0.18) !important; }
    html.dark .qa-icon-wrap.bg-pink-50    { background-color: rgba(236,72,153,0.18) !important; }
    html.dark .qa-icon-wrap.bg-teal-50    { background-color: rgba(20,184,166,0.18) !important; }

    /* Activity table */
    html.dark .activity-table th {
        color: #64748b !important;
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    html.dark .activity-table td { border-color: #334155 !important; }
    html.dark .activity-table tr:hover td { background: rgba(255,255,255,0.04) !important; }
    html.dark .activity-table .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .activity-table .text-gray-500 { color: #94a3b8 !important; }
    html.dark .activity-table .text-gray-400 { color: #64748b !important; }

    /* Status badges in activity table */
    html.dark .activity-table .bg-emerald-50  { background-color: rgba(5,150,105,0.18) !important; }
    html.dark .activity-table .text-emerald-700 { color: #6ee7b7 !important; }
    html.dark .activity-table .border-emerald-100 { border-color: rgba(5,150,105,0.2) !important; }
    html.dark .activity-table .bg-rose-50     { background-color: rgba(244,63,94,0.18) !important; }
    html.dark .activity-table .text-rose-600  { color: #fca5a5 !important; }
    html.dark .activity-table .border-rose-100 { border-color: rgba(244,63,94,0.2) !important; }

    /* Pending banner */
    html.dark .pending-banner {
        background: linear-gradient(135deg, rgba(120,53,15,0.25) 0%, rgba(120,53,15,0.15) 100%) !important;
        border-color: rgba(245,158,11,0.3) !important;
    }
    html.dark .pending-banner .text-amber-900 { color: #fde68a !important; }
    html.dark .pending-banner .text-amber-700 { color: #fbbf24 !important; }
    html.dark .pending-banner .bg-white       { background-color: rgba(255,255,255,0.08) !important; }
    html.dark .pending-banner .border-amber-200 { border-color: rgba(245,158,11,0.25) !important; }
    html.dark .pending-banner .bg-amber-100   { background-color: rgba(245,158,11,0.18) !important; }
    html.dark .pending-banner .text-amber-600 { color: #fbbf24 !important; }

    /* Page header badges */
    html.dark .bg-emerald-50.border.border-emerald-200 {
        background-color: rgba(5,150,105,0.18) !important;
        border-color: rgba(5,150,105,0.25) !important;
    }
    html.dark .text-emerald-700 { color: #6ee7b7 !important; }

    /* "bg-white rounded-2xl" containers (Quick Actions wrapper, Activities wrapper) */
    html.dark .bg-white.rounded-2xl {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark .bg-white.rounded-2xl .border-b { border-color: #334155 !important; }
    html.dark .bg-white.rounded-2xl .text-gray-800 { color: #f1f5f9 !important; }
    html.dark .bg-white.rounded-2xl .text-gray-400 { color: #64748b !important; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- ── Pending Requests Banner ── --}}
    @if($pendingBorrowingRequests->count() > 0)
        <div class="pending-banner">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-amber-600" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-900">
                        {{ $pendingBorrowingRequests->count() }} permintaan peminjaman menunggu persetujuan
                    </p>
                    <div class="flex flex-wrap gap-1.5 mt-1">
                        @foreach($pendingBorrowingRequests->take(3) as $req)
                            <span class="text-xs text-amber-700 bg-white border border-amber-200 rounded-full px-2.5 py-0.5 font-medium shadow-sm">
                                {{ $req->user->name }} · {{ Str::limit($req->item->nama, 16) }}
                            </span>
                        @endforeach
                        @if($pendingBorrowingRequests->count() > 3)
                            <span class="text-xs text-amber-600 bg-amber-100 border border-amber-200 rounded-full px-2.5 py-0.5 font-semibold">
                                +{{ $pendingBorrowingRequests->count() - 3 }} lainnya
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ route($routePrefix . '.borrowing-requests.index') }}"
               class="flex-shrink-0 inline-flex items-center gap-1.5 px-5 py-2 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white text-xs font-bold rounded-xl transition-colors shadow-sm">
                Tinjau Sekarang
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Sistem Online
                </span>
                <span class="text-xs text-gray-400">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin</h1>
            <p class="text-sm text-gray-400 mt-1">Ringkasan sistem manajemen inventaris secara real-time</p>
        </div>
        <a href="{{ route($routePrefix . '.insights.index') }}"
           class="self-start sm:self-auto inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Lihat Insights
        </a>
    </div>

    {{-- ── Primary Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

        {{-- Total Barang --}}
        <a href="{{ route($routePrefix . '.inventory.index') }}" class="stat-card-inner stat-card-blue">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="stat-card-icon-wrap">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white/40">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-white leading-none">{{ number_format($jumlahJenisBarang) }}</p>
            <p class="text-[11px] font-medium text-white/70 mt-1">Total Jenis Barang</p>
        </a>

        {{-- Total Masuk --}}
        <a href="{{ route($routePrefix . '.incoming.index') }}" class="stat-card-inner stat-card-emerald">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="stat-card-icon-wrap">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white/40">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-white leading-none">{{ number_format($totalMasuk) }}</p>
            <p class="text-[11px] font-medium text-white/70 mt-1">Total Masuk</p>
        </a>

        {{-- Total Keluar --}}
        <a href="{{ route($routePrefix . '.outgoing.index') }}" class="stat-card-inner stat-card-rose">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="stat-card-icon-wrap">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                    </svg>
                </div>
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white/40">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-white leading-none">{{ number_format($totalKeluar) }}</p>
            <p class="text-[11px] font-medium text-white/70 mt-1">Total Keluar</p>
        </a>

        {{-- Estimasi Nilai Stok --}}
        <div class="stat-card-inner stat-card-violet" style="cursor:default;">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2.5">
                <div class="stat-card-icon-wrap">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[9px] font-bold text-white/60 bg-white/10 border border-white/20 px-1.5 py-0.5 rounded">IDR</span>
            </div>
            @php
                $idrLen = strlen(number_format($totalValue, 0, ',', '.'));
                $idrSize = $idrLen > 10 ? 'text-base sm:text-lg' : ($idrLen > 7 ? 'text-lg sm:text-xl' : 'text-xl sm:text-2xl');
            @endphp
            <p class="{{ $idrSize }} font-extrabold text-white leading-none" style="overflow-wrap:anywhere;">{{ number_format($totalValue, 0, ',', '.') }}</p>
            <p class="text-[11px] font-medium text-white/70 mt-1">Estimasi Nilai Stok</p>
        </div>

    </div>

    {{-- ── Secondary Stats ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @php
            $secStats = [
                ['href' => route($routePrefix . '.content.listusers'),   'label' => 'Pengguna',    'value' => $userCount,            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'iconBg' => 'bg-blue-50',   'iconColor' => 'text-blue-500'],
                ['href' => route($routePrefix . '.suppliers.index'),      'label' => 'Suppliers',   'value' => $supplierCount,        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'iconBg' => 'bg-amber-50',  'iconColor' => 'text-amber-500'],
                ['href' => route($routePrefix . '.categories.index'),     'label' => 'Kategori',    'value' => $categoryCount,        'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'iconBg' => 'bg-pink-50',   'iconColor' => 'text-pink-500'],
                ['href' => route($routePrefix . '.borrowings.index'),     'label' => 'Peminjaman',    'value' => $borrowableItemsCount, 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'iconBg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-500'],
            ];
        @endphp
        @foreach($secStats as $stat)
            <a href="{{ $stat['href'] }}" class="mini-stat">
                <div class="w-9 h-9 {{ $stat['iconBg'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 {{ $stat['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-base font-extrabold text-gray-900">{{ number_format($stat['value']) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $stat['label'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- ── Charts ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Area Chart --}}
        <div class="lg:col-span-2 chart-card">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <h3 class="section-title">Pergerakan Inventaris</h3>
                    <p class="section-sub">Barang masuk &amp; keluar per periode</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Inline legend --}}
                    <div class="hidden sm:flex items-center gap-3">
                        <span class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                            <span class="chart-legend-dot" style="background:#10B981;"></span>Masuk
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                            <span class="chart-legend-dot" style="background:#F43F5E;"></span>Keluar
                        </span>
                    </div>
                    {{-- Period toggle --}}
                    <div class="flex gap-0.5 bg-gray-100 p-1 rounded-lg">
                        @foreach(['weekly' => 'Minggu', 'monthly' => 'Bulan', 'yearly' => 'Tahun'] as $key => $label)
                            <button onclick="updateChart('{{ $key }}')"
                                class="period-btn {{ $range === $key ? 'active' : '' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div id="chart" class="-mx-2" style="height:256px;"></div>
        </div>

        {{-- Donut Chart --}}
        <div class="chart-card flex flex-col">
            {{-- Header --}}
            <div class="mb-4">
                <h3 class="section-title">Distribusi Data</h3>
                <p class="section-sub">Proporsi tiap entitas di sistem</p>
            </div>

            @php
                $pieTotal = $jumlahJenisBarang + $userCount + $supplierCount + $categoryCount;
                $legendItems = [
                    ['color' => '#3B82F6', 'bg' => '#EFF6FF', 'label' => 'Items',      'value' => $jumlahJenisBarang, 'pct' => $pieTotal > 0 ? round($jumlahJenisBarang / $pieTotal * 100) : 0],
                    ['color' => '#10B981', 'bg' => '#ECFDF5', 'label' => 'Users',      'value' => $userCount,         'pct' => $pieTotal > 0 ? round($userCount / $pieTotal * 100) : 0],
                    ['color' => '#F59E0B', 'bg' => '#FFFBEB', 'label' => 'Suppliers',  'value' => $supplierCount,     'pct' => $pieTotal > 0 ? round($supplierCount / $pieTotal * 100) : 0],
                    ['color' => '#8B5CF6', 'bg' => '#F5F3FF', 'label' => 'Categories', 'value' => $categoryCount,     'pct' => $pieTotal > 0 ? round($categoryCount / $pieTotal * 100) : 0],
                ];
            @endphp

            {{-- Chart + Legend side-by-side --}}
            <div class="flex items-center gap-4 flex-1">
                {{-- Chart (kiri) --}}
                <div id="pieChart" class="flex-shrink-0" style="width:160px; height:160px;"></div>

                {{-- Legend (kanan) --}}
                <div class="flex-1 min-w-0 space-y-3">
                    {{-- Total count --}}
                    <div class="mb-3 pb-3 border-b border-gray-100">
                        <p class="text-2xl font-extrabold text-gray-900">{{ number_format($pieTotal) }}</p>
                        <p class="text-[11px] text-gray-400">Total records</p>
                    </div>

                    @foreach($legendItems as $item)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $item['color'] }}"></span>
                                    <span class="text-xs font-medium text-gray-600 truncate">{{ $item['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0 ml-2">
                                    <span class="text-[10px] text-gray-400">{{ $item['pct'] }}%</span>
                                    <span class="text-xs font-bold text-gray-800 min-w-[1.5rem] text-right">{{ number_format($item['value']) }}</span>
                                </div>
                            </div>
                            <div class="h-1.5 rounded-full" style="background:{{ $item['bg'] }}">
                                <div class="h-1.5 rounded-full transition-all duration-700"
                                     style="width:{{ $item['pct'] }}%; background:{{ $item['color'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


    </div>

    {{-- ── Quick Actions ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6" style="box-shadow:0 2px 12px -4px rgba(0,0,0,0.06);">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="section-title">Aksi Cepat</h3>
                <p class="section-sub">Navigasi ke halaman yang sering digunakan</p>
            </div>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-9 gap-3">
            @php
                $actions = [
                    ['href' => route($routePrefix . '.inventory.add'),             'label' => 'Tambah Barang',  'desc' => 'Daftarkan item baru',  'icon' => 'M12 4v16m8-8H4',                                                                                                                                          'bg' => 'bg-blue-50',    'ic' => 'text-blue-600'],
                    ['href' => route($routePrefix . '.incoming.create'),            'label' => 'Barang Masuk',   'desc' => 'Catat stok masuk',     'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12',                                                                                       'bg' => 'bg-emerald-50', 'ic' => 'text-emerald-600'],
                    ['href' => route($routePrefix . '.outgoing.create'),            'label' => 'Barang Keluar',  'desc' => 'Catat stok keluar',    'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',                                                                                       'bg' => 'bg-rose-50',    'ic' => 'text-rose-500'],
                    ['href' => route($routePrefix . '.borrowing-requests.index'),   'label' => 'Approval',       'desc' => 'Tinjau permintaan',    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'bg' => 'bg-amber-50',   'ic' => 'text-amber-600'],
                    ['href' => route($routePrefix . '.reports.index'),              'label' => 'Laporan',        'desc' => 'Export & analisis',    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',             'bg' => 'bg-indigo-50',  'ic' => 'text-indigo-600'],
                    ['href' => route($routePrefix . '.insights.index'),             'label' => 'Insights',       'desc' => 'Statistik mendalam',   'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'bg' => 'bg-purple-50',  'ic' => 'text-purple-600'],
                    ['href' => route($routePrefix . '.suppliers.index'),            'label' => 'Supplier',       'desc' => 'Kelola vendor',        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'bg' => 'bg-slate-50',   'ic' => 'text-slate-600'],
                    ['href' => route($routePrefix . '.categories.index'),           'label' => 'Kategori',       'desc' => 'Kelola kategori',      'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',               'bg' => 'bg-pink-50',    'ic' => 'text-pink-500'],
                    ['href' => route($routePrefix . '.locations.index'),            'label' => 'Lokasi',         'desc' => 'Rak & ruangan',        'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',                           'bg' => 'bg-teal-50',    'ic' => 'text-teal-600'],
                ];
            @endphp
            @foreach($actions as $action)
                <a href="{{ $action['href'] }}" class="qa-card">
                    <div class="qa-icon-wrap {{ $action['bg'] }}">
                        <svg class="w-5 h-5 {{ $action['ic'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $action['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800 leading-snug">{{ $action['label'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 hidden sm:block leading-snug">{{ $action['desc'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ── Recent Activities ── --}}
    @if($transaksiTerakhir && count($transaksiTerakhir) > 0)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden" style="box-shadow:0 2px 12px -4px rgba(0,0,0,0.06);">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="section-title">Aktivitas Terbaru</h3>
                    <p class="section-sub">{{ count($transaksiTerakhir) }} transaksi terakhir</p>
                </div>
                <a href="{{ route($routePrefix . '.activities.index') }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full activity-table">
                    <thead>
                        <tr>
                            <th class="text-left">Waktu</th>
                            <th class="text-left">Tipe</th>
                            <th class="text-left">Item</th>
                            <th class="text-right">Jumlah</th>
                            <th class="text-right hidden md:table-cell">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiTerakhir as $transaksi)
                            <tr>
                                <td>
                                    <p class="font-semibold text-gray-800">{{ $transaksi->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $transaksi->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</p>
                                </td>
                                <td>
                                    @if($transaksi->tipe === 'masuk')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-rose-50 text-rose-600 border border-rose-100">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Keluar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-semibold text-gray-800 line-clamp-1">{{ $transaksi->nama }}</span>
                                </td>
                                <td class="text-right whitespace-nowrap">
                                    <span class="font-bold {{ $transaksi->tipe === 'masuk' ? 'text-emerald-600' : 'text-rose-500' }}">
                                        {{ $transaksi->tipe === 'masuk' ? '+' : '-' }}{{ number_format($transaksi->jumlah) }}
                                    </span>
                                </td>
                                <td class="text-right font-semibold text-gray-500 hidden md:table-cell whitespace-nowrap">
                                    {{ number_format($transaksi->stok_sekarang) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    const chart = new ApexCharts(document.querySelector("#chart"), {
        series: [
            { name: 'Barang Masuk',  data: @json($chartMasuk) },
            { name: 'Barang Keluar', data: @json($chartKeluar) }
        ],
        chart: {
            type: 'area',
            height: 256,
            toolbar: { show: false },
            background: 'transparent',
            fontFamily: 'inherit',
            animations: { enabled: true, easing: 'easeinout', speed: 700, dynamicAnimation: { speed: 500 } },
            zoom: { enabled: false },
            sparkline: { enabled: false }
        },
        colors: ['#10B981', '#F43F5E'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: [2.5, 2.5], lineCap: 'round' },
        fill: {
            type: 'gradient',
            gradient: {
                type: 'vertical',
                shadeIntensity: 1,
                gradientToColors: ['#10B981', '#F43F5E'],
                opacityFrom: 0.22,
                opacityTo: 0.0,
                stops: [0, 90]
            }
        },
        markers: {
            size: 0,
            strokeWidth: 2,
            strokeColors: '#fff',
            hover: { size: 5, sizeOffset: 2 }
        },
        xaxis: {
            categories: @json($chartDates),
            labels: {
                style: { colors: '#9CA3AF', fontSize: '11px', fontWeight: 500 },
                rotate: 0,
                hideOverlappingLabels: true,
                trim: true
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
            crosshairs: {
                show: true,
                stroke: { color: '#e5e7eb', width: 1, dashArray: 4 }
            },
            tooltip: { enabled: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#9CA3AF', fontSize: '11px' },
                formatter: (v) => Number.isInteger(v) ? v.toLocaleString('id-ID') : ''
            },
            min: 0,
            forceNiceScale: true,
            tickAmount: 4
        },
        grid: {
            borderColor: '#F3F4F6',
            strokeDashArray: 5,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } },
            padding: { top: -4, right: 8, bottom: 0, left: 8 }
        },
        legend: { show: false },
        tooltip: {
            theme: 'light',
            shared: true,
            intersect: false,
            x: { show: true },
            y: { formatter: (v) => v.toLocaleString('id-ID') + ' unit' },
            style: { fontSize: '12px' }
        }
    });
    chart.render();

    // ── Dark mode detection helper ──
    const _isDark = () => document.documentElement.classList.contains('dark');
    const _dm = {
        labelColor:  () => _isDark() ? '#94a3b8' : '#9CA3AF',
        valueColor:  () => _isDark() ? '#f1f5f9' : '#111827',
        gridColor:   () => _isDark() ? '#334155' : '#F3F4F6',
        axisColor:   () => _isDark() ? '#64748b' : '#9CA3AF',
        strokeColor: () => _isDark() ? '#1e293b' : '#ffffff',
        tooltipTheme:() => _isDark() ? 'dark'   : 'light',
    };

    const pieChart = new ApexCharts(document.querySelector("#pieChart"), {
        series: [{{ $jumlahJenisBarang }}, {{ $userCount }}, {{ $supplierCount }}, {{ $categoryCount }}],
        chart: {
            type: 'donut',
            width: 160,
            height: 160,
            background: 'transparent',
            fontFamily: 'inherit',
            animations: { enabled: true, easing: 'easeinout', speed: 700 },
            dropShadow: { enabled: false },
            sparkline: { enabled: false }
        },
        labels: ['Items', 'Users', 'Suppliers', 'Categories'],
        colors: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6'],
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '10px',
                            fontWeight: 600,
                            color: _dm.labelColor(),
                            offsetY: -3
                        },
                        value: {
                            show: true,
                            fontSize: '18px',
                            fontWeight: 800,
                            color: _dm.valueColor(),
                            offsetY: 4,
                            formatter: (v) => Number(v).toLocaleString('id-ID')
                        },
                        total: {
                            show: true,
                            showAlways: true,
                            label: 'Total',
                            fontSize: '10px',
                            fontWeight: 600,
                            color: _dm.labelColor(),
                            formatter: () => '{{ $jumlahJenisBarang + $userCount + $supplierCount + $categoryCount }}'
                        }
                    }
                }
            }
        },
        states: {
            hover:  { filter: { type: 'darken', value: 0.85 } },
            active: { filter: { type: 'none' } }
        },
        legend: { show: false },
        tooltip: {
            theme: _dm.tooltipTheme(),
            y: { formatter: (v) => v.toLocaleString('id-ID') + ' records' }
        },
        stroke: { width: 3, colors: [_dm.strokeColor()] }
    });
    pieChart.render();

    // ── Re-render charts when dark mode toggles ──
    const _origToggle = window.toggleDarkMode;
    window.toggleDarkMode = function() {
        if (_origToggle) _origToggle();
        // Small delay to let the class update first
        setTimeout(() => {
            pieChart.updateOptions({
                plotOptions: { pie: { donut: { labels: {
                    name:  { color: _dm.labelColor() },
                    value: { color: _dm.valueColor() },
                    total: { color: _dm.labelColor() }
                }}}},
                stroke: { colors: [_dm.strokeColor()] },
                tooltip: { theme: _dm.tooltipTheme() }
            });
            chart.updateOptions({
                grid: { borderColor: _dm.gridColor() },
                xaxis: { labels: { style: { colors: _dm.axisColor() } } },
                yaxis: { labels: { style: { colors: _dm.axisColor() } } },
                tooltip: { theme: _dm.tooltipTheme() }
            });
        }, 50);
    };

    function updateChart(range) {
        window.location.href = `{{ route($routePrefix . '.dashboard') }}?range=${range}`;
    }
</script>
@endpush
@endsection
