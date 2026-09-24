@extends('user.layouts.dashboard-user')

@section('title', 'Form Pengajuan Peminjaman')

@section('user')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-full px-3 py-1">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-pulse"></span>
                    Pengajuan Langsung
                </span>
                <span class="text-xs text-gray-400 dark:text-slate-400">
                    Maksimal {{ $maxBorrowDays }} hari
                </span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Pengajuan Pinjam Alat</h1>
            <p class="text-sm text-gray-400 dark:text-slate-400 mt-1">Lengkapi rincian tanggal dan kebutuhan peminjaman perkakas berikut</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('user.borrowing.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-gray-700 dark:text-slate-200 text-xs font-semibold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Katalog
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden sticky top-20">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Informasi Alat</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400">Spesifikasi barang yang akan dipinjam</p>
                </div>

                <div class="p-5 space-y-4">
                    @if($item->gambar)
                        <div class="w-full h-40 rounded-xl overflow-hidden bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div>
                            <span class="text-[11px] font-semibold text-gray-400 dark:text-slate-400 block">Nama Barang</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->nama }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-slate-400">Stok Peminjaman</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300">
                                {{ $item->stok_peminjaman }} unit
                            </span>
                        </div>
                        @if($item->category)
                        <div class="flex justify-between items-center py-2 border-t border-gray-100 dark:border-slate-800">
                            <span class="text-xs text-gray-500 dark:text-slate-400">Kategori</span>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ $item->category->nama }}</span>
                        </div>
                        @endif
                        @if($item->keterangan)
                        <div class="pt-3 border-t border-gray-100 dark:border-slate-800">
                            <span class="text-[11px] font-semibold text-gray-400 dark:text-slate-400 block mb-1">Deskripsi / Catatan</span>
                            <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed">{{ $item->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-850 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Formulir Peminjaman</h3>
                    <p class="text-xs text-gray-400 dark:text-slate-400">Isi data peminjaman dengan lengkap dan benar</p>
                </div>

                <form action="{{ route('user.borrowing.store') }}" method="POST" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                    @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/40 text-rose-800 dark:text-rose-300 text-xs space-y-1">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Harap perbaiki kesalahan berikut:</span>
                        </div>
                        @foreach($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label for="jumlah" class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">
                                Jumlah Unit Pinjam <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   class="w-full text-xs font-semibold px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-blue-500 transition @error('jumlah') border-rose-300 focus:ring-rose-500 @enderror"
                                   id="jumlah"
                                   name="jumlah"
                                   min="1"
                                   max="{{ $item->stok_peminjaman }}"
                                   value="{{ old('jumlah', 1) }}"
                                   required>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-1">Maksimal: {{ $item->stok_peminjaman }} unit</p>
                            @error('jumlah')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_pinjam" class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">
                                Tanggal Mulai Pinjam <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   class="w-full text-xs font-semibold px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-blue-500 transition @error('tanggal_pinjam') border-rose-300 focus:ring-rose-500 @enderror"
                                   id="tanggal_pinjam"
                                   name="tanggal_pinjam"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal_pinjam') }}"
                                   required>
                            @error('tanggal_pinjam')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="tanggal_kembali_rencana" class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">
                                Rencana Pengembalian <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   class="w-full text-xs font-semibold px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-blue-500 transition @error('tanggal_kembali_rencana') border-rose-300 focus:ring-rose-500 @enderror"
                                   id="tanggal_kembali_rencana"
                                   name="tanggal_kembali_rencana"
                                   value="{{ old('tanggal_kembali_rencana') }}"
                                   required>
                            <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-1">
                                💡 Batas waktu peminjaman maksimal <strong>{{ $maxBorrowDays }} hari</strong>.
                            </p>
                            @error('tanggal_kembali_rencana')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">
                            Tujuan &amp; Keperluan Peminjaman
                        </label>
                        <textarea class="w-full text-xs px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-blue-500 transition resize-none @error('keterangan') border-rose-300 focus:ring-rose-500 @enderror"
                                  id="keterangan"
                                  name="keterangan"
                                  rows="3"
                                  placeholder="Jelaskan kebutuhan atau praktikum penggunaan alat ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kondisi_pinjam" class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">
                            Catatan Kondisi Awal (Opsional)
                        </label>
                        <textarea class="w-full text-xs px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-900 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-blue-500 transition resize-none @error('kondisi_pinjam') border-rose-300 focus:ring-rose-500 @enderror"
                                  id="kondisi_pinjam"
                                  name="kondisi_pinjam"
                                  rows="2"
                                  placeholder="Catat kondisi fisik atau kelengkapan jika ada catatan khusus...">{{ old('kondisi_pinjam') }}</textarea>
                        @error('kondisi_pinjam')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 pt-4 border-t border-gray-100 dark:border-slate-800">
                        <a href="{{ route('user.borrowing.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/25 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Pengajuan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali_rencana');
    const maxBorrowDays = {{ $maxBorrowDays }};

    function updateKembali() {
        if (!tanggalPinjam.value) return;
        const pinjamDate = new Date(tanggalPinjam.value);
        const nextDay = new Date(pinjamDate);
        nextDay.setDate(nextDay.getDate() + 1);
        tanggalKembali.min = nextDay.toISOString().split('T')[0];

        if (!tanggalKembali.value || !document.getElementById('tanggal_kembali_rencana').dataset.userEdited) {
            const defaultReturn = new Date(pinjamDate);
            defaultReturn.setDate(defaultReturn.getDate() + maxBorrowDays);
            tanggalKembali.value = defaultReturn.toISOString().split('T')[0];
        }

        const maxDate = new Date(pinjamDate);
        maxDate.setDate(maxDate.getDate() + maxBorrowDays);
        tanggalKembali.max = maxDate.toISOString().split('T')[0];

        if (tanggalKembali.value && new Date(tanggalKembali.value) <= pinjamDate) {
            tanggalKembali.value = '';
        }
    }

    tanggalPinjam.addEventListener('change', updateKembali);
    tanggalKembali.addEventListener('change', function() {
        this.dataset.userEdited = '1';
    });

    if (!tanggalPinjam.value) {
        tanggalPinjam.value = new Date().toISOString().split('T')[0];
        updateKembali();
    }
});
</script>
@endpush
