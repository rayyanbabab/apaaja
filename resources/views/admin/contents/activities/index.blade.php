@extends('admin.layouts.dashboard')

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────── --}}
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Audit Log & Aktivitas</h1>
            <p class="mt-1 text-gray-500">Riwayat lengkap semua aksi penting dan login pengguna</p>
        </div>
        <a href="{{ route($routePrefix . '.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>

{{-- ── TABS ─────────────────────────────────────────────────── --}}
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
        <a href="{{ route($routePrefix . '.activities.index', array_merge(request()->except('tab','page'), ['tab' => 'audit'])) }}"
           class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition-colors
                  {{ $tab === 'audit' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Audit Log
            <span class="ml-2 bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full text-xs font-bold">
                {{ number_format($auditStats['total']) }}
            </span>
        </a>
        <a href="{{ route($routePrefix . '.activities.index', array_merge(request()->except('tab','page'), ['tab' => 'login'])) }}"
           class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition-colors
                  {{ $tab === 'login' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Login Log
            <span class="ml-2 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs font-bold">
                {{ number_format($totalActivities) }}
            </span>
        </a>
    </nav>
</div>

{{-- ══════════════════════════════════ AUDIT LOG TAB ══════ --}}
@if($tab === 'audit')

    {{-- STATS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Aksi',  'value' => $auditStats['total'],      'color' => 'indigo', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5...'],
            ['label' => 'Hari Ini',    'value' => $auditStats['today'],      'color' => 'green'],
            ['label' => 'Minggu Ini',  'value' => $auditStats['this_week'],  'color' => 'yellow'],
            ['label' => 'Bulan Ini',   'value' => $auditStats['this_month'], 'color' => 'purple'],
        ] as $i => $stat)
        @php
            $colors = [
                'indigo' => ['bg' => 'from-indigo-500 to-indigo-600', 'light' => 'from-indigo-50 to-indigo-100', 'ring' => 'bg-indigo-200', 'text' => 'text-indigo-600'],
                'green'  => ['bg' => 'from-green-500 to-green-600',   'light' => 'from-green-50 to-green-100',   'ring' => 'bg-green-200',   'text' => 'text-green-600'],
                'yellow' => ['bg' => 'from-amber-500 to-amber-600',   'light' => 'from-amber-50 to-amber-100',   'ring' => 'bg-amber-200',   'text' => 'text-amber-600'],
                'purple' => ['bg' => 'from-purple-500 to-purple-600', 'light' => 'from-purple-50 to-purple-100', 'ring' => 'bg-purple-200',  'text' => 'text-purple-600'],
            ];
            $c = $colors[$stat['color']];
        @endphp
        <div class="relative bg-white/80 backdrop-blur-sm overflow-hidden shadow-lg rounded-2xl border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="absolute inset-0 bg-gradient-to-br {{ $c['light'] }} opacity-50"></div>
            <div class="absolute top-0 right-0 w-24 h-24 {{ $c['ring'] }} rounded-full -translate-y-12 translate-x-12 opacity-20 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative p-5">
                <div class="w-10 h-10 bg-gradient-to-br {{ $c['bg'] }} rounded-xl flex items-center justify-center shadow-md mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</dt>
                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stat['value']) }}</dd>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter Audit Log
            </h3>
            @if(request()->except('tab'))
                <a href="{{ route($routePrefix . '.activities.index', ['tab' => 'audit']) }}"
                   class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                    ✕ Reset Filter
                </a>
            @endif
        </div>
        <form method="GET" action="{{ route($routePrefix . '.activities.index') }}" class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <input type="hidden" name="tab" value="audit">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Modul</label>
                <select name="module" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">User</label>
                <select name="user_id" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                    <option value="">Semua User</option>
                    @foreach($auditUsers as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
            </div>
            <div class="col-span-2 sm:col-span-1 lg:col-span-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Deskripsi</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kata kunci..."
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- AUDIT LOG TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Daftar Aksi</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ number_format($auditLogs->total()) }} entri ditemukan</p>
            </div>
            <span class="text-xs text-gray-400">
                {{ $auditLogs->firstItem() ?? 0 }}–{{ $auditLogs->lastItem() ?? 0 }} dari {{ $auditLogs->total() }}
            </span>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($auditLogs as $log)
            @php
                $moduleColors = [
                    'Inventory'    => ['dot' => 'bg-blue-500',   'badge' => 'bg-blue-100 text-blue-700'],
                    'Barang Masuk' => ['dot' => 'bg-green-500',  'badge' => 'bg-green-100 text-green-700'],
                    'Barang Keluar'=> ['dot' => 'bg-orange-500', 'badge' => 'bg-orange-100 text-orange-700'],
                    'Peminjaman'   => ['dot' => 'bg-purple-500', 'badge' => 'bg-purple-100 text-purple-700'],
                    'User'         => ['dot' => 'bg-cyan-500',   'badge' => 'bg-cyan-100 text-cyan-700'],
                    'Supplier'     => ['dot' => 'bg-yellow-500', 'badge' => 'bg-yellow-100 text-yellow-700'],
                    'Kategori'     => ['dot' => 'bg-pink-500',   'badge' => 'bg-pink-100 text-pink-700'],
                ];
                $mc = $moduleColors[$log->module] ?? ['dot' => 'bg-gray-400', 'badge' => 'bg-gray-100 text-gray-600'];

                $actionIcons = [
                    'item.created'        => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M12 4v16m8-8H4'],
                    'item.updated'        => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    'item.deleted'        => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                    'incoming.created'    => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4'],
                    'incoming.deleted'    => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                    'outgoing.created'    => ['bg' => '#ffedd5', 'color' => '#ea580c', 'icon' => 'M17 8l4 4m0 0l-4 4m4-4H3'],
                    'outgoing.deleted'    => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                    'borrowing.approved'  => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'borrowing.rejected'  => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'borrowing.completed' => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M5 13l4 4L19 7'],
                    'user.created'        => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                    'user.updated'        => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    'user.deleted'        => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6'],
                    'user.status_changed' => ['bg' => '#fef9c3', 'color' => '#ca8a04', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    'supplier.created'    => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M12 4v16m8-8H4'],
                    'supplier.updated'    => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    'supplier.deleted'    => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                    'category.created'    => ['bg' => '#dcfce7', 'color' => '#16a34a', 'icon' => 'M12 4v16m8-8H4'],
                    'category.updated'    => ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    'category.deleted'    => ['bg' => '#fee2e2', 'color' => '#dc2626', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
                    'maintenance.created'  => ['bg' => '#f3e8ff', 'color' => '#9333ea', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    'maintenance.completed'=> ['bg' => '#dbeafe', 'color' => '#2563eb', 'icon' => 'M5 13l4 4L19 7'],
                ];
                $ai = $actionIcons[$log->action] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
            @endphp
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors group" x-data="{ open: false }">
                <div class="flex items-start gap-4">
                    {{-- Action Icon --}}
                    <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center mt-0.5"
                         style="background-color:{{ $ai['bg'] }};">
                        <svg style="width:16px;height:16px;color:{{ $ai['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ai['icon'] }}"/>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $mc['badge'] }}">
                                {{ $log->module }}
                            </span>
                            <span class="text-xs font-medium text-gray-600">{{ $log->action_label }}</span>
                        </div>
                        <p class="text-sm text-gray-800 font-medium leading-snug">{{ $log->description }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-1.5">
                            <span class="inline-flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $log->user->name ?? 'System' }}
                            </span>
                            <span class="inline-flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $log->created_at->format('d M Y, H:i') }}
                                <span class="ml-1 text-gray-300">({{ $log->created_at->diffForHumans() }})</span>
                            </span>
                            @if($log->ip_address)
                            <span class="inline-flex items-center text-xs text-gray-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                {{ $log->ip_address }}
                            </span>
                            @endif
                            @if($log->properties)
                            <button @click="open = !open"
                                    class="inline-flex items-center text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors">
                                <svg class="w-3 h-3 mr-1 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span x-text="open ? 'Sembunyikan Detail' : 'Lihat Detail'">Lihat Detail</span>
                            </button>
                            @endif
                        </div>

                        {{-- Expandable Properties --}}
                        @if($log->properties)
                        <div x-show="open" x-collapse class="mt-3">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Data Perubahan</p>
                                <table class="text-xs text-gray-600 w-full">
                                    @foreach($log->properties as $key => $val)
                                    <tr class="border-b border-gray-100 last:border-0">
                                        <td class="py-1 pr-4 font-medium text-gray-500 capitalize w-1/3">{{ str_replace('_', ' ', $key) }}</td>
                                        <td class="py-1 font-mono">{{ is_array($val) ? json_encode($val) : $val }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-700">Belum ada audit log</h3>
                <p class="text-xs text-gray-400 mt-1">Aksi pertama akan dicatat secara otomatis.</p>
            </div>
            @endforelse
        </div>

        @if($auditLogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>

@else
{{-- ══════════════════════════════════ LOGIN LOG TAB ═══════ --}}

    {{-- STATS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $loginStats = [
                ['label' => 'Total Login', 'value' => $totalActivities,     'color' => 'from-blue-500 to-blue-600',   'light' => 'from-blue-50 to-blue-100',   'ring' => 'bg-blue-200'],
                ['label' => 'Hari Ini',    'value' => $todayActivities,     'color' => 'from-green-500 to-green-600', 'light' => 'from-green-50 to-green-100', 'ring' => 'bg-green-200'],
                ['label' => 'Minggu Ini',  'value' => $thisWeekActivities,  'color' => 'from-amber-500 to-amber-600', 'light' => 'from-amber-50 to-amber-100', 'ring' => 'bg-amber-200'],
                ['label' => 'Bulan Ini',   'value' => $thisMonthActivities, 'color' => 'from-purple-500 to-purple-600','light'=> 'from-purple-50 to-purple-100','ring' => 'bg-purple-200'],
            ];
        @endphp
        @foreach($loginStats as $stat)
        <div class="relative bg-white/80 backdrop-blur-sm overflow-hidden shadow-lg rounded-2xl border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 group">
            <div class="absolute inset-0 bg-gradient-to-br {{ $stat['light'] }} opacity-50"></div>
            <div class="absolute top-0 right-0 w-24 h-24 {{ $stat['ring'] }} rounded-full -translate-y-12 translate-x-12 opacity-20 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative p-5">
                <div class="w-10 h-10 bg-gradient-to-br {{ $stat['color'] }} rounded-xl flex items-center justify-center shadow-md mb-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $stat['label'] }}</dt>
                <dd class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stat['value']) }}</dd>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTER BAR --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-700">Filter Login Log</h3>
            @if(request()->except('tab'))
                <a href="{{ route($routePrefix . '.activities.index', ['tab' => 'login']) }}"
                   class="text-xs text-red-500 hover:text-red-700 font-medium">✕ Reset</a>
            @endif
        </div>
        <form method="GET" action="{{ route($routePrefix . '.activities.index') }}" class="p-5 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3">
            <input type="hidden" name="tab" value="login">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                <select name="role" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari User</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-gray-50">
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- LOGIN LIST --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-700">Riwayat Login</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ number_format($activities->total()) }} catatan ditemukan</p>
            </div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($activities as $activity)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-4">
                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0"
                         src="{{ $activity->user->profil ? asset($activity->user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($activity->user->name) . '&color=7F9CF5&background=EBF4FF&size=40' }}"
                         alt="{{ $activity->user->name }}"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($activity->user->name) }}&color=7F9CF5&background=EBF4FF&size=40'">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-800">{{ $activity->user->name }}</span>
                            @php
                                $roleClass = match($activity->user->role?->value ?? 'user') {
                                    'admin'    => 'bg-red-100 text-red-700',
                                    'operator' => 'bg-orange-100 text-orange-700',
                                    default    => 'bg-blue-100 text-blue-700',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $roleClass }}">
                                {{ ucfirst($activity->user->role?->value ?? 'user') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $activity->user->email }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 mb-1">
                            ✓ Login
                        </span>
                        <p class="text-xs text-gray-400 block">{{ $activity->logged_in_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-300">{{ $activity->logged_in_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-700">Tidak ada riwayat login</h3>
                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter pencarian.</p>
            </div>
            @endforelse
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
@endif

@endsection

@push('scripts')
<script>
// x-collapse polyfill for Alpine if not installed
document.addEventListener('alpine:init', () => {
    if (!Alpine.directive('collapse')) {
        Alpine.directive('collapse', (el, { modifiers, expression }, { effect, cleanup }) => {
            let duration = 250;
            el.style.overflow = 'hidden';
            let setHeight = (show) => {
                if (show) {
                    el.style.display = 'block';
                    let height = el.scrollHeight;
                    el.style.height = '0px';
                    requestAnimationFrame(() => {
                        el.style.transition = `height ${duration}ms ease`;
                        el.style.height = height + 'px';
                        setTimeout(() => { el.style.height = null; el.style.overflow = null; }, duration);
                    });
                } else {
                    el.style.height = el.scrollHeight + 'px';
                    requestAnimationFrame(() => {
                        el.style.transition = `height ${duration}ms ease`;
                        el.style.height = '0px';
                        setTimeout(() => { el.style.display = 'none'; el.style.overflow = 'hidden'; }, duration);
                    });
                }
            };
            // Initial state handled by x-show
        });
    }
});
</script>
@endpush
