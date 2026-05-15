@extends('admin.layouts.dashboard')

@push('styles')
<style>
    /* ── Operator Stat Cards ── */
    .op-card-orange  { background: linear-gradient(135deg, #c2410c 0%, #f97316 100%); }
    .op-card-blue    { background: linear-gradient(135deg, #1d4ed8 0%, #60a5fa 100%); }
    .op-card-emerald { background: linear-gradient(135deg, #047857 0%, #34d399 100%); }
    .op-card-red     { background: linear-gradient(135deg, #be123c 0%, #f87171 100%); }

    .op-stat {
        border-radius: 1.25rem;
        padding: 1.35rem 1.4rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px -4px rgba(0,0,0,0.2);
        transition: transform 0.18s, box-shadow 0.18s;
    }
    .op-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 28px -4px rgba(0,0,0,0.25); }
    .op-stat-blob {
        position: absolute; right:-16px; top:-16px;
        width: 90px; height: 90px;
        border-radius: 9999px; background: rgba(255,255,255,0.1); pointer-events:none;
    }
    .op-stat-blob2 {
        position: absolute; right:16px; bottom:-24px;
        width: 60px; height: 60px;
        border-radius: 9999px; background: rgba(255,255,255,0.07); pointer-events:none;
    }
    .op-icon-wrap {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.18);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-bottom: 1rem;
    }

    /* ── Workflow Steps ── */
    .step-dot {
        width: 26px; height: 26px; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 800; flex-shrink: 0; margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6"
     x-data="{
        completeModal: { open: false, id: null, name: '', jumlah: 0, action: '' },
        scrapModal:    { open: false, id: null, name: '', jumlah: 0, action: '' },
        openComplete(id, name, jumlah, action) { this.completeModal = { open: true, id, name, jumlah, action }; },
        openScrap(id, name, jumlah, action)    { this.scrapModal    = { open: true, id, name, jumlah, action }; }
     }">

    {{-- ── Welcome Header ── --}}
    <div class="relative overflow-hidden rounded-2xl" style="background: linear-gradient(135deg,#059669 0%,#10b981 55%,#34d399 100%); box-shadow:0 6px 24px -4px rgba(5,150,105,0.4);">
        {{-- Decorative blobs --}}
        <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:9999px;background:rgba(255,255,255,0.08);pointer-events:none;"></div>
        <div style="position:absolute;left:40%;bottom:-30px;width:120px;height:120px;border-radius:9999px;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
        <div class="relative px-6 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-emerald-100 text-xs font-semibold uppercase tracking-widest mb-1">Operator Dashboard</p>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Halo, {{ Auth::user()->name }} 👋</h1>
                    <p class="text-emerald-100 text-sm mt-1">
                        {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route($routePrefix . '.maintenance.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white font-semibold text-sm rounded-xl border border-white/30 transition-all duration-200 backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Semua Record
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="op-stat op-card-orange">
            <div class="op-stat-blob"></div><div class="op-stat-blob2"></div>
            <div class="op-icon-wrap">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ $inRepairCount }}</p>
            <p class="text-xs font-semibold text-white/75 mt-1">Sedang Diservis</p>
            <p class="text-[11px] text-white/55 mt-0.5">{{ $totalUnitInRepair }} unit diproses</p>
        </div>

        <div class="op-stat op-card-blue">
            <div class="op-stat-blob"></div><div class="op-stat-blob2"></div>
            <div class="op-icon-wrap">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ $totalUnitInRepair }}</p>
            <p class="text-xs font-semibold text-white/75 mt-1">Total Unit Servis</p>
            <p class="text-[11px] text-white/55 mt-0.5">unit sedang ditangani</p>
        </div>

        <div class="op-stat op-card-emerald">
            <div class="op-stat-blob"></div><div class="op-stat-blob2"></div>
            <div class="op-icon-wrap">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ $completedToday }}</p>
            <p class="text-xs font-semibold text-white/75 mt-1">Selesai Hari Ini</p>
            <p class="text-[11px] text-white/55 mt-0.5">total: {{ $completedTotal }}</p>
        </div>

        <div class="op-stat op-card-red">
            <div class="op-stat-blob"></div><div class="op-stat-blob2"></div>
            <div class="op-icon-wrap">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ $scrappedTotal }}</p>
            <p class="text-xs font-semibold text-white/75 mt-1">Total Scrap</p>
            <p class="text-[11px] text-white/55 mt-0.5">tidak dapat diperbaiki</p>
        </div>

    </div>

    {{-- ── Antrean Servis & Workflow ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Antrean Aktif --}}
        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-800/90 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700/50">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-slate-100">Antrean Aktif</h3>
                        <p class="text-xs text-gray-400 dark:text-slate-400">Barang sedang dalam proses servis</p>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.maintenance.index', ['status' => 'in_repair']) }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                    Lihat semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php $queue = $recentMaintenances->where('status', 'in_repair'); @endphp

            @if($queue->count() > 0)
                <div>
                    @foreach($queue->take(6) as $m)
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 border-b border-gray-50 dark:border-slate-700/30 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors last:border-0 group">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-slate-100 truncate">{{ $m->item?->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5">
                                    <span class="font-medium text-orange-600 dark:text-orange-400">{{ $m->jumlah }} unit</span>
                                    · masuk {{ $m->started_at?->setTimezone('Asia/Jakarta')->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <button type="button"
                                    @click="openComplete({{ $m->id }}, '{{ addslashes($m->item?->nama) }}', {{ $m->jumlah }}, '{{ route($routePrefix . '.maintenance.complete', $m) }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Selesai
                            </button>
                            <button type="button"
                                    @click="openScrap({{ $m->id }}, '{{ addslashes($m->item?->nama) }}', {{ $m->jumlah }}, '{{ route($routePrefix . '.maintenance.scrap', $m) }}')"
                                    class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors border border-gray-200 hover:border-red-200"
                                    title="Scrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    <div class="w-14 h-14 mx-auto mb-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center border border-transparent dark:border-emerald-500/20">
                        <svg class="w-7 h-7 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-700 dark:text-slate-200">Antrean Kosong</p>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-1">Semua barang sudah selesai diservis!</p>
                </div>
            @endif
        </div>

        {{-- Alur Kerja Maintenance --}}
        <div class="bg-white/80 dark:bg-slate-800/90 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700/50">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-slate-100">Alur Kerja</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400">Proses maintenance barang</p>
                </div>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="step-dot bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">1</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200">Admin melaporkan</p>
                        <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Admin menandai barang rusak &amp; mengirim ke servis — kamu akan mendapat notifikasi.</p>
                    </div>
                </div>
                <div class="w-px h-4 bg-gray-200 dark:bg-slate-700 ml-3"></div>
                <div class="flex items-start gap-3">
                    <div class="step-dot bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300">2</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200">Kamu mengeksekusi</p>
                        <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Servis barang, lalu tandai <strong class="dark:text-slate-300">Selesai</strong> atau <strong class="dark:text-slate-300">Scrap</strong> dari daftar antrean.</p>
                    </div>
                </div>
                <div class="w-px h-4 bg-gray-200 dark:bg-slate-700 ml-3"></div>
                <div class="flex items-start gap-3">
                    <div class="step-dot bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300">3</div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-slate-200">Admin dikonfirmasi</p>
                        <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Admin otomatis dinotifikasi saat kamu menyelesaikan eksekusi.</p>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.maintenance.index') }}"
                   class="block text-center mt-1 py-2.5 text-sm font-bold bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl transition-colors shadow-sm">
                    Buka Antrean Servis
                </a>
            </div>
        </div>

    </div>

    {{-- ── Riwayat Servis Terakhir ── --}}
    @php $history = $recentMaintenances->whereNotIn('status', ['in_repair'])->take(5); @endphp
    @if($history->count() > 0)
    <div class="bg-white/80 dark:bg-slate-800/90 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-700/50">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-slate-100">Riwayat Servis Terakhir</h3>
                <p class="text-xs text-gray-400 dark:text-slate-400">{{ $history->count() }} record terakhir yang sudah selesai</p>
            </div>
            <a href="{{ route($routePrefix . '.maintenance.index') }}"
               class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                Lihat semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700/50">Item</th>
                        <th class="text-left text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700/50">Status</th>
                        <th class="text-center text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700/50">Unit</th>
                        <th class="text-right hidden sm:table-cell text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700/50">Waktu</th>
                        <th class="text-right text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700/50"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $m)
                    <tr class="group hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3 border-b border-gray-50 dark:border-slate-700/30">
                            <p class="font-semibold text-gray-800 dark:text-slate-200 truncate max-w-[160px]">{{ $m->item?->nama ?? '-' }}</p>
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ $m->statusColor() }}">
                                {{ $m->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 border-b border-gray-50 dark:border-slate-700/30 text-center">
                            <span class="text-sm font-bold text-gray-700 dark:text-slate-300">{{ $m->jumlah }}</span>
                        </td>
                        <td class="px-5 py-3 border-b border-gray-50 dark:border-slate-700/30 text-right text-gray-400 dark:text-slate-500 hidden sm:table-cell whitespace-nowrap">
                            {{ $m->completed_at?->setTimezone('Asia/Jakarta')->diffForHumans() }}
                        </td>
                        <td class="px-5 py-3 border-b border-gray-50 dark:border-slate-700/30 text-right">
                            <a href="{{ route($routePrefix . '.maintenance.show', $m) }}"
                               class="inline-flex items-center gap-0.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ── Modal Selesai ── --}}
    <div x-show="completeModal.open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="completeModal.open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); display: none;">
        <div @click.outside="completeModal.open = false"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border border-white/20 dark:border-slate-700/50 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700/50">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-slate-100">Tandai Selesai Servis</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5">Stok akan dikembalikan ke inventori</p>
                </div>
                <button @click="completeModal.open = false" class="p-1.5 text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="completeModal.action" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 rounded-xl p-3.5 text-sm text-emerald-800 dark:text-emerald-300">
                    Barang <strong class="dark:text-emerald-200" x-text="completeModal.name"></strong> sebanyak <strong class="dark:text-emerald-200" x-text="completeModal.jumlah + ' unit'"></strong> akan ditandai selesai. Stok dikembalikan ke inventori.
                </div>
                <textarea name="catatan_selesai" rows="3" placeholder="Catatan hasil servis (opsional)..."
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-slate-600 bg-transparent dark:text-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 resize-none placeholder-gray-400 dark:placeholder-slate-500 transition-colors"></textarea>
                <div class="flex gap-2.5">
                    <button type="submit" class="flex-1 py-2.5 text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors shadow-sm">Selesai &amp; Kembalikan Stok</button>
                    <button type="button" @click="completeModal.open = false" class="px-5 py-2.5 text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal Scrap ── --}}
    <div x-show="scrapModal.open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="scrapModal.open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); display: none;">
        <div @click.outside="scrapModal.open = false"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border border-white/20 dark:border-slate-700/50 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-slate-700/50">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-slate-100">Konfirmasi Scrap</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <button @click="scrapModal.open = false" class="p-1.5 text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800/50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" :action="scrapModal.action" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800/50 rounded-xl p-3.5 text-sm text-red-800 dark:text-red-300">
                    Barang <strong class="dark:text-red-200" x-text="scrapModal.name"></strong> (<span x-text="scrapModal.jumlah"></span> unit) akan di-scrap.
                    <span class="font-bold dark:text-red-200">Stok TIDAK dikembalikan.</span>
                </div>
                <textarea name="catatan_selesai" rows="2" placeholder="Alasan scrap..."
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 dark:border-slate-600 bg-transparent dark:text-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 resize-none placeholder-gray-400 dark:placeholder-slate-500 transition-colors"></textarea>
                <div class="flex gap-2.5">
                    <button type="submit" class="flex-1 py-2.5 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors shadow-sm">Ya, Scrap Barang Ini</button>
                    <button type="button" @click="scrapModal.open = false" class="px-5 py-2.5 text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
