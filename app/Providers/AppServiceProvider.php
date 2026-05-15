<?php

namespace App\Providers;

use App\Models\Borrowing;
use App\Models\BorrowingCart;
use App\Models\BorrowingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (request()->header('x-forwarded-proto') == 'https' || str_starts_with(env('APP_URL', ''), 'https://')) {
            URL::forceScheme('https');
        }

        Inertia::setRootView('app');

        // ── Admin components (sidebar, header, mobile hamburger) ──────────────────
        // All three share badge counts + company profile in a single composer.
        View::composer(
            [
                'admin.components.sidebar-new',
                'admin.components.header',
                'admin.components.mobile-hamburger',
            ],
            function (\Illuminate\View\View $view) {
                if (! Auth::check()) {
                    return;
                }

                $requestCounts = BorrowingRequest::selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status');

                $userRole = Auth::user()->role->value ?? 'admin';
                $routePrefix = $userRole === 'operator' ? 'staff' : 'admin';

                $view->with([
                    // Badge counts
                    'sidebarActiveBorrowingCount' => Borrowing::whereIn('status', ['dipinjam', 'terlambat'])->count(),
                    'sidebarPendingRequestCount'  => $requestCounts->get('pending', 0),
                    'sidebarAdminUnreadCount'     => Auth::user()->unreadNotifications()->count(),

                    // Company profile from Settings
                    'companyName'    => Setting::get('company_name',    'Artilia'),
                    'companyLogo'    => Setting::get('company_logo',    null),
                    'companyTagline' => Setting::get('company_tagline', 'Inventory Management'),

                    // Route prefix (admin or staff)
                    'routePrefix' => $routePrefix,
                ]);
            }
        );

        // ── Share routePrefix to ALL admin content views ──────────────────────────
        View::composer('admin.*', function (\Illuminate\View\View $view) {
            if (! Auth::check()) {
                return;
            }
            $userRole = Auth::user()->role->value ?? 'admin';
            $view->with('routePrefix', $userRole === 'operator' ? 'staff' : 'admin');
        });

        // ── User sidebar badge counts ─────────────────────────────────────────────
        View::composer(
            [
                'user.components.sidebar-user',
                'user.components.mobile-hamburger-user',
            ],
            function (\Illuminate\View\View $view) {
                if (! Auth::check()) {
                    return;
                }

                $view->with([
                    'sidebarCartCount'       => BorrowingCart::where('user_id', Auth::id())->count(),
                    'sidebarUserUnreadCount' => Auth::user()->unreadNotifications()->count(),

                    // Company profile
                    'companyName'    => Setting::get('company_name',    'Artilia'),
                    'companyLogo'    => Setting::get('company_logo',    null),
                    'companyTagline' => Setting::get('company_tagline', 'Inventory Management'),
                ]);
            }
        );
    }
}
