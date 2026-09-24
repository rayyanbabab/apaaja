<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    private function cacheKey(int|string $userId, string $suffix): string
    {
        return "notifications:user:{$userId}:{$suffix}";
    }

    private function clearNotificationCache(int|string $userId): void
    {
        Cache::forget($this->cacheKey($userId, 'recent'));
        Cache::forget($this->cacheKey($userId, 'unread_count'));
    }

    /**
     * Rewrite a stored notification URL so it matches the current user's role prefix.
     * Uses the current request host — works regardless of APP_URL in .env.
     */
    private function resolveNotificationUrl(string $url, string $role): string
    {
        if (!$url || $url === '#') return $url;

        $prefixMap = [
            'admin'    => '/admin/',
            'operator' => '/staff/',
            'user'     => '/user/',
        ];

        $correctPrefix = $prefixMap[$role] ?? null;
        if (!$correctPrefix) return $url;

        // Use current request host (127.0.0.1:8000, domain, etc.) — NOT APP_URL
        $appUrl = rtrim(request()->getSchemeAndHttpHost(), '/');

        // Extract only the path — ignoring whatever host was stored
        $parsed = parse_url($url);
        $path   = $parsed['path'] ?? $url;
        $query  = isset($parsed['query']) ? '?' . $parsed['query'] : '';

        // Dashboard links → role's own dashboard
        if (preg_match('#^/(admin|staff|user)/dashboard$#', $path)) {
            return $appUrl . $correctPrefix . 'dashboard';
        }

        // Maintenance: admin ↔ staff prefix
        if (preg_match('#^/(admin|staff|user)/(maintenance/.*)$#', $path, $m)) {
            $prefix = ($role === 'operator') ? '/staff/' : '/admin/';
            return $appUrl . $prefix . $m[2] . $query;
        }

        // Inventory: always /admin/
        if (preg_match('#^/(admin|staff|user)/(inventory/.*)$#', $path, $m)) {
            return $appUrl . '/admin/' . $m[2] . $query;
        }

        // Borrowing: admin → /admin/, others → /user/
        if (preg_match('#^/(admin|staff|user)/(borrowing.*)$#', $path, $m)) {
            $prefix = ($role === 'admin') ? '/admin/' : '/user/';
            return $appUrl . $prefix . $m[2] . $query;
        }

        // Default: swap stored prefix with correct role prefix
        $newPath = preg_replace('#^/(admin|staff|user)/#', $correctPrefix, $path);
        return $appUrl . $newPath . $query;
    }

    /**
     * Halaman daftar semua notifikasi.
     * Mendukung query: ?filter=all|unread  &  ?type=overdue|approved|...
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user        = Auth::user();
        $filter      = $request->query('filter', 'all');   // all | unread
        $typeFilter  = $request->query('type', '');        // overdue, approved, etc.
        $role        = $user->role instanceof \App\Enums\UsersRole ? $user->role->value : (string) ($user->role ?? 'user');

        $query = $user->notifications()->orderBy('created_at', 'desc');

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        if ($typeFilter) {
            $query->where('data->type', $typeFilter);
        }

        $notifications = $query->paginate(20)->withQueryString();

        // Rewrite URLs in each notification to match current user role
        $notifications->getCollection()->transform(function ($n) use ($role) {
            $data = $n->data;
            if (isset($data['url'])) {
                $data['url'] = $this->resolveNotificationUrl($data['url'], $role);
                $n->data = $data;
            }
            return $n;
        });

        if ($filter === 'all' && !$typeFilter) {
            $user->unreadNotifications()->update(['read_at' => now()]);
            $this->clearNotificationCache($user->id);
        }

        if (in_array($role, ['admin', 'operator'])) {
            return view('admin.contents.notifications.index', compact('notifications'));
        }

        return view('user.contents.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai terbaca.
     */
    public function markRead($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        $this->clearNotificationCache($user->id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai terbaca.
     */
    public function markAllRead()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        $this->clearNotificationCache($user->id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
    }

    /**
     * API: Jumlah notifikasi belum dibaca (untuk polling badge).
     */
    public function unreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        $count = Cache::remember(
            $this->cacheKey($user->id, 'unread_count'),
            now()->addSeconds(10),
            fn () => $user->unreadNotifications()->count()
        );
        return response()->json(['count' => $count]);
    }

    /**
     * API: 8 notifikasi terbaru untuk dropdown.
     */
    public function recent()
    {
        /** @var User $user */
        $user = Auth::user();

        $payload = Cache::remember(
            $this->cacheKey($user->id, 'recent'),
            now()->addSeconds(10),
            function () use ($user) {
                $notifications = $user
                    ->notifications()
                    ->select(['id', 'data', 'read_at', 'created_at'])
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get()
                    ->map(function ($n) use ($user) {
                        $data    = $n->data;
                        $role    = $user->role instanceof \App\Enums\UsersRole ? $user->role->value : (string) ($user->role ?? 'user');
                        $message = $data['message'] ?? '';

                        // Fix legacy overdue messages that stored negative/float day counts
                        if (($data['type'] ?? '') === 'overdue' && isset($data['due_date'])) {
                            try {
                                $due  = \Carbon\Carbon::parse($data['due_date']);
                                $days = max(1, (int) ceil($due->diffInDays(now(), true)));
                                $message = preg_replace('/terlambat\s+[\-\d\.]+\s+hari/i', 'terlambat ' . $days . ' hari', $message);
                            } catch (\Throwable $e) {}
                        }

                        $rawUrl   = $data['url'] ?? '#';
                        $resolvedUrl = $this->resolveNotificationUrl($rawUrl, $role);

                        return [
                            'id'         => $n->id,
                            'message'    => $message,
                            'type'       => $data['type'] ?? 'info',
                            'url'        => $resolvedUrl,
                            'read_at'    => $n->read_at,
                            'is_unread'  => is_null($n->read_at),
                            'created_at' => $n->created_at->diffForHumans(),
                        ];
                    });

                $unreadCount = $user->unreadNotifications()->count();
                Cache::put($this->cacheKey($user->id, 'unread_count'), $unreadCount, now()->addSeconds(10));

                return [
                    'notifications' => $notifications,
                    'unread_count' => $unreadCount,
                ];
            }
        );

        return response()->json($payload);
    }
}
