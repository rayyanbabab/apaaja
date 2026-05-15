<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Setting;
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
        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);
        if ($request->has('export_pdf')) {
            return $this->exportInventoryPDF($items, $request);
        }
        if ($request->has('export_excel')) {
            return $this->exportInventoryExcel($items, $request);
        }
        return view('admin.contents.reports.inventory', compact('items', 'suppliers', 'categories', 'sortBy', 'sortDirection', 'lowStockThreshold'));
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

    public function borrowingReport(Request $request)
    {
        $query = BorrowingRequest::with(['user', 'item', 'item.category', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $borrowings = $query->latest()->get();

        // Stats
        $stats = [
            'total'     => $borrowings->count(),
            'pending'   => $borrowings->where('status', 'pending')->count(),
            'approved'  => $borrowings->where('status', 'approved')->count(),
            'completed' => $borrowings->where('status', 'completed')->count(),
            'rejected'  => $borrowings->where('status', 'rejected')->count(),
            'cancelled' => $borrowings->where('status', 'cancelled')->count(),
            'overdue'   => $borrowings->filter(fn($b) =>
                $b->status === 'approved' &&
                $b->tanggal_kembali_rencana &&
                $b->tanggal_kembali_rencana->isPast()
            )->count(),
        ];

        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);

        if ($request->has('export_pdf')) {
            return $this->exportBorrowingPDF($borrowings, $request, $stats);
        }
        if ($request->has('export_excel')) {
            return $this->exportBorrowingExcel($borrowings, $stats);
        }

        return view('admin.contents.reports.borrowing',
            compact('borrowings', 'stats', 'users'));
    }

    private function exportBorrowingPDF($borrowings, $request, $stats)
    {
        $company = $this->getCompanyInfo();
        $data = [
            'title'      => 'Laporan Peminjaman Barang',
            'date'       => Carbon::now()->format('d F Y'),
            'print_time' => Carbon::now()->format('d F Y H:i:s'),
            'printed_by' => auth()->user()->name ?? 'SYSTEM',
            'logoBase64' => $company['logo'],
            'company'    => $company,
            'borrowings' => $borrowings,
            'stats'      => $stats,
            'date_from'  => $request->date_from,
            'date_to'    => $request->date_to,
            'status'     => $request->status,
        ];
        $pdf = PDF::loadView('admin.contents.reports.pdf.borrowing', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans', 'isPhpEnabled' => true]);
        return $pdf->download('laporan-peminjaman-'.date('Y-m-d').'.pdf');
    }

    /**
     * Get company info from settings.
     */
    private function getCompanyInfo(): array
    {
        return [
            'name'     => Setting::get('company_name', 'PT. ARTILIA'),
            'tagline'  => Setting::get('company_tagline', 'Inventory Management System'),
            'address'  => Setting::get('company_address', ''),
            'phone'    => Setting::get('company_phone', ''),
            'email'    => Setting::get('company_email', ''),
            'logo'     => $this->getLogoBase64(),  // PDF: full-resolution base64
            'logo_url' => $this->getLogoUrl(),      // Excel: real HTTP URL (dynamic host)
        ];
    }

    private function getLogoBase64(): string
    {
        // 1. Prioritas: logo yang diupload via company settings
        $settingLogo = Setting::get('company_logo');
        if ($settingLogo && file_exists(public_path($settingLogo))) {
            $mime = mime_content_type(public_path($settingLogo));
            $data = base64_encode(file_get_contents(public_path($settingLogo)));
            return 'data:' . $mime . ';base64,' . $data;
        }

        // 2. Fallback: cari file logo di public/
        $candidates = [
            public_path('logo.png'),
            public_path('artilia.png'),
            public_path('images/logo.png'),
        ];
        foreach ($candidates as $path) {
            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $data = base64_encode(file_get_contents($path));
                return 'data:' . $mime . ';base64,' . $data;
            }
        }
        return '';
    }

        /**
     * Generates a 64x64 thumbnail of the logo, saves to public/temp/,
     * and returns its HTTP URL. Small size = correct display in Excel header.
     */
    private function getLogoUrl(): string
    {
        $base        = request()->getSchemeAndHttpHost();
        $settingLogo = Setting::get('company_logo');
        $sourcePath  = null;

        if ($settingLogo && file_exists(public_path($settingLogo))) {
            $sourcePath = public_path($settingLogo);
        } else {
            foreach (['logo.png', 'artilia.png', 'images/logo.png'] as $rel) {
                if (file_exists(public_path($rel))) {
                    $sourcePath = public_path($rel);
                    break;
                }
            }
        }

        if (!$sourcePath) return '';

        // Resize to 64x64 with GD, save to public/temp/logo-export.png
        if (function_exists('imagecreatefromstring')) {
            $src = @imagecreatefromstring(file_get_contents($sourcePath));
            if ($src) {
                $thumb = imagecreatetruecolor(64, 64);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                imagefill($thumb, 0, 0, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
                imagecopyresampled($thumb, $src, 0, 0, 0, 0, 64, 64, imagesx($src), imagesy($src));
                imagedestroy($src);
                $dir = public_path('temp');
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                imagepng($thumb, $dir . '/logo-export.png', 6);
                imagedestroy($thumb);
                return $base . '/temp/logo-export.png';
            }
        }

        // GD not available — return full original URL
        return $base . '/' . ltrim($settingLogo ?: 'logo.png', '/');
    }

    private function exportInventoryPDF($items, $request)
    {
        $company = $this->getCompanyInfo();
        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);
        $items->each(function ($item) {
            $item->kode_barang = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
        });

        $data = [
            'title'              => 'Laporan Persediaan Barang',
            'date'               => Carbon::now()->format('d F Y'),
            'print_time'         => Carbon::now()->format('d F Y H:i:s'),
            'printed_by'         => auth()->user()->name ?? 'SYSTEM',
            'logoBase64'         => $company['logo'],
            'company'            => $company,
            'items'              => $items,
            'supplier_id'        => $request->supplier_id,
            'category_id'        => $request->category_id,
            'total_items'        => $items->count(),
            'total_stock'        => $items->sum(fn($i) => $i->stok_total ?? 0),
            'total_value'        => $items->sum(fn($i) => ($i->stok_total ?? 0) * ($i->harga ?? 0)),
            'low_stock_items'    => $items->filter(fn($i) => ($i->stok_total ?? 0) <= $lowStockThreshold && ($i->stok_total ?? 0) > 0)->count(),
            'out_of_stock_items' => $items->filter(fn($i) => ($i->stok_total ?? 0) == 0)->count(),
            'low_stock_threshold' => $lowStockThreshold,
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.inventory', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans', 'isPhpEnabled' => true]);

        return $pdf->download('laporan-persediaan-'.date('Y-m-d').'.pdf');
    }

    private function exportOutgoingPDF($items, $request)
    {
        $company = $this->getCompanyInfo();
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title'          => 'Laporan Barang Keluar',
            'date'           => Carbon::now()->format('d F Y'),
            'print_time'     => Carbon::now()->format('d F Y H:i:s'),
            'printed_by'     => auth()->user()->name ?? 'SYSTEM',
            'logoBase64'     => $company['logo'],
            'company'        => $company,
            'items'          => $items,
            'date_from'      => $request->date_from,
            'date_to'        => $request->date_to,
            'status'         => $request->status,
            'total_items'    => $items->count(),
            'total_quantity'  => $items->sum('jumlah'),
            'unique_items'   => $items->pluck('item_id')->unique()->count(),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.outgoing', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans', 'isPhpEnabled' => true]);

        return $pdf->download('laporan-barang-keluar-'.date('Y-m-d').'.pdf');
    }

    private function exportIncomingPDF($items, $request)
    {
        $company = $this->getCompanyInfo();
        $items->each(function ($item) {
            if ($item->item) {
                $item->item->kode_barang = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT);
            }
        });

        $data = [
            'title'          => 'Laporan Barang Masuk',
            'date'           => Carbon::now()->format('d F Y'),
            'print_time'     => Carbon::now()->format('d F Y H:i:s'),
            'printed_by'     => auth()->user()->name ?? 'SYSTEM',
            'logoBase64'     => $company['logo'],
            'company'        => $company,
            'items'          => $items,
            'date_from'      => $request->date_from,
            'date_to'        => $request->date_to,
            'total_items'    => $items->count(),
            'total_quantity'  => $items->sum('jumlah'),
            'unique_items'   => $items->pluck('item_id')->unique()->count(),
            'total_value'    => $items->sum(fn($i) => $i->jumlah * ($i->item->harga ?? 0)),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.incoming', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans', 'isPhpEnabled' => true]);

        return $pdf->download('laporan-barang-masuk-'.date('Y-m-d').'.pdf');
    }

    private function exportSuppliersPDF($suppliers)
    {
        $company = $this->getCompanyInfo();
        $data = [
            'title'              => 'Laporan Supplier',
            'date'               => Carbon::now()->format('d F Y'),
            'print_time'         => Carbon::now()->format('d F Y H:i:s'),
            'printed_by'         => auth()->user()->name ?? 'SYSTEM',
            'logoBase64'         => $company['logo'],
            'company'            => $company,
            'suppliers'          => $suppliers,
            'total_suppliers'    => $suppliers->count(),
            'active_suppliers'   => $suppliers->where('status', 'active')->count(),
            'inactive_suppliers' => $suppliers->where('status', 'inactive')->count(),
            'total_items'        => $suppliers->sum('items_count'),
        ];

        $pdf = PDF::loadView('admin.contents.reports.pdf.suppliers', $data);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans', 'isPhpEnabled' => true]);

        return $pdf->download('laporan-supplier-'.date('Y-m-d').'.pdf');
    }

    private function exportInventoryExcel($items, $request)
    {
        $filename = 'laporan-persediaan-' . date('Y-m-d') . '.xls';
        $company  = $this->getCompanyInfo();

        $lowStockThreshold = (int) Setting::get('low_stock_threshold', 5);
        $callback = function() use ($items, $request, $company, $lowStockThreshold) {
            ob_start();
            echo "\xEF\xBB\xBF";
            $cols       = 10;
            $totalItems = $items->count();
            $totalStock = $items->sum(fn($i) => $i->stok_total ?? 0);
            $totalValue = $items->sum(fn($i) => ($i->stok_total ?? 0) * ($i->harga ?? 0));
            $lowStock   = $items->filter(fn($i) => ($i->stok_total ?? 0) <= $lowStockThreshold && ($i->stok_total ?? 0) > 0)->count();
            $outOfStock = $items->filter(fn($i) => ($i->stok_total ?? 0) == 0)->count();

            $summary = "Total Item: <b>" . number_format($totalItems) . "</b> &nbsp;|&nbsp; "
                     . "Total Stok: <b>" . number_format($totalStock) . "</b> &nbsp;|&nbsp; "
                     . "Total Nilai: <b>Rp " . number_format($totalValue, 0, ',', '.') . "</b> &nbsp;|&nbsp; "
                     . "Stok Rendah: <b>" . number_format($lowStock) . "</b> &nbsp;|&nbsp; "
                     . "Habis: <b>" . number_format($outOfStock) . "</b>";

            echo $this->excelHeader($company, 'LAPORAN PERSEDIAAN BARANG', '#1F4E79', $cols, $summary);
            echo '<tr>'
               . '<th class="th">No</th>'
               . '<th class="th">Kode Barang</th>'
               . '<th class="th" style="text-align:left">Nama Barang</th>'
               . '<th class="th" style="text-align:left">Supplier</th>'
               . '<th class="th" style="text-align:left">Kategori</th>'
               . '<th class="th">Stok Total</th>'
               . '<th class="th">Harga Satuan</th>'
               . '<th class="th">Total Nilai</th>'
               . '<th class="th">Status</th>'
               . '<th class="th">Tipe</th>'
               . '</tr>';

            foreach ($items as $i => $item) {
                $kode     = 'ITM-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);
                $stok     = $item->stok_total ?? 0;
                $harga    = $item->harga ?? 0;
                $nilai    = $stok * $harga;
                $e        = $i % 2 === 0;
                [$st, $sc] = $stok == 0 ? ['Habis', '#C00000'] : ($stok <= $lowStockThreshold ? ['Stok Rendah', '#CC8400'] : ['Tersedia', '#375623']);
                $type = 'Stok';
                if ($item->type) {
                    if (method_exists($item->type, 'label')) $type = $item->type->label();
                    elseif (is_object($item->type) && property_exists($item->type, 'value')) $type = ucfirst($item->type->value);
                    else $type = ucfirst($item->type);
                }
                echo '<tr>'
                   . $this->tc($i+1, $e) . $this->tc(htmlspecialchars($kode), $e)
                   . $this->tl(htmlspecialchars($item->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->supplier->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->category->name ?? 'N/A'), $e)
                   . $this->tn(number_format($stok), $e)
                   . $this->tn('Rp ' . number_format($harga, 0, ',', '.'), $e)
                   . $this->tn('Rp ' . number_format($nilai, 0, ',', '.'), $e)
                   . '<td style="padding:6px;border:1px solid #ccc;text-align:center;background:' . $sc . ';color:#fff;font-size:10px;font-weight:bold">' . $st . '</td>'
                   . $this->tc(htmlspecialchars($type), $e)
                   . '</tr>';
            }
            echo $this->excelSignature($cols);
            echo $this->excelFooter($company, $cols, 'Tersedia (>' . $lowStockThreshold . ') | Stok Rendah (1-' . $lowStockThreshold . ') | Habis (0)', number_format($totalItems) . ' item');
            ob_end_flush();
        };
        return response()->stream($callback, 200, $this->excelResponseHeaders($filename));
    }


    private function exportOutgoingExcel($items, $request)
    {
        $filename = 'laporan-barang-keluar-' . date('Y-m-d') . '.xls';
        $company  = $this->getCompanyInfo();

        $callback = function() use ($items, $request, $company) {
            ob_start();
            echo "\xEF\xBB\xBF";
            $cols   = 10;
            $total  = $items->count();
            $qty    = $items->sum('jumlah');
            $unique = $items->pluck('item_id')->unique()->count();

            $summary = "Total Transaksi: <b>" . number_format($total) . "</b> &nbsp;|&nbsp; "
                     . "Total Kuantitas: <b>" . number_format($qty) . "</b> &nbsp;|&nbsp; "
                     . "Jenis Item: <b>" . number_format($unique) . "</b>";

            echo $this->excelHeader($company, 'LAPORAN BARANG KELUAR', '#7B0000', $cols, $summary);
            echo '<tr>'
               . '<th class="th">No</th>'
               . '<th class="th">Tanggal</th>'
               . '<th class="th">Kode Barang</th>'
               . '<th class="th" style="text-align:left">Nama Barang</th>'
               . '<th class="th" style="text-align:left">Diambil Oleh</th>'
               . '<th class="th" style="text-align:left">Supplier</th>'
               . '<th class="th" style="text-align:left">Kategori</th>'
               . '<th class="th">Jumlah</th>'
               . '<th class="th">Status</th>'
               . '<th class="th" style="text-align:left">Keterangan</th>'
               . '</tr>';

            $statusMap = [
                'sold'          => ['Terjual',     '#375623'],
                'damaged'       => ['Rusak',        '#C00000'],
                'expired'       => ['Kadaluarsa',   '#7B5200'],
                'lost'          => ['Hilang',        '#7B3200'],
                'borrowed'      => ['Dipinjam',      '#1F4E79'],
                'to_production' => ['Ke Produksi',   '#375A5E'],
            ];
            foreach ($items as $i => $item) {
                $kode    = $item->item ? 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT) : 'N/A';
                $tanggal = $item->created_at ? $item->created_at->format('d M Y') : date('d M Y');
                $e       = $i % 2 === 0;
                [$stLabel, $stColor] = $statusMap[$item->status] ?? [ucfirst($item->status ?? '-'), '#555'];
                echo '<tr>'
                   . $this->tc($i+1, $e) . $this->tc(htmlspecialchars($tanggal), $e)
                   . $this->tc(htmlspecialchars($kode), $e)
                   . $this->tl(htmlspecialchars($item->item->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->user->name ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->item->supplier->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->item->category->name ?? 'N/A'), $e)
                   . $this->tn(number_format($item->jumlah ?? 0), $e)
                   . '<td style="padding:6px;border:1px solid #ccc;text-align:center;background:' . $stColor . ';color:#fff;font-size:10px;font-weight:bold">' . $stLabel . '</td>'
                   . $this->tl(htmlspecialchars($item->keterangan ?? '-'), $e)
                   . '</tr>';
            }
            echo $this->excelSignature($cols);
            echo $this->excelFooter($company, $cols, 'Status: Terjual | Rusak | Kadaluarsa | Hilang | Dipinjam | Ke Produksi', number_format($total) . ' transaksi');
            ob_end_flush();
        };
        return response()->stream($callback, 200, $this->excelResponseHeaders($filename));
    }


    private function exportIncomingExcel($items, $request)
    {
        $filename   = 'laporan-barang-masuk-' . date('Y-m-d') . '.xls';
        $company    = $this->getCompanyInfo();

        $callback = function() use ($items, $request, $company) {
            ob_start();
            echo "\xEF\xBB\xBF";
            $cols       = 10;
            $total      = $items->count();
            $qty        = $items->sum('jumlah');
            $unique     = $items->pluck('item_id')->unique()->count();
            $totalValue = $items->sum(fn($i) => ($i->jumlah ?? 0) * ($i->item->harga ?? 0));

            $summary = "Total Transaksi: <b>" . number_format($total) . "</b> &nbsp;|&nbsp; "
                     . "Total Kuantitas: <b>" . number_format($qty) . "</b> &nbsp;|&nbsp; "
                     . "Jenis Item: <b>" . number_format($unique) . "</b> &nbsp;|&nbsp; "
                     . "Total Nilai: <b>Rp " . number_format($totalValue, 0, ',', '.') . "</b>";

            echo $this->excelHeader($company, 'LAPORAN BARANG MASUK', '#1A5E2F', $cols, $summary);
            echo '<tr>'
               . '<th class="th">No</th>'
               . '<th class="th">Tanggal</th>'
               . '<th class="th">Kode Barang</th>'
               . '<th class="th" style="text-align:left">Nama Barang</th>'
               . '<th class="th" style="text-align:left">Supplier</th>'
               . '<th class="th" style="text-align:left">Kategori</th>'
               . '<th class="th">Jumlah</th>'
               . '<th class="th">Harga Satuan</th>'
               . '<th class="th">Total Nilai</th>'
               . '<th class="th" style="text-align:left">Diinput Oleh</th>'
               . '</tr>';

            foreach ($items as $i => $item) {
                $kode   = 'N/A'; $harga = 0;
                if ($item->item) { $kode = 'ITM-' . str_pad($item->item->id, 4, '0', STR_PAD_LEFT); $harga = $item->item->harga ?? 0; }
                $tanggal = $item->created_at ? $item->created_at->format('d M Y') : date('d M Y');
                $jumlah  = $item->jumlah ?? 0;
                $nilai   = $jumlah * $harga;
                $e       = $i % 2 === 0;
                echo '<tr>'
                   . $this->tc($i+1, $e) . $this->tc(htmlspecialchars($tanggal), $e)
                   . $this->tc(htmlspecialchars($kode), $e)
                   . $this->tl(htmlspecialchars($item->item->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->item->supplier->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($item->item->category->name ?? 'N/A'), $e)
                   . $this->tn(number_format($jumlah), $e)
                   . $this->tn('Rp ' . number_format($harga, 0, ',', '.'), $e)
                   . $this->tn('Rp ' . number_format($nilai, 0, ',', '.'), $e)
                   . $this->tl(htmlspecialchars($item->user->name ?? 'System'), $e)
                   . '</tr>';
            }
            echo $this->excelSignature($cols);
            echo $this->excelFooter($company, $cols, 'Total Nilai = Jumlah × Harga Satuan', number_format($total) . ' transaksi');
            ob_end_flush();
        };
        return response()->stream($callback, 200, $this->excelResponseHeaders($filename));
    }


    private function exportSuppliersExcel($suppliers)
    {
        $filename = 'laporan-supplier-' . date('Y-m-d') . '.xls';
        $company  = $this->getCompanyInfo();

        $callback = function() use ($suppliers, $company) {
            ob_start();
            echo "\xEF\xBB\xBF";
            $cols     = 9;
            $total    = $suppliers->count();
            $active   = $suppliers->where('status', 'active')->count();
            $inactive = $suppliers->where('status', 'inactive')->count();
            $totalItems = $suppliers->sum('items_count');

            $summary = "Total Supplier: <b>" . number_format($total) . "</b> &nbsp;|&nbsp; "
                     . "Aktif: <b>" . number_format($active) . "</b> &nbsp;|&nbsp; "
                     . "Tidak Aktif: <b>" . number_format($inactive) . "</b> &nbsp;|&nbsp; "
                     . "Total Item Disuplai: <b>" . number_format($totalItems) . "</b>";

            echo $this->excelHeader($company, 'LAPORAN DATA SUPPLIER', '#7B3900', $cols, $summary);
            echo '<tr>'
               . '<th class="th">No</th>'
               . '<th class="th" style="text-align:left">Nama Supplier</th>'
               . '<th class="th" style="text-align:left">Kontak</th>'
               . '<th class="th" style="text-align:left">Email</th>'
               . '<th class="th" style="text-align:left">Alamat</th>'
               . '<th class="th">Jml Item</th>'
               . '<th class="th">Status</th>'
               . '<th class="th" style="text-align:left">Contact Person</th>'
               . '<th class="th">Tgl Bergabung</th>'
               . '</tr>';

            foreach ($suppliers as $i => $s) {
                $tanggal  = $s->created_at ? $s->created_at->format('d M Y') : '-';
                $items    = $s->items_count ?? ($s->items ? $s->items->count() : 0);
                $e        = $i % 2 === 0;
                $isActive = $s->status === 'active';
                [$stLabel, $stColor] = $isActive ? ['Aktif', '#375623'] : ['Tidak Aktif', '#C00000'];
                echo '<tr>'
                   . $this->tc($i+1, $e)
                   . $this->tl(htmlspecialchars($s->nama ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($s->phone ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($s->email ?? 'N/A'), $e)
                   . $this->tl(htmlspecialchars($s->address ?? 'N/A'), $e)
                   . $this->tc(number_format($items), $e)
                   . '<td style="padding:6px;border:1px solid #ccc;text-align:center;background:' . $stColor . ';color:#fff;font-size:10px;font-weight:bold">' . $stLabel . '</td>'
                   . $this->tl(htmlspecialchars($s->contact_person ?? 'N/A'), $e)
                   . $this->tc(htmlspecialchars($tanggal), $e)
                   . '</tr>';
            }
            echo $this->excelSignature($cols);
            echo $this->excelFooter($company, $cols, 'Aktif = masih bekerjasama | Tidak Aktif = sudah tidak bekerjasama', number_format($total) . ' supplier');
            ob_end_flush();
        };
        return response()->stream($callback, 200, $this->excelResponseHeaders($filename));
    }


    private function exportBorrowingExcel($borrowings, $stats)
    {
        $filename = 'laporan-peminjaman-' . date('Y-m-d') . '.xls';
        $company  = $this->getCompanyInfo();

        $callback = function() use ($borrowings, $stats, $company) {
            ob_start();
            echo "\xEF\xBB\xBF";
            $cols = 10;

            $summary = "Total: <b>" . $stats['total'] . "</b> &nbsp;|&nbsp; "
                     . "Menunggu: <b>" . $stats['pending'] . "</b> &nbsp;|&nbsp; "
                     . "Disetujui: <b>" . $stats['approved'] . "</b> &nbsp;|&nbsp; "
                     . "Selesai: <b>" . $stats['completed'] . "</b> &nbsp;|&nbsp; "
                     . "Ditolak: <b>" . $stats['rejected'] . "</b> &nbsp;|&nbsp; "
                     . "Terlambat: <b>" . $stats['overdue'] . "</b>";

            echo $this->excelHeader($company, 'LAPORAN PEMINJAMAN BARANG', '#3B1F6A', $cols, $summary);
            echo '<tr>'
               . '<th class="th">No</th>'
               . '<th class="th">Tgl Pengajuan</th>'
               . '<th class="th" style="text-align:left">Peminjam</th>'
               . '<th class="th" style="text-align:left">Barang</th>'
               . '<th class="th" style="text-align:left">Kategori</th>'
               . '<th class="th">Jumlah</th>'
               . '<th class="th">Tgl Pinjam</th>'
               . '<th class="th">Rencana Kembali</th>'
               . '<th class="th">Tgl Selesai</th>'
               . '<th class="th">Status</th>'
               . '</tr>';

            $statusMap = [
                'pending'   => ['Menunggu',   '#7B5200'],
                'approved'  => ['Disetujui',  '#1A5E2F'],
                'completed' => ['Selesai',    '#1F4E79'],
                'rejected'  => ['Ditolak',    '#C00000'],
                'cancelled' => ['Dibatalkan', '#555555'],
            ];
            foreach ($borrowings as $i => $b) {
                $isOverdue = $b->status === 'approved'
                    && $b->tanggal_kembali_rencana
                    && $b->tanggal_kembali_rencana->isPast();
                [$stLabel, $stColor] = $isOverdue
                    ? ['Terlambat', '#8B3000']
                    : ($statusMap[$b->status] ?? [ucfirst($b->status ?? '-'), '#555']);
                $e = $i % 2 === 0;
                echo '<tr>'
                   . $this->tc($i+1, $e)
                   . $this->tc(htmlspecialchars($b->created_at?->format('d/m/Y') ?? '-'), $e)
                   . $this->tl(htmlspecialchars($b->user?->name ?? '-'), $e)
                   . $this->tl(htmlspecialchars($b->item?->nama ?? '-'), $e)
                   . $this->tl(htmlspecialchars($b->item?->category?->name ?? '-'), $e)
                   . $this->tc($b->jumlah, $e)
                   . $this->tc(htmlspecialchars($b->tanggal_pinjam?->format('d/m/Y') ?? '-'), $e)
                   . $this->tc(htmlspecialchars($b->tanggal_kembali_rencana?->format('d/m/Y') ?? '-'), $e)
                   . $this->tc(htmlspecialchars($b->completed_at ? \Carbon\Carbon::parse($b->completed_at)->format('d/m/Y') : '-'), $e)
                   . '<td style="padding:6px;border:1px solid #ccc;text-align:center;background:' . $stColor . ';color:#fff;font-size:10px;font-weight:bold">' . $stLabel . '</td>'
                   . '</tr>';
            }
            echo $this->excelSignature($cols);
            echo $this->excelFooter($company, $cols, 'Status: Menunggu | Disetujui | Selesai | Ditolak | Dibatalkan | Terlambat', number_format($stats['total']) . ' record');
            ob_end_flush();
        };
        return response()->stream($callback, 200, $this->excelResponseHeaders($filename));
    }


    /* ═══════════════════════════════════════════
       SHARED EXCEL HELPER METHODS
    ═══════════════════════════════════════════ */

    private function excelResponseHeaders(string $filename): array
    {
        return [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'public',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        ];
    }

    private function excelHeader(array $company, string $reportTitle, string $accentColor, int $cols, string $summaryHtml): string
    {
        $lightBg = $this->lightenHex($accentColor, 0.92);

        // Logo — use a small base64 thumbnail (64×64 px) so Excel's HTML parser can handle it
        if (!empty($company['logo_url'])) {
            $logoHtml = '<img src="' . $company['logo_url'] . '" style="width:64px;height:64px;object-fit:contain;" />';
        } else {
            $initials = strtoupper(substr($company['name'], 0, 3));
            $logoHtml = '<div style="width:64px;height:64px;background:' . $accentColor . ';color:#fff;font-size:18px;font-weight:900;text-align:center;line-height:64px;border-radius:6px;">' . $initials . '</div>';
        }

        $contact    = htmlspecialchars(implode('  |  ', array_filter([$company['address'], $company['phone'], $company['email']])));
        $coName     = htmlspecialchars($company['name']);
        $tagline    = htmlspecialchars($company['tagline']);
        $printedBy  = htmlspecialchars(auth()->user()->name ?? 'SYSTEM');
        $docNo      = 'XLS-' . date('Ymd') . '-' . strtoupper(substr(md5($reportTitle), 0, 5));
        $dark       = $this->darkenHex($accentColor, 0.12);

        $h  = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $h .= '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
        $h .= '@page{margin:0.6in 0.5in}';
        $h .= 'body{font-family:"Segoe UI",Calibri,Arial,sans-serif;font-size:10px;color:#1a1a1a}';
        $h .= 'table{border-collapse:collapse;width:100%}';
        $h .= '.th{background:' . $accentColor . ';color:#fff;font-weight:bold;padding:7px 6px;text-align:center;border:1px solid ' . $dark . ';font-size:10px;text-transform:uppercase;letter-spacing:0.3px}';
        $h .= '.te{padding:6px;border:1px solid #D5D5D5;text-align:center;background:#FFF;font-size:10px;vertical-align:middle}';
        $h .= '.to{padding:6px;border:1px solid #D5D5D5;text-align:center;background:' . $lightBg . ';font-size:10px;vertical-align:middle}';
        $h .= '.le{padding:6px 8px;border:1px solid #D5D5D5;text-align:left;background:#FFF;font-size:10px;vertical-align:middle}';
        $h .= '.lo{padding:6px 8px;border:1px solid #D5D5D5;text-align:left;background:' . $lightBg . ';font-size:10px;vertical-align:middle}';
        $h .= '.ne{padding:6px;border:1px solid #D5D5D5;text-align:right;background:#FFF;font-size:10px;vertical-align:middle}';
        $h .= '.no{padding:6px;border:1px solid #D5D5D5;text-align:right;background:' . $lightBg . ';font-size:10px;vertical-align:middle}';
        $h .= '</style></head><body><table>';

        // KOP
        $h .= '<tr><td colspan="' . $cols . '" style="border-bottom:3px double #1a1a1a;padding-bottom:10px;">';
        $h .= '<table style="width:100%;border-collapse:collapse"><tr>';
        $h .= '<td style="width:80px;vertical-align:middle">' . $logoHtml . '</td>';
        $h .= '<td style="vertical-align:middle;padding-left:14px">';
        $h .= '<div style="font-size:16px;font-weight:900;color:#1a1a1a;text-transform:uppercase">' . $coName . '</div>';
        if ($tagline) $h .= '<div style="font-size:10px;color:#555;margin-top:2px">' . $tagline . '</div>';
        if ($contact) $h .= '<div style="font-size:9px;color:#888;margin-top:3px">' . $contact . '</div>';
        $h .= '</td>';
        $h .= '<td style="width:210px;vertical-align:middle;text-align:right">';
        $h .= '<table style="border:1px solid #e0e0e0;width:100%;font-size:9px;color:#444;border-collapse:collapse">';
        $h .= '<tr><td style="padding:3px 8px;color:#999;white-space:nowrap">No. Dokumen</td><td style="padding:3px 8px;font-weight:bold">' . $docNo . '</td></tr>';
        $h .= '<tr style="background:#f9f9f9"><td style="padding:3px 8px;color:#999">Tanggal</td><td style="padding:3px 8px">' . date('d F Y') . '</td></tr>';
        $h .= '<tr><td style="padding:3px 8px;color:#999">Dicetak oleh</td><td style="padding:3px 8px">' . $printedBy . '</td></tr>';
        $h .= '<tr style="background:#f9f9f9"><td style="padding:3px 8px;color:#999">Jam Cetak</td><td style="padding:3px 8px">' . date('H:i:s') . '</td></tr>';
        $h .= '</table></td>';
        $h .= '</tr></table></td></tr>';

        // Judul
        $h .= '<tr><td colspan="' . $cols . '" style="height:8px"></td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="background:' . $accentColor . ';color:#fff;font-size:14px;font-weight:900;text-align:center;padding:10px;letter-spacing:1px;text-transform:uppercase">' . $reportTitle . '</td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="background:' . $dark . ';color:#fff;text-align:center;padding:5px;font-size:9px">Dicetak: ' . date('d F Y H:i:s') . '</td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="height:8px"></td></tr>';

        // Ringkasan
        $h .= '<tr><td colspan="' . $cols . '" style="background:' . $lightBg . ';border:1px solid #e0e0e0;padding:8px 12px;font-size:10px;color:#333">';
        $h .= '<span style="font-weight:bold;text-transform:uppercase;font-size:9px;color:' . $accentColor . ';border-left:3px solid ' . $accentColor . ';padding-left:6px">RINGKASAN:</span> &nbsp; ';
        $h .= $summaryHtml . '</td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="height:8px"></td></tr>';

        return $h;
    }

    private function excelSignature(int $cols): string
    {
        $third = (int) floor(($cols - 2) / 3);
        $rest  = $cols - ($third * 2) - 4;
        $base  = 'border:1px solid #ddd;text-align:center;padding:6px;font-size:10px;';

        $h  = '<tr><td colspan="' . $cols . '" style="height:24px"></td></tr>';
        $h .= '<tr><td colspan="3" style="border-left:3px solid #1a1a1a;padding-left:8px;font-weight:bold;font-size:10px;color:#333;text-transform:uppercase;letter-spacing:0.5px">Lembar Pengesahan</td><td colspan="' . ($cols-3) . '"></td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="text-align:right;font-size:9px;color:#888;padding-bottom:6px">' . date('d F Y') . '</td></tr>';

        $hdrStyle = 'background:#1a1a1a;color:#fff;font-weight:bold;text-align:center;padding:7px;font-size:10px;border:1px solid #333;text-transform:uppercase';
        $h .= '<tr><td style="width:16px"></td><td colspan="' . $third . '" style="' . $hdrStyle . '">Disiapkan Oleh</td><td style="width:16px"></td><td colspan="' . $third . '" style="' . $hdrStyle . '">Diperiksa Oleh</td><td style="width:16px"></td><td colspan="' . $rest . '" style="' . $hdrStyle . '">Mengetahui</td></tr>';

        $bodyStyle = $base . 'background:#f9f9f9;height:60px;vertical-align:bottom';
        $h .= '<tr><td></td><td colspan="' . $third . '" style="' . $bodyStyle . '">Staf Logistik / Admin</td><td></td><td colspan="' . $third . '" style="' . $bodyStyle . '">Kepala Bagian / Supervisor</td><td></td><td colspan="' . $rest . '" style="' . $bodyStyle . '">Direktur / Pimpinan</td></tr>';

        $nameStyle = $base . 'background:#fff';
        $sig = '( ..................................... )<br><span style="font-size:9px;color:#888">NIP. .....................................</span>';
        $h .= '<tr><td></td><td colspan="' . $third . '" style="' . $nameStyle . '">' . $sig . '</td><td></td><td colspan="' . $third . '" style="' . $nameStyle . '">' . $sig . '</td><td></td><td colspan="' . $rest . '" style="' . $nameStyle . '">' . $sig . '</td></tr>';

        return $h;
    }

    private function excelFooter(array $company, int $cols, string $note, string $count): string
    {
        $h  = '<tr><td colspan="' . $cols . '" style="height:12px"></td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="background:#f5f5f5;border:1px solid #e0e0e0;padding:6px 10px;font-size:9px;color:#666"><b>Keterangan:</b> ' . $note . '</td></tr>';
        $h .= '<tr><td colspan="' . $cols . '" style="background:#f0f0f0;border:1px solid #e0e0e0;padding:6px 10px;font-size:9px;color:#555">'
            . '<b>' . htmlspecialchars($company['name']) . '</b>'
            . ($company['tagline'] ? ' &mdash; ' . htmlspecialchars($company['tagline']) : '')
            . ' &nbsp;|&nbsp; Dicetak: ' . date('d F Y H:i:s')
            . ' &nbsp;|&nbsp; Total: ' . $count
            . ' &nbsp;|&nbsp; Dokumen dicetak otomatis oleh sistem.'
            . '</td></tr>';
        $h .= '</table></body></html>';
        return $h;
    }

    private function tc($val, bool $even): string { return '<td class="' . ($even ? 'te' : 'to') . '">' . $val . '</td>'; }
    private function tl($val, bool $even): string { return '<td class="' . ($even ? 'le' : 'lo') . '">' . $val . '</td>'; }
    private function tn($val, bool $even): string { return '<td class="' . ($even ? 'ne' : 'no') . '">' . $val . '</td>'; }

    private function lightenHex(string $hex, float $f): string {
        $hex = ltrim($hex, '#');
        return sprintf('#%02x%02x%02x',
            (int) round(hexdec(substr($hex,0,2)) + (255 - hexdec(substr($hex,0,2))) * $f),
            (int) round(hexdec(substr($hex,2,2)) + (255 - hexdec(substr($hex,2,2))) * $f),
            (int) round(hexdec(substr($hex,4,2)) + (255 - hexdec(substr($hex,4,2))) * $f)
        );
    }

    private function darkenHex(string $hex, float $f): string {
        $hex = ltrim($hex, '#');
        return sprintf('#%02x%02x%02x',
            (int) round(hexdec(substr($hex,0,2)) * (1 - $f)),
            (int) round(hexdec(substr($hex,2,2)) * (1 - $f)),
            (int) round(hexdec(substr($hex,4,2)) * (1 - $f))
        );
    }
}