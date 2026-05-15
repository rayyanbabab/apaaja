@extends('admin.layouts.dashboard')

@push('styles')
<style>
    /* ── Filter Tabs ── */
    .ntab {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0.3rem 0.8rem;
        border-radius: 0.55rem;
        font-size: 0.71rem; font-weight: 600;
        white-space: nowrap;
        border: 1.5px solid transparent;
        transition: all 0.15s;
        text-decoration: none;
        cursor: pointer;
    }
    .ntab.on  { background:#1d4ed8; color:#fff; border-color:#1d4ed8; box-shadow:0 2px 8px -2px rgba(29,78,216,.35); }
    .ntab.off { background:rgba(255,255,255,.7); color:#6b7280; border-color:rgba(209,213,219,.6); backdrop-filter:blur(8px); }
    .ntab.off:hover { background:rgba(255,255,255,.95); color:#374151; border-color:#d1d5db; }
    .ntab-count {
        font-size: 10px; font-weight: 700;
        padding: 1px 5px; border-radius: 4px;
        line-height: 1.4;
    }

    /* ── Notification rows ── */
    .nrow {
        position: relative;
        display: flex; align-items: flex-start; gap: 0.8rem;
        padding: 0.9rem 1.2rem;
        transition: background 0.12s;
        cursor: default;
    }
    .nrow:not(:last-child) { border-bottom: 1px solid rgba(243,244,246,.9); }
    .nrow.unread { background: rgba(239,246,255,.5); }
    .nrow:not(.unread):hover { background: rgba(249,250,251,.7); }

    /* Left colored bar for unread */
    .nrow-bar {
        position: absolute; left: 0; top: 10px; bottom: 10px;
        width: 3px; border-radius: 0 3px 3px 0;
    }

    /* Icon bubble */
    .nrow-icon {
        flex-shrink: 0; width: 34px; height: 34px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        margin-top: 1px;
    }

    /* Show action links only on hover */
    .nrow-actions { display: flex; align-items: center; gap: 0.6rem; margin-top: 0.4rem; }
    .nrow-actions a, .nrow-actions button {
        font-size: 11px; font-weight: 600;
        transition: color .12s;
        background: none; border: none; padding: 0; cursor: pointer;
    }
    .nrow-actions a { color: #2563eb; }
    .nrow-actions a:hover { color: #1d4ed8; }
    .nrow-actions button { color: #9ca3af; }
    .nrow-actions button:hover { color: #6b7280; }
    .nrow-actions .sep { color: #e5e7eb; font-size: 10px; }

    /* Type pill */
    .npill {
        display: inline-flex; align-items: center;
        padding: 1px 7px; border-radius: 4px;
        font-size: 10px; font-weight: 700;
        letter-spacing: .01em;
        ring: 1px;
    }
</style>
@endpush

@section('content')
@php
    $activeFilter = request('filter', 'all');
    $typeFilter   = request('type', '');
    $allCount     = $notifications->total();
    $unreadCount  = auth()->user()->unreadNotifications()->count();

    $tabTypes = [
        'maintenance'      => ['label' => 'Maintenance',  'emoji' => '🔧'],
        'overdue'          => ['label' => 'Terlambat',    'emoji' => '🚨'],
        'pending_reminder' => ['label' => 'Pending',      'emoji' => '⏳'],
        'low_stock'        => ['label' => 'Stok Menipis', 'emoji' => '⚠️'],
        'completed'        => ['label' => 'Selesai',      'emoji' => '✅'],
    ];

    $metaMap = [
        'overdue'          => ['bg'=>'bg-red-50',    'icon-c'=>'text-red-500',    'pill'=>'bg-red-50 text-red-700',    'bar'=>'bg-red-400',    'label'=>'Terlambat',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'pending_reminder' => ['bg'=>'bg-amber-50',  'icon-c'=>'text-amber-500',  'pill'=>'bg-amber-50 text-amber-700','bar'=>'bg-amber-400',  'label'=>'Pending',      'icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        'low_stock'        => ['bg'=>'bg-orange-50', 'icon-c'=>'text-orange-500', 'pill'=>'bg-orange-50 text-orange-700','bar'=>'bg-orange-400','label'=>'Stok Menipis','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'approved'         => ['bg'=>'bg-green-50',  'icon-c'=>'text-green-500',  'pill'=>'bg-green-50 text-green-700', 'bar'=>'bg-green-400',  'label'=>'Disetujui',    'icon'=>'M5 13l4 4L19 7'],
        'rejected'         => ['bg'=>'bg-red-50',    'icon-c'=>'text-red-500',    'pill'=>'bg-red-50 text-red-700',    'bar'=>'bg-red-400',    'label'=>'Ditolak',      'icon'=>'M6 18L18 6M6 6l12 12'],
        'completed'        => ['bg'=>'bg-blue-50',   'icon-c'=>'text-blue-500',   'pill'=>'bg-blue-50 text-blue-700',  'bar'=>'bg-blue-400',   'label'=>'Selesai',      'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'maintenance'      => ['bg'=>'bg-purple-50', 'icon-c'=>'text-purple-500', 'pill'=>'bg-purple-50 text-purple-700','bar'=>'bg-purple-400','label'=>'Maintenance', 'icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        'info'             => ['bg'=>'bg-gray-50',   'icon-c'=>'text-gray-400',   'pill'=>'bg-gray-100 text-gray-600', 'bar'=>'bg-gray-300',   'label'=>'Info',         'icon'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

<div class="max-w-3xl mx-auto space-y-4">

    {{-- ── HEADER ── --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base font-bold text-gray-900">Notifikasi</h1>
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center justify-center h-4 min-w-[1rem] rounded-full bg-blue-600 px-1 text-[10px] font-bold text-white">{{ $unreadCount }}</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-400">{{ $allCount }} total · {{ $unreadCount }} belum dibaca</p>
            </div>
        </div>

        @if($unreadCount > 0)
            <form method="POST" action="{{ route($routePrefix . '.notifications.read-all') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200/80 bg-white/80 backdrop-blur text-xs font-semibold text-gray-500 hover:text-gray-800 hover:bg-white shadow-sm transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- ── FILTER TABS ── --}}
    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5" style="scrollbar-width:none;">
        {{-- Semua --}}
        <a href="{{ request()->fullUrlWithQuery(['filter'=>'all','type'=>'']) }}"
           class="ntab {{ ($activeFilter==='all'&&!$typeFilter)?'on':'off' }}">
            Semua
            <span class="ntab-count {{ ($activeFilter==='all'&&!$typeFilter)?'bg-white/20 text-white':'bg-gray-100 text-gray-500' }}">{{ $allCount }}</span>
        </a>

        {{-- Belum Dibaca --}}
        <a href="{{ request()->fullUrlWithQuery(['filter'=>'unread','type'=>'']) }}"
           class="ntab {{ $activeFilter==='unread'?'on':'off' }}">
            Belum Dibaca
            @if($unreadCount > 0)
                <span class="ntab-count {{ $activeFilter==='unread'?'bg-white/20 text-white':'bg-blue-100 text-blue-600' }}">{{ $unreadCount }}</span>
            @endif
        </a>

        <span class="flex-shrink-0 h-4 w-px bg-gray-200 mx-0.5"></span>

        @foreach($tabTypes as $key => $info)
            <a href="{{ request()->fullUrlWithQuery(['filter'=>'all','type'=>$key]) }}"
               class="ntab {{ $typeFilter===$key?'on':'off' }}">
                {{ $info['emoji'] }} {{ $info['label'] }}
            </a>
        @endforeach
    </div>

    {{-- ── NOTIFICATION LIST ── --}}
    <div class="rounded-2xl overflow-hidden border border-white/70"
         style="background:rgba(255,255,255,0.72);backdrop-filter:blur(20px) saturate(150%);box-shadow:inset 0 1px 0 rgba(255,255,255,0.85),0 4px 24px rgba(0,0,0,0.06);">

        @forelse($notifications as $notif)
            @php
                $data     = $notif->data;
                $isUnread = is_null($notif->read_at);
                $type     = $data['type'] ?? 'info';
                $meta     = $metaMap[$type] ?? $metaMap['info'];
                $hasUrl   = isset($data['url']) && $data['url'] !== '#';
            @endphp

            <div class="nrow {{ $isUnread?'unread':'' }}">
                {{-- Unread bar --}}
                @if($isUnread)
                    <div class="nrow-bar {{ $meta['bar'] }}"></div>
                @endif

                {{-- Icon --}}
                <div class="nrow-icon {{ $meta['bg'] }}">
                    <svg class="{{ $meta['icon-c'] }}" style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}"/>
                    </svg>
                </div>

                {{-- Body --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="npill {{ $meta['pill'] }}">{{ $meta['label'] }}</span>
                            @if($isUnread)
                                <span class="flex items-center gap-1 text-[10px] font-semibold text-blue-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>Baru
                                </span>
                            @endif
                        </div>
                        <span class="flex-shrink-0 text-[10px] text-gray-400 whitespace-nowrap"
                              title="{{ $notif->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p class="text-[13px] leading-relaxed {{ $isUnread?'font-medium text-gray-800':'text-gray-500' }}">
                        {{ $data['message'] ?? '-' }}
                    </p>

                    @if($hasUrl || $isUnread)
                        <div class="nrow-actions">
                            @if($hasUrl)
                                <a href="{{ $data['url'] }}">
                                    Lihat Detail
                                    <svg style="width:10px;height:10px;display:inline;vertical-align:middle;margin-left:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                            @if($hasUrl && $isUnread)
                                <span class="sep">·</span>
                            @endif
                            @if($isUnread)
                                <form method="POST" action="{{ route($routePrefix.'.notifications.read', $notif->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit">Tandai dibaca</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        @empty
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-600">
                    @if($typeFilter) Tidak ada notifikasi "{{ $tabTypes[$typeFilter]['label'] ?? $typeFilter }}"
                    @elseif($activeFilter==='unread') Semua sudah dibaca ✓
                    @else Belum ada notifikasi
                    @endif
                </p>
                <p class="mt-0.5 text-xs text-gray-400">
                    @if($activeFilter==='unread') Tidak ada notifikasi yang belum dibaca 🎉
                    @else Notifikasi sistem akan muncul di sini secara otomatis
                    @endif
                </p>
                @if($typeFilter || $activeFilter==='unread')
                    <a href="{{ request()->fullUrlWithQuery(['filter'=>'all','type'=>'']) }}"
                       class="mt-3 text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Lihat semua notifikasi
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ── PAGINATION ── --}}
    @if($notifications->hasPages())
        <div class="flex justify-center">
            {{ $notifications->appends(request()->query())->links() }}
        </div>
    @endif

</div>
@endsection
