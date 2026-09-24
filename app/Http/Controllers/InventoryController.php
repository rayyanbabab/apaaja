<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;

use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Supplier;
use App\Services\AuditLogger;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier', 'location'])->withCount('inventories');
        $searchTerm = $request->get('search') ?: $request->get('query');
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhere('id', 'like', "%{$searchTerm}%"); 
                if (preg_match('/ITM-(\d+)/i', $searchTerm, $matches)) {
                    $itemId = (int) $matches[1];
                    $q->orWhere('id', $itemId);
                }
                $q->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('supplier', function ($supplierQuery) use ($searchTerm) {
                        $supplierQuery->where('company_name', 'like', "%{$searchTerm}%");
                    });
            });
        }
        if ($request->has('type') && !empty($request->get('type'))) {
            $query->where('type', $request->get('type'));
        }
        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $query->where('category_id', $request->get('category_id'));
        }
        if ($request->has('supplier_id') && !empty($request->get('supplier_id'))) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }
        if ($request->has('location_id') && !empty($request->get('location_id'))) {
            $query->where('location_id', $request->get('location_id'));
        }
        // Filter ketersediaan
        if ($request->filled('availability')) {
            match ($request->get('availability')) {
                'available'  => $query->where('stok_total', '>', 0),
                'low_stock'  => $query->whereBetween('stok_total', [1, (int) \App\Models\Setting::get('low_stock_threshold', 5)]),
                'out_of_stock' => $query->where('stok_total', 0),
                default      => null,
            };
        }
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        switch ($sortBy) {
            case 'nama':
                $query->orderBy('nama', $sortDirection);
                break;
            case 'supplier':
                $query->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.nama', $sortDirection)
                    ->select('items.*');
                break;
            case 'category':
                $query->join('categories', 'items.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $sortDirection)
                    ->select('items.*');
                break;
            case 'stok':
                $query->orderBy('stok_total', $sortDirection);
                break;
            default:
                $query->orderBy('created_at', $sortDirection);
        }

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();
        $locations = Location::active()->with('parent')->orderBy('name')->get();

        // Use targeted aggregate queries instead of loading all items into memory
        $statisticsQuery = Item::query();
        if (!empty($searchTerm)) {
            $statisticsQuery->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhere('id', 'like', "%{$searchTerm}%");
                $q->orWhereHas('category', fn($sq) => $sq->where('name', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('supplier', fn($sq) => $sq->where('company_name', 'like', "%{$searchTerm}%"));
            });
        }
        if ($request->has('type') && !empty($request->get('type'))) {
            $statisticsQuery->where('type', $request->get('type'));
        }
        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $statisticsQuery->where('category_id', $request->get('category_id'));
        }
        if ($request->has('supplier_id') && !empty($request->get('supplier_id'))) {
            $statisticsQuery->where('supplier_id', $request->get('supplier_id'));
        }
        if ($request->has('location_id') && !empty($request->get('location_id'))) {
            $statisticsQuery->where('location_id', $request->get('location_id'));
        }

        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);

        $statistics = [
            'total_items'      => (clone $statisticsQuery)->count(),
            'total_stock'      => (clone $statisticsQuery)->sum('stok_total'),
            'low_stock'        => (clone $statisticsQuery)->whereBetween('stok_total', [1, $lowStockThreshold])->count(),
            'out_of_stock'     => (clone $statisticsQuery)->where('stok_total', 0)->count(),
            'under_maintenance' => \App\Models\Maintenance::where('status', 'in_repair')->count(),
        ];

        return view('admin.contents.inventory.index', [
            'items'              => $items,
            'categories'         => $categories,
            'suppliers'          => $suppliers,
            'locations'          => $locations,
            'statistics'         => $statistics,
            'sortBy'             => $sortBy,
            'sortDirection'      => $sortDirection,
            'searchQuery'        => $request->get('query', ''),
            'lowStockThreshold'  => $lowStockThreshold,
        ]);
    }

    public function additems()
    {
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();
        $locations = Location::active()->with('parent')->orderBy('name')->get();

        return view('admin.contents.inventory.AddItems', compact('categories', 'suppliers', 'locations'));
    }

    public function store(StoreItemRequest $request)
    {
        $validatedData = $request->validated();
        $itemData = [
            'nama' => $validatedData['nama'],
            'category_id' => $validatedData['category_id'] ?? null,
            'supplier_id' => $validatedData['supplier_id'] ?? null,
            'location_id' => $validatedData['location_id'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'type' => $validatedData['type'] ?? 'stok', 
            'stok_total' => 0, 
            'stok_reguler' => 0,
            'stok_peminjaman' => 0,
            'harga' => ($validatedData['type'] ?? 'stok') === 'stok' ? ($validatedData['harga'] ?? 0) : 0,
        ];
        $item = Item::create($itemData);

        // Auto-generate item code using the newly assigned ID
        $item->update(['kode' => 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT)]);

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $gambar = $request->file('gambar');
            $imagesPath = public_path('images');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
            $filename = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move($imagesPath, $filename);
            $item->update(['gambar' => 'images/' . $filename]);
        }

        AuditLogger::log(
            'item.created',
            'Inventory',
            "Barang \"" . $item->nama . "\" (" . $item->kode . ") ditambahkan",
            $item
        );

        return panel_redirect('inventory.index')
            ->with('success', 'Item berhasil ditambahkan. Gunakan menu Barang Masuk untuk menambahkan stok.');
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $oldName = $item->nama;
        $validated = $request->validated();
        $updateData = [
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'type' => $validated['type'] ?? 'stok',
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
            'location_id' => $validated['location_id'] ?? null,
        ];
        if (($validated['type'] ?? 'stok') === 'stok') {
            $updateData['harga'] = $validated['harga'] ?? 0;
        } else {
            $updateData['harga'] = 0; 
        }
        $item->update($updateData);
        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            if ($item->gambar && file_exists(public_path($item->gambar))) {
                unlink(public_path($item->gambar));
            }
            $imagesPath = public_path('images');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
            $filename = time() . '_' . uniqid() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move($imagesPath, $filename);
            $item->update(['gambar' => 'images/' . $filename]);
        }

        AuditLogger::log(
            'item.updated',
            'Inventory',
            "Barang \"" . $item->nama . "\" (" . $item->kode . ") diperbarui",
            $item,
            ['nama_lama' => $oldName, 'nama_baru' => $item->nama]
        );

        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '/inventory/tabs/detail')) {
            return panel_redirect('inventory.tab.detail')
                ->with('success', 'Item berhasil diperbarui');
        }
        return panel_redirect('inventory.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    public function show($id)
    {
        $item = Item::with(['category', 'supplier', 'location.parent'])->findOrFail($id);
        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);
        return view('admin.contents.inventory.show', compact('item', 'lowStockThreshold'));
    }
    
    public function edit($id)
    {
        $item = Item::with(['category', 'supplier'])->findOrFail($id);
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();
        $locations = Location::active()->with('parent')->orderBy('name')->get();
        
        return view('admin.contents.inventory.EditItems', [
            'item' => $item,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'locations' => $locations,
        ]);
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);

            if (($item->stok_total ?? 0) > 0) {
                return panel_redirect('inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang masih memiliki stok. Stok saat ini: ' . $item->stok_total);
            }

            if ($item->inventories()->exists()) {
                return panel_redirect('inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang memiliki riwayat transaksi.');
            }

            if ($item->gambar && file_exists(public_path($item->gambar))) {
                unlink(public_path($item->gambar));
            }

            $itemName = $item->nama;
            $itemKode = $item->kode;
            $item->delete();

            AuditLogger::log(
                'item.deleted',
                'Inventory',
                "Barang \"" . $itemName . "\" (" . $itemKode . ") dihapus permanen"
            );

            return panel_redirect('inventory.index')
                ->with('success', 'Item "' . $itemName . '" berhasil dihapus permanen');
        } catch (\Exception $e) {
            return panel_redirect('inventory.index')
                ->with('error', 'Terjadi kesalahan saat menghapus item: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if ($request->ajax()) {
            $items = Item::when($query, function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                    ->orWhere('id', 'like', "%{$query}%");
            })->latest()->take(20)->get();

            return response()->json($items);
        }
        return panel_redirect('inventory.index', ['search' => $query]);
    }

    public function printLabel(Item $item)
    {
        return view('admin.contents.inventory.print_label', compact('item'));
    }

    /**
     * AJAX: look up a single item by kode for the barcode scanner modal.
     */
    public function lookupByKode(Request $request)
    {
        $kode = strtoupper(trim($request->input('kode', '')));

        if (!$kode) {
            return response()->json(['found' => false, 'message' => 'Kode tidak boleh kosong.'], 422);
        }

        $item = Item::with(['category', 'supplier', 'location.parent'])
            ->where('kode', $kode)
            ->first();

        if (!$item) {
            return response()->json(['found' => false, 'message' => "Barang dengan kode \"{$kode}\" tidak ditemukan."], 404);
        }

        $activeBorrowings = \App\Models\Borrowing::with('user')
            ->where('item_id', $item->id)
            ->where('status', 'dipinjam')
            ->get()
            ->map(fn($b) => [
                'id'            => $b->id,
                'peminjam'      => $b->user?->name ?? '–',
                'jumlah'        => $b->jumlah,
                'tanggal_pinjam' => \Carbon\Carbon::parse($b->tanggal_pinjam)->format('d M Y'),
            ]);

        $maintenanceActive = \App\Models\Maintenance::where('item_id', $item->id)
            ->where('status', 'in_repair')
            ->count();

        return response()->json([
            'found' => true,
            'item'  => [
                'id'              => $item->id,
                'kode'            => $item->kode,
                'nama'            => $item->nama,
                'type'            => $item->type?->value ?? 'stok',
                'type_label'      => $item->type?->label() ?? 'Stok',
                'gambar'          => $item->gambar ? asset($item->gambar) : null,
                'keterangan'      => $item->keterangan,
                'kondisi'         => $item->kondisi,
                'harga'           => $item->harga,
                'harga_fmt'       => $item->harga > 0 ? 'Rp ' . number_format($item->harga, 0, ',', '.') : null,
                'stok_total'      => $item->stok_total,
                'stok_reguler'    => $item->stok_reguler,
                'stok_peminjaman' => $item->stok_peminjaman,
                'stok_in_repair'  => $maintenanceActive,
                'category'        => $item->category?->name,
                'supplier'        => $item->supplier?->company_name,
                'location'        => $item->location ? (($item->location->parent?->name ? $item->location->parent->name . ' › ' : '') . $item->location->name) : null,
                'created_at'      => $item->created_at->setTimezone('Asia/Jakarta')->format('d M Y'),
                'updated_at'      => $item->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
                'url_show'        => route('admin.inventory.show', $item->id),
                'url_edit'        => route('admin.inventory.edit', $item->id),
                'url_print'       => route('admin.inventory.print-label', $item->id),
                'url_scan'        => route('scanner.handle', $item->kode),
            ],
            'active_borrowings' => $activeBorrowings,
        ]);
    }
}
