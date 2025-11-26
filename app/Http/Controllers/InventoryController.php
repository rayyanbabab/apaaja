<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;

use App\Http\Requests\UpdateItemRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Supplier;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'supplier']);
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
        $allItemsQuery = Item::query();
        if (!empty($searchTerm)) {
            $allItemsQuery->where(function ($q) use ($searchTerm) {
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
            $allItemsQuery->where('type', $request->get('type'));
        }

        if ($request->has('category_id') && !empty($request->get('category_id'))) {
            $allItemsQuery->where('category_id', $request->get('category_id'));
        }

        if ($request->has('supplier_id') && !empty($request->get('supplier_id'))) {
            $allItemsQuery->where('supplier_id', $request->get('supplier_id'));
        }

        $allItems = $allItemsQuery->get();
    
        $statistics = [
            'total_items' => $allItems->count(),
            'total_stock' => $allItems->sum('stok_total'),
            'low_stock' => $allItems->where('stok_total', '<=', 5)->where('stok_total', '>', 0)->count(),
            'out_of_stock' => $allItems->where('stok_total', 0)->count(),
        ];

        return view('admin.contents.inventory.index', [
            'items' => $items,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'statistics' => $statistics,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'searchQuery' => $request->get('query', ''),
        ]);
    }

    public function additems()
    {
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();

        return view('admin.contents.inventory.AddItems', compact('categories', 'suppliers'));
    }

    public function store(StoreItemRequest $request)
    {
        $validatedData = $request->validated();
        $itemData = [
            'nama' => $validatedData['nama'],
            'category_id' => $validatedData['category_id'] ?? null,
            'supplier_id' => $validatedData['supplier_id'] ?? null,
            'keterangan' => $validatedData['keterangan'] ?? null,
            'type' => $validatedData['type'] ?? 'stok', 
            'stok_total' => 0, 
            'stok_reguler' => 0,
            'stok_peminjaman' => 0,
            'harga' => ($validatedData['type'] ?? 'stok') === 'stok' ? ($validatedData['harga'] ?? 0) : 0,
        ];
        $item = Item::create($itemData);
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
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item berhasil ditambahkan. Gunakan menu Barang Masuk untuk menambahkan stok.');
    }

    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $validated = $request->validated();
        $updateData = [
            'nama' => $validated['nama'],
            'keterangan' => $validated['keterangan'] ?? null,
            'type' => $validated['type'] ?? 'stok',
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $validated['supplier_id'] ?? null,
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
        $referer = $request->header('referer');
        if ($referer && str_contains($referer, '/inventory/tabs/detail')) {
            return redirect()->route('admin.inventory.tab.detail')
                ->with('success', 'Item berhasil diperbarui');
        }
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    public function show($id)
    {
        $item = Item::with(['category', 'supplier'])->findOrFail($id);
        return view('admin.contents.inventory.show', compact('item'));
    }
    
    public function edit($id)
    {
        $item = Item::with(['category', 'supplier'])->findOrFail($id);
        $categories = \App\Models\Category::active()->get();
        $suppliers = \App\Models\Supplier::active()->get();
        
        return view('admin.contents.inventory.EditItems', [
            'item' => $item,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);
            
            \Log::info('Attempting to delete item', [
                'item_id' => $id,
                'item_name' => $item->nama,
                'stok_total' => $item->stok_total,
                'has_inventories' => $item->inventories()->exists()
            ]);
            if (($item->stok_total ?? 0) > 0) {
                \Log::warning('Delete blocked: item has stock', ['item_id' => $id, 'stock' => $item->stok_total]);
                return redirect()->route('admin.inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang masih memiliki stok. Stok saat ini: ' . $item->stok_total);
            }
            if ($item->inventories()->exists()) {
                \Log::warning('Delete blocked: item has transactions', ['item_id' => $id]);
                return redirect()->route('admin.inventory.index')
                    ->with('error', 'Tidak dapat menghapus item yang memiliki riwayat transaksi.');
            }
            if ($item->gambar && file_exists(public_path($item->gambar))) {
                unlink(public_path($item->gambar));
                \Log::info('Image deleted', ['image_path' => $item->gambar]);
            }
            $deleted = $item->delete();
            \Log::info('Item deleted', [
                'item_id' => $id,
                'delete_result' => $deleted,
                'item_exists' => Item::where('id', $id)->exists()
            ]);
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Item "' . $item->nama . '" berhasil dihapus permanen');
        } catch (\Exception $e) {
            \Log::error('Error deleting item', [
                'item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.inventory.index')
                ->with('error', 'Terjadi kesalahan saat menghapus item: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $items = Item::when($query, function ($q) use ($query) {
            $q->where('nama', 'like', "%{$query}%")
                ->orWhere('kode', 'like', "%{$query}%");
        })->latest()->get();
        if ($request->ajax()) {
            return view('admin.components.partials.itemlist', compact('items'))->render();
        }
        return redirect()->route('admin.inventory.index', ['query' => $query]);
    }
}
