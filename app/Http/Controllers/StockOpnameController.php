<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        $stockOpnames = StockOpname::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contents.stock-opname.index', compact('stockOpnames'));
    }

    public function create()
    {
        return view('admin.contents.stock-opname.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $stockOpname = StockOpname::create([
                'user_id' => Auth::id(),
                'status' => StockOpname::STATUS_IN_PROGRESS,
                'start_date' => now(),
                'notes' => $request->notes,
            ]);

            $items = Item::all();
            $opnameItems = [];

            foreach ($items as $item) {
                $opnameItems[] = [
                    'stock_opname_id' => $stockOpname->id,
                    'item_id' => $item->id,
                    'system_stok' => $item->stok_total, // Menggunakan stok total agar sesuai dengan tipe item (stok_reguler / stok_peminjaman)
                    'actual_stok' => null,
                    'variance' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            StockOpnameItem::insert($opnameItems);

            DB::commit();

            return redirect()->route('admin.stock-opnames.show', $stockOpname->id)
                ->with('success', 'Sesi Stock Opname berhasil dimulai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai Stock Opname: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $stockOpname = StockOpname::with(['items.item.category', 'items.item.location'])->findOrFail($id);
        return view('admin.contents.stock-opname.show', compact('stockOpname'));
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'actual_stok' => 'required|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        $item = StockOpnameItem::findOrFail($id);
        
        if ($item->stockOpname->status !== StockOpname::STATUS_IN_PROGRESS) {
            return response()->json(['success' => false, 'message' => 'Sesi ini sudah tidak aktif.']);
        }

        $variance = $request->actual_stok - $item->system_stok;

        $item->update([
            'actual_stok' => $request->actual_stok,
            'variance' => $variance,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data tersimpan.',
            'variance' => $variance
        ]);
    }

    public function complete(Request $request, $id)
    {
        $stockOpname = StockOpname::with('items')->findOrFail($id);

        if ($stockOpname->status !== StockOpname::STATUS_IN_PROGRESS) {
            return back()->with('error', 'Sesi ini sudah tidak aktif.');
        }

        // Check if all items have been audited (actual_stok is not null)
        $unauditedCount = $stockOpname->items()->whereNull('actual_stok')->count();
        if ($unauditedCount > 0 && !$request->has('force')) {
            return back()->with('error', 'Ada ' . $unauditedCount . ' barang yang belum diaudit. Mohon lengkapi atau centang opsi Paksa Selesai.');
        }

        try {
            DB::beginTransaction();

            foreach ($stockOpname->items as $opnameItem) {
                if ($opnameItem->actual_stok !== null && $opnameItem->variance !== 0) {
                    $item = Item::find($opnameItem->item_id);
                    if ($item) {
                        // Create inventory history for adjustment
                        $tipe = $opnameItem->variance > 0 ? Inventory::TYPE_IN : Inventory::TYPE_OUT;
                        
                        Inventory::create([
                            'item_id' => $item->id,
                            'user_id' => Auth::id(),
                            'tipe' => $tipe,
                            'jumlah' => abs($opnameItem->variance),
                            'status' => Inventory::STATUS_PROCESSED,
                            'keterangan' => 'Penyesuaian stok dari Stock Opname #' . $stockOpname->id . ($opnameItem->notes ? ': ' . $opnameItem->notes : ''),
                        ]);

                        // Update actual item stock
                        if ($item->type === \App\Enums\ItemType::PEMINJAMAN) {
                            $item->stok_peminjaman = $opnameItem->actual_stok;
                        } else {
                            $item->stok_reguler = $opnameItem->actual_stok;
                        }
                        $item->updateStokTotal();
                    }
                }
            }

            $stockOpname->update([
                'status' => StockOpname::STATUS_COMPLETED,
                'end_date' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.stock-opnames.index')
                ->with('success', 'Sesi Stock Opname diselesaikan dan stok berhasil disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan Stock Opname: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        $stockOpname = StockOpname::findOrFail($id);

        if ($stockOpname->status !== StockOpname::STATUS_IN_PROGRESS) {
            return back()->with('error', 'Sesi ini tidak dapat dibatalkan.');
        }

        $stockOpname->update([
            'status' => StockOpname::STATUS_CANCELLED,
            'end_date' => now(),
        ]);

        return redirect()->route('admin.stock-opnames.index')
            ->with('info', 'Sesi Stock Opname dibatalkan.');
    }
}
