@extends('admin.layouts.dashboard')
@php
    $routePrefix = Auth::user()->role->value === 'operator' ? 'staff' : 'admin';
@endphp
@section('content')

{{-- ═══ Background Orbs ═══ --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
    <div class="absolute top-[10%] left-[10%] w-96 h-96 bg-blue-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute bottom-[10%] right-[10%] w-96 h-96 bg-indigo-400/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
</div>

<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route($routePrefix . '.stock-opnames.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Mulai Stock Opname</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem akan mengambil data stok saat ini sebagai referensi audit.</p>
        </div>
    </div>

    <form action="{{ route($routePrefix . '.stock-opnames.store') }}" method="POST" class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-xl rounded-2xl p-6">
        @csrf
        
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-shadow bg-white/50 text-sm" placeholder="Contoh: Audit akhir bulan untuk gudang utama..."></textarea>
            </div>

            <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Informasi Penting</p>
                        <p class="opacity-90 leading-relaxed">
                            Saat Anda menekan tombol "Mulai Audit", sistem akan secara otomatis menyimpan jumlah stok sistem saat ini untuk <b>seluruh barang</b>. Pastikan tidak ada transaksi barang keluar/masuk yang tertunda sebelum memulai.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route($routePrefix . '.stock-opnames.index') }}" class="px-5 py-2.5 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Mulai Audit Sekarang
            </button>
        </div>
    </form>
</div>
@endsection
