<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.contents.reports.index');
    }

    public function inventoryReport(Request $request)
    {
        $query = Item::with(['supplier', 'category'])
            ->select('items.*');
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $sortBy = $request->get('sort_by', 'nama');
        $sortDirection = $request->get('sort_direction', 'asc');

        switch ($sortBy) {
            case 'nama':
                $query->orderBy('nama', $sortDirection);
                break;
            case 'supplier':
                $query->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                    ->orderBy('suppliers.nama', $sortDirection)
                    ->select('items.*');
                break;
            case 'category':
                $query->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->orderBy('categories.name', $sortDirection)
                    ->select('items.*');
                break;
            case 'stok':
                $query->orderBy('stok_total', $sortDirection);
                break;
            default:
                $query->orderBy('nama', $sortDirection);
        }

        $items = $query->get();
        if (in_array($sortBy, ['supplier', 'category'])) {
            $items->load(['supplier', 'category']);
        }
        $suppliers = Supplier::all();
        $categories = Category::all();
        if ($request->has('export_pdf')) {
            return $this->exportInventoryPDF($items, $request);
        }
        if ($request->has('export_excel')) {
            return $this->exportInventoryExcel($items, $request);
        }
        return view('admin.contents.reports.inventory', compact('items', 'suppliers', 'categories', 'sortBy', 'sortDirection'));
    }

    public function outgoingReport(Request $request)
    {
        $query = Inventory::with(['item', 'item.supplier', 'item.category', 'user'])
            ->where('tipe', 'keluar');
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $outgoingItems = $query->latest()->get();

        if ($request->has('export_pdf')) {
            return $this->exportOutgoingPDF($outgoingItems, $request);
        }
        if ($request->has('export_excel')) {
            return $this->exportOutgoingExcel($outgoingItems, $request);
        }
        return view('admin.contents.reports.outgoing', compact('outgoingItems'));
    }

    public function incomingReport(Request $request)
    {
        $query = Inventory::with(['item', 'item.supplier', 'item.category'])
            ->where('tipe', 'masuk');
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $incomingItems = $query->latest()->get();

        if ($request->has('export_pdf')) {
            return $this->exportIncomingPDF($incomingItems, $request);
        }
        if ($request->has('export_excel')) {
            return $this->exportIncomingExcel($incomingItems, $request);
        }
        return view('admin.contents.reports.incoming', compact('incomingItems'));
    }

    public function suppliersReport(Request $request)
    {
        $suppliers = Supplier::withCount('items')->get();

        if ($request->has('export_pdf')) {
            return $this->exportSuppliersPDF($suppliers);
        }

        if ($request->has('export_excel')) {
            return $this->exportSuppliersExcel($suppliers);
        }
        return view('admin.contents.reports.suppliers', compact('suppliers'));
    }

    private function exportInventoryPDF($items, $request)
    {
        $items->each(function ($item) {
            $item->kode_barang = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
        });

        $data = [
            'title' => 'Laporan Persediaan Barang',
            'date' => Carbon::now()->format('d F Y'),
            'print_time' => Carbon::now()->format('d F Y H:i:s'),
            'printed_by' => auth()->user()->name ?? 'SYSTEM',
            'items' => $items,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'total_items' => $items->count(),
            'total_stock' => $items->sum(function ($item) {
                return $item->stok_total ?? 0;
            }),
            'total_value' => $items->sum(function ($item) {
                return ($item->stok_total ?? 0) * ($item->harga ?? 0);
            }),
            'low_stock_items' => $items->filter(function($item) { 
                return ($item->stok_total ?? 0) < 10 && ($item->stok_total ?? 0) > 0; 
            })->count(),
            'out_of_stock_items' => $items->filter(function($item) { 
                return ($item->stok_total ?? 0) == 0; 
            })->count(),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.inventory', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'isPhpEnabled' => true,
        ]);

        return $pdf->download('laporan-persediaan-'.date('Y-m-d').'.pdf');
    }

    private function exportOutgoingPDF($items, $request)
    {
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title' => 'Laporan Barang Keluar',
            'date' => Carbon::now()->format('d F Y'),
            'print_time' => Carbon::now()->format('d F Y H:i:s'),
            'printed_by' => auth()->user()->name ?? 'SYSTEM',
            'items' => $items,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'status' => $request->status,
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('jumlah'),
            'unique_items' => $items->pluck('item_id')->unique()->count(),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.outgoing', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'isPhpEnabled' => true,
        ]);

        return $pdf->download('laporan-barang-keluar-'.date('Y-m-d').'.pdf');
    }

    private function exportIncomingPDF($items, $request)
    {
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title' => 'Laporan Barang Masuk',
            'date' => Carbon::now()->format('d F Y'),
            'print_time' => Carbon::now()->format('d F Y H:i:s'),
            'printed_by' => auth()->user()->name ?? 'SYSTEM',
            'items' => $items,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'total_items' => $items->count(),
            'total_quantity' => $items->sum('jumlah'),
            'unique_items' => $items->pluck('item_id')->unique()->count(),
            'total_value' => $items->sum(function ($item) {
                return $item->jumlah * ($item->item->harga ?? 0);
            }),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.incoming', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'isPhpEnabled' => true,
        ]);

        return $pdf->download('laporan-barang-masuk-'.date('Y-m-d').'.pdf');
    }

    private function exportSuppliersPDF($suppliers)
    {
        $data = [
            'title' => 'Laporan Supplier',
            'date' => Carbon::now()->format('d F Y'),
            'print_time' => Carbon::now()->format('d F Y H:i:s'),
            'printed_by' => auth()->user()->name ?? 'SYSTEM',
            'suppliers' => $suppliers,
            'total_suppliers' => $suppliers->count(),
            'active_suppliers' => $suppliers->where('status', 'active')->count(),
            'inactive_suppliers' => $suppliers->where('status', 'inactive')->count(),
            'total_items' => $suppliers->sum('items_count'),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.suppliers', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'isPhpEnabled' => true,
        ]);

        return $pdf->download('laporan-supplier-'.date('Y-m-d').'.pdf');
    }

    private function exportInventoryExcel($items, $request)
    {
        $filename = 'laporan-persediaan-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function() use ($items, $request) {
            ob_start();
            
            echo "\xEF\xBB\xBF"; 
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo '@page { margin: 0.5in; }';
            echo 'table { border-collapse: collapse; width: 100%; font-family: "Segoe UI", Arial, sans-serif; }';
            echo '.header-company { background-color: #1F4788; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 14px; }';
            echo '.header-info { background-color: #4472C4; color: white; text-align: center; padding: 6px; font-size: 10px; }';
            echo '.header-title { background-color: #2E5090; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 16px; }';
            echo '.summary { background-color: #E7E6E6; color: #1F4788; font-weight: bold; padding: 8px; font-size: 11px; border: 1px solid #C0C0C0; }';
            echo '.table-header { background-color: #2E5090; color: white; font-weight: bold; text-align: center; padding: 10px 6px; border: 1px solid #1F4788; font-size: 10px; vertical-align: middle; }';
            echo '.data-even { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #F2F2F2; font-size: 10px; }';
            echo '.data-left-even { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-left-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #F2F2F2; font-size: 10px; }';
            echo '.number-even { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #FFFFFF; font-size: 10px; }';
            echo '.number-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #F2F2F2; font-size: 10px; }';
            echo '.status-available { background-color: #70AD47; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-low { background-color: #FFC000; color: #000000; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-out { background-color: #C00000; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.footer { background-color: #F8F9FA; padding: 6px; font-size: 9px; border: 1px solid #D0D0D0; color: #5B5B5B; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com | Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="header-title">LAPORAN PERSEDIAAN BARANG</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . ' | Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            $totalItems = $items->count();
            $totalStock = $items->sum(function($item) { return $item->stok_total ?? 0; });
            $totalValue = $items->sum(function($item) { return ($item->stok_total ?? 0) * ($item->harga ?? 0); });
            $lowStockItems = $items->filter(function($item) { return ($item->stok_total ?? 0) < 10 && ($item->stok_total ?? 0) > 0; })->count();
            $outOfStockItems = $items->filter(function($item) { return ($item->stok_total ?? 0) == 0; })->count();
            
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN: Total Item: ' . number_format($totalItems) . ' | Total Stok: ' . number_format($totalStock) . ' | Total Nilai: Rp ' . number_format($totalValue, 0, ',', '.') . ' | Item Stok Rendah: ' . number_format($lowStockItems) . ' | Item Habis: ' . number_format($outOfStockItems) . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr>';
            echo '<td class="table-header" style="width: 40px;">No</td>';
            echo '<td class="table-header" style="width: 100px;">Kode Barang</td>';
            echo '<td class="table-header" style="width: 200px;">Nama Barang</td>';
            echo '<td class="table-header" style="width: 150px;">Supplier</td>';
            echo '<td class="table-header" style="width: 120px;">Kategori</td>';
            echo '<td class="table-header" style="width: 90px;">Stok Total</td>';
            echo '<td class="table-header" style="width: 120px;">Harga Satuan</td>';
            echo '<td class="table-header" style="width: 130px;">Total Nilai</td>';
            echo '<td class="table-header" style="width: 100px;">Status</td>';
            echo '<td class="table-header" style="width: 90px;">Tipe</td>';
            echo '</tr>';

            foreach ($items as $index => $item) {
                $kodeBarang = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                $stokTotal = $item->stok_total ?? 0;
                $harga = $item->harga ?? 0;
                $totalNilai = $stokTotal * $harga;
                
                $isEven = ($index % 2 == 0);
                $dataClass = $isEven ? 'data-even' : 'data-odd';
                $dataLeftClass = $isEven ? 'data-left-even' : 'data-left-odd';
                $numberClass = $isEven ? 'number-even' : 'number-odd';

                if ($stokTotal == 0) {
                    $status = 'Habis';
                    $statusClass = 'status-out';
                } elseif ($stokTotal < 10) {
                    $status = 'Stok Rendah';
                    $statusClass = 'status-low';
                } else {
                    $status = 'Tersedia';
                    $statusClass = 'status-available';
                }

                $type = 'Stok';
                if ($item->type) {
                    if (method_exists($item->type, 'label')) {
                        $type = $item->type->label();
                    } elseif (is_object($item->type) && property_exists($item->type, 'value')) {
                        $type = ucfirst($item->type->value);
                    } else {
                        $type = ucfirst($item->type);
                    }
                }

                echo '<tr>';
                echo '<td class="' . $dataClass . '">' . ($index + 1) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($kodeBarang) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->category->name ?? 'N/A') . '</td>';
                echo '<td class="' . $numberClass . '">' . number_format($stokTotal) . '</td>';
                echo '<td class="' . $numberClass . '">Rp ' . number_format($harga, 0, ',', '.') . '</td>';
                echo '<td class="' . $numberClass . '">Rp ' . number_format($totalNilai, 0, ',', '.') . '</td>';
                echo '<td class="' . $statusClass . '">' . $status . '</td>';
                echo '<td class="' . $dataClass . '">' . $type . '</td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="10" style="height: 12px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">KETERANGAN: Status Tersedia (Stok >= 10 unit) | Status Stok Rendah (Stok 1-9 unit) | Status Habis (Stok 0 unit)</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . ' | Total record: ' . number_format($totalItems) . ' item</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer"><strong>PT. ARTILIA - Inventory Management System</strong> | Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
            
            ob_end_flush();
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportOutgoingExcel($items, $request)
    {
        $filename = 'laporan-barang-keluar-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function() use ($items, $request) {
            ob_start();
            
            echo "\xEF\xBB\xBF"; 
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo '@page { margin: 0.5in; }';
            echo 'table { border-collapse: collapse; width: 100%; font-family: "Segoe UI", Arial, sans-serif; }';
            echo '.header-company { background-color: #1F4788; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 14px; }';
            echo '.header-info { background-color: #4472C4; color: white; text-align: center; padding: 6px; font-size: 10px; }';
            echo '.header-title { background-color: #C00000; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 16px; }';
            echo '.summary { background-color: #E7E6E6; color: #1F4788; font-weight: bold; padding: 8px; font-size: 11px; border: 1px solid #C0C0C0; }';
            echo '.table-header { background-color: #C00000; color: white; font-weight: bold; text-align: center; padding: 10px 6px; border: 1px solid #8B0000; font-size: 10px; vertical-align: middle; }';
            echo '.data-even { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #F2F2F2; font-size: 10px; }';
            echo '.data-left-even { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-left-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #F2F2F2; font-size: 10px; }';
            echo '.number-even { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #FFFFFF; font-size: 10px; }';
            echo '.number-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #F2F2F2; font-size: 10px; }';
            echo '.status-sold { background-color: #70AD47; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-damaged { background-color: #C00000; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-expired { background-color: #FFC000; color: #000000; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-lost { background-color: #FF6B6B; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-borrowed { background-color: #4472C4; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-production { background-color: #44546A; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.footer { background-color: #F8F9FA; padding: 6px; font-size: 9px; border: 1px solid #D0D0D0; color: #5B5B5B; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
        
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com | Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="header-title">LAPORAN BARANG KELUAR</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . ' | Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            $totalTransactions = $items->count();
            $totalQuantity = $items->sum('jumlah');
            $uniqueItems = $items->pluck('item_id')->unique()->count();
            
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN: Total Transaksi: ' . number_format($totalTransactions) . ' | Total Kuantitas: ' . number_format($totalQuantity) . ' | Jenis Item: ' . number_format($uniqueItems) . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr>';
            echo '<td class="table-header" style="width: 40px;">No</td>';
            echo '<td class="table-header" style="width: 95px;">Tanggal</td>';
            echo '<td class="table-header" style="width: 90px;">Kode Barang</td>';
            echo '<td class="table-header" style="width: 180px;">Nama Barang</td>';
            echo '<td class="table-header" style="width: 120px;">Diambil Oleh</td>';
            echo '<td class="table-header" style="width: 130px;">Supplier</td>';
            echo '<td class="table-header" style="width: 100px;">Kategori</td>';
            echo '<td class="table-header" style="width: 70px;">Jumlah</td>';
            echo '<td class="table-header" style="width: 100px;">Status</td>';
            echo '<td class="table-header" style="width: 170px;">Keterangan</td>';
            echo '</tr>';

            foreach ($items as $index => $item) {
                $kodeBarang = 'N/A';
                if ($item->item) {
                    $kodeBarang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
                }

                $tanggal = $item->created_at ? $item->created_at->format('d M Y') : date('d M Y');
                
                $isEven = ($index % 2 == 0);
                $dataClass = $isEven ? 'data-even' : 'data-odd';
                $dataLeftClass = $isEven ? 'data-left-even' : 'data-left-odd';
                $numberClass = $isEven ? 'number-even' : 'number-odd';
                
                $statusMap = [
                    'sold' => ['text' => 'Terjual', 'class' => 'status-sold'],
                    'damaged' => ['text' => 'Rusak', 'class' => 'status-damaged'],
                    'expired' => ['text' => 'Kadaluarsa', 'class' => 'status-expired'],
                    'lost' => ['text' => 'Hilang', 'class' => 'status-lost'],
                    'borrowed' => ['text' => 'Dipinjam', 'class' => 'status-borrowed'],
                    'to_production' => ['text' => 'Ke Produksi', 'class' => 'status-production'],
                ];
                
                $statusInfo = $statusMap[$item->status] ?? ['text' => ucfirst($item->status ?? 'N/A'), 'class' => $dataClass];
                $jumlah = $item->jumlah ?? 0;

                echo '<tr>';
                echo '<td class="' . $dataClass . '">' . ($index + 1) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($tanggal) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($kodeBarang) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->user->name ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->category->name ?? 'N/A') . '</td>';
                echo '<td class="' . $numberClass . '">' . number_format($jumlah) . '</td>';
                echo '<td class="' . $statusInfo['class'] . '">' . $statusInfo['text'] . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->keterangan ?? '-') . '</td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="10" style="height: 12px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">KETERANGAN STATUS: Terjual (barang terjual ke customer) | Rusak (mengalami kerusakan) | Kadaluarsa (melewati tanggal expired) | Hilang (tidak ditemukan) | Dipinjam (sedang dipinjam) | Ke Produksi (digunakan untuk produksi)</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . ' | Total record: ' . number_format($totalTransactions) . ' transaksi</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer"><strong>PT. ARTILIA - Inventory Management System</strong> | Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
            
            ob_end_flush();
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportIncomingExcel($items, $request)
    {
        $filename = 'laporan-barang-masuk-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function() use ($items, $request) {
            ob_start();
            
            echo "\xEF\xBB\xBF"; 
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo '@page { margin: 0.5in; }';
            echo 'table { border-collapse: collapse; width: 100%; font-family: "Segoe UI", Arial, sans-serif; }';
            echo '.header-company { background-color: #1F4788; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 14px; }';
            echo '.header-info { background-color: #4472C4; color: white; text-align: center; padding: 6px; font-size: 10px; }';
            echo '.header-title { background-color: #70AD47; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 16px; }';
            echo '.summary { background-color: #E7E6E6; color: #1F4788; font-weight: bold; padding: 8px; font-size: 11px; border: 1px solid #C0C0C0; }';
            echo '.table-header { background-color: #70AD47; color: white; font-weight: bold; text-align: center; padding: 10px 6px; border: 1px solid #548235; font-size: 10px; vertical-align: middle; }';
            echo '.data-even { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #F2F2F2; font-size: 10px; }';
            echo '.data-left-even { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-left-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #F2F2F2; font-size: 10px; }';
            echo '.number-even { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #FFFFFF; font-size: 10px; }';
            echo '.number-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: right; background-color: #F2F2F2; font-size: 10px; }';
            echo '.footer { background-color: #F8F9FA; padding: 6px; font-size: 9px; border: 1px solid #D0D0D0; color: #5B5B5B; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            echo '<tr><td colspan="10" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com | Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            echo '<tr><td colspan="10" class="header-title">LAPORAN BARANG MASUK</td></tr>';
            echo '<tr><td colspan="10" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . ' | Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 8px;"></td></tr>';
            
            $totalTransactions = $items->count();
            $totalQuantity = $items->sum('jumlah');
            $uniqueItems = $items->pluck('item_id')->unique()->count();
            $totalValue = $items->sum(function($item) { 
                return ($item->jumlah ?? 0) * ($item->item->harga ?? 0); 
            });
            
            echo '<tr><td colspan="10" class="summary">RINGKASAN LAPORAN: Total Transaksi: ' . number_format($totalTransactions) . ' | Total Kuantitas: ' . number_format($totalQuantity) . ' | Jenis Item: ' . number_format($uniqueItems) . ' | Total Nilai: Rp ' . number_format($totalValue, 0, ',', '.') . '</td></tr>';
            echo '<tr><td colspan="10" style="height: 10px;"></td></tr>';
            
            echo '<tr>';
            echo '<td class="table-header" style="width: 40px;">No</td>';
            echo '<td class="table-header" style="width: 95px;">Tanggal</td>';
            echo '<td class="table-header" style="width: 90px;">Kode Barang</td>';
            echo '<td class="table-header" style="width: 180px;">Nama Barang</td>';
            echo '<td class="table-header" style="width: 130px;">Supplier</td>';
            echo '<td class="table-header" style="width: 100px;">Kategori</td>';
            echo '<td class="table-header" style="width: 70px;">Jumlah</td>';
            echo '<td class="table-header" style="width: 100px;">Harga Satuan</td>';
            echo '<td class="table-header" style="width: 110px;">Total Nilai</td>';
            echo '<td class="table-header" style="width: 120px;">Diinput Oleh</td>';
            echo '</tr>';

            foreach ($items as $index => $item) {
                $kodeBarang = 'N/A';
                $harga = 0;
                if ($item->item) {
                    $kodeBarang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
                    $harga = $item->item->harga ?? 0;
                }

                $tanggal = $item->created_at ? $item->created_at->format('d M Y') : date('d M Y');
                $jumlah = $item->jumlah ?? 0;
                $totalNilai = $jumlah * $harga;
                
                $isEven = ($index % 2 == 0);
                $dataClass = $isEven ? 'data-even' : 'data-odd';
                $dataLeftClass = $isEven ? 'data-left-even' : 'data-left-odd';
                $numberClass = $isEven ? 'number-even' : 'number-odd';

                echo '<tr>';
                echo '<td class="' . $dataClass . '">' . ($index + 1) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($tanggal) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($kodeBarang) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->item->category->name ?? 'N/A') . '</td>';
                echo '<td class="' . $numberClass . '">' . number_format($jumlah) . '</td>';
                echo '<td class="' . $numberClass . '">Rp ' . number_format($harga, 0, ',', '.') . '</td>';
                echo '<td class="' . $numberClass . '">Rp ' . number_format($totalNilai, 0, ',', '.') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($item->user->name ?? 'System') . '</td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="10" style="height: 12px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">KETERANGAN: Laporan ini menampilkan semua barang yang masuk ke inventory | Total nilai dihitung berdasarkan jumlah × harga satuan | Data diurutkan berdasarkan tanggal terbaru</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . ' | Total record: ' . number_format($totalTransactions) . ' transaksi</td></tr>';
            echo '<tr><td colspan="10" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="10" class="footer"><strong>PT. ARTILIA - Inventory Management System</strong> | Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
            
            ob_end_flush();
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportSuppliersExcel($suppliers)
    {
        $filename = 'laporan-supplier-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function() use ($suppliers) {
            ob_start();
            
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '<style>';
            echo '@page { margin: 0.5in; }';
            echo 'table { border-collapse: collapse; width: 100%; font-family: "Segoe UI", Arial, sans-serif; }';
            echo '.header-company { background-color: #1F4788; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 14px; }';
            echo '.header-info { background-color: #4472C4; color: white; text-align: center; padding: 6px; font-size: 10px; }';
            echo '.header-title { background-color: #8E44AD; color: white; font-weight: bold; text-align: center; padding: 12px; font-size: 16px; }';
            echo '.summary { background-color: #E7E6E6; color: #1F4788; font-weight: bold; padding: 8px; font-size: 11px; border: 1px solid #C0C0C0; }';
            echo '.table-header { background-color: #8E44AD; color: white; font-weight: bold; text-align: center; padding: 10px 6px; border: 1px solid #6C3483; font-size: 10px; vertical-align: middle; }';
            echo '.data-even { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: center; background-color: #F2F2F2; font-size: 10px; }';
            echo '.data-left-even { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #FFFFFF; font-size: 10px; }';
            echo '.data-left-odd { padding: 6px; border: 1px solid #D0D0D0; text-align: left; background-color: #F2F2F2; font-size: 10px; }';
            echo '.status-active { background-color: #70AD47; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.status-inactive { background-color: #C00000; color: white; font-weight: bold; padding: 6px; border: 1px solid #D0D0D0; text-align: center; font-size: 10px; }';
            echo '.footer { background-color: #F8F9FA; padding: 6px; font-size: 9px; border: 1px solid #D0D0D0; color: #5B5B5B; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<table>';
            
            echo '<tr><td colspan="9" class="header-company">PT. ARTILIA - INVENTORY MANAGEMENT SYSTEM</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Jl. Cipendeu No. 123, Jakarta Selatan 12345</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Telp: (021) 1234567 | Email: info@artilia.com | Website: www.artilia.com | NPWP: 01.234.567.8-901.000</td></tr>';
            echo '<tr><td colspan="9" style="height: 8px;"></td></tr>';
            
            echo '<tr><td colspan="9" class="header-title">LAPORAN DATA SUPPLIER</td></tr>';
            echo '<tr><td colspan="9" class="header-info">Tanggal Cetak: ' . date('d F Y H:i:s') . ' | Dicetak Oleh: ' . (auth()->user()->name ?? 'SYSTEM') . '</td></tr>';
            echo '<tr><td colspan="9" style="height: 8px;"></td></tr>';
            
            $totalSuppliers = $suppliers->count();
            $activeSuppliers = $suppliers->where('status', 'active')->count();
            $inactiveSuppliers = $suppliers->where('status', 'inactive')->count();
            $totalItems = $suppliers->sum('items_count');
            
            echo '<tr><td colspan="9" class="summary">RINGKASAN LAPORAN: Total Supplier: ' . number_format($totalSuppliers) . ' | Supplier Aktif: ' . number_format($activeSuppliers) . ' | Tidak Aktif: ' . number_format($inactiveSuppliers) . ' | Total Item dari Semua Supplier: ' . number_format($totalItems) . '</td></tr>';
            echo '<tr><td colspan="9" style="height: 10px;"></td></tr>';
            
            echo '<tr>';
            echo '<td class="table-header" style="width: 40px;">No</td>';
            echo '<td class="table-header" style="width: 150px;">Nama Supplier</td>';
            echo '<td class="table-header" style="width: 110px;">Kontak</td>';
            echo '<td class="table-header" style="width: 140px;">Email</td>';
            echo '<td class="table-header" style="width: 180px;">Alamat</td>';
            echo '<td class="table-header" style="width: 70px;">Jumlah Item</td>';
            echo '<td class="table-header" style="width: 80px;">Status</td>';
            echo '<td class="table-header" style="width: 120px;">Contact Person</td>';
            echo '<td class="table-header" style="width: 100px;">Tgl Bergabung</td>';
            echo '</tr>';

            foreach ($suppliers as $index => $supplier) {
                $kontak = $supplier->phone ?? 'N/A';
                $email = $supplier->email ?? 'N/A';
                $alamat = $supplier->address ?? 'N/A';
                $contactPerson = $supplier->contact_person ?? 'N/A';
                
                $tanggalBergabung = 'N/A';
                if ($supplier->created_at) {
                    $tanggalBergabung = $supplier->created_at->format('d M Y');
                } else {
                    $tanggalBergabung = date('d M Y');
                }

                $jumlahItem = $supplier->items_count ?? $supplier->items->count() ?? 0;
                
                $isEven = ($index % 2 == 0);
                $dataClass = $isEven ? 'data-even' : 'data-odd';
                $dataLeftClass = $isEven ? 'data-left-even' : 'data-left-odd';
                
                $statusClass = $supplier->status === 'active' ? 'status-active' : 'status-inactive';
                $statusText = $supplier->status === 'active' ? 'Aktif' : 'Tidak Aktif';

                echo '<tr>';
                echo '<td class="' . $dataClass . '">' . ($index + 1) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($supplier->nama ?? 'N/A') . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($kontak) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($email) . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($alamat) . '</td>';
                echo '<td class="' . $dataClass . '">' . number_format($jumlahItem) . '</td>';
                echo '<td class="' . $statusClass . '">' . $statusText . '</td>';
                echo '<td class="' . $dataLeftClass . '">' . htmlspecialchars($contactPerson) . '</td>';
                echo '<td class="' . $dataClass . '">' . htmlspecialchars($tanggalBergabung) . '</td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="9" style="height: 12px;"></td></tr>';
            echo '<tr><td colspan="9" class="footer">KETERANGAN: Status Aktif (supplier masih bekerjasama) | Tidak Aktif (sudah tidak bekerjasama) | Jumlah Item (total jenis barang yang disuplai) | Contact Person (PIC dari supplier)</td></tr>';
            echo '<tr><td colspan="9" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="9" class="footer">Laporan ini digenerate otomatis oleh sistem pada: ' . date('d F Y H:i:s') . ' | Total record: ' . number_format($totalSuppliers) . ' supplier</td></tr>';
            echo '<tr><td colspan="9" style="height: 5px;"></td></tr>';
            echo '<tr><td colspan="9" class="footer"><strong>PT. ARTILIA - Inventory Management System</strong> | Email: support@artilia.com | Telp: (021) 1234567 ext. 101</td></tr>';
            
            echo '</table>';
            echo '</body>';
            echo '</html>';
            
            ob_end_flush();
        };

        return response()->stream($callback, 200, $headers);
    }
}
