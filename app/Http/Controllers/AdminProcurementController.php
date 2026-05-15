<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\ProcurementRequest;
use App\Notifications\ProcurementReviewedNotification;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminProcurementController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcurementRequest::with(['user'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $procurements = $query->paginate(15)->appends($request->except('page'));

        $counts = ProcurementRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingCount  = (int) ($counts['pending']  ?? 0);
        $approvedCount = (int) ($counts['approved'] ?? 0);
        $rejectedCount = (int) ($counts['rejected'] ?? 0);
        $totalCount    = $pendingCount + $approvedCount + $rejectedCount;

        return view('admin.contents.procurement-requests.index', compact(
            'procurements', 'pendingCount', 'approvedCount', 'rejectedCount', 'totalCount'
        ));
    }

    public function show($id)
    {
        $procurement = ProcurementRequest::with(['user', 'reviewer', 'item', 'inventory'])
            ->findOrFail($id);

        // Items list for approve dropdown
        $items = Item::orderBy('nama')->get(['id', 'nama', 'kode', 'type', 'stok_reguler', 'stok_peminjaman']);

        return view('admin.contents.procurement-requests.show', compact('procurement', 'items'));
    }

    public function approve(Request $request, $id)
    {
        $procurement = ProcurementRequest::with('user')->findOrFail($id);

        if ($procurement->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan yang masih pending yang dapat disetujui.');
        }

        $validated = $request->validate([
            'action_type'    => 'required|in:existing,new',
            'item_id'        => 'required_if:action_type,existing|nullable|exists:items,id',
            'new_item_name'  => 'required_if:action_type,new|nullable|string|max:255',
            'admin_notes'    => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $jumlah = $procurement->jumlah;

            if ($validated['action_type'] === 'existing') {
                // Use existing item
                $item = Item::lockForUpdate()->findOrFail($validated['item_id']);
                $stockType = $item->type->value === 'stok' ? 'reguler' : 'peminjaman';

                // Add stock
                $item->addStok($jumlah, $stockType);

            } else {
                // Create brand-new item (minimal, admin can fill rest later)
                $kode = 'ITM-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['new_item_name']), 0, 4))
                      . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

                // Ensure unique kode
                while (Item::where('kode', $kode)->exists()) {
                    $kode = 'ITM-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['new_item_name']), 0, 4))
                          . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                }

                $item = Item::create([
                    'kode'             => $kode,
                    'nama'             => $validated['new_item_name'],
                    'stok_total'       => $jumlah,
                    'stok_reguler'     => $jumlah,
                    'stok_peminjaman'  => 0,
                    'type'             => 'stok',
                ]);
            }

            // Create inventory record (incoming)
            $inventory = Inventory::create([
                'item_id'    => $item->id,
                'user_id'    => Auth::id(),
                'tipe'       => 'masuk',
                'jumlah'     => $jumlah,
                'status'     => 'received',
                'keterangan' => 'Pengadaan dari permintaan #' . $procurement->id . ' oleh ' . $procurement->user->name,
            ]);

            // Update procurement
            $procurement->update([
                'status'       => 'approved',
                'reviewed_by'  => Auth::id(),
                'reviewed_at'  => now(),
                'admin_notes'  => $validated['admin_notes'] ?? null,
                'item_id'      => $item->id,
                'inventory_id' => $inventory->id,
            ]);

            // Notify user
            $procurement->user->notify(new ProcurementReviewedNotification($procurement->fresh(['user', 'item'])));

            AuditLogger::log(
                'procurement.approved',
                'Pengadaan',
                "Permintaan pengadaan #{$procurement->id} \"{$procurement->nama_barang}\" oleh {$procurement->user->name} disetujui. Item: {$item->nama} (+{$jumlah})",
                $procurement
            );

            DB::commit();

            return redirect()->route('admin.procurement-requests.index')
                ->with('success', "Permintaan pengadaan \"{$procurement->nama_barang}\" berhasil disetujui. Stok +{$jumlah} ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $procurement = ProcurementRequest::with('user')->findOrFail($id);

        if ($procurement->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan yang masih pending yang dapat ditolak.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $procurement->update([
                'status'      => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $validated['admin_notes'],
            ]);

            $procurement->user->notify(new ProcurementReviewedNotification($procurement->fresh(['user'])));

            AuditLogger::log(
                'procurement.rejected',
                'Pengadaan',
                "Permintaan pengadaan #{$procurement->id} \"{$procurement->nama_barang}\" oleh {$procurement->user->name} ditolak",
                $procurement
            );

            DB::commit();

            return redirect()->route('admin.procurement-requests.index')
                ->with('success', "Permintaan pengadaan \"{$procurement->nama_barang}\" berhasil ditolak.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
