@extends('admin.layouts.dashboard')

@section('title', 'Manajemen Paket Perkakas (Tooling Kit SPK)')

@section('content')
<style>
/* ══ Tooling Kits — Premium UI ══ */
html.dark .kit-card    { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .kit-title   { color: #f1f5f9 !important; }
html.dark .kit-sub     { color: #94a3b8 !important; }
html.dark .kit-item-box{ background-color: #0f172a !important; border-color: #334155 !important; }
html.dark .kit-input   { background-color: #0f172a !important; border-color: #334155 !important; color: #f1f5f9 !important; }
html.dark .kit-modal-bg{ background-color: #1e293b !important; border-color: #334155 !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5"
     x-data="{
        createModal: false,
        kitRows: [{ item_id: '', jumlah: 1, catatan: '' }],
        addRow()    { this.kitRows.push({ item_id: '', jumlah: 1, catatan: '' }); },
        removeRow(i){ if (this.kitRows.length > 1) this.kitRows.splice(i, 1); }
     }">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="kit-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="kit-title text-xl font-bold text-gray-900 tracking-tight">Tooling Kit SPK</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                Prodi Manufaktur & Mesin
                            </span>
                        </div>
                        <p class="kit-sub text-sm text-gray-500 mt-0.5">Paket perkakas standar per jenis pengerjaan benda kerja untuk peminjaman cepat teknisi</p>
                    </div>
                </div>
                <button @click="createModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-amber-200 transition-all flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Paket SPK Baru
                </button>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ═══ KIT GRID ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse($kits as $kit)
        <div class="kit-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
            {{-- Card Header --}}
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-lg">
                                {{ $kit->kode }}
                            </span>
                            @if($kit->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-500">Nonaktif</span>
                            @endif
                        </div>
                        <h3 class="kit-title text-base font-bold text-gray-900 mt-2 leading-snug">{{ $kit->nama }}</h3>
                        <p class="text-xs text-indigo-600 font-semibold mt-0.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $kit->target_machine ?? 'Umum / Konvensional' }}
                        </p>
                        @if($kit->deskripsi)
                            <p class="kit-sub text-xs text-gray-400 mt-1.5 leading-relaxed">{{ $kit->deskripsi }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-1 flex-shrink-0">
                        <form method="POST" action="{{ route($routePrefix . '.tooling-kits.toggle-status', $kit->id) }}">
                            @csrf
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="{{ $kit->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </button>
                        </form>
                        <form method="POST" action="{{ route($routePrefix . '.tooling-kits.destroy', $kit->id) }}" onsubmit="return confirm('Hapus paket ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Paket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Kit Items List --}}
                <div class="kit-item-box bg-gray-50 rounded-xl p-3.5 border border-gray-100 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <p class="kit-title text-xs font-bold text-gray-700">
                            Daftar Perkakas
                        </p>
                        <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg">{{ $kit->kitItems->count() }} item</span>
                    </div>
                    <div class="space-y-1.5 max-h-44 overflow-y-auto pr-0.5">
                        @foreach($kit->kitItems as $kItem)
                        <div class="kit-card flex items-center justify-between text-xs py-1.5 px-2.5 rounded-lg bg-white border border-gray-100">
                            <div class="flex items-center gap-2 overflow-hidden min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0"></span>
                                <span class="kit-title font-medium text-gray-800 truncate">{{ $kItem->item->nama ?? 'Item tidak ditemukan' }}</span>
                                @if($kItem->catatan)
                                    <span class="kit-sub text-[10px] text-gray-400 truncate hidden sm:block">({{ $kItem->catatan }})</span>
                                @endif
                            </div>
                            <span class="font-extrabold text-gray-900 bg-amber-50 border border-amber-200 text-[11px] px-2 py-0.5 rounded-lg flex-shrink-0 ml-2">{{ $kItem->jumlah }}×</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2m0 0h14m-14 0a2 2 0 002 2v2a2 2 0 01-2 2M5 9V7a2 2 0 012-2h6a2 2 0 012 2v2M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>
            </div>
            <p class="text-base font-bold text-gray-800">Belum Ada Paket Tooling Kit</p>
            <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto leading-relaxed">
                Buat paket standar proses permesinan (paket bubut poros, frais roda gigi, dll.) untuk mempercepat peminjaman teknisi.
            </p>
            <button @click="createModal = true" class="mt-4 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                + Buat Paket Sekarang
            </button>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($kits->hasPages())
    <div class="kit-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        {{ $kits->links() }}
    </div>
    @endif

    {{-- ═══ MODAL: BUAT PAKET ═══ --}}
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="createModal = false" class="kit-modal-bg bg-white rounded-2xl shadow-2xl border border-gray-100 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-500 sticky top-0"></div>
            <div class="p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="kit-title text-base font-bold text-gray-900">Buat Paket Perkakas SPK Baru</h3>
                        <p class="kit-sub text-xs text-gray-400 mt-0.5">Tentukan alat potong, alat ukur, dan pencekam untuk 1 jenis pengerjaan</p>
                    </div>
                    <button @click="createModal = false" class="text-gray-300 hover:text-gray-600 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route($routePrefix . '.tooling-kits.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kode Paket *</label>
                            <input type="text" name="kode" placeholder="Contoh: KIT-LATHE-01" required
                                   class="kit-input w-full text-xs rounded-xl border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Paket Pengerjaan *</label>
                            <input type="text" name="nama" placeholder="Contoh: Pemesinan Poros Bertingkat" required
                                   class="kit-input w-full text-xs rounded-xl border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Mesin Target</label>
                            <input type="text" name="target_machine" placeholder="Contoh: Mesin Bubut Konvensional"
                                   class="kit-input w-full text-xs rounded-xl border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi Pengerjaan</label>
                            <input type="text" name="deskripsi" placeholder="Keterangan proses pengerjaan benda kerja"
                                   class="kit-input w-full text-xs rounded-xl border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-2.5 px-3">
                        </div>
                    </div>

                    {{-- Dynamic Items --}}
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-bold text-gray-800">Daftar Perkakas dalam Paket *</label>
                            <button type="button" @click="addRow()" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-700 px-2.5 py-1 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors border border-amber-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Alat
                            </button>
                        </div>
                        <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                            <template x-for="(row, index) in kitRows" :key="index">
                                <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <select :name="'item_ids[' + index + ']'" x-model="row.item_id" required
                                                class="w-full text-xs rounded-lg border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-1.5 px-2 bg-white">
                                            <option value="">— Pilih Perkakas/Alat —</option>
                                            @foreach($availableItems as $item)
                                                <option value="{{ $item->id }}">{{ $item->kode ? '['.$item->kode.'] ' : '' }}{{ $item->nama }} (Stok: {{ $item->stok_peminjaman }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-16">
                                        <input type="number" :name="'quantities[' + index + ']'" x-model="row.jumlah" min="1" required placeholder="Jml"
                                               class="w-full text-xs rounded-lg border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-1.5 px-2 bg-white text-center font-bold">
                                    </div>
                                    <div class="w-28">
                                        <input type="text" :name="'notes[' + index + ']'" x-model="row.catatan" placeholder="Catatan"
                                               class="w-full text-xs rounded-lg border-gray-200 focus:ring-amber-500 focus:border-amber-500 py-1.5 px-2 bg-white">
                                    </div>
                                    <button type="button" @click="removeRow(index)" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="createModal = false" class="px-3.5 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-all">
                            Simpan Paket Perkakas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
