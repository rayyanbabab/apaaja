@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route($routePrefix . '.maintenance.index') }}"
           class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Masukkan Barang ke Servis</h1>
            <p class="text-sm text-gray-500">Catat barang yang perlu diperbaiki. Stok akan berkurang sementara.</p>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="flex items-start gap-3 bg-orange-50 border border-orange-100 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-orange-700">Saat barang dicatat masuk servis, stok akan dikurangi sementara. Stok akan dikembalikan setelah servis selesai, atau <strong>tidak dikembalikan</strong> jika barang di-scrap.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-semibold text-gray-700">Form Masuk Servis</h2>
        </div>
        <form method="POST" action="{{ route($routePrefix . '.maintenance.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Item Selection --}}
            <div>
                <label for="item_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Pilih Barang <span class="text-red-500">*</span>
                </label>
                <select id="item_id" name="item_id"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white @error('item_id') border-red-400 @enderror"
                        x-data x-on:change="
                            const sel = $el.options[$el.selectedIndex];
                            document.getElementById('stock-info').textContent = sel.dataset.stok ? 'Stok tersedia: ' + sel.dataset.stok + ' unit' : '';
                        ">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}"
                                data-stok="{{ $item->stok_total }}"
                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }} ({{ $item->kode }}) — Stok: {{ $item->stok_total }} unit
                        </option>
                    @endforeach
                </select>
                <p id="stock-info" class="text-xs text-blue-600 mt-1 font-medium"></p>
                @error('item_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div>
                <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Jumlah Unit Yang Diservis <span class="text-red-500">*</span>
                </label>
                <input type="number" id="jumlah" name="jumlah" min="1"
                       value="{{ old('jumlah', 1) }}"
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 @error('jumlah') border-red-400 @enderror">
                @error('jumlah')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kondisi Masuk --}}
            <div>
                <label for="kondisi_masuk" class="block text-sm font-semibold text-gray-700 mb-1.5">Kondisi Saat Masuk</label>
                <input type="text" id="kondisi_masuk" name="kondisi_masuk"
                       value="{{ old('kondisi_masuk') }}"
                       placeholder="Contoh: Layar retak, tidak bisa menyala..."
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
            </div>

            {{-- Catatan --}}
            <div>
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Kerusakan</label>
                <textarea id="catatan" name="catatan" rows="3"
                          placeholder="Deskripsi lengkap kerusakan atau masalah yang perlu diperbaiki..."
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 resize-none">{{ old('catatan') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-2.5 text-sm font-semibold bg-orange-600 hover:bg-orange-700 text-white rounded-xl shadow-sm transition-all duration-200">
                    Masukkan ke Servis &amp; Kurangi Stok
                </button>
                <a href="{{ route($routePrefix . '.maintenance.index') }}"
                   class="px-5 py-2.5 text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
