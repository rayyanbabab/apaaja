<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginLog::with('user')->orderBy('logged_in_at', 'desc');

        if ($request->filled('date_from')) {
            $query->whereDate('logged_in_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('logged_in_at', '<=', $request->date_to);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('logged_in_at', $request->month)
                  ->whereYear('logged_in_at', $request->year);
        } elseif ($request->filled('year')) {
            $query->whereYear('logged_in_at', $request->year);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $activities = $query->paginate(20)->withQueryString();
        $totalActivities = LoginLog::count();
        $todayActivities = LoginLog::whereDate('logged_in_at', today())->count();
        $thisWeekActivities = LoginLog::whereBetween('logged_in_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        $thisMonthActivities = LoginLog::whereMonth('logged_in_at', now()->month)
                                     ->whereYear('logged_in_at', now()->year)
                                     ->count();

        return view('admin.contents.activities.index', compact(
            'activities',
            'totalActivities',
            'todayActivities',
            'thisWeekActivities',
            'thisMonthActivities'
        ));
    }

    public function export(Request $request)
    {
        // Export functionality can be added here
        return response()->json(['message' => 'Export functionality coming soon']);
    }
}
