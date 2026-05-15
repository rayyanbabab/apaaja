@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                Scan Result
            </h1>
            <p class="text-sm text-gray-500 mt-1">Aksi cepat untuk barang yang dipindai.</p>
        </div>
        <a href="{{ route('scanner.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Scan Lagi
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl">
            <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Left: Item Info ── --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Item Card --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 flex flex-col items-center text-center">
                    @if($item->gambar)
                        <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-28 h-28 object-cover rounded-xl shadow border border-gray-200 mb-4">
                    @else
                        <div class="w-28 h-28 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mb-4 border border-gray-200 dark:border-gray-600">
                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-6a2 2 0 00-2 2v3a2 2 0 002 2h6a2 2 0 002-2v-3a2 2 0 00-2-2z"/></svg>
                        </div>
                    @endif
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $item->nama }}</h2>
                    <span class="mt-1 font-mono text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full border border-gray-200 dark:border-gray-600">{{ $item->kode }}</span>
                    @if($item->category)
                        <span class="mt-2 text-xs text-gray-400">{{ $item->category->name }}</span>
                    @endif
                </div>
                <div class="grid grid-cols-2 border-t border-gray-100 dark:border-gray-700">
                    <div class="p-4 text-center border-r border-gray-100 dark:border-gray-700">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $item->stok_total }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-0.5">Total</div>
                    </div>
                    <div class="p-4 text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $item->stok_reguler }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-0.5">Tersedia</div>
                    </div>
                    <div class="p-4 text-center border-r border-t border-gray-100 dark:border-gray-700">
                        <div class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $item->stok_peminjaman }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-0.5">Dipinjam</div>
                    </div>
                    <div class="p-4 text-center border-t border-gray-100 dark:border-gray-700">
                        <div class="text-xl font-bold text-red-600 dark:text-red-400">{{ $item->stok_in_repair ?? 0 }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-gray-400 mt-0.5">In Repair</div>
                    </div>
                </div>
                <div class="px-4 pb-4 pt-2">
                    <a href="{{ route($routePrefix . '.inventory.print-label', $item->id) }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-indigo-200 text-indigo-700 hover:bg-indigo-50 rounded-xl text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Label QR
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Right: Actions & History ── --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Quick Return --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Pengembalian Instan</h3>
                    @if($activeBorrowings->count() > 0)
                        <span class="ml-auto text-xs font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $activeBorrowings->count() }} aktif</span>
                    @endif
                </div>
                @if($activeBorrowings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                                    <th class="px-5 py-3 text-center font-semibold">Jml</th>
                                    <th class="px-5 py-3 text-left font-semibold">Tgl Pinjam</th>
                                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($activeBorrowings as $b)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-5 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $b->user->name ?? '–' }}</td>
                                    <td class="px-5 py-3 text-center font-bold text-indigo-600 dark:text-indigo-400">{{ $b->jumlah }}</td>
                                    <td class="px-5 py-3 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <form action="{{ route('scanner.return', $b->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" onclick="return confirm('Kembalikan barang ini?')"
                                                class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-semibold transition-colors shadow-sm">
                                                Kembalikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm">Tidak ada peminjaman aktif untuk barang ini.</p>
                    </div>
                @endif
            </div>

            {{-- Quick Maintenance --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">Ubah Status / Maintenance</h3>
                </div>
                <div class="p-5">
                    @if($item->stok_reguler > 0)
                    <form action="{{ route('scanner.maintenance', $item->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jumlah</label>
                                <input type="number" name="jumlah" min="1" max="{{ $item->stok_reguler }}" value="1"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <p class="text-[10px] text-gray-400 mt-1">Maks: {{ $item->stok_reguler }} unit</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status Baru</label>
                                <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="in_repair">🔧 Dalam Perbaikan</option>
                                    <option value="scrapped">🗑️ Scrapped</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Catatan</label>
                                <input type="text" name="catatan" placeholder="cth: LCD Pecah"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" onclick="return confirm('Ubah status barang ini?')"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm shadow-red-500/20">
                                Update Status
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="py-4 text-center text-sm text-gray-400">Tidak ada stok reguler tersedia untuk dipindahkan.</div>
                    @endif
                </div>
            </div>

            {{-- History Tabs --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden"
                 x-data="{ tab: 'borrowing' }">
                <div class="flex border-b border-gray-100 dark:border-gray-700">
                    <button @click="tab='borrowing'" :class="tab==='borrowing' ? 'border-b-2 border-indigo-600 text-indigo-700 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/10' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-5 py-3.5 text-sm font-semibold transition-colors">
                        📋 Riwayat Peminjaman
                        @if($borrowingHistory->count()) <span class="ml-1.5 text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-1.5 py-0.5 rounded-full">{{ $borrowingHistory->count() }}</span> @endif
                    </button>
                    <button @click="tab='maintenance'" :class="tab==='maintenance' ? 'border-b-2 border-orange-500 text-orange-700 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-900/10' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-5 py-3.5 text-sm font-semibold transition-colors">
                        🔧 Riwayat Maintenance
                        @if($maintenanceHistory->count()) <span class="ml-1.5 text-xs bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-1.5 py-0.5 rounded-full">{{ $maintenanceHistory->count() }}</span> @endif
                    </button>
                </div>

                {{-- Borrowing History --}}
                <div x-show="tab==='borrowing'" class="overflow-x-auto">
                    @if($borrowingHistory->count())
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                                    <th class="px-5 py-3 text-center font-semibold">Jml</th>
                                    <th class="px-5 py-3 text-left font-semibold">Tgl Kembali</th>
                                    <th class="px-5 py-3 text-center font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($borrowingHistory as $b)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $b->user->name ?? '–' }}</td>
                                    <td class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">{{ $b->jumlah }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ $b->tanggal_kembali_aktual ? \Carbon\Carbon::parse($b->tanggal_kembali_aktual)->format('d M Y') : '–' }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $b->status === 'dikembalikan' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($b->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-400 text-sm">Belum ada riwayat peminjaman.</div>
                    @endif
                </div>

                {{-- Maintenance History --}}
                <div x-show="tab==='maintenance'" class="overflow-x-auto">
                    @if($maintenanceHistory->count())
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold">Dicatat Oleh</th>
                                    <th class="px-5 py-3 text-center font-semibold">Jml</th>
                                    <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                                    <th class="px-5 py-3 text-center font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($maintenanceHistory as $m)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $m->user->name ?? '–' }}</td>
                                    <td class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-300">{{ $m->jumlah }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($m->started_at)->format('d M Y') }}</td>
                                    <td class="px-5 py-3 text-center">
                                        @php
                                            $badge = match($m->status) {
                                                'in_repair'  => 'bg-orange-100 text-orange-700',
                                                'completed'  => 'bg-green-100 text-green-700',
                                                'scrapped'   => 'bg-red-100 text-red-700',
                                                default      => 'bg-gray-100 text-gray-600',
                                            };
                                            $label = match($m->status) {
                                                'in_repair' => 'In Repair', 'completed' => 'Selesai', 'scrapped' => 'Scrapped', default => $m->status
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $badge }}">{{ $label }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-400 text-sm">Belum ada riwayat maintenance.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
