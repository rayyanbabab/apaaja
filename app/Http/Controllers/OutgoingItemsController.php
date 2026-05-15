<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OutgoingItemsController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['item.supplier', 'item.category', 'user'])
            ->where('tipe', 'keluar')
            ->whereHas('item')
            ->latest();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }
        if ($request->filled('user_filter')) {
            $query->where('user_id', $request->user_filter);
        }
        $query->where('status', 'to_production');
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $outgoingItems = $query->paginate(15)->withQueryString();
        $users = User::select('id', 'name')
            ->whereHas('inventories', function ($q) {
                $q->where('tipe', 'keluar');
            })
            ->where('role', 'user')
            ->orderBy('name')
            ->get();
        $stats = Inventory::where('tipe', 'keluar')
            ->whereHas('item')
            ->selectRaw('
                COUNT(*) as total_items,
                SUM(jumlah) as total_quantity,
                SUM(CASE WHEN status = "damaged" THEN 1 ELSE 0 END) as damaged_items
            ')
            ->first()
            ->toArray();
        return view('admin.contents.outgoing.index', compact('outgoingItems', 'stats', 'users'));
    }

    public function create()
    {
        $items = Item::with(['supplier:id,nama,company_name', 'category:id,name', 'location:id,name,kode,parent_id', 'location.parent:id,name'])
            ->select('id', 'nama', 'stok_total', 'stok_reguler', 'supplier_id', 'category_id', 'location_id', 'type', 'harga', 'keterangan')
            ->where('stok_total', '>', 0)
            ->where('type', 'stok')
            ->get()
            ->map(function($item) {
                if ($item->location) {
                    $item->location_label = $item->location->parent
                        ? $item->location->parent->name . ' › ' . $item->location->name
                        : $item->location->name;
                    $item->location_kode  = $item->location->kode ?? '';
                } else {
                    $item->location_label = '';
                    $item->location_kode  = '';
                }
                return $item;
            });
        $users = User::select('id', 'name', 'email')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.outgoing.create', compact('items', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required|in:to_production',
            'user_select' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'user')),
            ],
            'keterangan' => 'nullable|string|max:500',
        ]);
        $selectedUser = User::findOrFail($validated['user_select']);
        $stockErrors = [];
        foreach ($validated['items'] as $index => $itemData) {
            $item = Item::findOrFail($itemData['item_id']);
            if ($item->stok_total < $itemData['quantity']) {
                $stockErrors["items.{$index}.quantity"] = "Stok {$item->nama} tidak mencukupi. Stok tersedia: {$item->stok_total}";
            }
        }
        if (!empty($stockErrors)) {
            return redirect()->back()->withErrors($stockErrors)->withInput();
        }

        DB::transaction(function () use ($validated, $selectedUser) {
            foreach ($validated['items'] as $itemData) {
                $item = Item::lockForUpdate()->findOrFail($itemData['item_id']);

                Inventory::create([
                    'item_id' => $itemData['item_id'],
                    'tipe' => 'keluar',
                    'jumlah' => $itemData['quantity'],
                    'status' => $validated['status'],
                    'user_id' => $selectedUser->id,
                    'keterangan' => $validated['keterangan'] ?? 'Barang keluar untuk produksi',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('items')
                    ->where('id', $item->id)
                    ->update([
                        'stok_reguler' => DB::raw('stok_reguler - ' . $itemData['quantity']),
                        'stok_total' => DB::raw('stok_total - ' . $itemData['quantity']),
                        'updated_at' => now(),
                    ]);

                // Trigger low-stock notification if needed
                $item->refresh()->checkAndNotifyLowStock();
            }
        });
        $totalItems = count($validated['items']);

        AuditLogger::log(
            'outgoing.created',
            'Barang Keluar',
            "Pencatatan {$totalItems} item barang keluar untuk {$selectedUser->name}"
        );

        return panel_redirect('outgoing.index')->with('success', "Berhasil mencatat {$totalItems} item barang keluar");
    }

    public function show($id)
    {
        $outgoingItem = Inventory::with(['item', 'item.supplier', 'item.category'])
            ->where('tipe', 'keluar')
            ->findOrFail($id);
        return view('admin.contents.outgoing.show', compact('outgoingItem'));
    }

    public function edit($id)
    {
        $outgoingItem = Inventory::where('tipe', 'keluar')->findOrFail($id);
        $items = Item::with(['supplier:id,nama,company_name', 'category:id,name'])
            ->select('id', 'nama', 'stok_total', 'supplier_id', 'category_id', 'type', 'harga', 'keterangan')
            ->where('stok_total', '>', 0)
            ->where('type', 'stok')
            ->get();
        $users = User::select('id', 'name', 'email')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.outgoing.edit', compact('outgoingItem', 'items', 'users'));
    }

    public function update(Request $request, $id)
    {
        $outgoingItem = Inventory::where('tipe', 'keluar')->findOrFail($id);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'user')),
            ],
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $oldItem = Item::find($outgoingItem->item_id);
        $newItem = Item::find($validated['item_id']);

        DB::transaction(function () use ($validated, $outgoingItem, $oldItem, $newItem) {
            DB::table('items')
                ->where('id', $oldItem->id)
                ->update([
                    'stok_reguler' => DB::raw('stok_reguler + ' . $outgoingItem->jumlah),
                    'stok_total' => DB::raw('stok_total + ' . $outgoingItem->jumlah),
                    'updated_at' => now(),
                ]);

            $newItem = $newItem->fresh();
            if ($newItem->stok_total < $validated['jumlah']) {
                throw new \Exception('Stok tidak mencukupi untuk item yang dipilih');
            }

            $outgoingItem->update($validated);

            DB::table('items')
                ->where('id', $newItem->id)
                ->update([
                    'stok_reguler' => DB::raw('stok_reguler - ' . $validated['jumlah']),
                    'stok_total' => DB::raw('stok_total - ' . $validated['jumlah']),
                    'updated_at' => now(),
                ]);

            // Trigger low-stock notification for the new item if needed
            $newItem->refresh()->checkAndNotifyLowStock();
        });

        return panel_redirect('outgoing.index')->with('success', 'Data barang keluar berhasil diperbarui');
    }

    public function destroy($id)
    {
        $outgoingItem = Inventory::where('tipe', 'keluar')->findOrFail($id);
        $itemName = optional($outgoingItem->item)->nama ?? '(Tidak diketahui)';
        $jumlah   = $outgoingItem->jumlah;

        DB::transaction(function () use ($outgoingItem) {
            DB::table('items')
                ->where('id', $outgoingItem->item_id)
                ->update([
                    'stok_reguler' => DB::raw('stok_reguler + ' . $outgoingItem->jumlah),
                    'stok_total' => DB::raw('stok_total + ' . $outgoingItem->jumlah),
                    'updated_at' => now()
                ]);
            $outgoingItem->delete();
        });

        AuditLogger::log(
            'outgoing.deleted',
            'Barang Keluar',
            "Catatan barang keluar \"{$itemName}\" (+{$jumlah} dikembalikan ke stok) dihapus"
        );

        return panel_redirect('outgoing.index')->with('success', 'Data barang keluar berhasil dihapus');
    }

    public function returnItem(Request $request, $id)
    {
        $outgoingItem = Inventory::where('tipe', 'keluar')->findOrFail($id);

        if ($outgoingItem->status === 'returned') {
            return back()->withErrors(['error' => 'Barang ini sudah dikembalikan sebelumnya.']);
        }

        DB::transaction(function () use ($outgoingItem) {
            // Restore stock
            DB::table('items')
                ->where('id', $outgoingItem->item_id)
                ->update([
                    'stok_reguler' => DB::raw('stok_reguler + ' . $outgoingItem->jumlah),
                    'stok_total' => DB::raw('stok_total + ' . $outgoingItem->jumlah),
                    'updated_at' => now(),
                ]);

            // Mark inventory record as returned
            $outgoingItem->update(['status' => 'returned']);
        });

        return back()->with('success', 'Barang berhasil dikembalikan dan stok telah dipulihkan.');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:inventories,id',
        ]);
        DB::transaction(function () use ($validated) {
            $outgoingItems = Inventory::whereIn('id', $validated['selected_items'])
                ->where('tipe', 'keluar')
                ->get();
            foreach ($outgoingItems as $outgoingItem) {
                DB::table('items')
                    ->where('id', $outgoingItem->item_id)
                    ->update([
                        'stok_reguler' => DB::raw('stok_reguler + ' . $outgoingItem->jumlah),
                        'stok_total' => DB::raw('stok_total + ' . $outgoingItem->jumlah),
                        'updated_at' => now()
                    ]);
                $outgoingItem->delete();
            }
        });
        return panel_redirect('outgoing.index')->with('success', 'Data barang keluar terpilih berhasil dihapus');
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query');
        if (empty($query)) {
            return response()->json([]);
        }
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('id', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();
        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'display' => $user->name . ' (ID: ' . $user->id . ') - ' . $user->email,
            ];
        }));
    }
}