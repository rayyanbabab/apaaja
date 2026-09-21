@extends('admin.layouts.dashboard')

@section('title', 'Smart Logistics: EOQ/ROP & Bin Rack')

@section('content')
<style>
/* ══ Logistics — Premium UI ══ */
html.dark .log-card  { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .log-title { color: #f1f5f9 !important; }
html.dark .log-sub   { color: #94a3b8 !important; }
html.dark .log-input { background-color: #0f172a !important; border-color: #334155 !important; color: #f1f5f9 !important; }
html.dark .log-th    { background-color: #1e293b !important; color: #94a3b8 !important; border-color: #334155 !important; }
html.dark .log-tr:hover { background-color: rgba(51,65,85,0.4) !important; }
html.dark .log-td-text  { color: #e2e8f0 !important; }
html.dark .log-td-sub   { color: #94a3b8 !important; }
html.dark .log-modal-bg { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .log-banner   { background: linear-gradient(to right, rgba(13,148,136,0.18), rgba(79,70,229,0.15)) !important; border-color: #334155 !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5"
     x-data="{
        paramModal: {
            open: false, id: null, name: '', bin_rack: '',
            lead_time: 3, daily_usage: 1.0, safety_stock: 5, holding_cost: 5000, order_cost: 50000
        },
        openParam(id, name, bin, lead, daily, safety, holding, order) {
            this.paramModal = { open: true, id, name, bin_rack: bin||'', lead_time: lead||3, daily_usage: daily||1.0, safety_stock: safety||5, holding_cost: holding||5000, order_cost: order||50000 };
        }
     }">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="log-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-teal-500 via-cyan-500 to-indigo-500"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-teal-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="log-title text-xl font-bold text-gray-900 tracking-tight">Smart Logistics: EOQ/ROP & Bin Rack</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700 border border-teal-200">
                                Manajemen Rantai Pasok
                            </span>
                        </div>
                        <p class="log-sub text-sm text-gray-500 mt-0.5">Kuantitas pemesanan ekonomis (EOQ), ambang restock (ROP), dan tata letak rak presisi</p>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.procurement-requests.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-all flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h4"/></svg>
                    Daftar Pengadaan
                </a>
            </div>

            {{-- Formula Banner --}}
            <div class="log-banner mt-5 p-3.5 rounded-xl bg-gradient-to-r from-teal-50 via-cyan-50/60 to-indigo-50 border border-teal-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-2.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-teal-600 text-white text-[11px] font-extrabold flex items-center justify-center flex-shrink-0">i</div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-teal-900">Formula Logistik Terapan:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-white/90 border border-teal-200 font-mono text-[11px] text-teal-800 font-semibold shadow-sm">
                            ROP = (Konsumsi × Lead Time) + Safety Stock
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-white/90 border border-indigo-200 font-mono text-[11px] text-indigo-800 font-semibold shadow-sm">
                            EOQ = √[ (2 × D × S) / H ]
                        </span>
                    </div>
                </div>
                <span class="text-[11px] font-semibold text-teal-900 bg-white/90 border border-teal-200 px-2.5 py-1 rounded-lg flex-shrink-0 shadow-sm">
                    Mencegah Overstock & Stockout
                </span>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ═══ KPI CARDS ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5">
        <div class="log-card bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="log-sub text-[11px] font-medium text-gray-400 uppercase tracking-wide">Total Valuasi</p>
                <p class="log-title text-sm font-extrabold text-gray-900 leading-tight">Rp {{ number_format($totalValuation, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="log-card bg-white rounded-2xl border border-red-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-red-600 uppercase tracking-wide">Di Bawah ROP</p>
                <p class="text-xl font-extrabold text-red-700">{{ $itemsBelowROPCount }} <span class="text-sm font-semibold">item</span></p>
            </div>
        </div>

        <div class="log-card bg-white rounded-2xl border border-amber-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                <span class="text-amber-700 font-extrabold text-base">A</span>
            </div>
            <div>
                <p class="text-[11px] font-medium text-amber-600 uppercase tracking-wide">Kelas A (Vital)</p>
                <p class="text-xl font-extrabold text-amber-700">{{ $countClassA }} <span class="text-sm font-semibold">item</span></p>
            </div>
        </div>

        <div class="log-card bg-white rounded-2xl border border-blue-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center flex-shrink-0">
                <span class="text-blue-700 font-extrabold text-sm">B/C</span>
            </div>
            <div>
                <p class="text-[11px] font-medium text-blue-600 uppercase tracking-wide">Kelas B & C</p>
                <p class="text-xl font-extrabold text-blue-700">{{ $countClassB + $countClassC }} <span class="text-sm font-semibold">item</span></p>
            </div>
        </div>

        <div class="log-card bg-white rounded-2xl border border-teal-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-medium text-teal-600 uppercase tracking-wide">Slot Rak Terdata</p>
                <p class="text-xl font-extrabold text-teal-700">{{ $totalBinAssigned }}</p>
            </div>
        </div>
    </div>

    {{-- ═══ FILTER & SEARCH ═══ --}}
    <div class="log-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route($routePrefix . '.logistics.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                <a href="{{ route($routePrefix . '.logistics.index') }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ !request('filter') && !request('abc') ? 'bg-teal-600 text-white shadow-sm shadow-teal-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua Barang
                </a>
                <a href="{{ route($routePrefix . '.logistics.index', ['filter' => 'below_rop']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('filter')==='below_rop' ? 'bg-red-600 text-white shadow-sm shadow-red-200' : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' }}">
                    Di Bawah ROP
                </a>
                <a href="{{ route($routePrefix . '.logistics.index', ['abc' => 'a']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('abc')==='a' ? 'bg-amber-600 text-white shadow-sm shadow-amber-200' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' }}">
                    Pareto Kelas A
                </a>
                <a href="{{ route($routePrefix . '.logistics.index', ['abc' => 'b']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('abc')==='b' ? 'bg-blue-600 text-white shadow-sm shadow-blue-200' : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200' }}">
                    Pareto Kelas B
                </a>
                <a href="{{ route($routePrefix . '.logistics.index', ['abc' => 'c']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('abc')==='c' ? 'bg-slate-700 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Pareto Kelas C
                </a>
            </div>
            <div class="relative flex-1 w-full md:max-w-sm ml-auto">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, atau alamat rak..."
                       class="log-input w-full text-xs pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-teal-500 focus:border-teal-500 bg-gray-50 text-gray-800 placeholder-gray-400">
            </div>
        </form>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="log-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                <thead class="log-th bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Barang & Bin Rack</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Klasifikasi ABC</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Stok vs ROP</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Parameter Logistik</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Rekomendasi EOQ</th>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    @php
                        $rop = $item->calculateROP();
                        $eoq = $item->calculateEOQ();
                        $isLow = $item->isBelowROP();
                        $abc = $abcMap[$item->id] ?? 'C';
                    @endphp
                    <tr class="log-tr hover:bg-teal-50/20 transition-colors duration-150 {{ $isLow ? 'bg-red-50/30' : '' }}">
                        {{-- Name & Bin --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($item->gambar)
                                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="log-td-text font-bold text-gray-900 leading-tight">{{ $item->nama }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        @if($item->bin_rack)
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] font-bold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-lg">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
                                                {{ $item->bin_rack }}
                                            </span>
                                        @else
                                            <span class="font-mono text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">Belum ada rak</span>
                                        @endif
                                        <span class="log-td-sub text-[10px] text-gray-400">{{ $item->location->nama ?? 'Gudang Utama' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- ABC --}}
                        <td class="px-4 py-4">
                            @if($abc === 'A')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Kelas A (Vital)
                                </span>
                            @elseif($abc === 'B')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Kelas B (Moderat)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
                                    Kelas C (Konsumabel)
                                </span>
                            @endif
                            <p class="log-td-sub text-[10px] text-gray-400 mt-1">Rp {{ number_format($item->valuation, 0, ',', '.') }}</p>
                        </td>

                        {{-- Stock vs ROP --}}
                        <td class="px-4 py-4">
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-sm font-extrabold {{ $isLow ? 'text-red-600' : 'log-td-text text-gray-900' }}">{{ $item->stok_total }}</span>
                                <span class="log-td-sub text-[11px] text-gray-400">unit / ROP: <strong class="log-td-text text-gray-700">{{ $rop }}</strong></span>
                            </div>
                            @if($isLow)
                                <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 animate-pulse">
                                    ⚠ Perlu Restock!
                                </span>
                            @else
                                <p class="text-[10px] text-emerald-600 font-medium mt-0.5">✓ Stok aman</p>
                            @endif
                        </td>

                        {{-- Parameters --}}
                        <td class="px-4 py-4">
                            <p class="log-td-text text-gray-700 text-xs">Lead Time: <strong class="text-gray-900">{{ $item->lead_time_days }} hari</strong></p>
                            <p class="log-td-sub text-[11px] text-gray-400 mt-0.5">Konsumsi: <strong class="log-td-text text-gray-700">{{ $item->daily_usage_rate }}</strong> u/hari · SS: {{ $item->safety_stock }}</p>
                        </td>

                        {{-- EOQ --}}
                        <td class="px-4 py-4">
                            <div class="inline-flex items-baseline gap-1 bg-teal-50 border border-teal-200 px-3 py-1.5 rounded-xl">
                                <span class="text-xs text-teal-600 font-medium">Optimal:</span>
                                <span class="text-sm font-extrabold text-teal-900">{{ $eoq }}</span>
                                <span class="text-xs text-teal-600">unit</span>
                            </div>
                            <p class="log-td-sub text-[10px] text-gray-400 mt-1">Biaya simpan & pesan minimal</p>
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($isLow)
                                    <form method="POST" action="{{ route($routePrefix . '.logistics.convert-procurement', $item->id) }}" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-[11px] font-bold rounded-lg shadow-sm transition-all"
                                                title="Buat Pengadaan Otomatis ({{ $eoq }} Unit)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Pesan {{ $eoq }}
                                        </button>
                                    </form>
                                @endif
                                <button @click="openParam({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ addslashes($item->bin_rack ?? '') }}', {{ $item->lead_time_days ?? 3 }}, {{ $item->daily_usage_rate ?? 1.0 }}, {{ $item->safety_stock ?? 5 }}, {{ $item->holding_cost ?? 5000 }}, {{ $item->order_cost ?? 50000 }})"
                                        class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200" title="Edit Parameter Logistik">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <p class="log-title text-sm font-semibold text-gray-700">Tidak ada barang dengan filter ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $items->links() }}</div>
        @endif
    </div>

    {{-- ═══ MODAL: EDIT PARAMETER ═══ --}}
    <div x-show="paramModal.open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="paramModal.open = false" class="log-modal-bg bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-lg w-full overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-teal-500 to-cyan-500"></div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="log-title text-base font-bold text-gray-900">Parameter Logistik & Bin Rack</h3>
                        <p class="log-sub text-xs text-gray-400 mt-0.5" x-text="paramModal.name"></p>
                    </div>
                    <button @click="paramModal.open = false" class="text-gray-300 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'{{ route($routePrefix . '.logistics.update-param', ':id') }}'.replace(':id', paramModal.id)" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Slot / Bin Rack *</label>
                        <input type="text" name="bin_rack" x-model="paramModal.bin_rack" placeholder="Contoh: RAK-A / TKT-2 / BIN-05"
                               class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        <p class="text-[11px] text-gray-400 mt-1">Memandu teknisi/operator langsung ke titik penyimpanan.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Lead Time (Hari) *</label>
                            <input type="number" name="lead_time_days" x-model="paramModal.lead_time" min="1" required class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Laju Pemakaian (Unit/Hari) *</label>
                            <input type="number" step="0.1" name="daily_usage_rate" x-model="paramModal.daily_usage" min="0.01" required class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Safety Stock *</label>
                            <input type="number" name="safety_stock" x-model="paramModal.safety_stock" min="0" required class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Biaya Simpan / Thn (H)</label>
                            <input type="number" name="holding_cost" x-model="paramModal.holding_cost" min="0" class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Biaya Pesan (S)</label>
                            <input type="number" name="order_cost" x-model="paramModal.order_cost" min="0" class="log-input w-full text-xs rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" @click="paramModal.open = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl shadow-sm">Simpan Parameter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
