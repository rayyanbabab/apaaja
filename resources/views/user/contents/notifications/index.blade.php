@extends('user.layouts.dashboard-user')

@section('user')
@php
    $activeFilter = request('filter', 'all');
    $typeFilter   = request('type', '');
    $allCount     = $notifications->total();
    $unreadCount  = auth()->user()->unreadNotifications()->count();

    $tabTypes = [
        'approved'  => ['label' => 'Disetujui',       'emoji' => '✅'],
        'rejected'  => ['label' => 'Ditolak',         'emoji' => '❌'],
        'overdue'   => ['label' => 'Terlambat',       'emoji' => '🚨'],
        'expired'   => ['label' => 'Kedaluwarsa',     'emoji' => '⏰'],
        'completed' => ['label' => 'Selesai',         'emoji' => '🎉'],
        'ongoing'   => ['label' => 'Dipinjam',        'emoji' => '📦'],
    ];

    $metaMap = [
        'approved'  => ['bg' => 'bg-green-100',  'color' => 'text-green-600',  'pill' => 'bg-green-50 text-green-700 ring-green-200',  'left' => 'bg-green-500',  'label' => 'Disetujui',       'icon' => 'M5 13l4 4L19 7'],
        'rejected'  => ['bg' => 'bg-red-100',    'color' => 'text-red-600',    'pill' => 'bg-red-50 text-red-700 ring-red-200',        'left' => 'bg-red-500',    'label' => 'Ditolak',         'icon' => 'M6 18L18 6M6 6l12 12'],
        'overdue'   => ['bg' => 'bg-red-100',    'color' => 'text-red-600',    'pill' => 'bg-red-50 text-red-700 ring-red-200',        'left' => 'bg-red-500',    'label' => 'Terlambat',       'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'expired'   => ['bg' => 'bg-orange-100', 'color' => 'text-orange-600', 'pill' => 'bg-orange-50 text-orange-700 ring-orange-200', 'left' => 'bg-orange-500', 'label' => 'Kedaluwarsa',   'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'completed' => ['bg' => 'bg-blue-100',   'color' => 'text-blue-600',   'pill' => 'bg-blue-50 text-blue-700 ring-blue-200',     'left' => 'bg-blue-500',   'label' => 'Selesai',         'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'ongoing'   => ['bg' => 'bg-amber-100',  'color' => 'text-amber-600',  'pill' => 'bg-amber-50 text-amber-700 ring-amber-200',  'left' => 'bg-amber-500',  'label' => 'Sedang Dipinjam', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        'info'      => ['bg' => 'bg-gray-100',   'color' => 'text-gray-500',   'pill' => 'bg-gray-50 text-gray-600 ring-gray-200',     'left' => 'bg-gray-400',   'label' => 'Informasi',       'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<div class="max-w-3xl mx-auto space-y-5">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-base">🔔</span>
                Notifikasi Saya
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center h-5 min-w-5 rounded-full bg-blue-600 px-1.5 text-xs font-bold text-white">{{ $unreadCount }}</span>
                @endif
            </h1>
            <p class="mt-0.5 text-sm text-gray-500 ml-10">Pembaruan terkait aktivitas peminjaman Anda</p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('user.notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 hover:text-gray-900 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
            <svg class="h-4 w-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── FILTER TABS ── --}}
    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5" style="scrollbar-width:none;">
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'type' => '']) }}"
           class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold whitespace-nowrap transition-all
                  {{ $activeFilter === 'all' && !$typeFilter
                     ? 'bg-gray-900 text-white shadow-sm'
                     : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
            Semua
            <span class="rounded-md px-1.5 py-0.5 text-xs font-bold {{ $activeFilter === 'all' && !$typeFilter ? 'bg-white/15' : 'bg-gray-100 text-gray-600' }}">
                {{ $allCount }}
            </span>
        </a>

        <a href="{{ request()->fullUrlWithQuery(['filter' => 'unread', 'type' => '']) }}"
           class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold whitespace-nowrap transition-all
                  {{ $activeFilter === 'unread'
                     ? 'bg-blue-600 text-white shadow-sm'
                     : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
            Belum Dibaca
            @if($unreadCount > 0)
                <span class="rounded-md px-1.5 py-0.5 text-xs font-bold {{ $activeFilter === 'unread' ? 'bg-white/20' : 'bg-blue-100 text-blue-700' }}">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>

        <span class="flex-shrink-0 h-5 w-px bg-gray-200 mx-0.5"></span>

        @foreach($tabTypes as $typeKey => $tabInfo)
            <a href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'type' => $typeKey]) }}"
               class="flex-shrink-0 inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold whitespace-nowrap transition-all
                      {{ $typeFilter === $typeKey
                         ? 'bg-gray-900 text-white shadow-sm'
                         : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
                {{ $tabInfo['emoji'] }} {{ $tabInfo['label'] }}
            </a>
        @endforeach
    </div>

    {{-- ── NOTIFICATION LIST ── --}}
    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $data     = $notification->data;
                $isUnread = is_null($notification->read_at);
                $type     = $data['type'] ?? 'info';
                $meta     = $metaMap[$type] ?? $metaMap['info'];

                // Fix legacy overdue messages with incorrect day count
                $displayMessage = $data['message'] ?? 'Notifikasi';
                if ($type === 'overdue' && isset($data['due_date'])) {
                    try {
                        $dueDate   = \Carbon\Carbon::parse($data['due_date']);
                        $daysFixed = max(1, (int) ceil($dueDate->diffInDays(now(), true)));
                        $displayMessage = preg_replace('/terlambat\s+[\-\d\.]+\s+hari/i', 'terlambat ' . $daysFixed . ' hari', $displayMessage);
                    } catch (\Throwable $e) {}
                }
            @endphp

            <div id="notif-{{ $notification->id }}"
                 class="relative flex items-start gap-4 px-5 py-4 transition-colors
                         {{ !$loop->last ? 'border-b border-gray-50' : '' }}
                         {{ $isUnread ? 'bg-blue-50/40' : 'bg-white hover:bg-gray-50/60' }}">

                @if($isUnread)
                    <div class="absolute left-0 top-0 bottom-0 w-0.5 {{ $meta['left'] }} rounded-r"></div>
                @endif

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $meta['bg'] }}">
                        <svg style="height:18px;width:18px;" class="{{ $meta['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $meta['pill'] }}">
                                {{ $meta['label'] }}
                            </span>
                            @if($isUnread)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Baru
                                </span>
                            @endif
                        </div>
                        <span class="flex-shrink-0 text-xs text-gray-400 whitespace-nowrap" title="{{ $notification->created_at->format('d M Y, H:i:s') }}">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p class="mt-1.5 text-sm leading-relaxed {{ $isUnread ? 'font-medium text-gray-900' : 'text-gray-600' }}">
                        {{ $displayMessage }}
                    </p>

                    <div class="mt-2 flex items-center gap-3">
                        @if(isset($data['url']) && $data['url'] !== '#')
                            <a href="{{ $data['url'] }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                Lihat Detail
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                        @if($isUnread)
                            <form method="POST" action="{{ route('user.notifications.read', $notification->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-gray-700 transition-colors">
                                    Tandai dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-24 text-center px-6">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 ring-8 ring-gray-50/50 mb-5">
                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">
                    @if($typeFilter)
                        Tidak ada notifikasi "{{ $tabTypes[$typeFilter]['label'] ?? $typeFilter }}"
                    @elseif($activeFilter === 'unread')
                        Semua sudah dibaca
                    @else
                        Belum ada notifikasi
                    @endif
                </p>
                <p class="mt-1 text-xs text-gray-400 max-w-xs">
                    @if($activeFilter === 'unread')
                        Semua beres! Tidak ada notifikasi yang belum dibaca 🎉
                    @else
                        Notifikasi peminjaman akan muncul di sini secara otomatis
                    @endif
                </p>
                @if($typeFilter || $activeFilter === 'unread')
                    <a href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'type' => '']) }}"
                       class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700">
                        ← Lihat semua notifikasi
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ── PAGINATION ── --}}
    @if($notifications->hasPages())
        <div>{{ $notifications->appends(request()->query())->links() }}</div>
    @endif

</div>
@endsection
