@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Permintaan Pengadaan
                </h1>
                <p class="text-sm text-gray-500 mt-1">Kelola permintaan pengadaan barang dari pengguna</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900" data-count="{{ $totalCount }}">{{ $totalCount }}</p>
                    <p class="text-xs text-gray-500">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-700" data-count="{{ $pendingCount }}">{{ $pendingCount }}</p>
                    <p class="text-xs text-gray-500">Pending</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-700" data-count="{{ $approvedCount }}">{{ $approvedCount }}</p>
                    <p class="text-xs text-gray-500">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600" data-count="{{ $rejectedCount }}">{{ $rejectedCount }}</p>
                    <p class="text-xs text-gray-500">Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters + Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Filter bar --}}
        <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
            <h3 class="text-sm font-semibold text-gray-900 flex-1">Daftar Permintaan</h3>
            <form method="GET" action="{{ route('admin.procurement-requests.index') }}" class="flex items-center gap-2" id="filterForm">
                {{-- Status filter --}}
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                {{-- Search --}}
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari barang / user..."
                       class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent w-44">
                <button type="submit" class="px-3 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
                @if(request()->hasAny(['status', 'search']))
                    <a href="{{ route('admin.procurement-requests.index') }}"
                       class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm rounded-lg transition-colors">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pemohon</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Barang</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Tanggal</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($procurements as $procurement)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-blue-700">
                                        {{ strtoupper(substr($procurement->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $procurement->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $procurement->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-900">{{ $procurement->nama_barang }}</p>
                                @if($procurement->alasan)
                                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $procurement->alasan }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-bold text-gray-900">{{ $procurement->jumlah }}</span>
                                <span class="text-xs text-gray-400"> unit</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($procurement->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>Pending
                                    </span>
                                @elseif($procurement->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 hidden md:table-cell">
                                {{ $procurement->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.procurement-requests.show', $procurement->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold
                                       {{ $procurement->status === 'pending' ? 'text-orange-700 bg-orange-50 hover:bg-orange-100 border border-orange-200' : 'text-gray-600 bg-gray-50 hover:bg-gray-100 border border-gray-200' }}
                                       rounded-lg transition-colors">
                                    @if($procurement->status === 'pending')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Proses
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Tidak ada permintaan</p>
                                <p class="text-xs text-gray-400">Belum ada permintaan pengadaan yang masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($procurements->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $procurements->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
