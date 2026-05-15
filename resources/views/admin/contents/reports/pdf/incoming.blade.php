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

        /* ── HEADER ── */
        .header {
            margin-bottom: 18px;
            border-bottom: 2.5px solid #059669;
            padding-bottom: 12px;
        }
        .header-table { display: table; width: 100%; }
        .header-logo-cell {
            display: table-cell;
            width: 60px;
            vertical-align: middle;
        }
        .header-logo-cell img {
            width: 52px; height: 52px;
            border-radius: 10px; object-fit: cover;
        }
        .logo-fallback {
            width: 52px; height: 52px;
            border-radius: 10px;
            background: #059669;
            text-align: center;
            padding-top: 14px;
            color: #fff; font-weight: 800; font-size: 11px;
        }
        .header-brand-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }
        .brand-name  { font-size: 13px; font-weight: 800; color: #2c3e50; }
        .brand-sub   { font-size: 9px;  color: #7f8c8d;  margin-top: 2px; }
        .report-title {
            font-size: 15px; font-weight: bold; color: #059669;
            margin: 10px 0 4px; text-transform: uppercase; letter-spacing: 1px;
        }
        .report-meta { font-size: 9px; color: #7f8c8d; margin-bottom: 14px; }

        /* ── FILTER ── */
        .filter-info {
            background-color: #f0fdf4; padding: 8px; border-radius: 4px;
            margin-bottom: 15px; font-size: 9px; border-left: 4px solid #059669;
        }
        .filter-info strong { color: #047857; }

        /* ── SUMMARY ── */
        .summary-section {
            background-color: #f0fdf4; padding: 12px; border-radius: 6px;
            margin-bottom: 20px; border: 1px solid #bbf7d0;
        }
        .summary-grid   { display: table; width: 100%; }
        .summary-item   {
            display: table-cell; text-align: center; padding: 8px;
            border-right: 1px solid #a7f3d0; vertical-align: middle;
        }
        .summary-item:last-child { border-right: none; }
        .summary-label  {
            font-size: 8px; color: #047857; text-transform: uppercase;
            font-weight: bold; margin-bottom: 4px; letter-spacing: 0.5px;
        }
        .summary-value  { font-size: 14px; font-weight: bold; color: #065f46; }

        /* ── TABLE ── */
        .table-container { margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th {
            background-color: #059669; color: #ffffff; font-weight: bold;
            padding: 8px 6px; text-align: center; border: 1px solid #047857;
            font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        td { padding: 6px; border: 1px solid #d1fae5; font-size: 9px; vertical-align: middle; }
        tr:nth-child(even) { background-color: #f0fdf4; }
        .total-row { background-color: #d1fae5; font-weight: bold; color: #065f46; }
        .total-row td { border-top: 2px solid #059669; border-bottom: 2px solid #059669; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .font-bold   { font-weight: bold; }
        .currency    { font-family: 'Courier New', monospace; }

        /* ── SIGNATURE ── */
        .signature-section { margin-top: 26px; page-break-inside: avoid; }
        .sig-label {
            font-size: 8px; color: #6c757d; text-transform: uppercase; font-weight: bold;
            letter-spacing: 0.5px; border-left: 3px solid #059669;
            padding-left: 6px; margin-bottom: 8px;
        }
        .sig-date-row { text-align: right; font-size: 9px; color: #6c757d; margin-bottom: 6px; }
        .sig-grid { display: table; width: 100%; }
        .sig-col  { display: table-cell; text-align: center; width: 33.33%; padding: 0 6px; }
        .sig-box  {
            border: 1px solid #bbf7d0; border-radius: 6px;
            padding: 8px 6px 7px; background: #f0fdf4;
        }
        .sig-role { font-weight: 700; font-size: 9px; color: #2c3e50; margin-bottom: 1px; }
        .sig-unit { font-size: 8px; color: #6c757d; margin-bottom: 46px; }
        .sig-line { border-top: 1px solid #9ca3af; padding-top: 4px; }
        .sig-name { font-size: 9px; font-weight: 800; color: #2c3e50; }
        .sig-nip  { font-size: 8px; color: #6c757d; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 14px; padding-top: 7px; border-top: 1px solid #d1fae5;
            font-size: 8px; color: #6c757d; display: table; width: 100%;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    {{-- HEADER --}}
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

    @if($date_from || $date_to)
    <div class="filter-info">
        <strong>Filter Applied:</strong>
        @if($date_from) From: {{ \Carbon\Carbon::parse($date_from)->format('d M Y') }} @endif
        @if($date_to) @if($date_from) | @endif To: {{ \Carbon\Carbon::parse($date_to)->format('d M Y') }} @endif
    </div>
    @endif

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Items In</div>
                <div class="summary-value">{{ $total_items }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Quantity</div>
                <div class="summary-value">{{ number_format($total_quantity) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Unique Items</div>
                <div class="summary-value">{{ $items->groupBy('item_id')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Value</div>
                <div class="summary-value currency">Rp {{ number_format($items->sum(function($item) { return ($item->jumlah ?? 0) * ($item->item->harga ?? 0); }), 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Avg Qty/Item</div>
                <div class="summary-value">{{ $total_items > 0 ? number_format($total_quantity / $total_items, 1) : 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Latest Entry</div>
                <div class="summary-value">{{ $items->max('created_at') ? \Carbon\Carbon::parse($items->max('created_at'))->format('d/m/Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">Date</th>
                    <th style="width: 8%;">Item Code</th>
                    <th style="width: 17%;">Item Name</th>
                    <th style="width: 13%;">Supplier</th>
                    <th style="width: 10%;">Category</th>
                    <th style="width: 7%;">Quantity</th>
                    <th style="width: 10%;">Unit Price</th>
                    <th style="width: 11%;">Total Value</th>
                    <th style="width: 12%;">Entry By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->created_at ? $item->created_at->format('d/m/Y') : date('d/m/Y') }}</td>
                    <td class="text-center font-bold">{{ $item->item->kode_barang ?? 'N/A' }}</td>
                    <td class="font-bold">{{ $item->item->nama }}</td>
                    <td>{{ $item->item->supplier->nama ?? 'N/A' }}</td>
                    <td>{{ $item->item->category->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->jumlah) }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->item->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format(($item->jumlah ?? 0) * ($item->item->harga ?? 0), 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->user->name ?? 'System' }}</td>
                </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="5" class="text-right font-bold">TOTAL:</td>
                    <td class="text-center font-bold">{{ number_format($total_quantity) }}</td>
                    <td></td>
                    <td class="text-right font-bold currency">Rp {{ number_format($items->sum(function($item) { return ($item->jumlah ?? 0) * ($item->item->harga ?? 0); }), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- SIGNATURE --}}
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