@extends('user.layouts.dashboard-user')

@section('title', 'Ajukan Permintaan Pengadaan')

@section('user')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
            <a href="{{ route('user.procurement.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Permintaan Pengadaan</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-slate-400 font-medium">Ajukan Permintaan</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">Ajukan Permintaan Pengadaan</h1>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Minta Admin untuk mengadakan barang yang belum tersedia di sistem</p>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="text-sm text-red-700 space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700 shadow-sm overflow-hidden">
        {{-- Card header --}}
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 bg-transparent flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-500/20 border border-transparent dark:border-orange-500/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-slate-100">Detail Permintaan</p>
                <p class="text-xs text-gray-500 dark:text-slate-400">Isi informasi barang yang ingin Anda minta</p>
            </div>
        </div>

        <form method="POST" action="{{ route('user.procurement.store') }}" class="px-6 py-5 space-y-5">
            @csrf

            {{-- Nama Barang --}}
            <div class="space-y-1.5">
                <label for="nama_barang" class="block text-sm font-semibold text-gray-700 dark:text-slate-200">
                    Nama Barang <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_barang" name="nama_barang"
                       value="{{ old('nama_barang') }}"
                       placeholder="cth: Laptop Dell Latitude, Kursi Ergonomis, ATK..."
                       required
                       class="w-full px-3 py-2.5 text-sm bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 border border-gray-200 dark:border-slate-600 placeholder-gray-400 dark:placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition @error('nama_barang') border-red-300 bg-red-50 dark:bg-red-900/30 dark:border-red-600 @enderror">
                @error('nama_barang')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div class="space-y-1.5">
                <label for="jumlah" class="block text-sm font-semibold text-gray-700 dark:text-slate-200">
                    Jumlah yang Dibutuhkan <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" id="jumlah" name="jumlah"
                           value="{{ old('jumlah', 1) }}"
                           min="1" max="9999" required
                           class="w-32 px-3 py-2.5 text-sm bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 border border-gray-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition text-center font-semibold @error('jumlah') border-red-300 bg-red-50 dark:bg-red-900/30 dark:border-red-600 @enderror">
                    <span class="text-sm text-gray-500 dark:text-slate-300">unit</span>
                </div>
                @error('jumlah')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alasan --}}
            <div class="space-y-1.5">
                <label for="alasan" class="block text-sm font-semibold text-gray-700 dark:text-slate-200">
                    Alasan / Keperluan
                    <span class="text-xs font-normal text-gray-400 dark:text-slate-400">(opsional)</span>
                </label>
                <textarea id="alasan" name="alasan" rows="4"
                          placeholder="Jelaskan mengapa barang ini dibutuhkan, untuk keperluan apa, dll..."
                          class="w-full px-3 py-2.5 text-sm bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 border border-gray-200 dark:border-slate-600 placeholder-gray-400 dark:placeholder-slate-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition resize-none">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info box --}}
            <div class="bg-blue-50/50 dark:bg-slate-800/80 border border-blue-100 dark:border-blue-500/30 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-4 h-4 text-blue-500 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed font-medium">
                    Permintaan Anda akan diteruskan ke Admin. Jika disetujui, barang akan ditambahkan ke inventaris dan Anda akan mendapat notifikasi.
                </p>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('user.procurement.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-slate-300 bg-white/80 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/50 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
