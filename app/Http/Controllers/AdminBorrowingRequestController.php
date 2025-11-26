<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminBorrowingRequestController extends Controller
{
    public function index()
    {
        $allRequests = BorrowingRequest::with(['user', 'item', 'item.supplier', 'approvedBy'])
            ->latest()
            ->get();
        $groupedRequests = collect();
        $processedBatchIds = [];
        foreach ($allRequests as $request) {
            if ($request->batch_id && !in_array($request->batch_id, $processedBatchIds)) {
                $batchItems = $allRequests->where('batch_id', $request->batch_id);
                $groupedRequests->push([
                    'is_batch' => true,
                    'batch_id' => $request->batch_id,
                    'main_request' => $request,
                    'items' => $batchItems,
                    'created_at' => $request->created_at,
                ]);
                $processedBatchIds[] = $request->batch_id;
            } elseif (!$request->batch_id) {
                $groupedRequests->push([
                    'is_batch' => false,
                    'main_request' => $request,
                    'created_at' => $request->created_at,
                ]);
            }
        }
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $requests = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedRequests->forPage($currentPage, $perPage),
            $groupedRequests->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $pendingCount = BorrowingRequest::where('status', 'pending')->count();
        $approvedCount = BorrowingRequest::where('status', 'approved')->count();
        $rejectedCount = BorrowingRequest::where('status', 'rejected')->count();
        $completedCount = BorrowingRequest::where('status', 'completed')->count();

        return view('admin.contents.borrowing-requests.index', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount'
        ));
    }

    public function show($id)
    {
        $request = BorrowingRequest::with(['user', 'item', 'item.supplier', 'item.category', 'approvedBy'])
            ->findOrFail($id);

        return view('admin.contents.borrowing-requests.show', compact('request'));
    }

    public function approve(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'approve_batch' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($borrowingRequest->batch_id && ($validated['approve_batch'] ?? false)) {
                $batchRequests = BorrowingRequest::where('batch_id', $borrowingRequest->batch_id)
                    ->where('status', 'pending')
                    ->get();

                foreach ($batchRequests as $batchReq) {
                    $item = $batchReq->item;
                    if ($item->stok_peminjaman < $batchReq->jumlah) {
                        DB::rollBack();
                        return back()->withErrors(['error' => 'Stok '.$item->nama.' tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
                    }
                    $batchReq->update([
                        'status' => 'approved',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'admin_notes' => $validated['admin_notes'] ?? null,
                    ]);
                    $item->decrement('stok_peminjaman', $batchReq->jumlah);
                    $item->updateStokTotal();
                }

                DB::commit();
                return redirect()->route('admin.borrowing-requests.index')
                    ->with('success', 'Semua permintaan dalam paket berhasil disetujui.');
            } else {
                $item = $borrowingRequest->item;
                if ($item->stok_peminjaman < $borrowingRequest->jumlah) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
                }

                $borrowingRequest->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'admin_notes' => $validated['admin_notes'] ?? null,
                ]);

                $item->decrement('stok_peminjaman', $borrowingRequest->jumlah);
                $item->updateStokTotal();

                DB::commit();
                return redirect()->route('admin.borrowing-requests.index')
                    ->with('success', 'Permintaan peminjaman berhasil disetujui.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan.']);
        }
    }

    public function reject(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
            'reject_batch' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($borrowingRequest->batch_id && ($validated['reject_batch'] ?? false)) {
                BorrowingRequest::where('batch_id', $borrowingRequest->batch_id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'approved_by' => Auth::id(),
                        'approved_at' => now(),
                        'admin_notes' => $validated['admin_notes'],
                    ]);
                DB::commit();
                return redirect()->route('admin.borrowing-requests.index')
                    ->with('success', 'Semua permintaan dalam paket berhasil ditolak.');
            } else {
                $borrowingRequest->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'admin_notes' => $validated['admin_notes'],
                ]);

                DB::commit();
                return redirect()->route('admin.borrowing-requests.index')
                    ->with('success', 'Permintaan peminjaman berhasil ditolak.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan.']);
        }
    }

    public function complete($id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        if ($borrowingRequest->status !== 'approved') {
            return back()->withErrors(['error' => 'Hanya permintaan yang disetujui yang dapat diselesaikan.']);
        }

        $borrowingRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        $borrowingRequest->item->increment('stok_peminjaman', $borrowingRequest->jumlah);
        $borrowingRequest->item->updateStokTotal();

        return redirect()->route('admin.borrowing-requests.index')
            ->with('success', 'Peminjaman berhasil diselesaikan dan stok dikembalikan.');
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
