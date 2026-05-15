@extends('admin.layouts.dashboard')
@php
    $routePrefix = Auth::user()->role->value === 'operator' ? 'staff' : 'admin';
    $isComplete = $stockOpname->status === 'completed';
    $isCancelled = $stockOpname->status === 'cancelled';
    $isReadonly = $isComplete || $isCancelled;
@endphp

@section('content')

{{-- ═══ Background Orbs ═══ --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
    <div class="absolute top-[10%] left-[10%] w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute bottom-[10%] right-[10%] w-96 h-96 bg-indigo-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
</div>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route($routePrefix . '.stock-opnames.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Audit #OPN-{{ str_pad($stockOpname->id, 4, '0', STR_PAD_LEFT) }}</h1>
                    @if($stockOpname->status === 'draft')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800 uppercase tracking-wider">Draft</span>
                    @elseif($stockOpname->status === 'in_progress')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase tracking-wider animate-pulse">In Progress</span>
                    @elseif($stockOpname->status === 'completed')
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">Completed</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase tracking-wider">Cancelled</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mt-1">Dibuat tanggal {{ $stockOpname->start_date->format('d M Y') }} oleh {{ $stockOpname->user->name }}.</p>
            </div>
        </div>

        @if(!$isReadonly)
        <div class="flex items-center gap-3">
            <button type="button" onclick="openModal('modal-cancel-session')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                Batalkan Sesi
            </button>
            <button type="button" onclick="openModal('modal-complete-audit')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Selesaikan Audit
            </button>
        </div>
        @endif
    </div>

    @if($stockOpname->notes)
    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex gap-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-blue-800 leading-relaxed">{{ $stockOpname->notes }}</p>
    </div>
    @endif

{{-- 2-column layout: table + sticky sidebar --}}
<div class="flex gap-6 items-start">

    {{-- ─── Items Table ─── --}}
    <div class="flex-1 min-w-0">
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-xl rounded-2xl overflow-hidden"
         x-data="stockOpnameItems()">
        
        <div class="p-4 border-b border-gray-100/50 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Barang Audit</h2>
            <div class="text-sm text-gray-500 font-medium">
                <span x-text="completedCount"></span> / {{ count($stockOpname->items) }} Diperiksa
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100/50">
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/3">Barang</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">Stok Sistem</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center w-32">Stok Aktual</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center w-24">Selisih</th>
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider w-1/4">Catatan</th>
                        @if(!$isReadonly)
                        <th class="px-4 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider text-center">Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($stockOpname->items as $idx => $opnameItem)
                        <tr class="hover:bg-gray-50/50 transition-colors" x-data="auditItem({{ $opnameItem->id }}, {{ $opnameItem->system_stok }}, {{ $opnameItem->actual_stok ?? 'null' }}, '{{ addslashes($opnameItem->notes) }}')">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                        @if($opnameItem->item->gambar)
                                            <img src="{{ asset($opnameItem->item->gambar) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 leading-snug">{{ $opnameItem->item->nama }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $opnameItem->item->kode }} &bull; {{ $opnameItem->item->location->name ?? 'Tanpa Lokasi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded bg-gray-100 text-sm font-bold text-gray-700">
                                    {{ $opnameItem->system_stok }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($isReadonly)
                                    <span class="text-sm font-bold text-gray-900">{{ $opnameItem->actual_stok ?? '-' }}</span>
                                @else
                                    <input type="number" min="0" x-model="actualStok" @blur="save()" @keydown.enter.prevent="save()" 
                                           class="w-full text-center text-sm font-bold rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-shadow bg-white/50 py-1.5"
                                           placeholder="?">
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <template x-if="variance === null">
                                    <span class="text-sm text-gray-400">-</span>
                                </template>
                                <template x-if="variance !== null">
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded text-sm font-bold"
                                          :class="{
                                            'bg-emerald-100 text-emerald-700': variance === 0,
                                            'bg-red-100 text-red-700': variance < 0,
                                            'bg-blue-100 text-blue-700': variance > 0
                                          }">
                                        <span x-text="variance > 0 ? '+' + variance : variance"></span>
                                    </span>
                                </template>
                            </td>
                            <td class="px-4 py-3">
                                @if($isReadonly)
                                    <p class="text-xs text-gray-600 truncate max-w-[200px]" title="{{ $opnameItem->notes }}">{{ $opnameItem->notes ?? '-' }}</p>
                                @else
                                    <input type="text" x-model="notes" @blur="save()" @keydown.enter.prevent="save()" 
                                           class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-shadow bg-white/50 py-1.5"
                                           placeholder="Keterangan...">
                                @endif
                            </td>
                            
                            @if(!$isReadonly)
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center">
                                    <template x-if="state === 'idle'">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                                    </template>
                                    <template x-if="state === 'saving'">
                                        <svg class="animate-spin w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="state === 'saved'">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                    <template x-if="state === 'error'">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </template>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>{{-- end flex-1 --}}

    {{-- ─── Sticky Sidebar ─── --}}
    <div class="w-72 flex-shrink-0" x-data="stockOpnameItems()">
        <div class="sticky top-6 space-y-4">

            {{-- Progress card — gradient header --}}
            <div class="rounded-2xl overflow-hidden shadow-xl border border-indigo-200/60">
                {{-- Gradient header --}}
                <div style="background:linear-gradient(135deg,#4f46e5 0%,#6d28d9 60%,#7c3aed 100%); padding:16px 18px 14px;">
                    <h3 style="color:rgba(255,255,255,0.75); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin:0 0 12px;">Progress Audit</h3>
                    {{-- Completed fraction --}}
                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:6px;">
                        <span style="color:#e0e7ff; font-size:11px;">Diperiksa</span>
                        <span style="color:#fff; font-size:13px; font-weight:700;"><span x-text="completedCount"></span> / {{ count($stockOpname->items) }}</span>
                    </div>
                    {{-- Progress bar --}}
                    <div style="background:rgba(255,255,255,0.2); border-radius:999px; height:8px; overflow:hidden; margin-bottom:4px;">
                        <div style="height:100%; border-radius:999px; background:linear-gradient(90deg,#a5b4fc,#e0e7ff); transition:width .5s ease;"
                             :style="'width:' + (completedCount / {{ count($stockOpname->items) }} * 100) + '%'"></div>
                    </div>
                    <p style="color:rgba(255,255,255,0.55); font-size:10px; margin:0;" x-text="Math.round(completedCount / {{ count($stockOpname->items) }} * 100) + '% selesai'"></p>
                </div>
                {{-- Stats row --}}
                @php
                    $totalItems   = count($stockOpname->items);
                    $checkedItems = $stockOpname->items->whereNotNull('actual_stok')->count();
                    $minusItems   = $stockOpname->items->filter(fn($i) => $i->actual_stok !== null && $i->actual_stok < $i->system_stok)->count();
                    $plusItems    = $stockOpname->items->filter(fn($i) => $i->actual_stok !== null && $i->actual_stok > $i->system_stok)->count();
                    $matchItems   = $stockOpname->items->filter(fn($i) => $i->actual_stok !== null && $i->actual_stok == $i->system_stok)->count();
                @endphp
                <div style="background:#fff; display:grid; grid-template-columns:repeat(3,1fr); border-top:1px solid #e0e7ff;">
                    <div style="text-align:center; padding:12px 6px; border-right:1px solid #f1f5f9;">
                        <div style="font-size:20px; font-weight:800; color:#059669; line-height:1;">{{ $matchItems }}</div>
                        <div style="font-size:10px; color:#6ee7b7; font-weight:600; margin-top:2px;">Sesuai</div>
                    </div>
                    <div style="text-align:center; padding:12px 6px; border-right:1px solid #f1f5f9;">
                        <div style="font-size:20px; font-weight:800; color:#dc2626; line-height:1;">{{ $minusItems }}</div>
                        <div style="font-size:10px; color:#fca5a5; font-weight:600; margin-top:2px;">Kurang</div>
                    </div>
                    <div style="text-align:center; padding:12px 6px;">
                        <div style="font-size:20px; font-weight:800; color:#2563eb; line-height:1;">{{ $plusItems }}</div>
                        <div style="font-size:10px; color:#93c5fd; font-weight:600; margin-top:2px;">Lebih</div>
                    </div>
                </div>
            </div>

            {{-- Info card --}}
            <div class="rounded-2xl overflow-hidden shadow-xl border border-violet-200/60">
                <div style="background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 100%); padding:12px 18px 10px;">
                    <h3 style="color:rgba(255,255,255,0.75); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin:0;">Informasi Sesi</h3>
                </div>
                <div style="background:#fff; padding:14px 18px; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                        <span style="color:#6b7280;">ID Audit</span>
                        <span style="font-weight:700; color:#4f46e5; font-family:monospace;">#OPN-{{ str_pad($stockOpname->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                        <span style="color:#6b7280;">Tanggal</span>
                        <span style="font-weight:600; color:#111827;">{{ $stockOpname->start_date->format('d M Y') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                        <span style="color:#6b7280;">Pelaksana</span>
                        <span style="font-weight:600; color:#111827; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $stockOpname->user->name }}">{{ $stockOpname->user->name }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                        <span style="color:#6b7280;">Status</span>
                        @if($stockOpname->status === 'draft')
                            <span style="padding:2px 10px; border-radius:999px; font-size:10px; font-weight:700; background:#f3f4f6; color:#374151; text-transform:uppercase;">Draft</span>
                        @elseif($stockOpname->status === 'in_progress')
                            <span style="padding:2px 10px; border-radius:999px; font-size:10px; font-weight:700; background:#dbeafe; color:#1d4ed8; text-transform:uppercase; animation:qrBlink 1.5s ease-in-out infinite;">In Progress</span>
                        @elseif($stockOpname->status === 'completed')
                            <span style="padding:2px 10px; border-radius:999px; font-size:10px; font-weight:700; background:#d1fae5; color:#065f46; text-transform:uppercase;">Completed</span>
                        @else
                            <span style="padding:2px 10px; border-radius:999px; font-size:10px; font-weight:700; background:#fee2e2; color:#991b1b; text-transform:uppercase;">Cancelled</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action buttons --}}
            @if(!$isReadonly)
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button type="button" onclick="openModal('modal-complete-audit')"
                    style="width:100%; padding:11px; background:linear-gradient(135deg,#059669,#047857); color:#fff; border:none; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 4px 14px rgba(5,150,105,0.35); transition:opacity .15s;"
                    onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Selesaikan Audit
                </button>
                <button type="button" onclick="openModal('modal-cancel-session')"
                    style="width:100%; padding:11px; background:#fff; border:1px solid #d1d5db; color:#374151; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:background .15s;"
                    onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batalkan Sesi
                </button>
            </div>
            @endif

            {{-- Back link --}}
            <a href="{{ route($routePrefix . '.stock-opnames.index') }}"
               style="width:100%; padding:11px; background:rgba(255,255,255,0.7); border:1px solid #e5e7eb; color:#4b5563; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px; backdrop-filter:blur(8px); transition:background .15s; box-sizing:border-box;"
               onmouseover="this.style.background='rgba(249,250,251,0.9)'" onmouseout="this.style.background='rgba(255,255,255,0.7)'">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>

        </div>
    </div>{{-- end sticky sidebar --}}

</div>{{-- end flex --}}

<script>
    document.addEventListener('alpine:init', () => {
        let itemsCompleted = {{ collect($stockOpname->items)->whereNotNull('actual_stok')->count() }};

        Alpine.data('stockOpnameItems', () => ({
            completedCount: itemsCompleted,
            incrementCompleted() { this.completedCount++; }
        }));

        Alpine.data('auditItem', (itemId, systemStok, initActualStok, initNotes) => ({
            itemId: itemId,
            systemStok: systemStok,
            actualStok: initActualStok !== null ? initActualStok : '',
            notes: initNotes || '',
            variance: initActualStok !== null ? (initActualStok - systemStok) : null,
            state: initActualStok !== null ? 'saved' : 'idle',
            lastSavedStok: initActualStok !== null ? initActualStok : null,
            lastSavedNotes: initNotes || '',

            async save() {
                if (this.actualStok === '' || this.actualStok === null) return;
                
                // Prevent unnecessary saves
                if (this.actualStok == this.lastSavedStok && this.notes == this.lastSavedNotes) return;
                
                let isFirstTimeSave = this.lastSavedStok === null;
                
                this.state = 'saving';
                this.variance = this.actualStok - this.systemStok;

                try {
                    const response = await fetch(`{{ route($routePrefix . '.stock-opnames.index') }}/items/${this.itemId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            actual_stok: this.actualStok,
                            notes: this.notes
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.state = 'saved';
                        this.variance = data.variance;
                        this.lastSavedStok = this.actualStok;
                        this.lastSavedNotes = this.notes;

                        if (isFirstTimeSave) {
                            // Find parent component and increment
                            this.$el.closest('[x-data^="stockOpnameItems"]').__x.$data.incrementCompleted();
                        }
                    } else {
                        this.state = 'error';
                    }
                } catch (error) {
                    this.state = 'error';
                    console.error('Error saving item:', error);
                }
            }
        }));
    });
</script>

@if(!$isReadonly)
@push('scripts')
    {{-- Cancel Session Modal --}}
    <x-popup id="modal-cancel-session" title="Batalkan Sesi"
        message="Yakin ingin membatalkan audit ini? Semua progress audit yang belum diselesaikan akan hilang."
        formId="cancel-session-form"
        confirmText="Batalkan Sesi"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
        cancelText="Tutup" />

    <form id="cancel-session-form" action="{{ route($routePrefix . '.stock-opnames.cancel', $stockOpname->id) }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Complete Audit Modal --}}
    <x-popup id="modal-complete-audit" title="Selesaikan Audit"
        message="Selesaikan audit? Stok pada sistem akan disesuaikan secara permanen berdasarkan input aktual Anda."
        formId="complete-audit-form"
        icon="info"
        confirmText="Selesaikan Audit"
        confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal" />

    <form id="complete-audit-form" action="{{ route($routePrefix . '.stock-opnames.complete', $stockOpname->id) }}" method="POST" style="display: none;">
        @csrf
    </form>
@endpush
@endif

@endsection
