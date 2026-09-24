<header class="app-top-header flex h-16 items-center justify-between border-b border-gray-100 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm px-3 sm:px-4 lg:px-6" style="position:sticky;top:0;z-index:30;min-width:0;overflow:visible;">

    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">

        <button type="button"
                x-data
                @click="$dispatch('open-mobile-menu')"
                onclick="window.dispatchEvent(new CustomEvent('open-mobile-menu'))"
                class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200/80 dark:border-slate-700 shadow-sm hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-150 active:scale-95 flex-shrink-0 cursor-pointer"
                aria-label="Buka navigasi">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="relative flex-shrink-0">
            <img src="{{ Auth::user()->profil ? asset(Auth::user()->profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=4F76F6&background=EEF2FF&size=40' }}"
                alt="{{ Auth::user()->name }}"
                class="h-9 w-9 rounded-full object-cover ring-2 ring-gray-100 dark:ring-slate-800 shadow-sm"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=4F76F6&background=EEF2FF&size=40'" />
        </div>
        <div class="flex flex-col -space-y-0.5 min-w-0">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-[120px] sm:max-w-none">{{ Auth::user()->name }}</span>
                <span class="hidden sm:inline-flex items-center gap-1 rounded-full bg-blue-50 dark:bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600 dark:text-blue-400 ring-1 ring-blue-100 dark:ring-blue-500/20 flex-shrink-0">
                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    User
                </span>
            </div>
            <p class="hidden sm:block text-xs text-gray-400 dark:text-slate-400 truncate max-w-[200px]">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <div class="flex items-center gap-2">

        @include('admin.components.partials.dark-mode-toggle')

        <div class="relative" x-data="notifDropdownUser('{{ route('user.notifications.recent') }}', '{{ route('user.notifications.read-all') }}', '{{ route('user.notifications.index') }}')" @click.away="open = false">
            <button @click="toggle()" id="user-notif-bell"
                class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700/60 transition-colors focus:outline-none ring-1 ring-gray-200 dark:ring-slate-700">
                <svg class="h-4.5 w-4.5" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <span x-show="unreadCount > 0"
                x-text="unreadCount > 9 ? '9+' : unreadCount"
                class="absolute -top-1.5 -right-1.5 flex h-4.5 min-w-[18px] items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-bold text-white ring-2 ring-white dark:ring-slate-900"
                style="display:none;height:18px;pointer-events:none;z-index:40;"></span>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute right-0 top-11 z-50 w-72 sm:w-80 rounded-2xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl"
                style="display:none;max-width:calc(100vw - 1.5rem);">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-slate-800">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white">Pemberitahuan</h4>
                    <button @click="markAllRead()" x-show="unreadCount > 0"
                        class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">Tandai dibaca</button>
                </div>
                <div class="max-h-72 overflow-y-auto divide-y divide-gray-50 dark:divide-slate-800">
                    <template x-if="notifications.length === 0">
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-800 mb-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-500 dark:text-slate-400">Belum ada notifikasi</p>
                        </div>
                    </template>
                    <template x-for="n in notifications" :key="n.id">
                        <a :href="n.url" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-800/60 transition-colors"
                            :class="n.is_unread ? 'bg-blue-50/40 dark:bg-blue-950/20' : ''">
                            <span class="flex-shrink-0 mt-1.5 flex h-2 w-2 rounded-full"
                                :class="n.is_unread ? 'bg-blue-500' : 'bg-gray-300 dark:bg-slate-600'"></span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs leading-snug" :class="n.is_unread ? 'font-bold text-gray-900 dark:text-white' : 'text-gray-600 dark:text-slate-300'" x-text="n.message"></p>
                                <p class="mt-0.5 text-[10px] text-gray-400 dark:text-slate-400" x-text="n.created_at"></p>
                            </div>
                        </a>
                    </template>
                </div>
                <div class="border-t border-gray-100 dark:border-slate-800 px-4 py-2.5 bg-gray-50/50 dark:bg-slate-900/50 rounded-b-2xl">
                    <a :href="allNotifUrl" class="block text-center text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                        Lihat semua notifikasi &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function notifDropdownUser(recentUrl, markAllUrl, allUrl) {
    return {
        open: false,
        unreadCount: 0,
        notifications: [],
        allNotifUrl: allUrl,

        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifications();
        },

        async fetchNotifications() {
            try {
                const res = await fetch(recentUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch(e) {}
        },

        async markAllRead() {
            try {
                await fetch(markAllUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                this.unreadCount = 0;
                this.notifications = this.notifications.map(n => ({...n, is_unread: false}));
            } catch(e) {}
        }
    };
}
</script>
