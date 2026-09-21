<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ProcurementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller
{
    public function index(Request $request)
    {
        $allItems = Item::with(['category', 'location'])->get();

        // 1. Hitung Valuasi Total & Pareto ABC
        $totalValuation = $allItems->sum(fn($i) => $i->valuation);
        
        // Urutkan item dari valuasi tertinggi untuk analisis Pareto ABC
        $sortedForABC = $allItems->sortByDesc(fn($i) => $i->valuation)->values();
        $cumulative = 0;
        $abcMap = [];

        foreach ($sortedForABC as $item) {
            $val = $item->valuation;
            $cumulative += $val;
            $ratio = $totalValuation > 0 ? ($cumulative / $totalValuation) * 100 : 100;

            if ($ratio <= 70) {
                $abcMap[$item->id] = 'A';
            } elseif ($ratio <= 90) {
                $abcMap[$item->id] = 'B';
            } else {
                $abcMap[$item->id] = 'C';
            }
        }

        // 2. Filter & Query
        $query = Item::with(['category', 'location']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kode', 'like', "%{$s}%")
                  ->orWhere('bin_rack', 'like', "%{$s}%");
            });
        }

        $itemsList = $query->orderBy('nama')->get();

        // Filter ROP jika dipilih
        if ($request->get('filter') === 'below_rop') {
            $itemsList = $itemsList->filter(fn($i) => $i->isBelowROP())->values();
        } elseif ($request->filled('abc')) {
            $targetABC = strtoupper($request->abc);
            $itemsList = $itemsList->filter(fn($i) => ($abcMap[$i->id] ?? 'C') === $targetABC)->values();
        }

        // 3. Statistik KPI Logistik
        $itemsBelowROPCount = $allItems->filter(fn($i) => $i->isBelowROP())->count();
        $totalBinAssigned   = $allItems->whereNotNull('bin_rack')->where('bin_rack', '!=', '')->count();
        $countClassA        = count(array_filter($abcMap, fn($c) => $c === 'A'));
        $countClassB        = count(array_filter($abcMap, fn($c) => $c === 'B'));
        $countClassC        = count(array_filter($abcMap, fn($c) => $c === 'C'));

        // Manual simple paginate from Collection
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $itemsList->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $itemsList->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('admin.contents.logistics.index', [
            'items'              => $paginatedItems,
            'abcMap'             => $abcMap,
            'totalValuation'     => $totalValuation,
            'itemsBelowROPCount' => $itemsBelowROPCount,
            'totalBinAssigned'   => $totalBinAssigned,
            'countClassA'        => $countClassA,
            'countClassB'        => $countClassB,
            'countClassC'        => $countClassC,
        ]);
    }

    public function convertToProcurement(Item $item)
    {
        $eoqQty = $item->calculateEOQ();
        $rop    = $item->calculateROP();

        ProcurementRequest::create([
            'user_id'     => Auth::id(),
            'nama_barang' => $item->nama,
            'jumlah'      => $eoqQty,
            'alasan'      => "Rekomendasi Algoritma EOQ & ROP (Stok sekarang: {$item->stok_total}, titik aman ROP: {$rop}). Kuantitas pemesanan ekonomis dihitung otomatis.",
            'status'      => 'pending',
            'item_id'     => $item->id,
        ]);

        return back()->with('success', "Draft pengadaan untuk '{$item->nama}' sebanyak {$eoqQty} unit berhasil dibuat berdasarkan kalkulasi EOQ!");
    }

    public function updateParameters(Request $request, Item $item)
    {
        $validated = $request->validate([
            'bin_rack'         => 'nullable|string|max:50',
            'lead_time_days'   => 'required|integer|min:1',
            'daily_usage_rate' => 'required|numeric|min:0.01',
            'safety_stock'     => 'required|integer|min:0',
            'holding_cost'     => 'required|integer|min:0',
            'order_cost'       => 'required|integer|min:0',
        ]);

        $item->update($validated);

        return back()->with('success', "Parameter logistik & alamat rak untuk '{$item->nama}' berhasil disimpan!");
    }
}
