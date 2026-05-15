<?php

namespace App\Http\Controllers;

use App\Enums\ItemType;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ImportController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // ITEMS TEMPLATE — Download sebagai .xlsx
    // ─────────────────────────────────────────────────────────────
    public function itemsTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Barang');

        // Header row
        $headers = ['nama*', 'kode', 'kategori', 'supplier', 'lokasi',
                    'stok_reguler*', 'stok_peminjaman', 'harga', 'keterangan', 'tipe'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i); // A, B, C...
            $sheet->setCellValue("{$col}1", $h);
        }

        // Style header
        $headerRange = 'A1:J1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0284C7']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color' => ['rgb' => 'FFFFFF']]],
        ]);

        // Column widths
        $widths = [28, 14, 16, 20, 18, 14, 16, 14, 24, 12];
        foreach ($widths as $i => $w) {
            $sheet->getColumnDimensionByColumn($i + 1)->setWidth($w);
        }
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Example rows
        $examples = [
            ['Laptop Dell Latitude', 'LPT-001', 'Elektronik', 'PT. Maju Jaya', 'Gudang A - Rak 1', 10, 0, 8500000, 'Core i5 Gen 12', 'stok'],
            ['Proyektor Epson',      'PRJ-001', 'Peralatan',  'PT. Maju Jaya', '',                 5,  3, 3500000, 'Full HD 3LCD',   'peminjaman'],
            ['Kursi Kantor',         '',        'Furnitur',   '',              '',                 20, 0, 750000,  '',               'stok'],
        ];
        foreach ($examples as $r => $row) {
            foreach ($row as $c => $val) {
                $col = chr(65 + $c);
                $sheet->setCellValue("{$col}" . ($r + 2), $val);
            }
            // Zebra stripe
            if ($r % 2 === 1) {
                $sheet->getStyle('A' . ($r + 2) . ':J' . ($r + 2))
                      ->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setRGB('F0F9FF');
            }
        }

        // Note row
        $noteRow = count($examples) + 2;
        $sheet->setCellValue("A{$noteRow}", '* = wajib diisi  |  tipe: stok atau peminjaman');
        $sheet->mergeCells("A{$noteRow}:J{$noteRow}");
        $sheet->getStyle("A{$noteRow}")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF7ED']],
        ]);

        $sheet->setAutoFilter('A1:J1');
        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(
            fn() => $writer->save('php://output'),
            'template-barang.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    // ─────────────────────────────────────────────────────────────
    // IMPORT ITEMS — Support .xlsx & .csv
    // ─────────────────────────────────────────────────────────────
    public function importItems(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ], [
            'csv_file.required' => 'Pilih file CSV atau Excel terlebih dahulu.',
            'csv_file.mimes'    => 'File harus berformat CSV atau Excel (.xlsx/.xls).',
            'csv_file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file      = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $rows      = $extension === 'csv' || $extension === 'txt'
                      ? $this->readCsv($file->getRealPath())
                      : $this->readExcel($file->getRealPath());

        if (empty($rows) || count($rows) < 2) {
            return redirect()->back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $header   = array_map('trim', $rows[0]);
        $imported = 0;
        $errors   = [];

        $categories = Category::all()->keyBy(fn($c) => mb_strtolower($c->name));
        $suppliers  = Supplier::all()->keyBy(fn($s) => mb_strtolower($s->company_name ?? $s->nama ?? ''));
        $locations  = Location::all()->keyBy(fn($l) => mb_strtolower($l->name ?? $l->label ?? ''));

        for ($i = 1; $i < count($rows); $i++) {
            $rowNum = $i + 1;
            $row    = $rows[$i];
            if (empty(array_filter($row))) continue;

            $data = [];
            foreach ($header as $j => $col) {
                $data[$col] = isset($row[$j]) ? trim((string)$row[$j]) : '';
            }

            // Normalize header aliases
            $data['nama']           = $data['nama'] ?? $data['nama*'] ?? '';
            $data['stok_reguler']   = $data['stok_reguler'] ?? $data['stok_reguler*'] ?? '';

            if (empty($data['nama'])) {
                $errors[] = "Baris {$rowNum}: 'nama' tidak boleh kosong.";
                continue;
            }
            if ($data['stok_reguler'] === '' || !is_numeric($data['stok_reguler']) || intval($data['stok_reguler']) < 0) {
                $errors[] = "Baris {$rowNum}: 'stok_reguler' harus angka >= 0.";
                continue;
            }

            // Resolve relations
            $categoryId = null;
            if (!empty($data['kategori'])) {
                $cat = $categories->get(mb_strtolower($data['kategori']));
                if (!$cat) {
                    $cat = Category::create(['name' => $data['kategori'], 'status' => 'active']);
                    $categories->put(mb_strtolower($data['kategori']), $cat);
                }
                $categoryId = $cat->id;
            }

            $supplierId = null;
            if (!empty($data['supplier'])) {
                $sup = $suppliers->get(mb_strtolower($data['supplier']));
                if (!$sup) {
                    $sup = Supplier::create(['company_name' => $data['supplier'], 'nama' => $data['supplier'], 'status' => 'active']);
                    $suppliers->put(mb_strtolower($data['supplier']), $sup);
                }
                $supplierId = $sup->id;
            }

            $locationId = null;
            if (!empty($data['lokasi'])) {
                $loc = $locations->get(mb_strtolower($data['lokasi']));
                if ($loc) $locationId = $loc->id;
            }

            $typeRaw        = mb_strtolower($data['tipe'] ?? 'stok');
            $type           = $typeRaw === 'peminjaman' ? ItemType::PEMINJAMAN : ItemType::STOK;
            $stokReguler    = (int)($data['stok_reguler']);
            $stokPeminjaman = (int)($data['stok_peminjaman'] ?? 0);

            try {
                Item::create([
                    'nama'            => $data['nama'],
                    'kode'            => $data['kode'] ?: null,
                    'category_id'     => $categoryId,
                    'supplier_id'     => $supplierId,
                    'location_id'     => $locationId,
                    'stok_reguler'    => $stokReguler,
                    'stok_peminjaman' => $stokPeminjaman,
                    'stok_total'      => $stokReguler + $stokPeminjaman,
                    'harga'           => is_numeric($data['harga'] ?? '') ? (int)$data['harga'] : 0,
                    'keterangan'      => $data['keterangan'] ?: null,
                    'type'            => $type,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: Gagal — " . $e->getMessage();
            }
        }

        $routePrefix = session('role', 'admin');
        if ($imported > 0) {
            $msg = "{$imported} barang berhasil diimport.";
            if (count($errors)) $msg .= ' ' . count($errors) . ' baris dilewati.';
            return redirect()->route($routePrefix . '.inventory.index')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }

        return redirect()->back()
            ->with('error', 'Tidak ada data yang berhasil diimport.')
            ->with('import_errors', $errors);
    }

    // ─────────────────────────────────────────────────────────────
    // USERS TEMPLATE — Download sebagai .xlsx
    // ─────────────────────────────────────────────────────────────
    public function usersTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template User');

        $headers = ['nama*', 'email*', 'password*', 'role', 'bio'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C3AED']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color' => ['rgb' => 'FFFFFF']]],
        ]);

        foreach ([28, 28, 18, 12, 28] as $i => $w) {
            $sheet->getColumnDimensionByColumn($i + 1)->setWidth($w);
        }
        $sheet->getRowDimension(1)->setRowHeight(22);

        $examples = [
            ['John Doe',   'john@example.com',  'password123', 'user',     'Staff IT'],
            ['Jane Smith', 'jane@example.com',  'secret456',   'operator', 'Kepala Gudang'],
            ['Alice',      'alice@example.com', 'pass789',     'user',     ''],
        ];
        foreach ($examples as $r => $row) {
            foreach ($row as $c => $val) {
                $sheet->setCellValue(chr(65 + $c) . ($r + 2), $val);
            }
            if ($r % 2 === 1) {
                $sheet->getStyle('A' . ($r + 2) . ':E' . ($r + 2))
                      ->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setRGB('FAF5FF');
            }
        }

        $noteRow = count($examples) + 2;
        $sheet->setCellValue("A{$noteRow}", '* = wajib  |  role: user / operator / admin  |  Password min 6 karakter');
        $sheet->mergeCells("A{$noteRow}:E{$noteRow}");
        $sheet->getStyle("A{$noteRow}")->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF7ED']],
        ]);

        $sheet->setAutoFilter('A1:E1');
        $sheet->freezePane('A2');

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(
            fn() => $writer->save('php://output'),
            'template-user.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    // ─────────────────────────────────────────────────────────────
    // IMPORT USERS — Support .xlsx & .csv
    // ─────────────────────────────────────────────────────────────
    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ], [
            'csv_file.required' => 'Pilih file CSV atau Excel terlebih dahulu.',
            'csv_file.mimes'    => 'File harus berformat CSV atau Excel (.xlsx/.xls).',
        ]);

        $file      = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $rows      = $extension === 'csv' || $extension === 'txt'
                      ? $this->readCsv($file->getRealPath())
                      : $this->readExcel($file->getRealPath());

        if (empty($rows) || count($rows) < 2) {
            return redirect()->back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $header     = array_map('trim', $rows[0]);
        $imported   = 0;
        $errors     = [];
        $validRoles = ['admin', 'operator', 'user'];

        for ($i = 1; $i < count($rows); $i++) {
            $rowNum = $i + 1;
            $row    = $rows[$i];
            if (empty(array_filter($row))) continue;

            $data = [];
            foreach ($header as $j => $col) {
                // Strip * from header keys
                $key        = rtrim(trim($col), '*');
                $data[$key] = isset($row[$j]) ? trim((string)$row[$j]) : '';
            }

            if (empty($data['nama'])) {
                $errors[] = "Baris {$rowNum}: 'nama' tidak boleh kosong.";
                continue;
            }
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris {$rowNum}: 'email' tidak valid — '{$data['email']}'.";
                continue;
            }
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors[] = "Baris {$rowNum}: 'password' minimal 6 karakter.";
                continue;
            }
            if (User::where('email', $data['email'])->exists()) {
                $errors[] = "Baris {$rowNum}: Email '{$data['email']}' sudah terdaftar, dilewati.";
                continue;
            }

            $role = mb_strtolower($data['role'] ?? 'user');
            if (!in_array($role, $validRoles)) $role = 'user';

            try {
                User::create([
                    'name'      => $data['nama'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'role'      => $role,
                    'bio'       => $data['bio'] ?: null,
                    'is_active' => true,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNum}: Gagal — " . $e->getMessage();
            }
        }

        $routePrefix = session('role', 'admin');
        if ($imported > 0) {
            $msg = "{$imported} user berhasil diimport.";
            if (count($errors)) $msg .= ' ' . count($errors) . ' baris dilewati.';
            return redirect()->route($routePrefix . '.content.listusers')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }

        return redirect()->back()
            ->with('error', 'Tidak ada user yang berhasil diimport.')
            ->with('import_errors', $errors);
    }

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────

    /** Read CSV file → array of arrays */
    private function readCsv(string $path): array
    {
        $rows   = [];
        $handle = fopen($path, 'r');
        // Strip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    /** Read Excel file (.xlsx/.xls) → array of arrays */
    private function readExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = [];
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getFormattedValue();
            }
            $rows[] = $rowData;
        }
        return $rows;
    }
}
