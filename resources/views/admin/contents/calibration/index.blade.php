@extends('admin.layouts.dashboard')

@section('title', 'Kalibrasi & Tool Life Perkakas Presisi')

@section('content')
<style>
/* ══ Calibration — Premium UI ══ */
html.dark .cal-card  { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .cal-title { color: #f1f5f9 !important; }
html.dark .cal-sub   { color: #94a3b8 !important; }
html.dark .cal-input { background-color: #0f172a !important; border-color: #334155 !important; color: #f1f5f9 !important; }
html.dark .cal-th    { background-color: #1e293b !important; color: #94a3b8 !important; border-color: #334155 !important; }
html.dark .cal-tr:hover { background-color: rgba(51,65,85,0.4) !important; }
html.dark .cal-td-text  { color: #e2e8f0 !important; }
html.dark .cal-td-sub   { color: #94a3b8 !important; }
html.dark .cal-modal-bg { background-color: #1e293b !important; border-color: #334155 !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5"
     x-data="{
        calibrationModal: { open: false, id: null, name: '', date: '', cert: '', notes: '' },
        toolLifeModal:    { open: false, id: null, name: '', current_hours: 0, max_hours: 0 },
        toolTypeModal:    { open: false, id: null, name: '', current_type: 'general', max_hours: 100 },

        openCalibration(id, name, date, cert, notes) {
            this.calibrationModal = { open: true, id, name, date, cert, notes };
        },
        openToolLife(id, name, current_hours, max_hours) {
            this.toolLifeModal = { open: true, id, name, current_hours, max_hours };
        },
        openToolType(id, name, current_type, max_hours) {
            this.toolTypeModal = { open: true, id, name, current_type, max_hours };
        }
     }">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="cal-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-500"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="cal-title text-xl font-bold text-gray-900 tracking-tight">Kalibrasi & Tool Life Perkakas Presisi</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                Lab & Bengkel Mesin
                            </span>
                        </div>
                        <p class="cal-sub text-sm text-gray-500 mt-0.5">Pantau sertifikat kalibrasi alat ukur dan usia pakai mata pahat potong manufaktur</p>
                    </div>
                </div>
                <button @click="toolTypeModal.open = true; toolTypeModal.id = null; toolTypeModal.name = '';"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-200 transition-all duration-150 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tetapkan Tipe Perkakas
                </button>
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
        <div class="cal-card bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
            </div>
            <div><p class="cal-sub text-[11px] font-medium text-gray-400 uppercase tracking-wide">Total Perkakas</p><p class="cal-title text-xl font-extrabold text-gray-900">{{ $totalTools }}</p></div>
        </div>

        <div class="cal-card bg-white rounded-2xl border border-emerald-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><p class="text-[11px] font-medium text-emerald-600 uppercase tracking-wide">Valid</p><p class="text-xl font-extrabold text-emerald-700">{{ $calibratedCount }}</p></div>
        </div>

        <div class="cal-card bg-white rounded-2xl border border-amber-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div><p class="text-[11px] font-medium text-amber-600 uppercase tracking-wide">Due Soon</p><p class="text-xl font-extrabold text-amber-700">{{ $dueSoonCount }}</p></div>
        </div>

        <div class="cal-card bg-white rounded-2xl border border-red-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div><p class="text-[11px] font-medium text-red-600 uppercase tracking-wide">Expired</p><p class="text-xl font-extrabold text-red-700">{{ $expiredCount }}</p></div>
        </div>

        <div class="cal-card bg-white rounded-2xl border border-purple-100 shadow-sm p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div><p class="text-[11px] font-medium text-purple-600 uppercase tracking-wide">Pahat Aus ≥80%</p><p class="text-xl font-extrabold text-purple-700">{{ $criticalCuttingTools }}</p></div>
        </div>
    </div>

    {{-- ═══ FILTER & SEARCH ═══ --}}
    <div class="cal-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route($routePrefix . '.calibration.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                @foreach([
                    ['all', 'Semua', request('type','all')==='all'],
                    ['measuring', 'Alat Ukur Presisi', request('type')==='measuring'],
                    ['cutting', 'Alat Potong (Pahat)', request('type')==='cutting'],
                    ['dies_mold', 'Dies & Mold', request('type')==='dies_mold'],
                    ['jig_fixture', 'Jig & Fixture', request('type')==='jig_fixture'],
                ] as [$val, $label, $active])
                <a href="{{ route($routePrefix . '.calibration.index', ['type' => $val]) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $active ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto ml-auto">
                <select name="status" onchange="this.form.submit()"
                        class="cal-input text-xs border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 bg-gray-50 text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="calibrated" {{ request('status')==='calibrated' ? 'selected' : '' }}>Kalibrasi Valid</option>
                    <option value="due_soon"   {{ request('status')==='due_soon'   ? 'selected' : '' }}>Due Soon</option>
                    <option value="expired"    {{ request('status')==='expired'    ? 'selected' : '' }}>Expired</option>
                </select>
                <div class="relative flex-1 md:w-64">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode, no sertifikat..."
                           class="cal-input w-full text-xs pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-800 placeholder-gray-400">
                </div>
            </div>
        </form>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="cal-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                <thead class="cal-th bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Perkakas / Alat</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Kategori Tool</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status Kalibrasi</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Sertifikat / Jatuh Tempo</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tool Life (Mata Potong)</th>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                    <tr class="cal-tr hover:bg-indigo-50/20 transition-colors duration-150">
                        {{-- Name & Code --}}
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
                                    <p class="cal-td-text font-bold text-gray-900 leading-tight">{{ $item->nama }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="font-mono text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->kode ?? 'ITM-'.str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="cal-td-sub text-[10px] text-gray-400">{{ $item->location->nama ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Tool Type Badge --}}
                        <td class="px-4 py-4">
                            @if($item->tool_type === 'measuring_tool')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2"/></svg>
                                    Alat Ukur Presisi
                                </span>
                            @elseif($item->tool_type === 'cutting_tool')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L10.758 8"/></svg>
                                    Alat Potong (Pahat)
                                </span>
                            @elseif($item->tool_type === 'dies_mold')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Dies & Mold</span>
                            @elseif($item->tool_type === 'jig_fixture')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-teal-50 text-teal-700 border border-teal-200">Jig & Fixture</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600">Umum / Standar</span>
                            @endif
                        </td>

                        {{-- Calibration Status --}}
                        <td class="px-4 py-4">
                            @if($item->tool_type === 'measuring_tool')
                                @if($item->calibration_status === 'calibrated')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terkalibrasi
                                    </span>
                                @elseif($item->calibration_status === 'due_soon')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Segera Jatuh Tempo
                                    </span>
                                @elseif($item->calibration_status === 'expired')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span> Expired (Terkunci)
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Belum diset</span>
                                @endif
                            @else
                                <span class="text-gray-300 text-sm font-bold">—</span>
                            @endif
                        </td>

                        {{-- Certificate / Due Date --}}
                        <td class="px-4 py-4">
                            @if($item->tool_type === 'measuring_tool')
                                <p class="cal-td-text font-medium text-gray-900 text-xs leading-snug">{{ $item->calibration_certificate_number ?? '—' }}</p>
                                <p class="cal-td-sub text-[11px] text-gray-400 mt-0.5">
                                    JT: <strong class="{{ $item->isCalibrationExpired() ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $item->calibration_due_date ? $item->calibration_due_date->format('d M Y') : '-' }}</strong>
                                </p>
                            @else
                                <span class="text-gray-300 text-sm font-bold">—</span>
                            @endif
                        </td>

                        {{-- Tool Life Progress Bar --}}
                        <td class="px-4 py-4">
                            @if($item->tool_type === 'cutting_tool')
                                @php
                                    $pct = $item->getToolLifeProgressPercentage();
                                    $barColor = $pct >= 80 ? 'bg-red-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-indigo-500');
                                @endphp
                                <div class="w-36">
                                    <div class="flex items-center justify-between text-[10px] font-medium text-gray-600 mb-1">
                                        <span class="cal-td-text">{{ $item->tool_life_hours }} jam</span>
                                        <span class="{{ $pct >= 80 ? 'text-red-600 font-bold' : 'cal-td-sub text-gray-400' }}">{{ $pct }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="cal-td-sub text-[10px] text-gray-400 mt-1">Maks: {{ $item->max_tool_life_hours ?? 'n/a' }} jam</p>
                                </div>
                            @else
                                <span class="text-gray-300 text-sm font-bold">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($item->tool_type === 'measuring_tool')
                                    <button @click="openCalibration({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->calibration_due_date ? $item->calibration_due_date->format('Y-m-d') : '' }}', '{{ addslashes($item->calibration_certificate_number ?? '') }}', '{{ addslashes($item->calibration_notes ?? '') }}')"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200" title="Update Sertifikat Kalibrasi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                @endif
                                @if($item->tool_type === 'cutting_tool')
                                    <button @click="openToolLife({{ $item->id }}, '{{ addslashes($item->nama) }}', {{ $item->tool_life_hours ?? 0 }}, {{ $item->max_tool_life_hours ?? 100 }})"
                                            class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors border border-purple-200" title="Catat Jam Potong">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                @endif
                                <button @click="openToolType({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->tool_type }}', {{ $item->max_tool_life_hours ?? 100 }})"
                                        class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors border border-gray-200" title="Ubah Tipe Perkakas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <p class="cal-title text-sm font-semibold text-gray-700">Belum ada perkakas dengan filter ini</p>
                            <p class="cal-sub text-xs text-gray-400 mt-1">Gunakan "Tetapkan Tipe Perkakas" untuk mengklasifikasikan alat presisi.</p>
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

    {{-- ═══ MODAL: UPDATE KALIBRASI ═══ --}}
    <div x-show="calibrationModal.open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="calibrationModal.open = false" class="cal-modal-bg bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-md w-full overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="cal-title text-base font-bold text-gray-900">Update Sertifikat Kalibrasi</h3>
                        <p class="cal-sub text-xs text-gray-400 mt-0.5" x-text="calibrationModal.name"></p>
                    </div>
                    <button @click="calibrationModal.open = false" class="text-gray-300 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'{{ route($routePrefix . '.calibration.update-cert', ':id') }}'.replace(':id', calibrationModal.id)" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Jatuh Tempo Kalibrasi *</label>
                        <input type="date" name="calibration_due_date" x-model="calibrationModal.date" required
                               class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                        <p class="text-[11px] text-gray-400 mt-1">Jika lewat, alat otomatis terkunci dari peminjaman.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Sertifikat (KAN / Metrologi)</label>
                        <input type="text" name="calibration_certificate_number" x-model="calibrationModal.cert" placeholder="Contoh: KAN-CAL-2026/089"
                               class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Catatan Toleransi & Hasil</label>
                        <textarea name="calibration_notes" x-model="calibrationModal.notes" rows="2" placeholder="Contoh: Deviasi 0.002mm, kondisi spindle bersih."
                                  class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" @click="calibrationModal.open = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Simpan Sertifikat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: LOG JAM POTONG ═══ --}}
    <div x-show="toolLifeModal.open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="toolLifeModal.open = false" class="cal-modal-bg bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-md w-full overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="cal-title text-base font-bold text-gray-900">Catat Jam Potong Pahat (Tool Life)</h3>
                        <p class="cal-sub text-xs text-gray-400 mt-0.5" x-text="toolLifeModal.name"></p>
                    </div>
                    <button @click="toolLifeModal.open = false" class="text-gray-300 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'{{ route($routePrefix . '.calibration.update-life', ':id') }}'.replace(':id', toolLifeModal.id)" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tambah Jam Pemotongan (+ Jam)</label>
                        <input type="number" step="0.1" name="additional_hours" placeholder="Contoh: 3.5"
                               class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-purple-500 focus:border-purple-500 py-2.5 px-3">
                        <p class="text-[11px] text-gray-400 mt-1">Akumulasi saat ini: <strong x-text="toolLifeModal.current_hours + ' jam'"></strong></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Batas Maksimal Usia Pakai (Jam)</label>
                        <input type="number" step="0.5" name="max_tool_life_hours" x-model="toolLifeModal.max_hours"
                               class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-purple-500 focus:border-purple-500 py-2.5 px-3">
                        <p class="text-[11px] text-gray-400 mt-1">Bahaya otomatis ditampilkan saat mencapai 80% dari batas ini.</p>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" @click="toolLifeModal.open = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-sm">Simpan Jam Pakai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: TIPE PERKAKAS ═══ --}}
    <div x-show="toolTypeModal.open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="toolTypeModal.open = false" class="cal-modal-bg bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-md w-full overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-cyan-500"></div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="cal-title text-base font-bold text-gray-900">Klasifikasi Tipe Perkakas</h3>
                        <p class="cal-sub text-xs text-gray-400 mt-0.5" x-text="toolTypeModal.name || 'Pilih item dari daftar'"></p>
                    </div>
                    <button @click="toolTypeModal.open = false" class="text-gray-300 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'{{ route($routePrefix . '.calibration.update-type', ':id') }}'.replace(':id', toolTypeModal.id)" method="POST" class="space-y-3.5">
                    @csrf
                    <template x-if="!toolTypeModal.id">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Item Barang *</label>
                            <select @change="toolTypeModal.id = $event.target.value" required class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                                <option value="">— Pilih Barang —</option>
                                @foreach($allItems as $singleItem)
                                    <option value="{{ $singleItem->id }}">{{ $singleItem->kode ? '['.$singleItem->kode.'] ' : '' }}{{ $singleItem->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tipe Perkakas *</label>
                        <select name="tool_type" x-model="toolTypeModal.current_type" required class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                            <option value="measuring_tool">Alat Ukur Presisi (Caliper, Micrometer, Dial)</option>
                            <option value="cutting_tool">Alat Potong (Endmill, Carbide Insert, Pahat Bubut)</option>
                            <option value="dies_mold">Cetakan / Dies & Mold</option>
                            <option value="jig_fixture">Jig & Fixture Pengikat Benda Kerja</option>
                            <option value="general">Barang / Alat Umum Standar</option>
                        </select>
                    </div>
                    <div x-show="toolTypeModal.current_type === 'measuring_tool'">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Kalibrasi Berikutnya</label>
                        <input type="date" name="calibration_due_date" class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                    </div>
                    <div x-show="toolTypeModal.current_type === 'cutting_tool'">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Batas Maksimal Jam Potong</label>
                        <input type="number" step="1" name="max_tool_life_hours" x-model="toolTypeModal.max_hours" class="cal-input w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" @click="toolTypeModal.open = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">Simpan Klasifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
