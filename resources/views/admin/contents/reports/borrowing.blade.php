@extends('admin.layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <a href="{{ route($routePrefix . '.reports.index') }}" class="hover:text-blue-600 transition-colors">Reports</a>
                        <span>/</span>
                        <span class="text-gray-700">Peminjaman</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Peminjaman Barang</h1>
                    <p class="text-sm text-gray-600 mt-1">Data seluruh permintaan dan aktivitas peminjaman barang</p>
                </div>
                <form method="GET" action="{{ route($routePrefix . '.reports.borrowing') }}" class="flex items-center gap-2">
                    @foreach(request()->except(['export_pdf','export_excel']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <button name="export_excel" value="1" type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </button>
                    <button name="export_pdf" value="1" type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        @php
        $statCards = [
            ['label' => 'Total',      'value' => $stats['total'],     'bg' => 'bg-gray-100',   'text' => 'text-gray-800'],
            ['label' => 'Menunggu',   'value' => $stats['pending'],   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            ['label' => 'Disetujui',  'value' => $stats['approved'],  'bg' => 'bg-green-100',  'text' => 'text-green-800'],
            ['label' => 'Selesai',    'value' => $stats['completed'], 'bg' => 'bg-blue-100',   'text' => 'text-blue-800'],
            ['label' => 'Ditolak',    'value' => $stats['rejected'],  'bg' => 'bg-red-100',    'text' => 'text-red-800'],
            ['label' => 'Dibatalkan', 'value' => $stats['cancelled'], 'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
            ['label' => 'Terlambat',  'value' => $stats['overdue'],   'bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
        ];
        @endphp
        @foreach($statCards as $card)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</div>
            <div class="mt-1">
                <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $card['bg'] }} {{ $card['text'] }}">
                    {{ $card['label'] }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route($routePrefix . '.reports.borrowing') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            @foreach(['pending'=>'Menunggu','approved'=>'Disetujui','completed'=>'Selesai','rejected'=>'Ditolak','cancelled'=>'Dibatalkan'] as $val => $label)
                                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peminjam</label>
                        <select name="user_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Semua Peminjam</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                <div class="flex space-x-2 mt-4">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route($routePrefix . '.reports.borrowing') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Data Peminjaman</h3>
            <span class="text-sm text-gray-500">{{ $borrowings->count() }} record ditemukan</span>
        </div>

        @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Selesai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($borrowings as $i => $b)
                    @php
                        $isOverdue = $b->status === 'approved' && $b->tanggal_kembali_rencana && $b->tanggal_kembali_rencana->isPast();
                        $statusMap = [
                            'pending'   => ['label' => 'Menunggu',   'class' => 'bg-yellow-100 text-yellow-800'],
                            'approved'  => ['label' => 'Disetujui',  'class' => 'bg-green-100 text-green-800'],
                            'completed' => ['label' => 'Selesai',    'class' => 'bg-blue-100 text-blue-800'],
                            'rejected'  => ['label' => 'Ditolak',    'class' => 'bg-red-100 text-red-800'],
                            'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-100 text-gray-600'],
                        ];
                        $statusInfo = $statusMap[$b->status] ?? ['label' => ucfirst($b->status), 'class' => 'bg-gray-100 text-gray-600'];
                        if ($isOverdue) $statusInfo = ['label' => 'Terlambat', 'class' => 'bg-orange-100 text-orange-800'];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $b->created_at?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $b->user?->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $b->item?->nama ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $b->item?->category?->name ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $b->jumlah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $b->tanggal_pinjam?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $isOverdue ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                {{ $b->tanggal_kembali_rencana?->format('d M Y') ?? '-' }}
                            </div>
                            @if($isOverdue)
                                <div class="text-xs text-red-500">Melewati batas</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $b->completed_at ? \Carbon\Carbon::parse($b->completed_at)->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            {{ $b->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data peminjaman</h3>
            <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian.</p>
        </div>
        @endif
    </div>

</div>
@endsection
