<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'audit'); // 'audit' or 'login'

        // ── AUDIT LOG TAB ──────────────────────────────────────────────
        $auditQuery = AuditLog::with('user')->latest();

        if ($request->filled('module')) {
            $auditQuery->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $auditQuery->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $auditQuery->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $auditQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $auditQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $auditQuery->where('description', 'like', '%' . $request->search . '%');
        }

        $auditLogs = $auditQuery->paginate(20)->withQueryString();

        $auditStats = [
            'total'      => AuditLog::count(),
            'today'      => AuditLog::whereDate('created_at', today())->count(),
            'this_week'  => AuditLog::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => AuditLog::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        // Unique modules & users for filter dropdowns
        $modules = AuditLog::distinct()->pluck('module')->sort()->values();
        $auditUsers = \App\Models\User::orderBy('name')
            ->whereIn('id', AuditLog::distinct()->pluck('user_id'))
            ->get(['id', 'name']);

        // ── LOGIN LOG TAB ──────────────────────────────────────────────
        $loginQuery = LoginLog::with('user')->orderBy('logged_in_at', 'desc');

        if ($tab === 'login') {
            if ($request->filled('date_from')) {
                $loginQuery->whereDate('logged_in_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $loginQuery->whereDate('logged_in_at', '<=', $request->date_to);
            }
            if ($request->filled('role')) {
                $loginQuery->whereHas('user', fn($q) => $q->where('role', $request->role));
            }
            if ($request->filled('search')) {
                $loginQuery->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            }
        }

        $activities     = $loginQuery->paginate(20)->withQueryString();
        $totalActivities     = LoginLog::count();
        $todayActivities     = LoginLog::whereDate('logged_in_at', today())->count();
        $thisWeekActivities  = LoginLog::whereBetween('logged_in_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $thisMonthActivities = LoginLog::whereMonth('logged_in_at', now()->month)->whereYear('logged_in_at', now()->year)->count();

        return view('admin.contents.activities.index', compact(
            'tab',
            'auditLogs',
            'auditStats',
            'modules',
            'auditUsers',
            'activities',
            'totalActivities',
            'todayActivities',
            'thisWeekActivities',
            'thisMonthActivities'
        ));
    }

    public function export(Request $request)
    {
        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
