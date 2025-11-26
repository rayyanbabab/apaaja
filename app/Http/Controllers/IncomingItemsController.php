<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingItemsController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['item', 'item.supplier', 'item.category'])
            ->where('tipe', 'masuk')
            ->whereHas('item') // Only show inventories where item still exists
            ->latest();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $incomingItems = $query->paginate(15);
        $stats = [
            'total_items' => Inventory::where('tipe', 'masuk')->whereHas('item')->count(),
            'total_quantity' => Inventory::where('tipe', 'masuk')->whereHas('item')->sum('jumlah'),
            'this_month' => Inventory::where('tipe', 'masuk')->whereHas('item')->whereMonth('created_at', now()->month)->count(),
            'today' => Inventory::where('tipe', 'masuk')->whereHas('item')->whereDate('created_at', today())->count(),
        ];

        return view('admin.contents.incoming.index', compact('incomingItems', 'stats'));
    }

    public function create()
    {
        $items = Item::with(['supplier:id,nama', 'category:id,name'])
        ->select('id', 'nama', 'stok_total', 'stok_reguler', 'stok_peminjaman', 'supplier_id', 'category_id', 'type', 'harga')
        ->orderBy('nama')    
        ->get()
        ->map(function($item) {
            $stockInfo = $item->type->value === 'stok' 
                ? "Stok Reguler: {$item->stok_reguler}" 
                : "Stok Peminjaman: {$item->stok_peminjaman}";
            
            $item->formatted_name = sprintf(
                '%s (%s) - %s | %s',
                $item->nama,
                $stockInfo,
                $item->supplier->nama ?? 'Tanpa Supplier',
                $item->category->name ?? 'Tanpa Kategori'
            );
            return $item;
        });
        $selectedItemId = request()->query('item_id');

        return view('admin.contents.incoming.create', [
            'items' => $items,
            'selectedItemId' => $selectedItemId
        ]);
    }

    public function store(Request $request)
    {
        \Log::info('Starting multi-item stock addition process', ['request' => $request->all()]);
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:10000'
            ],
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            \Log::info('Transaction started');

            foreach ($validated['items'] as $itemData) {
                $item = Item::lockForUpdate()->findOrFail($itemData['item_id']);
                \Log::info('Item retrieved', ['item' => $item->toArray()]);
                $itemType = $item->type->value;
                $jumlah = (int)$itemData['quantity'];
                
                if ($itemType === 'stok') {
                    $oldStokReguler = (int)$item->stok_reguler;
                    $oldStokTotal = (int)$item->stok_total;
                    $newStokReguler = $oldStokReguler + $jumlah;
                    $newStokTotal = $oldStokTotal + $jumlah;
                    
                    \Log::info('Stock calculation (Reguler)', [
                        'item_id' => $item->id,
                        'item_name' => $item->nama,
                        'item_type' => 'stok',
                        'old_stok_reguler' => $oldStokReguler,
                        'old_stok_total' => $oldStokTotal,
                        'jumlah' => $jumlah,
                        'new_stok_reguler' => $newStokReguler,
                        'new_stok_total' => $newStokTotal
                    ]);
                    $updateResult = DB::table('items')
                        ->where('id', $item->id)
                        ->update([
                            'stok_reguler' => $newStokReguler,
                            'stok_total' => $newStokTotal,
                            'updated_at' => now()
                        ]);
                        
                } elseif ($itemType === 'peminjaman') {
                    $oldStokPeminjaman = (int)$item->stok_peminjaman;
                    $oldStokTotal = (int)$item->stok_total;
                    $newStokPeminjaman = $oldStokPeminjaman + $jumlah;
                    $newStokTotal = $oldStokTotal + $jumlah;
                    
                    \Log::info('Stock calculation (Peminjaman)', [
                        'item_id' => $item->id,
                        'item_name' => $item->nama,
                        'item_type' => 'peminjaman',
                        'old_stok_peminjaman' => $oldStokPeminjaman,
                        'old_stok_total' => $oldStokTotal,
                        'jumlah' => $jumlah,
                        'new_stok_peminjaman' => $newStokPeminjaman,
                        'new_stok_total' => $newStokTotal
                    ]);
                    $updateResult = DB::table('items')
                        ->where('id', $item->id)
                        ->update([
                            'stok_peminjaman' => $newStokPeminjaman,
                            'stok_total' => $newStokTotal,
                            'updated_at' => now()
                        ]);
                } else {
                    \Log::error('Invalid item type', ['item_type' => $itemType]);
                    return back()->with('error', 'Tipe item tidak valid.');
                }
                $inventoryData = [
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'tipe' => 'masuk',
                    'jumlah' => $jumlah,
                    'status' => 'received',
                    'keterangan' => $validated['keterangan'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $inventory = Inventory::create($inventoryData);
                \Log::info('Inventory record created', ['inventory' => $inventory->toArray()]);
                $item = $item->fresh();
                
                \Log::info('Item stock update result', [
                    'update_result' => $updateResult,
                    'updated_item' => $item->toArray()
                ]);

                if ($updateResult === false) {
                    throw new \Exception('Gagal memperbarui stok barang: ' . $item->nama);
                }
            }

            DB::commit();
            \Log::info('Transaction committed successfully');

            $totalItems = count($validated['items']);
            return redirect()->route('admin.incoming.index')
                ->with('success', "Berhasil menambahkan stok untuk {$totalItems} item barang");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding stock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan stok. ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $incomingItem = Inventory::with(['item', 'item.supplier', 'item.category'])
            ->where('tipe', 'masuk')
            ->findOrFail($id);

        return view('admin.contents.incoming.show', compact('incomingItem'));
    }

    public function edit($id)
    {
        $incomingItem = Inventory::where('tipe', 'masuk')->findOrFail($id);
        $items = Item::with(['supplier:id,nama,company_name', 'category:id,name'])
            ->select('id', 'nama', 'stok_total', 'stok_reguler', 'stok_peminjaman', 'supplier_id', 'category_id', 'type', 'harga', 'keterangan')
            ->orderBy('nama')
            ->get();

        return view('admin.contents.incoming.edit', compact('incomingItem', 'items'));
    }

    public function update(Request $request, $id)
    {
        $incomingItem = Inventory::where('tipe', 'masuk')->findOrFail($id);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1|max:10000',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $oldItem = Item::lockForUpdate()->findOrFail($incomingItem->item_id);
            $newItem = Item::lockForUpdate()->findOrFail($validated['item_id']);
            if ($newItem->type->value !== 'stok') {
                return back()->with('error', 'Hanya barang bertipe stok yang dapat diperbarui melalui menu ini.');
            }
            if ($oldItem->id != $newItem->id) {
                $oldItem->reduceStok($incomingItem->jumlah, 'reguler');
            }
            $quantityDiff = $validated['jumlah'] - $incomingItem->jumlah;
            $incomingItem->update([
                'item_id' => $validated['item_id'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? $incomingItem->keterangan,
                'updated_at' => now(),
            ]);
            if ($oldItem->id == $newItem->id) {
                if ($quantityDiff > 0) {
                    $newItem->addStok($quantityDiff, 'reguler');
                } elseif ($quantityDiff < 0) {
                    $newItem->reduceStok(abs($quantityDiff), 'reguler');
                }
            } else {
                $newItem->addStok($validated['jumlah'], 'reguler');
            }

            DB::commit();

            return redirect()->route('admin.incoming.index')
                ->with('success', 'Data stok masuk berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating incoming item: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        $incomingItem = Inventory::where('tipe', 'masuk')->findOrFail($id);

        try {
            DB::beginTransaction();

            $item = Item::lockForUpdate()->findOrFail($incomingItem->item_id);
            $itemType = $item->type->value;
            $stockType = $itemType === 'stok' ? 'reguler' : 'peminjaman';
            $currentStock = $itemType === 'stok' ? $item->stok_reguler : $item->stok_peminjaman;
            if ($currentStock < $incomingItem->jumlah) {
                $stockTypeName = $itemType === 'stok' ? 'reguler' : 'peminjaman';
                return back()->with('error', "Tidak dapat menghapus stok masuk untuk {$item->nama} - Stok {$stockTypeName} tidak mencukupi (tersedia: {$currentStock}, diperlukan: {$incomingItem->jumlah})");
            }
            $item->reduceStok($incomingItem->jumlah, $stockType);
            $incomingItem->delete();

            DB::commit();

            return redirect()->route('admin.incoming.index')
                ->with('success', "Data stok masuk berhasil dihapus untuk {$item->nama} (-{$incomingItem->jumlah})");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting incoming item: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:inventories,id',
        ]);

        try {
            DB::beginTransaction();

            $incomingItems = Inventory::whereIn('id', $validated['selected_items'])
                ->where('tipe', 'masuk')
                ->lockForUpdate()
                ->get();

            $deletedCount = 0;
            $errors = [];

            foreach ($incomingItems as $incomingItem) {
                try {
                    $item = Item::lockForUpdate()->findOrFail($incomingItem->item_id);
                    $itemType = $item->type->value;
                    $stockType = $itemType === 'stok' ? 'reguler' : 'peminjaman';
                    $currentStock = $itemType === 'stok' ? $item->stok_reguler : $item->stok_peminjaman;
                    if ($currentStock < $incomingItem->jumlah) {
                        $stockTypeName = $itemType === 'stok' ? 'reguler' : 'peminjaman';
                        $errors[] = "Tidak dapat menghapus stok masuk untuk {$item->nama} - Stok {$stockTypeName} tidak mencukupi";
                        continue;
                    }
                    $item->reduceStok($incomingItem->jumlah, $stockType);
                    $incomingItem->delete();
                    $deletedCount++;

                } catch (\Exception $e) {
                    \Log::error("Error deleting incoming item #{$incomingItem->id}: " . $e->getMessage());
                    $errors[] = "Gagal menghapus stok masuk #{$incomingItem->id}";
                }
            }

            DB::commit();

            $message = '';
            if ($deletedCount > 0) {
                $message = "Berhasil menghapus {$deletedCount} data stok masuk.";
            }
            
            if (!empty($errors)) {
                $message .= ' ' . implode(' ', $errors);
                return redirect()->back()
                    ->with('warning', trim($message));
            }

            return redirect()->route('admin.incoming.index')
                ->with('success', $message ?: 'Tidak ada data yang dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk delete error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }
}