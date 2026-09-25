@extends('admin.layouts.dashboard')

@section('title', 'K3 Safety Interlock & Digital APD Induction')

@section('content')
<style>

html.dark .k3-card  { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .k3-title { color: #f1f5f9 !important; }
html.dark .k3-sub   { color: #94a3b8 !important; }
html.dark .k3-input { background-color: #0f172a !important; border-color: #334155 !important; color: #f1f5f9 !important; }
html.dark .k3-th    { background-color: #1e293b !important; color: #94a3b8 !important; border-color: #334155 !important; }
html.dark .k3-tr:hover { background-color: rgba(51,65,85,0.4) !important; }
html.dark .k3-td-text  { color: #e2e8f0 !important; }
html.dark .k3-td-sub   { color: #94a3b8 !important; }
html.dark .k3-modal-bg { background-color: #1e293b !important; border-color: #334155 !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5"
     x-data="{
        activeTab: 'matrix',
        itemSafetyModal: {
            open: false,
            id: null,
            name: '',
            risk_level: 'low',
            apds: [],
            instruction: '',
            quiz_required: false
        },
        incidentModal: {
            open: false
        },
        openItemSafety(id, name, risk, apds, instruction, quiz) {
            this.itemSafetyModal = {
                open: true,
                id: id,
                name: name,
                risk_level: risk || 'low',
                apds: Array.isArray(apds) ? apds : (apds ? JSON.parse(apds) : []),
                instruction: instruction || '',
                quiz_required: !!quiz
            };
        }
     }">

    <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 via-orange-600 to-red-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-orange-200">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="k3-title text-xl font-bold text-gray-900 tracking-tight">K3 Safety Interlock & Digital APD Induction</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                ISO 45001 & Permenaker 05/2018
                            </span>
                        </div>
                        <p class="k3-sub text-sm text-gray-500 mt-0.5">Gerbang verifikasi keselamatan peminjaman alat berisiko tinggi dan manajemen nihil kecelakaan (Zero Accident)</p>
                    </div>
                </div>
                <div class="flex items-center gap-2.5 flex-shrink-0">
                    <a href="{{ route('admin.safety.export-pdf') }}" target="_blank"
                       class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 text-xs font-bold rounded-xl transition-all shadow-xs whitespace-nowrap">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak Audit K3 (PDF)</span>
                    </a>
                    <button type="button" @click="incidentModal.open = true"
                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white text-xs font-bold rounded-xl shadow-md shadow-red-200 dark:shadow-none transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Catat Insiden K3</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm font-medium">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('info') }}
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5">

        <div class="k3-card bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-800/50 shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Zero Accident Record</p>
                <span class="p-1 rounded-md bg-emerald-500 text-white">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-emerald-800 dark:text-emerald-300">{{ $zeroAccidentDays }}</span>
                <span class="text-xs font-semibold text-emerald-600">Hari Kerja Selamat</span>
            </div>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-1">Nihil cedera berat / patah alat</p>
        </div>

        <div class="k3-card bg-white rounded-2xl border border-blue-100 shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <p class="k3-sub text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kepatuhan APD Loket</p>
                <span class="text-xs font-bold text-blue-600">{{ $verifiedRiskBorrowings }}/{{ $totalRiskBorrowings }} Selesai</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-blue-700">{{ $complianceRate }}%</span>
                <span class="text-xs font-semibold text-blue-500">{{ $totalRiskBorrowings > 0 ? 'Compliance' : 'Belum Ada Transaksi' }}</span>
            </div>
            <div class="w-full bg-blue-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, $complianceRate) }}%"></div>
            </div>
        </div>

        <div class="k3-card bg-white rounded-2xl border border-red-100 shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[11px] font-semibold text-red-500 uppercase tracking-wide">High Risk (Bahaya)</p>
                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-red-600">{{ $highRiskCount }}</span>
                <span class="text-xs font-semibold text-red-400">Unit Alat</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Wajib APD Lengkap + Interlock</p>
        </div>

        <div class="k3-card bg-white rounded-2xl border border-amber-100 shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <p class="text-[11px] font-semibold text-amber-600 uppercase tracking-wide">Medium Risk</p>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-amber-700">{{ $mediumRiskCount }}</span>
                <span class="text-xs font-semibold text-amber-500">Unit Alat</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Wajib Induksi Singkat</p>
        </div>

        <div class="k3-card bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-1.5">
                <p class="k3-sub text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kasus Insiden / SOP</p>
                <span class="text-xs font-semibold text-gray-500">Total</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-gray-800 dark:text-gray-200">{{ $totalIncidentsCount }}</span>
                <span class="text-xs font-semibold text-gray-400">Kasus Tercatat</span>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">Terekam dalam log audit K3</p>
        </div>
    </div>

    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2 overflow-x-auto">
        <button @click="activeTab = 'matrix'"
                :class="activeTab === 'matrix'
                    ? 'bg-amber-500 text-white font-bold shadow-sm'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800'"
                class="px-4 py-2 rounded-xl text-sm transition-all duration-150 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Matriks Risiko & SOP Alat</span>
        </button>

        <button @click="activeTab = 'clearance'"
                :class="activeTab === 'clearance'
                    ? 'bg-amber-500 text-white font-bold shadow-sm'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800'"
                class="px-4 py-2 rounded-xl text-sm transition-all duration-150 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Verifikasi Fisik APD Loket</span>
            @php
                $unverifiedCount = $clearanceRequests->whereNull('safety_verified_at')->count();
            @endphp
            @if($unverifiedCount > 0)
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-red-600 text-white">{{ $unverifiedCount }}</span>
            @endif
        </button>

        <button @click="activeTab = 'incidents'"
                :class="activeTab === 'incidents'
                    ? 'bg-amber-500 text-white font-bold shadow-sm'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800'"
                class="px-4 py-2 rounded-xl text-sm transition-all duration-150 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Buku Register Insiden K3</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300">{{ $incidents->total() }}</span>
        </button>
    </div>

    <div x-show="activeTab === 'matrix'" class="space-y-4">

        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <form action="{{ route('admin.safety.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input type="hidden" name="tab" value="matrix">
                <div class="flex-1 relative">
                    <input type="text" name="item_search" value="{{ request('item_search') }}"
                           placeholder="Cari alat manufaktur / lab berdasarkan nama atau kode..."
                           class="k3-input w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="w-full sm:w-48">
                    <select name="risk_level" onchange="this.form.submit()"
                            class="k3-input w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500">
                        <option value="">Semua Tingkat Bahaya</option>
                        <option value="high" {{ request('risk_level') === 'high' ? 'selected' : '' }}>🔴 High Risk (Bahaya Tinggi)</option>
                        <option value="medium" {{ request('risk_level') === 'medium' ? 'selected' : '' }}>🟡 Medium Risk (Waspada)</option>
                        <option value="low" {{ request('risk_level') === 'low' ? 'selected' : '' }}>🟢 Low Risk (Standar)</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-sm font-semibold rounded-xl transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="k3-th bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Perkakas / Alat</th>
                            <th class="px-4 py-3.5">Level Risiko K3</th>
                            <th class="px-4 py-3.5">Alat Pelindung Diri (APD) Wajib</th>
                            <th class="px-4 py-3.5">Instruksi SOP & Mitigasi</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($items as $item)
                        <tr class="k3-tr hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        @if($item->gambar)
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xs text-gray-400 font-bold">ALAT</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="k3-td-text font-bold text-gray-900">{{ $item->nama }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $item->kode ?: 'ITM-'.str_pad($item->id, 4, '0', STR_PAD_LEFT) }} • {{ $item->category->nama ?? 'General' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @php $badge = $item->risk_badge; @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if(!empty($item->required_apd_details))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($item->required_apd_details as $apdKey => $apdInfo)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200" title="{{ $apdInfo['desc'] }}">
                                                🛡️ {{ $apdInfo['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Bebas APD khusus (Standar Lab)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                @if($item->safety_instruction)
                                    <p class="text-xs text-gray-600 line-clamp-2" title="{{ $item->safety_instruction }}">
                                        "{{ $item->safety_instruction }}"
                                    </p>
                                @else
                                    <span class="text-xs text-gray-400 italic">- Belum ada instruksi khusus -</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <button type="button"
                                        @click="openItemSafety({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->safety_risk_level }}', {{ json_encode($item->required_apd ?? []) }}, '{{ addslashes($item->safety_instruction ?? '') }}', {{ $item->k3_quiz_required ? 1 : 0 }})"
                                        class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors">
                                    Set Protokol K3
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">
                                Tidak ada data alat ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

    <div x-show="activeTab === 'clearance'" class="space-y-4">
        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div>
                    <h3 class="k3-title text-base font-bold text-gray-900">Antrean Verifikasi Fisik APD oleh Toolman / Petugas</h3>
                    <p class="k3-sub text-xs text-gray-500">Praktikan harus memperlihatkan APD fisik di meja loket sebelum alat berisiko tinggi diserahkan.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.safety.index', ['tab' => 'clearance']) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('clearance_status') ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Semua</a>
                    <a href="{{ route('admin.safety.index', ['tab' => 'clearance', 'clearance_status' => 'pending_verify']) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('clearance_status') === 'pending_verify' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Menunggu Verifikasi</a>
                    <a href="{{ route('admin.safety.index', ['tab' => 'clearance', 'clearance_status' => 'verified']) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('clearance_status') === 'verified' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700' }}">Sudah Terverifikasi</a>
                </div>
            </div>
        </div>

        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="k3-th bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">ID Pinjam</th>
                            <th class="px-4 py-3.5">Praktikan / Mahasiswa</th>
                            <th class="px-4 py-3.5">Alat Berisiko</th>
                            <th class="px-4 py-3.5">Checklist APD Praktikan</th>
                            <th class="px-4 py-3.5">Status Fisik APD</th>
                            <th class="px-4 py-3.5 text-center">Tindakan Toolman</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($clearanceRequests as $req)
                        <tr class="k3-tr hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-bold text-gray-900">#{{ $req->id }}</span>
                                <div class="text-[11px] text-gray-400">{{ $req->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="k3-td-text font-bold text-gray-900">{{ $req->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $req->user->email }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-gray-900">{{ $req->item->nama }}</div>
                                @php $risk = $req->item->risk_badge; @endphp
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold {{ $risk['class'] }} px-2 py-0.5 rounded-full mt-0.5">
                                    {{ $risk['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if(!empty($req->safety_apd_checklist))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($req->safety_apd_checklist as $apdKey)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 border border-slate-200 font-medium">
                                                ✓ {{ $apdCatalog[$apdKey]['name'] ?? ucfirst(str_replace('_', ' ', $apdKey)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">- Tidak ada APD tercatat -</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($req->isSafetyVerified())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Terverifikasi
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-0.5">Oleh: {{ $req->safetyVerifier->name ?? 'Admin' }}</div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                        ⏳ Menunggu Fisik
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                @if(!$req->isSafetyVerified())
                                    <form action="{{ route('admin.safety.verify-apd', $req->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Apakah praktikan benar-benar sudah membawa dan memakai APD lengkap sesuai SOP alat?')"
                                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Sahkan Fisik APD
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.borrowing-requests.show', $req->id) }}"
                                       class="text-xs text-blue-600 hover:text-blue-800 font-semibold underline">
                                        Lihat Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">
                                Tidak ada antrean verifikasi fisik APD.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clearanceRequests->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $clearanceRequests->links() }}
                </div>
            @endif
        </div>
    </div>

    <div x-show="activeTab === 'incidents'" class="space-y-4">
        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div>
                    <h3 class="k3-title text-base font-bold text-gray-900">Buku Register Insiden, Near-Miss & Pelanggaran APD</h3>
                    <p class="k3-sub text-xs text-gray-500">Catatan resmi evaluasi keselamatan kerja laboratorium untuk mempertahankan status Zero Accident.</p>
                </div>
                <button @click="incidentModal.open = true"
                        class="px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center gap-1.5">
                    + Tambah Laporan Insiden
                </button>
            </div>
        </div>

        <div class="k3-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="k3-th bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-4 py-3.5">Praktikan Terlibat</th>
                            <th class="px-4 py-3.5">Alat / Lokasi</th>
                            <th class="px-4 py-3.5">Kategori Pelanggaran</th>
                            <th class="px-4 py-3.5">Uraian Kejadian & Tindakan</th>
                            <th class="px-4 py-3.5">Pelapor</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($incidents as $inc)
                        <tr class="k3-tr hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-3.5 whitespace-nowrap text-xs text-gray-700 font-semibold">
                                {{ $inc->incident_date->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-gray-900">{{ $inc->user->name }}</div>
                                <div class="text-[11px] text-gray-400">{{ $inc->user->email }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-gray-900">{{ $inc->item->nama ?? 'Umum / Area Lab' }}</div>
                                <div class="text-xs text-gray-400">{{ $inc->location }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @php $badge = $inc->incident_type_badge; @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800 border border-{{ $badge['color'] }}-200">
                                    {{ $badge['label'] }}
                                </span>
                                @if($inc->penalty_days > 0)
                                    <div class="text-[10px] text-red-600 font-bold mt-0.5">Sanksi: {{ $inc->penalty_days }} Hari Larangan Pinjam</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 max-w-sm text-xs">
                                <p class="font-medium text-gray-800">{{ $inc->description }}</p>
                                @if($inc->action_taken)
                                    <p class="text-emerald-700 mt-1 italic"><strong>Tindakan:</strong> {{ $inc->action_taken }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-xs text-gray-500 whitespace-nowrap">
                                {{ $inc->reportedBy->name ?? 'Petugas Lab' }}
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                @if($inc->status === 'resolved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Tuntas
                                    </span>
                                @elseif($inc->status === 'investigating')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                        Investigasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                        Ditutup
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                Belum ada catatan insiden (Status Zero Accident terjaga dengan baik).
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($incidents->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $incidents->links() }}
                </div>
            @endif
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="itemSafetyModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             role="dialog" aria-modal="true"
             @keydown.escape.window="itemSafetyModal.open = false">

            <div x-show="itemSafetyModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="itemSafetyModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">

                <div x-show="itemSafetyModal.open"
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
                            <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight">Pengaturan Protokol K3 & APD Alat</h3>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5 truncate max-w-xs" x-text="itemSafetyModal.name"></p>
                            </div>
                        </div>
                        <button type="button" @click="itemSafetyModal.open = false"
                                class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition"
                                title="Tutup Modal (Esc)">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/safety/item') }}/' + itemSafetyModal.id + '/update'" method="POST"
                          class="flex flex-col flex-1 min-h-0 overflow-hidden">
                        @csrf

                        <div class="p-5 sm:p-6 overflow-y-auto min-h-0 space-y-4 flex-1">

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Tingkat Risiko Bahaya Alat <span class="text-rose-500">*</span>
                                </label>
                                <select name="safety_risk_level" x-model="itemSafetyModal.risk_level" required
                                        class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                                    <option value="low">🟢 Low Risk (Aman / Tanpa APD Wajib)</option>
                                    <option value="medium">🟡 Medium Risk (Risiko Sedang - Waspada & APD Terbatas)</option>
                                    <option value="high">🔴 High Risk (Bahaya Tinggi - Wajib APD Lengkap & Verifikasi Loket)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                    Checklist APD yang Wajib Digunakan:
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                                    @foreach($apdCatalog as $key => $info)
                                    <label class="flex items-start gap-2 p-2 rounded-lg bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 text-xs cursor-pointer hover:border-amber-400 dark:hover:border-amber-500 transition shadow-2xs">
                                        <input type="checkbox" name="required_apd[]" value="{{ $key }}"
                                               :checked="itemSafetyModal.apds.includes('{{ $key }}')"
                                               class="mt-0.5 rounded text-amber-600 focus:ring-amber-500 border-gray-300 dark:border-slate-600">
                                        <div>
                                            <span class="font-semibold text-gray-800 dark:text-slate-200">{{ $info['name'] }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Instruksi Keselamatan Kerja (SOP):
                                </label>
                                <textarea name="safety_instruction" x-model="itemSafetyModal.instruction" rows="3"
                                          placeholder="Contoh: Pastikan benda kerja terkunci kuat pada chuck mesin. Dilarang memakai perhiasan atau baju longgar saat mengoperasikan mesin bubut."
                                          class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"></textarea>
                            </div>
                        </div>

                        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-end gap-2.5 flex-shrink-0">
                            <button type="button" @click="itemSafetyModal.open = false"
                                    class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 active:scale-95 rounded-xl shadow-md shadow-amber-500/25 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Protokol K3
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="incidentModal.open"
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             role="dialog" aria-modal="true"
             @keydown.escape.window="incidentModal.open = false">

            <div x-show="incidentModal.open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="incidentModal.open = false"
                 class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity"></div>

            <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 text-center">

                <div x-show="incidentModal.open"
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
                            <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center font-bold shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight">Catat Insiden / Pelanggaran K3 Lab</h3>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">Dokumentasi resmi ketidaksesuaian prosedur keselamatan kerja</p>
                            </div>
                        </div>
                        <button type="button" @click="incidentModal.open = false"
                                class="p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition"
                                title="Tutup Modal (Esc)">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.safety.store-incident') }}" method="POST"
                          class="flex flex-col flex-1 min-h-0 overflow-hidden">
                        @csrf

                        <div class="p-5 sm:p-6 overflow-y-auto min-h-0 space-y-3.5 flex-1">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                        Praktikan Terlibat <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="user_id" required
                                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Perkakas / Mesin Terkait</label>
                                    <select name="item_id"
                                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                                        <option value="">-- Tidak Terkait Alat Tertentu --</option>
                                        @foreach($allToolItems as $tool)
                                            <option value="{{ $tool->id }}">{{ $tool->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                        Tipe Kejadian <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="incident_type" required
                                            class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                                        <option value="apd_violation">Pelanggaran APD (Tidak Memakai APD)</option>
                                        <option value="sop_violation">Pelanggaran SOP Pengoperasian</option>
                                        <option value="near_miss">Near-Miss (Hampir Celaka)</option>
                                        <option value="tool_misuse">Salah Prosedur / Alat Rusak</option>
                                        <option value="minor_injury">Cedera Ringan (Gores/Luka Kecil)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                        Tanggal Kejadian <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="incident_date" value="{{ date('Y-m-d') }}" required
                                           class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition font-mono">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Lokasi Laboratorium / Mesin:</label>
                                <input type="text" name="location" placeholder="Misal: Lab Manufaktur Lantai 1 - Area Mesin Bubut 02"
                                       class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">
                                    Uraian Kronologis Kejadian <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="description" rows="2.5" required placeholder="Jelaskan bagaimana insiden atau pelanggaran APD terjadi..."
                                          class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Tindakan Pembinaan / Medis:</label>
                                    <input type="text" name="action_taken" placeholder="Contoh: Diberi teguran & pembinaan K3"
                                           class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-slate-300 mb-1">Sanksi Larangan Pinjam (Hari):</label>
                                    <input type="number" name="penalty_days" value="0" min="0"
                                           class="w-full text-xs rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 text-gray-900 dark:text-white py-2 px-3 focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 transition font-mono">
                                </div>
                            </div>
                        </div>

                        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/80 dark:bg-slate-850 flex items-center justify-end gap-2.5 flex-shrink-0">
                            <button type="button" @click="incidentModal.open = false"
                                    class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 rounded-xl transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 active:scale-95 rounded-xl shadow-md shadow-red-500/25 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Catat Insiden K3
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
@endsection
