@extends('admin.layouts.dashboard')

@push('styles')
<style>

    .stat-card-amber  { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
    .stat-card-blue   { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); }
    .stat-card-emerald{ background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .stat-card-rose   { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }

    .stat-card-icon-wrap {
        background: rgba(255,255,255,0.18);
        border-radius: 9px;
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-card-inner {
        border-radius: 1.15rem;
        padding: 1rem 1.15rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s cubic-bezier(0.22,1,0.36,1), box-shadow 0.18s;
        box-shadow: 0 4px 16px -4px rgba(0,0,0,0.18), inset 0 1px 0 rgba(255,255,255,0.16);
        text-decoration: none;
        display: block;
    }
    .stat-card-inner:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px -4px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.22);
    }
    .stat-card-blob {
        position: absolute;
        right: -16px; top: -16px;
        width: 80px; height: 80px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.11);
        pointer-events: none;
    }
    .stat-card-blob2 {
        position: absolute;
        right: 18px; bottom: -20px;
        width: 58px; height: 58px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.07);
        pointer-events: none;
    }

    .step-dot {
        width: 26px; height: 26px; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; flex-shrink: 0; margin-top: 1px;
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

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-full px-3 py-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Workshop Aktif
                </span>
                <span class="text-xs text-gray-400 dark:text-slate-400">
                    {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('l, d M Y, H:i') }} WIB
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Dashboard Operator</h1>
            <p class="text-sm text-gray-400 dark:text-slate-400 mt-1">Panel kontrol pemeliharaan alat &amp; eksekusi reparasi bengkel secara real-time</p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            <a href="{{ route($routePrefix . '.workshop.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                Denah Bengkel 2D
            </a>
            <a href="{{ route('scanner.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Scan Barcode
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">

        <div class="stat-card-inner stat-card-amber">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-2 py-0.5 rounded-full">Antrean</span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $inRepairCount }}</p>
            <p class="text-xs font-semibold text-white/80 mt-1">Sedang Diservis</p>
            <p class="text-[11px] text-white/60 mt-0.5">{{ $totalUnitInRepair }} unit menunggu eksekusi</p>
        </div>

        <div class="stat-card-inner stat-card-blue">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-2 py-0.5 rounded-full">Unit</span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $totalUnitInRepair }}</p>
            <p class="text-xs font-semibold text-white/80 mt-1">Total Unit Servis</p>
            <p class="text-[11px] text-white/60 mt-0.5">dalam pengerjaan mekanik</p>
        </div>

        <div class="stat-card-inner stat-card-emerald">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-2 py-0.5 rounded-full">Hari Ini</span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $completedToday }}</p>
            <p class="text-xs font-semibold text-white/80 mt-1">Selesai Hari Ini</p>
            <p class="text-[11px] text-white/60 mt-0.5">kumulatif: {{ $completedTotal }} selesai</p>
        </div>

        <div class="stat-card-inner stat-card-rose">
            <div class="stat-card-blob"></div>
            <div class="stat-card-blob2"></div>
            <div class="flex items-center justify-between mb-2">
                <div class="stat-card-icon-wrap">
                    <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider font-bold text-white/70 bg-white/10 px-2 py-0.5 rounded-full">Scrap</span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $scrappedTotal }}</p>
            <p class="text-xs font-semibold text-white/80 mt-1">Total Dimusnahkan</p>
            <p class="text-[11px] text-white/60 mt-0.5">alat afkir / tidak laik pakai</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Antrean Servis Aktif</h3>
                        <p class="text-xs text-gray-400 dark:text-slate-400">Peralatan dalam bengkel yang memerlukan tindakan perbaikan</p>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.maintenance.index', ['status' => 'in_repair']) }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors">
                    Lihat semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php $queue = $recentMaintenances->where('status', 'in_repair'); @endphp

            @if($queue->count() > 0)
                <div class="divide-y divide-gray-50 dark:divide-slate-800 flex-1">
                    @foreach($queue->take(6) as $m)
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-gray-50/75 dark:hover:bg-slate-800/40 transition-colors group">
                        <div class="flex items-center gap-3.5 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200/60 dark:border-amber-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $m->item?->nama ?? '-' }}</p>
                                    @if($m->item?->category)
                                        <span class="hidden sm:inline-block text-[10px] font-medium text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded">
                                            {{ $m->item->category->nama }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-slate-400 mt-0.5 flex items-center gap-2">
                                    <span class="font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-1.5 py-0.2 rounded">{{ $m->jumlah }} unit</span>
                                    <span>&bull;</span>
                                    <span>Masuk: {{ $m->started_at?->setTimezone('Asia/Jakarta')->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button"
                                    @click="openComplete({{ $m->id }}, '{{ addslashes($m->item?->nama) }}', {{ $m->jumlah }}, '{{ route($routePrefix . '.maintenance.complete', $m) }}')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all shadow-sm active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Selesai
                            </button>
                            <button type="button"
                                    @click="openScrap({{ $m->id }}, '{{ addslashes($m->item?->nama) }}', {{ $m->jumlah }}, '{{ route($routePrefix . '.maintenance.scrap', $m) }}')"
                                    class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition-colors border border-gray-200 dark:border-slate-700 hover:border-rose-200"
                                    title="Scrap Barang (Musnahkan)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center flex-1">
                    <div class="w-14 h-14 mx-auto mb-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-slate-100">Antrean Servis Bersih</p>
                    <p class="text-xs text-gray-400 dark:text-slate-400 mt-1 max-w-xs">Seluruh peralatan dan perkakas bengkel dalam kondisi siap pakai.</p>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Alur Kerja Operator</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400">Prosedur operasional standar pemeliharaan</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="step-dot bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300">1</div>
                        <div>
                            <p class="text-xs font-bold text-gray-800 dark:text-slate-200">Admin Melaporkan Masalah</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Admin menandai alat rusak &amp; mengarahkannya ke bengkel pemeliharaan.</p>
                        </div>
                    </div>
                    <div class="w-px h-3.5 bg-gray-200 dark:bg-slate-700 ml-3"></div>
                    <div class="flex items-start gap-3">
                        <div class="step-dot bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300">2</div>
                        <div>
                            <p class="text-xs font-bold text-gray-800 dark:text-slate-200">Eksekusi Reparasi</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Perbaiki perkakas, lalu tekan <strong class="text-emerald-600 dark:text-emerald-400">Selesai</strong> atau <strong class="text-rose-500">Scrap</strong> dari daftar.</p>
                        </div>
                    </div>
                    <div class="w-px h-3.5 bg-gray-200 dark:bg-slate-700 ml-3"></div>
                    <div class="flex items-start gap-3">
                        <div class="step-dot bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300">3</div>
                        <div>
                            <p class="text-xs font-bold text-gray-800 dark:text-slate-200">Stok Otomatis Pulih</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-0.5 leading-relaxed">Stok barang kembali aktif di katalog peminjaman dan admin menerima notifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5 pt-0">
                <a href="{{ route($routePrefix . '.maintenance.index') }}"
                   class="block text-center w-full py-2.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl transition-all shadow-sm shadow-emerald-600/20">
                    Buka Riwayat Lengkap Servis
                </a>
            </div>
        </div>

    </div>

    @php $history = $recentMaintenances->whereNotIn('status', ['in_repair'])->take(5); @endphp
    @if($history->count() > 0)
    <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Riwayat Tindakan Terakhir</h3>
                <p class="text-xs text-gray-400 dark:text-slate-400">Log hasil servis yang telah diselesaikan atau di-scrap</p>
            </div>
            <span class="text-xs font-semibold text-gray-400">{{ $history->count() }} record terbaru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50/70 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">Item &amp; Perkakas</th>
                        <th class="text-left text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50/70 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">Hasil</th>
                        <th class="text-center text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50/70 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">Jumlah</th>
                        <th class="text-right hidden sm:table-cell text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50/70 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">Waktu Selesai</th>
                        <th class="text-right text-[0.67rem] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 px-5 py-2.5 bg-gray-50/70 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @foreach($history as $m)
                    <tr class="group hover:bg-gray-50/60 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-5 py-3">
                            <p class="font-bold text-gray-900 dark:text-white truncate max-w-[200px]">{{ $m->item?->nama ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-slate-400">{{ $m->item?->kode_barang ?? $m->item?->kode ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $m->statusColor() }}">
                                {{ $m->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="text-xs font-extrabold text-gray-800 dark:text-slate-200">{{ $m->jumlah }} unit</span>
                        </td>
                        <td class="px-5 py-3 text-right text-gray-400 dark:text-slate-400 hidden sm:table-cell whitespace-nowrap text-xs">
                            {{ $m->completed_at?->setTimezone('Asia/Jakarta')->diffForHumans() ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route($routePrefix . '.maintenance.show', $m) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 transition-colors">
                                Detail
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <template x-teleport="body">
        <div x-show="completeModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             role="dialog" aria-modal="true"
             @keydown.escape.window="completeModal.open = false">

            <div x-show="completeModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="completeModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">
                <div x-show="completeModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     @click.stop
                     class="relative transform text-left bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-lg w-full flex flex-col max-h-[85vh] my-auto overflow-hidden">

                    <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0 bg-gray-50/70 dark:bg-slate-850">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight">Tandai Selesai Servis</h3>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">Stok barang akan otomatis dikembalikan ke inventori</p>
                            </div>
                        </div>
                        <button type="button" @click="completeModal.open = false"
                                class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition"
                                title="Tutup Modal (Esc)">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="completeModal.action" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                        @csrf @method('PATCH')
                        <div class="p-5 sm:p-6 overflow-y-auto min-h-0 space-y-4 flex-1">
                            <div class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/40 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm leading-relaxed flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    Barang <strong class="font-bold text-emerald-900 dark:text-emerald-200" x-text="completeModal.name"></strong> sebanyak <strong class="font-bold text-emerald-900 dark:text-emerald-200" x-text="completeModal.jumlah + ' unit'"></strong> akan ditandai selesai dan siap dipinjam.
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Catatan Hasil Servis (Opsional)
                                </label>
                                <textarea name="catatan_selesai" rows="3" placeholder="Tambahkan rincian tindakan servis atau perbaikan jika ada..."
                                          class="w-full text-xs sm:text-sm rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white p-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none placeholder-gray-400 dark:placeholder-slate-500"></textarea>
                            </div>
                        </div>

                        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-end gap-2.5 flex-shrink-0">
                            <button type="button" @click="completeModal.open = false"
                                    class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 rounded-xl shadow-md shadow-emerald-500/25 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Selesai &amp; Kembalikan Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="scrapModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             role="dialog" aria-modal="true"
             @keydown.escape.window="scrapModal.open = false">

            <div x-show="scrapModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="scrapModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">
                <div x-show="scrapModal.open"
                     x-transition:enter="ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2 sm:translate-y-0"
                     @click.stop
                     class="relative transform text-left bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 max-w-lg w-full flex flex-col max-h-[85vh] my-auto overflow-hidden">

                    <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0 bg-gray-50/70 dark:bg-slate-850">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight">Konfirmasi Pemusnahan (Scrap)</h3>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">Tindakan ini permanen dan stok tidak dapat dikembalikan</p>
                            </div>
                        </div>
                        <button type="button" @click="scrapModal.open = false"
                                class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition"
                                title="Tutup Modal (Esc)">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" :action="scrapModal.action" class="flex flex-col flex-1 min-h-0 overflow-hidden">
                        @csrf @method('PATCH')
                        <div class="p-5 sm:p-6 overflow-y-auto min-h-0 space-y-4 flex-1">
                            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/40 text-rose-800 dark:text-rose-300 text-xs sm:text-sm leading-relaxed flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    Barang <strong class="font-bold text-rose-900 dark:text-rose-200" x-text="scrapModal.name"></strong> (<span x-text="scrapModal.jumlah"></span> unit) akan di-scrap. <strong class="font-bold text-rose-700 dark:text-rose-300">Stok TIDAK dikembalikan.</strong>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Alasan Scrap <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="catatan_selesai" rows="3" required placeholder="Jelaskan kondisi kerusakan parah atau alasan tidak dapat diperbaiki..."
                                          class="w-full text-xs sm:text-sm rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white p-3 focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition resize-none placeholder-gray-400 dark:placeholder-slate-500"></textarea>
                            </div>
                        </div>

                        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-end gap-2.5 flex-shrink-0">
                            <button type="button" @click="scrapModal.open = false"
                                    class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-95 rounded-xl shadow-md shadow-rose-500/25 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Ya, Scrap Barang Ini
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
