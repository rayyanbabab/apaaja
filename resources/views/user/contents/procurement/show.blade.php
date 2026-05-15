@extends('user.layouts.dashboard-user')

@section('title', 'Detail Permintaan Pengadaan')

@section('user')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
        <a href="{{ route('user.procurement.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Permintaan Pengadaan</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-600 dark:text-slate-400 font-medium">Detail #{{ $procurement->id }}</span>
    </div>

    {{-- Status Card --}}
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-sm overflow-hidden mb-4">
        {{-- Top gradient bar based on status --}}
        <div class="h-1.5 w-full
            @if($procurement->status === 'pending')  bg-gradient-to-r from-yellow-400 to-amber-400
            @elseif($procurement->status === 'approved') bg-gradient-to-r from-emerald-400 to-green-400
            @else bg-gradient-to-r from-red-400 to-rose-400
            @endif">
        </div>

        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mb-0.5">Permintaan #{{ $procurement->id }}</p>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ $procurement->nama_barang }}</h2>
                        <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">{{ $procurement->jumlah }} unit • {{ $procurement->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</p>
                    </div>
                </div>

                {{-- Status --}}
                @if($procurement->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800/50 flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                        Pending
                    </span>
                @elseif($procurement->status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Disetujui
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Ditolak
                    </span>
                @endif
            </div>

            {{-- Alasan --}}
            @if($procurement->alasan)
                <div class="mt-5 pt-5 border-t border-gray-100 dark:border-slate-700/50">
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-2">Alasan / Keperluan</p>
                    <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">{{ $procurement->alasan }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Admin Response (if reviewed) --}}
    @if($procurement->status !== 'pending')
        <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white/20 dark:border-slate-700/50 shadow-sm p-5 mb-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-3">Tanggapan Admin</p>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-700 dark:text-slate-200">{{ $procurement->reviewer?->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 dark:text-slate-500 mb-2">{{ $procurement->reviewed_at?->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</p>
                    @if($procurement->admin_notes)
                        <p class="text-sm text-gray-700 dark:text-slate-300 bg-gray-50 dark:bg-slate-900/50 rounded-lg px-3 py-2">{{ $procurement->admin_notes }}</p>
                    @else
                        <p class="text-xs text-gray-400 dark:text-slate-500 italic">Tidak ada catatan.</p>
                    @endif
                </div>
            </div>

            {{-- If approved: show linked item --}}
            @if($procurement->status === 'approved' && $procurement->item)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700/50">
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wide mb-2">Barang yang Ditambahkan</p>
                    <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 rounded-xl px-3 py-2.5">
                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-800/50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ $procurement->item->nama }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400/80">Kode: {{ $procurement->item->kode }} • Stok bertambah {{ $procurement->jumlah }} unit</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('user.procurement.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>

        @if($procurement->status === 'pending')
            <form method="POST" action="{{ route('user.procurement.cancel', $procurement->id) }}"
                  onsubmit="return confirm('Yakin ingin membatalkan permintaan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800/30 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batalkan Permintaan
                </button>
            </form>
        @endif
    </div>

</div>
@endsection
