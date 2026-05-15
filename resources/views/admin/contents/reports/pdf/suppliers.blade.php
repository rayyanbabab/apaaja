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
            margin: 0; padding: 0;
        }

        /* ── HEADER ── */
        .header { margin-bottom: 18px; border-bottom: 2.5px solid #ea580c; padding-bottom: 12px; }
        .header-table { display: table; width: 100%; }
        .header-logo-cell { display: table-cell; width: 60px; vertical-align: middle; }
        .header-logo-cell img { width: 52px; height: 52px; border-radius: 10px; object-fit: cover; }
        .logo-fallback {
            width: 52px; height: 52px; border-radius: 10px; background: #ea580c;
            text-align: center; padding-top: 14px; color: #fff; font-weight: 800; font-size: 11px;
        }
        .header-brand-cell { display: table-cell; vertical-align: middle; padding-left: 10px; }
        .brand-name { font-size: 13px; font-weight: 800; color: #2c3e50; }
        .brand-sub  { font-size: 9px; color: #7f8c8d; margin-top: 2px; }
        .report-title {
            font-size: 15px; font-weight: bold; color: #ea580c;
            margin: 10px 0 4px; text-transform: uppercase; letter-spacing: 1px;
        }
        .report-meta { font-size: 9px; color: #7f8c8d; margin-bottom: 14px; }

        /* ── SUMMARY ── */
        .summary-section {
            background-color: #fff7ed; padding: 12px; border-radius: 6px;
            margin-bottom: 20px; border: 1px solid #fed7aa;
        }
        .summary-grid { display: table; width: 100%; }
        .summary-item {
            display: table-cell; text-align: center; padding: 8px;
            border-right: 1px solid #fdba74; vertical-align: middle;
        }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 8px; color: #9a3412; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; letter-spacing: 0.5px; }
        .summary-value { font-size: 14px; font-weight: bold; color: #7c2d12; }

        /* ── TABLE ── */
        .table-container { margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th {
            background-color: #ea580c; color: #ffffff; font-weight: bold;
            padding: 8px 6px; text-align: center; border: 1px solid #c2410c;
            font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        td { padding: 6px; border: 1px solid #fed7aa; font-size: 9px; vertical-align: middle; }
        tr:nth-child(even) { background-color: #fff7ed; }

        .status-badge { padding: 3px 6px; border-radius: 10px; font-size: 8px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-active   { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-inactive { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .font-bold   { font-weight: bold; }
        .contact-info { font-size: 8px; color: #6b7280; margin-top: 2px; line-height: 1.2; }
        .address-info { font-size: 8px; color: #374151; line-height: 1.2; }

        /* ── SIGNATURE ── */
        .signature-section { margin-top: 26px; page-break-inside: avoid; }
        .sig-label {
            font-size: 8px; color: #6c757d; text-transform: uppercase; font-weight: bold;
            letter-spacing: 0.5px; border-left: 3px solid #ea580c; padding-left: 6px; margin-bottom: 8px;
        }
        .sig-date-row { text-align: right; font-size: 9px; color: #6c757d; margin-bottom: 6px; }
        .sig-grid { display: table; width: 100%; }
        .sig-col  { display: table-cell; text-align: center; width: 33.33%; padding: 0 6px; }
        .sig-box  { border: 1px solid #fed7aa; border-radius: 6px; padding: 8px 6px 7px; background: #fff7ed; }
        .sig-role { font-weight: 700; font-size: 9px; color: #2c3e50; margin-bottom: 1px; }
        .sig-unit { font-size: 8px; color: #6c757d; margin-bottom: 46px; }
        .sig-line { border-top: 1px solid #9ca3af; padding-top: 4px; }
        .sig-name { font-size: 9px; font-weight: 800; color: #2c3e50; }
        .sig-nip  { font-size: 8px; color: #6c757d; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 14px; padding-top: 7px; border-top: 1px solid #fed7aa;
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

    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Suppliers</div>
                <div class="summary-value">{{ $total_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Active Suppliers</div>
                <div class="summary-value">{{ $active_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Inactive Suppliers</div>
                <div class="summary-value">{{ $inactive_suppliers }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">{{ $suppliers->sum(function($supplier) { return $supplier->items->count(); }) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Avg Items/Supplier</div>
                <div class="summary-value">{{ $total_suppliers > 0 ? number_format($suppliers->sum(function($supplier) { return $supplier->items->count(); }) / $total_suppliers, 1) : 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Latest Joined</div>
                <div class="summary-value">{{ $suppliers->max('created_at') ? \Carbon\Carbon::parse($suppliers->max('created_at'))->format('d/m/Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 18%;">Supplier Name</th>
                    <th style="width: 15%;">Contact</th>
                    <th style="width: 20%;">Address</th>
                    <th style="width: 8%;">Items</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 8%;">Contact Person</th>
                    <th style="width: 9%;">Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $index => $supplier)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $supplier->nama }}</td>
                    <td class="text-left">
                        @if($supplier->phone) <div>{{ $supplier->phone }}</div> @endif
                        @if($supplier->email) <div class="contact-info">{{ $supplier->email }}</div> @endif
                        @if(!$supplier->phone && !$supplier->email) <div class="contact-info">N/A</div> @endif
                    </td>
                    <td class="text-left">
                        <div class="address-info">{{ $supplier->address ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center">{{ $supplier->items->count() }}</td>
                    <td class="text-center">
                        @if($supplier->status === 'active')
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-inactive">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="contact-info">{{ $supplier->contact_person ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center">{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y') : date('d/m/Y') }}</td>
                </tr>
                @endforeach
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
                    <div class="sig-unit">Staf Pengadaan / Procurement</div>
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
        <div class="footer-right">Dicetak: {{ $print_time }} &nbsp;|&nbsp; Total: {{ $total_suppliers }} supplier</div>
    </div>

</body>
</html>