<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }
        
        .header {
            margin-bottom: 18px;
            border-bottom: 2.5px solid #495057;
            padding-bottom: 12px;
        }

        .header-table {
            display: table;
            width: 100%;
        }
        .header-logo-cell {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }
        .header-logo-cell img {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            object-fit: cover;
        }
        .logo-fallback {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            background: #495057;
            text-align: center;
            padding-top: 14px;
            color: #fff;
            font-weight: 800;
            font-size: 11px;
        }
        .header-brand-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }
        .brand-name {
            font-size: 13px;
            font-weight: 800;
            color: #2c3e50;
        }
        .brand-sub {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 2px;
        }

        .report-title {
            font-size: 15px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-meta {
            font-size: 9px;
            color: #7f8c8d;
            margin-bottom: 14px;
        }
        
        .summary-section {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #ced4da;
            vertical-align: middle;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .table-container {
            margin-top: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th {
            background-color: #495057;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #343a40;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #dee2e6;
            font-size: 9px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-in-stock {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-low-stock {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-out-stock {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* ── SIGNATURE ── */
        .signature-section {
            margin-top: 26px;
            page-break-inside: avoid;
        }
        .sig-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-left: 3px solid #495057;
            padding-left: 6px;
            margin-bottom: 8px;
        }
        .sig-date-row {
            text-align: right;
            font-size: 9px;
            color: #6c757d;
            margin-bottom: 6px;
        }
        .sig-grid {
            display: table;
            width: 100%;
        }
        .sig-col {
            display: table-cell;
            text-align: center;
            width: 33.33%;
            padding: 0 6px;
        }
        .sig-box {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px 6px 7px;
            background: #f8f9fa;
        }
        .sig-role {
            font-weight: 700;
            font-size: 9px;
            color: #2c3e50;
            margin-bottom: 1px;
        }
        .sig-unit {
            font-size: 8px;
            color: #6c757d;
            margin-bottom: 46px;
        }
        .sig-line {
            border-top: 1px solid #9ca3af;
            padding-top: 4px;
        }
        .sig-name {
            font-size: 9px;
            font-weight: 800;
            color: #2c3e50;
        }
        .sig-nip {
            font-size: 8px;
            color: #6c757d;
            margin-top: 2px;
        }
        /* ── FOOTER ── */
        .footer {
            margin-top: 14px;
            padding-top: 7px;
            border-top: 1px solid #dee2e6;
            font-size: 8px;
            color: #6c757d;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .filter-info {
            background-color: #e3f2fd;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            border-left: 4px solid #2196f3;
        }
        
        .filter-info strong {
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-table">
            <div class="header-logo-cell">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div class="logo-fallback">ART</div>
                @endif
            </div>
            <div class="header-brand-cell">
                <div class="brand-name">{{ $company['name'] }}</div>
                <div class="brand-sub">{{ $company['tagline'] }}@if($company['address']) &nbsp;|&nbsp; {{ $company['address'] }}@endif</div>
            </div>
        </div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            Tanggal Cetak: {{ $print_time }}&nbsp;&nbsp;|&nbsp;&nbsp;Dicetak oleh: {{ $printed_by }}
        </div>
    </div>

    @if($supplier_id || $category_id)
    <div class="filter-info">
        <strong>Filter Applied:</strong>
        @if($supplier_id)
            Supplier: {{ \App\Models\Supplier::find($supplier_id)->nama ?? 'All Suppliers' }}
        @endif
        @if($category_id)
            @if($supplier_id) | @endif
            Category: {{ \App\Models\Category::find($category_id)->name ?? 'All Categories' }}
        @endif
    </div>
    @endif

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">{{ $total_items }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Stock</div>
                <div class="summary-value">{{ number_format($total_stock) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Value</div>
                <div class="summary-value currency">Rp {{ number_format($total_value, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Low Stock Items</div>
                <div class="summary-value">{{ $items->filter(function($item) use ($low_stock_threshold) { return ($item->stok_total ?? 0) <= $low_stock_threshold && ($item->stok_total ?? 0) > 0; })->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Out of Stock</div>
                <div class="summary-value">{{ $items->filter(function($item) { return ($item->stok_total ?? 0) == 0; })->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">In Stock</div>
                <div class="summary-value">{{ $items->filter(function($item) { return ($item->stok_total ?? 0) > 0; })->count() }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">Item Code</th>
                    <th style="width: 18%;">Item Name</th>
                    <th style="width: 13%;">Supplier</th>
                    <th style="width: 10%;">Category</th>
                    <th style="width: 7%;">Stock</th>
                    <th style="width: 11%;">Unit Price</th>
                    <th style="width: 11%;">Total Value</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 11%;">Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold">{{ $item->kode_barang ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $item->nama ?? 'N/A' }}</td>
                    <td>{{ $item->supplier->nama ?? 'N/A' }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->stok_total ?? 0) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format(($item->stok_total ?? 0) * ($item->harga ?? 0), 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if(($item->stok_total ?? 0) == 0)
                            <span class="status-badge status-out-stock">Out</span>
                        @elseif(($item->stok_total ?? 0) <= $low_stock_threshold)
                            <span class="status-badge status-low-stock">Low</span>
                        @else
                            <span class="status-badge status-in-stock">OK</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->type && is_object($item->type) && method_exists($item->type, 'label'))
                            {{ $item->type->label() }}
                        @elseif($item->type && is_object($item->type) && property_exists($item->type, 'value'))
                            {{ ucfirst($item->type->value) }}
                        @elseif($item->type)
                            {{ ucfirst((string) $item->type) }}
                        @else
                            Stok
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── SIGNATURE ── --}}
    <div class="signature-section">
        <div class="sig-label">Lembar Pengesahan</div>
        <div class="sig-date-row">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        <div class="sig-grid">
            <div class="sig-col">
                <div class="sig-box">
                    <div class="sig-role">Disiapkan Oleh</div>
                    <div class="sig-unit">Staf Gudang / Logistik</div>
                    <div class="sig-line">
                        <div class="sig-name">(................................)</div>
                        <div class="sig-nip">NIP. .............................</div>
                    </div>
                </div>
            </div>
            <div class="sig-col">
                <div class="sig-box">
                    <div class="sig-role">Diperiksa Oleh</div>
                    <div class="sig-unit">Kepala Bagian / Supervisor</div>
                    <div class="sig-line">
                        <div class="sig-name">(................................)</div>
                        <div class="sig-nip">NIP. .............................</div>
                    </div>
                </div>
            </div>
            <div class="sig-col">
                <div class="sig-box">
                    <div class="sig-role">Mengetahui</div>
                    <div class="sig-unit">Direktur / Pimpinan</div>
                    <div class="sig-line">
                        <div class="sig-name">(................................)</div>
                        <div class="sig-nip">NIP. .............................</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-left"><strong>{{ $company['name'] }}</strong> &mdash; {{ $company['tagline'] }}</div>
        <div class="footer-right">Dicetak: {{ $print_time }} &nbsp;|&nbsp; Total: {{ $total_items }} item</div>
    </div>
</body>
</html>