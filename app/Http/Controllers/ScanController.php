<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Borrowing;
use App\Models\Maintenance;
use App\Notifications\OngoingBorrowingNotification;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Check that the user is admin or operator.
     */
    private function ensureStaff(): void
    {
        $userRole = auth()->user()->role;
        $userRole = $userRole instanceof \App\Enums\UsersRole ? $userRole->value : $userRole;

        if (!in_array($userRole, ['admin', 'operator'])) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Show the camera scanner view.
     */
    public function index()
    {
        $this->ensureStaff();
        return view('admin.scan.index');
    }

    /**
     * Handle the scanned item code — show quick actions page.
     */
    public function handleScan(string $kode)
    {
        $this->ensureStaff();

        $item = Item::with(['category', 'location'])->where('kode', $kode)->firstOrFail();

        // Active borrowings for this item
        $activeBorrowings = Borrowing::with('user')
            ->where('item_id', $item->id)
            ->where('status', 'dipinjam')
            ->orderByDesc('tanggal_pinjam')
            ->get();

        // Borrowing history (returned, last 10)
        $borrowingHistory = Borrowing::with('user')
            ->where('item_id', $item->id)
            ->where('status', '!=', 'dipinjam')
            ->orderByDesc('tanggal_kembali_aktual')
            ->limit(10)
            ->get();

        // Maintenance history (last 10)
        $maintenanceHistory = Maintenance::with('user')
            ->where('item_id', $item->id)
            ->orderByDesc('started_at')
            ->limit(10)
            ->get();

        return view('admin.scan.result', compact(
            'item',
            'activeBorrowings',
            'borrowingHistory',
            'maintenanceHistory'
        ));
    }

    /**
     * Quick return a borrowing from the scan result page.
     */
    public function quickReturn(Borrowing $borrowing)
    {
        $this->ensureStaff();

        if ($borrowing->status === 'dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan sebelumnya.');
        }

        $borrowing->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => now()->toDateString(),
        ]);

        $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');

        return back()->with('success', "Peminjaman oleh {$borrowing->user?->name} berhasil dikembalikan. Stok dipulihkan.");
    }

    /**
     * Quick maintenance or scrap from the scan result page.
     */
    public function quickMaintenance(Request $request, Item $item)
    {
        $this->ensureStaff();

        $validated = $request->validate([
            'jumlah'  => 'required|integer|min:1|max:' . max(1, $item->stok_reguler),
            'status'  => 'required|in:in_repair,scrapped',
            'catatan' => 'nullable|string|max:500',
        ]);

        $item->reduceStok($validated['jumlah'], 'reguler');

        $maintenance = Maintenance::create([
            'item_id'      => $item->id,
            'user_id'      => auth()->id(),
            'jumlah'       => $validated['jumlah'],
            'status'       => $validated['status'],
            'catatan'      => $validated['catatan'] ?? '-',
            'kondisi_masuk' => $validated['status'] === 'in_repair' ? 'Rusak/Perlu Perbaikan' : 'Scrap',
        ]);

        if ($validated['status'] === 'in_repair') {
            $operators = \App\Models\User::where('role', 'operator')->get();
            if ($operators->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send(
                    $operators,
                    new \App\Notifications\MaintenanceAssignedNotification($maintenance->load('item'), auth()->user()->name)
                );
            }
        }

        $msg = $validated['status'] === 'in_repair'
            ? "{$validated['jumlah']} unit barang dipindahkan ke Dalam Perbaikan. Operator telah dinotifikasi."
            : "{$validated['jumlah']} unit barang ditandai sebagai Scrapped.";

        return back()->with('success', $msg);
    }
}
