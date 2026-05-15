@extends('user.layouts.dashboard-user')
@section('user')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
                <p class="text-sm text-gray-500 mt-0.5">Here's an overview of your borrowing activity.</p>
            </div>
            <img class="w-12 h-12 rounded-full object-cover border-2 border-white shadow ring-1 ring-gray-100 flex-shrink-0"
                 src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=4F76F6&background=EEF2FF&size=64' }}"
                 alt="{{ $user->name }}"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=4F76F6&background=EEF2FF&size=64'">
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @php
        $stats = [
            ['label' => 'Total Borrowed',    'value' => $borrowingStats['total_borrowed'],    'icon_color' => 'text-blue-600',   'bg' => 'bg-blue-50',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Not Returned',      'value' => $borrowingStats['unreturned_items'],  'icon_color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Pending Approval',  'value' => $borrowingStats['pending_requests'],  'icon_color' => 'text-yellow-600', 'bg' => 'bg-yellow-50', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Total Requests',    'value' => $borrowingStats['total_requests'],    'icon_color' => 'text-green-600',  'bg' => 'bg-green-50',  'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Rejected',          'value' => $borrowingStats['rejected_requests'], 'icon_color' => 'text-red-600',   'bg' => 'bg-red-50',    'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Available Types',   'value' => $borrowingStats['available_items'],   'icon_color' => 'text-purple-600', 'bg' => 'bg-purple-50', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-xl {{ $stat['bg'] }}">
                <svg class="w-5 h-5 {{ $stat['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-gray-400 truncate">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $stat['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('user.borrowing.index') }}"
               class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors group border border-blue-100">
                <div class="flex items-center justify-center w-9 h-9 bg-blue-600 rounded-lg flex-shrink-0 group-hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Find Items</p>
                    <p class="text-xs text-gray-500 truncate">Browse items to borrow</p>
                </div>
            </a>

            <a href="{{ route('user.borrowing.my-requests') }}"
               class="flex items-center gap-3 p-4 rounded-xl bg-green-50 hover:bg-green-100 transition-colors group border border-green-100">
                <div class="flex items-center justify-center w-9 h-9 bg-green-600 rounded-lg flex-shrink-0 group-hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Borrowing Status</p>
                    <p class="text-xs text-gray-500 truncate">Track your requests</p>
                </div>
            </a>

            <a href="{{ route('user.profile.index') }}"
               class="flex items-center gap-3 p-4 rounded-xl bg-purple-50 hover:bg-purple-100 transition-colors group border border-purple-100">
                <div class="flex items-center justify-center w-9 h-9 bg-purple-600 rounded-lg flex-shrink-0 group-hover:bg-purple-700 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">My Profile</p>
                    <p class="text-xs text-gray-500 truncate">Manage your account</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Activities & Notifications --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Activities --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-900">Recent Activities</h2>
            </div>

            @if($recent_activities->count() > 0)
                <div class="space-y-2">
                    @foreach($recent_activities as $activity)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <img class="w-8 h-8 rounded-full object-cover flex-shrink-0 ring-1 ring-gray-200"
                                     src="{{ $activity['user_photo'] }}"
                                     alt="{{ $activity['user_name'] }}"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($activity['user_name']) }}&color=4F76F6&background=EEF2FF&size=40'">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['action'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $activity['timestamp'] }}</p>
                                </div>
                            </div>
                            <span class="ml-2 flex-shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium {{ $activity['status_class'] }}">
                                {{ $activity['status'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gray-100 mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-700">No activities yet</h3>
                    <p class="mt-1 text-xs text-gray-400">Start by borrowing your first item.</p>
                    <a href="{{ route('user.borrowing.index') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Start Borrowing
                    </a>
                </div>
            @endif
        </div>

        {{-- Notifications --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-900">Notifications</h2>
                @if($notifications->count() > 0)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700">
                        {{ $notifications->count() }} new
                    </span>
                @endif
            </div>

            @if($notifications->count() > 0)
                <div class="space-y-2">
                    @foreach($notifications as $notification)
                        @php
                            $notifData = $notification->data;
                            $notifMsg  = $notifData['message'] ?? 'Notification';
                            if (($notifData['type'] ?? '') === 'overdue' && isset($notifData['due_date'])) {
                                try {
                                    $nd      = \Carbon\Carbon::parse($notifData['due_date']);
                                    $ndFixed = max(1, (int) ceil($nd->diffInDays(now(), true)));
                                    $notifMsg = preg_replace('/terlambat\s+[\-\d\.]+\s+hari/i', 'terlambat ' . $ndFixed . ' hari', $notifMsg);
                                } catch (\Throwable $e) {}
                            }
                        @endphp
                        <div class="p-3 rounded-xl bg-blue-50 border border-blue-100">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 mt-0.5 flex items-center justify-center w-7 h-7 rounded-lg bg-blue-100">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800">{{ $notifMsg }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                    @if(isset($notification->data['admin_notes']))
                                        <div class="mt-2 text-xs text-gray-600 bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                                            <strong class="font-semibold">Catatan Admin:</strong> {{ $notification->data['admin_notes'] }}
                                        </div>
                                    @endif
                                    <a href="{{ $notification->data['url'] ?? '#' }}"
                                       class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                        View details
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-gray-100 mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-700">No notifications</h3>
                    <p class="mt-1 text-xs text-gray-400">All caught up! Check back later.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection