<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '6m');
        $months = $period === '12m' ? 12 : 6;
        $startMonth = now()->startOfMonth()->subMonths($months - 1);
        $endMonth = now()->endOfMonth();

        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);

        $kpi = [
            'total_items' => Item::count(),
            'total_stock' => (int) Item::sum('stok_total'),
            'inventory_value' => (float) Item::selectRaw('COALESCE(SUM(stok_total * harga), 0) as total')->value('total'),
            'low_stock_items' => Item::where('stok_total', '>', 0)->where('stok_total', '<=', $lowStockThreshold)->count(),
            'out_of_stock_items' => Item::where('stok_total', '<=', 0)->count(),
        ];

        $requestStatusCounts = BorrowingRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingCount = (int) ($requestStatusCounts['pending'] ?? 0);
        $approvedCount = (int) ($requestStatusCounts['approved'] ?? 0);
        $completedCount = (int) ($requestStatusCounts['completed'] ?? 0);
        $rejectedCount = (int) ($requestStatusCounts['rejected'] ?? 0);
        $cancelledCount = (int) ($requestStatusCounts['cancelled'] ?? 0);
        $totalRequests = $pendingCount + $approvedCount + $completedCount + $rejectedCount + $cancelledCount;

        $completionRate = $totalRequests > 0
            ? round(($completedCount / $totalRequests) * 100, 1)
            : 0;

        $overdueCount = BorrowingRequest::where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', '<', today())
            ->count();

        $inventoryMovementRows = Inventory::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym")
            ->selectRaw("SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END) as incoming")
            ->selectRaw("SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END) as outgoing")
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $movementLabels = [];
        $movementIncoming = [];
        $movementOutgoing = [];
        $movementNet = [];

        $cursor = $startMonth->copy();
        while ($cursor->lte($endMonth)) {
            $key = $cursor->format('Y-m');
            $row = $inventoryMovementRows->get($key);
            $incoming = (int) ($row->incoming ?? 0);
            $outgoing = (int) ($row->outgoing ?? 0);

            $movementLabels[] = $cursor->format('M Y');
            $movementIncoming[] = $incoming;
            $movementOutgoing[] = $outgoing;
            $movementNet[] = $incoming - $outgoing;

            $cursor->addMonth();
        }

        $topBorrowedItems = BorrowingRequest::query()
            ->select('items.nama as item_name')
            ->selectRaw('SUM(borrowing_requests.jumlah) as total_borrowed')
            ->join('items', 'items.id', '=', 'borrowing_requests.item_id')
            ->whereIn('borrowing_requests.status', ['approved', 'completed'])
            ->groupBy('items.id', 'items.nama')
            ->orderByDesc('total_borrowed')
            ->limit(5)
            ->get();

        $categoryStockRows = Item::query()
            ->select('categories.name as category_name')
            ->selectRaw('SUM(items.stok_total) as total_stock')
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_stock')
            ->limit(6)
            ->get();

        $categoryLabels = $categoryStockRows
            ->map(fn ($row) => $row->category_name ?: 'Uncategorized')
            ->values();
        $categoryTotals = $categoryStockRows
            ->map(fn ($row) => (int) $row->total_stock)
            ->values();

        $latestSignals = [
            'incoming_today' => (int) Inventory::where('tipe', 'masuk')->whereDate('created_at', today())->sum('jumlah'),
            'outgoing_today' => (int) Inventory::where('tipe', 'keluar')->whereDate('created_at', today())->sum('jumlah'),
            'active_borrowings' => $approvedCount,
            'overdue_borrowings' => $overdueCount,
        ];

        return view('admin.contents.analytics.index', [
            'period' => $period,
            'lowStockThreshold' => $lowStockThreshold,
            'kpi' => $kpi,
            'borrowing' => [
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'completed' => $completedCount,
                'rejected' => $rejectedCount,
                'cancelled' => $cancelledCount,
                'overdue' => $overdueCount,
                'total' => $totalRequests,
                'completion_rate' => $completionRate,
            ],
            'movementLabels' => $movementLabels,
            'movementIncoming' => $movementIncoming,
            'movementOutgoing' => $movementOutgoing,
            'movementNet' => $movementNet,
            'topBorrowedItems' => $topBorrowedItems,
            'categoryLabels' => $categoryLabels,
            'categoryTotals' => $categoryTotals,
            'latestSignals' => $latestSignals,
        ]);
    }
}
