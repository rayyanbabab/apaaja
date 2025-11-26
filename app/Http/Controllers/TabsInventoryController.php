<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class TabsInventoryController extends Controller
{
    public function Detail(Request $request)
    {
        $query = Item::with(['category', 'supplier']);

        $searchTerm = $request->get('search');
        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                    ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
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
        $items = $query->orderBy('updated_at', 'desc')->get();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('company_name')->get();

        return view('admin.containers.inventory.DetailSection', [
            'items' => $items,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ]);
    }

    public function History()
    {
        $histories = Inventory::with(['item', 'item.supplier'])->latest()->get();

        return view('admin.containers.inventory.HistorySection', [
            'histories' => $histories,
            'filter' => null,
        ]);
    }

    public function historyByStatus($status)
    {
        $allowed = ['sold', 'damaged', 'expired', 'lost'];

        if (! in_array($status, $allowed)) {
            abort(404);
        }

        $histories = Inventory::with(['item', 'item.supplier'])
            ->where('status', $status)
            ->latest()
            ->get();

        return view('admin.containers.inventory.HistoryByStatus', [
            'histories' => $histories,
            'status' => $status,
            'filter' => $status,
        ]);
    }

}
