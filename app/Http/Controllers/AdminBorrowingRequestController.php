<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Notifications\BorrowingApprovedNotification;
use App\Notifications\BorrowingRejectedNotification;
use App\Notifications\BorrowingCompletedNotification;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminBorrowingRequestController extends Controller
{
    public function index()
    {
        // Get only pending requests for approval
        $requests = BorrowingRequest::with(['user', 'item', 'item.supplier', 'approvedBy'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        // Single query for all status counts
        $counts = BorrowingRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingCount = $counts->get('pending', 0);
        $approvedCount = $counts->get('approved', 0);
        $rejectedCount = $counts->get('rejected', 0);
        $completedCount = $counts->get('completed', 0);

        return view('admin.contents.borrowing-requests.index', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount'
        ));
    }

    public function history(Request $request)
    {
        // Show all borrowing requests with final statuses (approved, rejected, completed, cancelled)
        $query = BorrowingRequest::with(['user', 'item', 'item.supplier', 'approvedBy'])
            ->whereIn('status', ['approved', 'rejected', 'completed', 'cancelled']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('item', function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'LIKE', "%{$search}%");
                });
            });
        }

        // Apply date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->latest()->paginate(15)->appends($request->except('page'));

        // Single query for all status counts
        $counts = BorrowingRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $approvedCount = $counts->get('approved', 0);
        $rejectedCount = $counts->get('rejected', 0);
        $completedCount = $counts->get('completed', 0);
        $cancelledCount = $counts->get('cancelled', 0);

        return view('admin.contents.borrowing-requests.history', compact(
            'requests',
            'approvedCount',
            'rejectedCount',
            'completedCount',
            'cancelledCount'
        ));
    }

    public function show($id)
    {
        $request = BorrowingRequest::with(['user', 'item', 'item.supplier', 'item.category', 'item.location', 'item.location.parent', 'approvedBy'])
            ->findOrFail($id);

        return view('admin.contents.borrowing-requests.show', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',  // Require notes for approval
        ]);

        DB::beginTransaction();
        try {
            // Single item approval only
            $item = $borrowingRequest->item;
            if ($item->stok_peminjaman < $borrowingRequest->jumlah) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Stok peminjaman tidak mencukupi. Stok tersedia: ' . $item->stok_peminjaman]);
            }

            $borrowingRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            $item->reduceStok($borrowingRequest->jumlah, 'peminjaman');

            // Send notification to user
            $borrowingRequest->user->notify(new BorrowingApprovedNotification($borrowingRequest));

            AuditLogger::log(
                'borrowing.approved',
                'Peminjaman',
                "Peminjaman #{$borrowingRequest->id} oleh {$borrowingRequest->user->name} ({$borrowingRequest->item->nama} x{$borrowingRequest->jumlah}) disetujui",
                $borrowingRequest
            );

            DB::commit();
            return panel_redirect('borrowing-requests.index')
                ->with('success', 'Permintaan peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Single item rejection only
            $borrowingRequest->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Send notification to user
            $borrowingRequest->user->notify(new BorrowingRejectedNotification($borrowingRequest));

            AuditLogger::log(
                'borrowing.rejected',
                'Peminjaman',
                "Peminjaman #{$borrowingRequest->id} oleh {$borrowingRequest->user->name} ({$borrowingRequest->item->nama}) ditolak",
                $borrowingRequest
            );

            DB::commit();
            return panel_redirect('borrowing-requests.index')
                ->with('success', 'Permintaan peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage()]);
        }
    }

    public function complete($id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        if ($borrowingRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Hanya permintaan yang disetujui yang dapat diselesaikan.']);
        }

        DB::beginTransaction();
        try {
            $borrowingRequest->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            $borrowingRequest->item->addStok($borrowingRequest->jumlah, 'peminjaman');

            // Send notification to user
            $borrowingRequest->user->notify(new BorrowingCompletedNotification($borrowingRequest));

            AuditLogger::log(
                'borrowing.completed',
                'Peminjaman',
                "Peminjaman #{$borrowingRequest->id} oleh {$borrowingRequest->user->name} ({$borrowingRequest->item->nama} x{$borrowingRequest->jumlah}) diselesaikan & stok dikembalikan",
                $borrowingRequest
            );

            DB::commit();
            return panel_redirect('borrowing-requests.index')
                ->with('success', 'Peminjaman berhasil diselesaikan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyelesaikan peminjaman: ' . $e->getMessage()]);
        }
    }

    public function pending()
    {
        $requests = BorrowingRequest::with(['user', 'item', 'item.supplier'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('admin.contents.borrowing-requests.pending', compact('requests'));
    }
}