<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #1a1a2e;
            background: #fff;
            line-height: 1.4;
        }

        /* ══════════════════════════════════════════
           KOP SURAT
        ══════════════════════════════════════════ */
        .kop {
            display: table;
            width: 100%;
            border-bottom: 3px double #1a1a2e;
            padding-bottom: 10px;
            margin-bottom: 6px;
        }
        .kop-logo { display: table-cell; width: 70px; vertical-align: middle; }
        .kop-logo img { width: 60px; height: 60px; object-fit: contain; }
        .kop-logo-fallback {
            width: 60px; height: 60px;
            background: #1a1a2e;
            border-radius: 6px;
            text-align: center;
            line-height: 60px;
            color: #fff;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 1px;
        }
        .kop-text { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .kop-company { font-size: 16px; font-weight: 900; color: #1a1a2e; letter-spacing: 0.5px; text-transform: uppercase; }
        .kop-tagline { font-size: 9px; color: #4b5563; margin-top: 2px; }
        .kop-contact { font-size: 8px; color: #6b7280; margin-top: 3px; line-height: 1.5; }
        .kop-nomor { display: table-cell; vertical-align: middle; text-align: right; width: 160px; }
        .kop-nomor-box {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 8px;
            color: #374151;
            line-height: 1.8;
        }
        .kop-nomor-box .label { color: #9ca3af; font-size: 7.5px; }

        /* ══════════════════════════════════════════
           JUDUL LAPORAN
        ══════════════════════════════════════════ */
        .report-heading {
            text-align: center;
            margin: 10px 0 4px;
        }
        .report-heading .title {
            font-size: 13px;
            font-weight: 900;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .report-heading .subtitle {
            font-size: 8.5px;
            color: #6b7280;
            margin-top: 3px;
        }
        .divider-thin { border: none; border-top: 1px solid #e5e7eb; margin: 6px 0; }

        /* ══════════════════════════════════════════
           INFO PERIODE
        ══════════════════════════════════════════ */
        .info-bar {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            font-size: 8px;
        }
        .info-bar-left  { display: table-cell; color: #374151; }
        .info-bar-right { display: table-cell; text-align: right; color: #374151; }
        .info-bar span  { color: #9ca3af; margin-right: 3px; }

        /* ══════════════════════════════════════════
           RINGKASAN STATISTIK
        ══════════════════════════════════════════ */
        .summary-title {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
            border-left: 3px solid #1a1a2e;
            padding-left: 6px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .stats-table td {
            text-align: center;
            padding: 7px 4px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .stat-val { font-size: 16px; font-weight: 900; display: block; }
        .stat-lbl { font-size: 7px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-top: 1px; }

        .s-total     { background: #f8fafc; }           .s-total .stat-val     { color: #1a1a2e; } .s-total .stat-lbl     { color: #6b7280; }
        .s-pending   { background: #fefce8; }           .s-pending .stat-val   { color: #a16207; } .s-pending .stat-lbl   { color: #a16207; }
        .s-approved  { background: #f0fdf4; }           .s-approved .stat-val  { color: #15803d; } .s-approved .stat-lbl  { color: #15803d; }
        .s-done      { background: #eff6ff; }           .s-done .stat-val      { color: #1d4ed8; } .s-done .stat-lbl      { color: #1d4ed8; }
        .s-rejected  { background: #fff1f2; }           .s-rejected .stat-val  { color: #be123c; } .s-rejected .stat-lbl  { color: #be123c; }
        .s-cancelled { background: #f9fafb; }           .s-cancelled .stat-val { color: #6b7280; } .s-cancelled .stat-lbl { color: #6b7280; }
        .s-overdue   { background: #fff7ed; }           .s-overdue .stat-val   { color: #c2410c; } .s-overdue .stat-lbl   { color: #c2410c; }

        /* ══════════════════════════════════════════
           TABEL DATA
        ══════════════════════════════════════════ */
        .section-title {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
            border-left: 3px solid #1a1a2e;
            padding-left: 6px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .data-table thead tr {
            background: #1a1a2e;
        }
        .data-table th {
            color: #fff;
            font-size: 7.5px;
            font-weight: bold;
            padding: 6px 5px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #374151;
        }
        .data-table th.left { text-align: left; padding-left: 8px; }
        .data-table td {
            padding: 5px;
            border: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: middle;
            color: #1f2937;
        }
        .data-table td.center { text-align: center; }
        .data-table td.right  { text-align: right; }
        .row-even { background: #ffffff; }
        .row-odd  { background: #f9fafb; }
        .row-overdue { background: #fff7f7; }

        .item-name   { font-weight: bold; font-size: 8px; color: #1a1a2e; }
        .item-sub    { font-size: 7px; color: #9ca3af; margin-top: 1px; }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .b-pending   { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .b-approved  { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .b-completed { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .b-rejected  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .b-cancelled { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .b-overdue   { background: #fff7ed; color: #9a3412; border: 1px solid #fdba74; }

        .overdue-mark { color: #dc2626; font-weight: bold; }

        /* ══════════════════════════════════════════
           LEMBAR PENGESAHAN
        ══════════════════════════════════════════ */
        .sig-section { margin-top: 20px; page-break-inside: avoid; }
        .sig-section-title {
            font-size: 8px;
            font-weight: bold;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-left: 3px solid #1a1a2e;
            padding-left: 6px;
            margin-bottom: 4px;
        }
        .sig-date { text-align: right; font-size: 8px; color: #6b7280; margin-bottom: 8px; }
        .sig-table { width: 100%; border-collapse: collapse; }
        .sig-table td { vertical-align: top; padding: 0 8px; width: 33.33%; }
        .sig-table td:first-child { padding-left: 0; }
        .sig-table td:last-child  { padding-right: 0; }
        .sig-box {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            overflow: hidden;
        }
        .sig-role {
            background: #1a1a2e;
            color: #fff;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            padding: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sig-body {
            height: 55px;
            background: #f9fafb;
            text-align: center;
            padding: 5px;
            font-size: 7.5px;
            color: #6b7280;
            vertical-align: bottom;
            display: table-cell;
            width: 100%;
        }
        .sig-name-area {
            border-top: 1px solid #e5e7eb;
            padding: 5px;
            text-align: center;
            background: #fff;
        }
        .sig-name-line { font-size: 8px; color: #374151; }
        .sig-nip       { font-size: 7px; color: #9ca3af; margin-top: 2px; }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
        .footer-wrap {
            margin-top: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
            display: table;
            width: 100%;
        }
        .footer-left  { display: table-cell; font-size: 7.5px; color: #9ca3af; }
        .footer-right { display: table-cell; font-size: 7.5px; color: #9ca3af; text-align: right; }
        .footer-left strong { color: #6b7280; }

        /* Empty state */
        .empty-row td { padding: 20px; text-align: center; color: #9ca3af; font-style: italic; }
    </style>
</head>
<body>

    {{-- ══ KOP SURAT ══ --}}
    <div class="kop">
        <div class="kop-logo">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo">
            @else
                <div class="kop-logo-fallback">{{ strtoupper(substr($company['name'], 0, 3)) }}</div>
            @endif
        </div>
        <div class="kop-text">
            <div class="kop-company">{{ $company['name'] }}</div>
            @if($company['tagline'])
            <div class="kop-tagline">{{ $company['tagline'] }}</div>
            @endif
            @php $contact = implode('  |  ', array_filter([$company['address'], $company['phone'], $company['email']])); @endphp
            @if($contact)
            <div class="kop-contact">{{ $contact }}</div>
            @endif
        </div>
        <div class="kop-nomor">
            <div class="kop-nomor-box">
                <div><span class="label">No. Dokumen :</span> RPT-{{ date('Ymd') }}-{{ str_pad($stats['total'], 3, '0', STR_PAD_LEFT) }}</div>
                <div><span class="label">Tanggal     :</span> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div><span class="label">Halaman     :</span> 1 dari 1</div>
                <div><span class="label">Dicetak oleh:</span> {{ $printed_by }}</div>
            </div>
        </div>
    </div>

    {{-- ══ JUDUL ══ --}}
    <div class="report-heading">
        <div class="title">{{ strtoupper($title) }}</div>
        <div class="subtitle">
            @if($date_from || $date_to)
                Periode: {{ $date_from ? \Carbon\Carbon::parse($date_from)->format('d M Y') : '—' }}
                s/d {{ $date_to ? \Carbon\Carbon::parse($date_to)->format('d M Y') : '—' }}
            @else
                Seluruh Periode
            @endif
            @if($status) &nbsp;&bull;&nbsp; Filter Status: {{ ucfirst($status) }} @endif
        </div>
    </div>
    <hr class="divider-thin">

    {{-- ══ RINGKASAN ══ --}}
    <div class="summary-title">Ringkasan Data</div>
    <table class="stats-table">
        <tr>
            <td class="s-total">
                <span class="stat-val">{{ $stats['total'] }}</span>
                <span class="stat-lbl">Total</span>
            </td>
            <td class="s-pending">
                <span class="stat-val">{{ $stats['pending'] }}</span>
                <span class="stat-lbl">Menunggu</span>
            </td>
            <td class="s-approved">
                <span class="stat-val">{{ $stats['approved'] }}</span>
                <span class="stat-lbl">Disetujui</span>
            </td>
            <td class="s-done">
                <span class="stat-val">{{ $stats['completed'] }}</span>
                <span class="stat-lbl">Selesai</span>
            </td>
            <td class="s-rejected">
                <span class="stat-val">{{ $stats['rejected'] }}</span>
                <span class="stat-lbl">Ditolak</span>
            </td>
            <td class="s-cancelled">
                <span class="stat-val">{{ $stats['cancelled'] }}</span>
                <span class="stat-lbl">Dibatalkan</span>
            </td>
            <td class="s-overdue">
                <span class="stat-val">{{ $stats['overdue'] }}</span>
                <span class="stat-lbl">Terlambat</span>
            </td>
        </tr>
    </table>

    {{-- ══ TABEL DATA ══ --}}
    <div class="section-title">Detail Peminjaman</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:22px">No</th>
                <th style="width:60px">Tgl Pengajuan</th>
                <th class="left" style="width:100px">Peminjam</th>
                <th class="left" style="width:120px">Barang</th>
                <th style="width:28px">Jml</th>
                <th style="width:60px">Tgl Pinjam</th>
                <th style="width:65px">Rencana Kembali</th>
                <th style="width:60px">Tgl Selesai</th>
                <th style="width:65px">Status</th>
                <th class="left">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $i => $b)
            @php
                $isOverdue = $b->status === 'approved'
                    && $b->tanggal_kembali_rencana
                    && $b->tanggal_kembali_rencana->isPast();

                $statusLabel = $isOverdue ? 'Terlambat' : match($b->status ?? '') {
                    'pending'   => 'Menunggu',
                    'approved'  => 'Disetujui',
                    'completed' => 'Selesai',
                    'rejected'  => 'Ditolak',
                    'cancelled' => 'Dibatalkan',
                    default     => ucfirst($b->status ?? '-'),
                };
                $badgeClass = $isOverdue ? 'b-overdue' : match($b->status ?? '') {
                    'pending'   => 'b-pending',
                    'approved'  => 'b-approved',
                    'completed' => 'b-completed',
                    'rejected'  => 'b-rejected',
                    'cancelled' => 'b-cancelled',
                    default     => 'b-cancelled',
                };
                $rowClass = $isOverdue ? 'row-overdue' : ($i % 2 === 0 ? 'row-even' : 'row-odd');
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $b->created_at?->format('d/m/Y') ?? '-' }}</td>
                <td>
                    <div class="item-name">{{ $b->user?->name ?? '-' }}</div>
                </td>
                <td>
                    <div class="item-name">{{ $b->item?->nama ?? '-' }}</div>
                    @if($b->item?->category?->name)
                    <div class="item-sub">{{ $b->item->category->name }}</div>
                    @endif
                </td>
                <td class="center"><strong>{{ $b->jumlah }}</strong></td>
                <td class="center">{{ $b->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                <td class="center {{ $isOverdue ? 'overdue-mark' : '' }}">
                    {{ $b->tanggal_kembali_rencana?->format('d/m/Y') ?? '-' }}
                </td>
                <td class="center">
                    {{ $b->completed_at ? \Carbon\Carbon::parse($b->completed_at)->format('d/m/Y') : '-' }}
                </td>
                <td class="center">
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
                <td>{{ $b->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="10">Tidak ada data peminjaman untuk ditampilkan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ══ LEMBAR PENGESAHAN ══ --}}
    <div class="sig-section">
        <div class="sig-section-title">Lembar Pengesahan</div>
        <div class="sig-date">{{ $company['address'] ? $company['address'] . ', ' : '' }}{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-box">
                        <div class="sig-role">Disiapkan Oleh</div>
                        <table style="width:100%"><tr><td class="sig-body">Staf Logistik / Admin</td></tr></table>
                        <div class="sig-name-area">
                            <div class="sig-name-line">( ................................................ )</div>
                            <div class="sig-nip">NIP. ..........................................</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="sig-box">
                        <div class="sig-role">Diperiksa Oleh</div>
                        <table style="width:100%"><tr><td class="sig-body">Kepala Bagian / Supervisor</td></tr></table>
                        <div class="sig-name-area">
                            <div class="sig-name-line">( ................................................ )</div>
                            <div class="sig-nip">NIP. ..........................................</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="sig-box">
                        <div class="sig-role">Mengetahui</div>
                        <table style="width:100%"><tr><td class="sig-body">Direktur / Pimpinan</td></tr></table>
                        <div class="sig-name-area">
                            <div class="sig-name-line">( ................................................ )</div>
                            <div class="sig-nip">NIP. ..........................................</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="footer-wrap">
        <div class="footer-left">
            <strong>{{ $company['name'] }}</strong>
            @if($company['tagline']) &mdash; {{ $company['tagline'] }}@endif
            &nbsp;|&nbsp; Dicetak: {{ $print_time }}
        </div>
        <div class="footer-right">
            Total {{ $stats['total'] }} record &nbsp;|&nbsp; Dokumen ini dicetak secara otomatis oleh sistem
        </div>
    </div>

</body>
</html>
