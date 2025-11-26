!DOCTYPE html>
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
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #059669;
            padding-bottom: 15px;
        }
        
        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .company-logo {
            font-size: 20px;
            font-weight: bold;
            color: #059669;
        }
        
        .company-details {
            text-align: right;
            font-size: 9px;
            color: #7f8c8d;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .report-meta {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        .filter-info {
            background-color: #f0fdf4;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9px;
            border-left: 4px solid #059669;
        }
        
        .filter-info strong {
            color: #047857;
        }
        
        .summary-section {
            background-color: #f0fdf4;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border-right: 1px solid #a7f3d0;
            vertical-align: middle;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            color: #047857;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #065f46;
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
            background-color: #059669;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #047857;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 6px;
            border: 1px solid #d1fae5;
            font-size: 9px;
            vertical-align: middle;
        }
        
        tr:nth-child(even) {
            background-color: #f0fdf4;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #d1fae5;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
        }
        
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
        
        .total-row {
            background-color: #d1fae5;
            font-weight: bold;
            color: #065f46;
        }
        
        .total-row td {
            border-top: 2px solid #059669;
            border-bottom: 2px solid #059669;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-logo">PT. ARTILIA</div>
            <div class="company-details">
                Inventory Management System<br>
                Incoming Items Report
            </div>
        </div>
        <div class="report-title">{{ $title }}</div>
        <div class="report-meta">
            <div>Generated on: {{ $date }}</div>
            <div>Report Period: {{ $date }}</div>
        </div>
    </div>

    @if($date_from || $date_to)
    <div class="filter-info">
        <strong>Filter Applied:</strong>
        @if($date_from)
            From: {{ \Carbon\Carbon::parse($date_from)->format('d M Y') }}
        @endif
        @if($date_to)
            @if($date_from) | @endif
            To: {{ \Carbon\Carbon::parse($date_to)->format('d M Y') }}
        @endif
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
                <div class="summary-value currency">Rp {{ number_format($items->sum(function($item) { return $item->jumlah * $item->item->harga; }), 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Avg Qty/Item</div>
                <div class="summary-value">{{ $total_items > 0 ? number_format($total_quantity / $total_items, 1) : 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Latest Entry</div>
                <div class="summary-value">{{ $items->max('created_at') ? $items->max('created_at')->format('d/m/Y') : 'N/A' }}</div>
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
                    <td class="text-right currency">Rp {{ number_format($item->item->harga, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($item->jumlah * $item->item->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->user->name ?? 'System' }}</td>
                </tr>
                @endforeach
                
                {{-- Total Row --}}
                <tr class="total-row">
                    <td colspan="5" class="text-right font-bold">TOTAL:</td>
                    <td class="text-center font-bold">{{ number_format($total_quantity) }}</td>
                    <td></td>
                    <td class="text-right font-bold currency">Rp {{ number_format($items->sum(function($item) { return $item->jumlah * $item->item->harga; }), 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>PT. ARTILIA - Inventory Management System</strong></p>
        <p>This report was generated automatically on {{ $date }} | Total Records: {{ $total_items }} incoming items</p>
        <p>For questions regarding this report, please contact the IT Department</p>
    </div>
</body>
</html>