@extends('admin.layouts.dashboard')

@section('title', 'Dokumen BAP Digital Signature')

@section('content')
<style>
/* ══ BAP Index — Premium UI ══ */
.bap-card { transition: box-shadow 0.2s; }
html.dark .bap-card { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .bap-title { color: #f1f5f9 !important; }
html.dark .bap-sub   { color: #94a3b8 !important; }
html.dark .bap-input { background-color: #0f172a !important; border-color: #334155 !important; color: #f1f5f9 !important; }
html.dark .bap-th    { background-color: #1e293b !important; color: #94a3b8 !important; border-color: #334155 !important; }
html.dark .bap-tr:hover { background-color: rgba(51,65,85,0.4) !important; }
html.dark .bap-td-text { color: #e2e8f0 !important; }
html.dark .bap-td-sub  { color: #94a3b8 !important; }
html.dark .bap-banner  { background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(139,92,246,0.08) 100%) !important; border-color: rgba(99,102,241,0.25) !important; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="bap-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Decorative top bar --}}
        <div class="h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>
        <div class="p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-md shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="bap-title text-xl font-bold text-gray-900 tracking-tight">Dokumen BAP Digital Signature</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                E-Signature & QR
                            </span>
                        </div>
                        <p class="bap-sub text-sm text-gray-500 mt-0.5">Berita Acara Peminjaman resmi dengan tanda tangan digital & token verifikasi QR</p>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.borrowing-requests.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Persetujuan Peminjaman
                </a>
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

    {{-- ═══ KPI STAT CARDS ═══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total --}}
        <div class="bap-card relative bg-white rounded-2xl border border-gray-100 shadow-sm p-5 overflow-hidden group hover:shadow-md">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="bap-sub text-xs font-medium text-gray-400 uppercase tracking-wide">Total BAP Disetujui</p>
                    <p class="bap-title text-2xl font-extrabold text-gray-900 mt-0.5 leading-none">{{ $totalEligible }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">dokumen</p>
                </div>
            </div>
        </div>

        {{-- Menunggu TTD --}}
        <div class="bap-card relative bg-white rounded-2xl border border-amber-100 shadow-sm p-5 overflow-hidden group hover:shadow-md">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-50 rounded-bl-3xl opacity-60"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-amber-600 uppercase tracking-wide">Menunggu TTD</p>
                    <p class="text-2xl font-extrabold text-amber-700 mt-0.5 leading-none">{{ $unsignedCount }}</p>
                    <p class="text-xs text-amber-500 mt-0.5">perlu ditandatangani</p>
                </div>
            </div>
            @if($unsignedCount > 0)
            <div class="absolute top-3 right-3">
                <span class="flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span></span>
            </div>
            @endif
        </div>

        {{-- Sudah TTD --}}
        <div class="bap-card relative bg-white rounded-2xl border border-emerald-100 shadow-sm p-5 overflow-hidden group hover:shadow-md">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-3xl opacity-60"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Sah & Ditandatangani</p>
                    <p class="text-2xl font-extrabold text-emerald-700 mt-0.5 leading-none">{{ $signedCount }}</p>
                    <p class="text-xs text-emerald-500 mt-0.5">dokumen terverifikasi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ FILTER & SEARCH ═══ --}}
    <div class="bap-card bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route($routePrefix . '.bap.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                <a href="{{ route($routePrefix . '.bap.index') }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua BAP ({{ $totalEligible }})
                </a>
                <a href="{{ route($routePrefix . '.bap.index', ['status' => 'unsigned']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('status') === 'unsigned' ? 'bg-amber-500 text-white shadow-sm shadow-amber-200' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' }}">
                    Menunggu TTD ({{ $unsignedCount }})
                </a>
                <a href="{{ route($routePrefix . '.bap.index', ['status' => 'signed']) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ request('status') === 'signed' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                    Ditandatangani ({{ $signedCount }})
                </a>
            </div>
            <div class="relative flex-1 w-full md:max-w-sm ml-auto">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. BAP, peminjam, barang..."
                       class="bap-input w-full text-xs pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-800 placeholder-gray-400">
            </div>
        </form>
    </div>

    {{-- ═══ MAIN TABLE ═══ --}}
    <div class="bap-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                <thead class="bap-th bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Peminjam & Dokumen</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Barang Dipinjam</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Nomor BAP</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Penandatangan</th>
                        <th class="px-5 py-3.5 text-[11px] font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bapRequests as $req)
                    <tr class="bap-tr hover:bg-indigo-50/30 transition-colors duration-150">
                        {{-- Peminjam --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $req->isSigned() ? 'bg-emerald-100 border border-emerald-200' : 'bg-amber-100 border border-amber-200' }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 {{ $req->isSigned() ? 'text-emerald-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="bap-td-text font-bold text-gray-900 leading-tight">{{ $req->user->name }}</p>
                                    <p class="bap-td-sub text-[11px] text-gray-400 mt-0.5">{{ $req->user->email }}</p>
                                    <span class="font-mono text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded mt-1 inline-block">Req #{{ $req->id }} · {{ $req->tanggal_pinjam?->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Item --}}
                        <td class="px-4 py-4">
                            <p class="bap-td-text font-bold text-indigo-900 leading-tight">{{ $req->item->nama }}</p>
                            <p class="bap-td-sub text-[11px] text-gray-400 mt-0.5">
                                <span class="font-semibold text-gray-600">{{ $req->jumlah }}×</span> unit · {{ $req->item->category->nama ?? 'Umum' }}
                            </p>
                        </td>

                        {{-- BAP Number --}}
                        <td class="px-4 py-4">
                            @if($req->bap_number)
                                <p class="font-mono font-bold text-gray-900">{{ $req->bap_number }}</p>
                                <p class="font-mono text-[10px] text-gray-400 truncate max-w-[150px] mt-0.5">{{ Str::limit($req->bap_token, 20) }}</p>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg italic">Belum diterbitkan</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4">
                            @if($req->isSigned())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sah & Ditandatangani
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu TTD
                                </span>
                            @endif
                        </td>

                        {{-- Signer --}}
                        <td class="px-4 py-4">
                            @if($req->isSigned())
                                <p class="bap-td-text font-semibold text-gray-900 leading-tight">{{ $req->signed_by_name }}</p>
                                <p class="bap-td-sub text-[10px] text-gray-400 mt-0.5">{{ $req->signed_at?->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
                            @else
                                <span class="text-gray-300 text-sm font-bold">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4 text-right">
                            @if($req->isSigned())
                                <a href="{{ route($routePrefix . '.borrowing-requests.bap', $req->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat BAP
                                </a>
                            @else
                                <a href="{{ route($routePrefix . '.borrowing-requests.bap', $req->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold rounded-lg shadow-sm transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Tanda Tangani
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="bap-title text-sm font-semibold text-gray-700">Belum ada dokumen BAP</p>
                            <p class="bap-sub text-xs text-gray-400 mt-1">Dokumen BAP muncul otomatis saat peminjaman berstatus Approved / Completed.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bapRequests->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $bapRequests->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
