@extends('user.layouts.dashboard-user')

@section('title', 'Permintaan Pengadaan Saya')

@section('user')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Permintaan Pengadaan</h1>
            <p class="text-sm text-gray-500 mt-1">Ajukan permintaan barang yang belum ada di inventaris</p>
        </div>
        <a href="{{ route('user.procurement.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Ajukan Permintaan
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        @php
            $pending  = (int)($counts['pending']  ?? 0);
            $approved = (int)($counts['approved'] ?? 0);
            $rejected = (int)($counts['rejected'] ?? 0);
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $pending }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pending</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $approved }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Disetujui</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-red-500">{{ $rejected }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Ditolak</p>
        </div>
    </div>

    {{-- List --}}
    @forelse($procurements as $item)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:border-orange-200 hover:shadow-md transition-all duration-200 mb-3">
            <div class="p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Left --}}
                    <div class="flex items-start gap-3 flex-1">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $item->nama_barang }}</h3>
                            <div class="flex flex-wrap gap-2 mt-1.5">
                                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                    {{ $item->jumlah }} unit
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $item->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                                </span>
                            </div>
                            @if($item->alasan)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $item->alasan }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Right --}}
                    <div class="flex flex-col items-start sm:items-end gap-2">
                        {{-- Status badge --}}
                        @if($item->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                Menunggu Review
                            </span>
                        @elseif($item->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Ditolak
                            </span>
                        @endif

                        <a href="{{ route('user.procurement.show', $item->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-14 text-center">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-700 mb-1">Belum ada permintaan</p>
            <p class="text-xs text-gray-400 mb-5">Ajukan permintaan untuk barang yang belum tersedia di inventaris</p>
            <a href="{{ route('user.procurement.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Permintaan Pertama
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($procurements->hasPages())
        <div class="mt-4">{{ $procurements->links() }}</div>
    @endif

</div>
@endsection
