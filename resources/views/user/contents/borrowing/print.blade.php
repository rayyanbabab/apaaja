<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bukti Peminjaman - {{ 'BR-' . str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</title>
<style>
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #1a1a1a; background: #fff; margin: 0; padding: 10mm; }
    @page { size: A4 portrait; margin: 8mm 10mm; }
    @media print { body { padding: 0; } }
    table { border-collapse: collapse; width: 100%; }
    .header-table td { vertical-align: middle; padding: 0; }
    .info-box { border: 1.5px solid #bfdbfe; border-radius: 8px; overflow: hidden; height: 100%; }
    .info-box-green { border-color: #bbf7d0; }
    .box-header { padding: 6px 10px; }
    .box-header-blue { background: #1e3a8a; }
    .box-header-green { background: #166534; }
    .box-header span { font-size: 8pt; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; }
    .box-body { padding: 9px 11px; background: #f8fafc; }
    .box-body-green { background: #f0fdf4; }
    .info-row td { padding: 3px 0; font-size: 9pt; }
    .label { color: #6b7280; width: 95px; vertical-align: top; }
    .value { color: #111827; font-weight: 600; }
    .value-blue { color: #1e40af; font-weight: 900; font-family: monospace; }
    .section-title { font-size: 8pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #1e40af; margin-bottom: 5px; margin-top: 10px; }
    .item-table thead tr { background: #1e3a8a; color: #fff; }
    .item-table th { text-align: left; padding: 8px 10px; font-size: 8.5pt; font-weight: 700; }
    .item-table td { padding: 9px 10px; font-size: 9pt; border-bottom: 1px solid #f1f5f9; }
    .date-box { border: 1.5px solid #bfdbfe; border-radius: 8px; padding: 8px; text-align: center; background: #eff6ff; }
    .date-box-red { border-color: #fca5a5; background: #fef2f2; }
    .date-box-gray { border-color: #d1d5db; background: #f9fafb; }
    .date-box-green { border-color: #86efac; background: #f0fdf4; }
    .date-label { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
    .date-value { font-size: 11pt; font-weight: 800; }
    .date-year { font-size: 9pt; font-weight: 600; }
    .terms-box { border: 1.5px solid #fde68a; border-radius: 8px; padding: 7px 12px; background: #fffbeb; margin-top: 10px; }
    .sign-table td { text-align: center; padding: 0 8px; vertical-align: top; }
    .sign-line { border-top: 1.5px solid #374151; margin: 0 10px; padding-top: 5px; margin-top: 36px; }
    .footer-box { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 10px; font-size: 7.5pt; color: #9ca3af; }
</style>
</head>
<body>

@php
    $companyName    = \App\Models\Setting::get('company_name',    'PT. ARTILIA');
    $companyTagline = \App\Models\Setting::get('company_tagline', 'Inventory Management System');
    $companyAddress = \App\Models\Setting::get('company_address', '');
    $companyPhone   = \App\Models\Setting::get('company_phone',   '');
    $companyEmail   = \App\Models\Setting::get('company_email',   '');
    $companyLogo    = \App\Models\Setting::get('company_logo',    null);

    $docNumber   = 'BR-' . str_pad($request->id, 5, '0', STR_PAD_LEFT);
    $duration    = $request->tanggal_pinjam->diffInDays($request->tanggal_kembali_rencana);
    $itemCode    = $request->item->kode ?? 'ITM-' . str_pad($request->item->id, 4, '0', STR_PAD_LEFT);
    $statusLabel = match($request->status) {
        'pending'   => 'Menunggu Persetujuan',
        'approved'  => 'Disetujui',
        'rejected'  => 'Ditolak',
        'completed' => 'Selesai & Dikembalikan',
        'cancelled' => 'Dibatalkan',
        default     => ucfirst($request->status),
    };
    $statusBg     = match($request->status) {
        'approved'  => '#dcfce7', 'completed' => '#dbeafe',
        'rejected'  => '#fee2e2', 'cancelled' => '#f3f4f6',
        default     => '#fef9c3',
    };
    $statusColor  = match($request->status) {
        'approved'  => '#166534', 'completed' => '#1e40af',
        'rejected'  => '#991b1b', 'cancelled' => '#374151',
        default     => '#854d0e',
    };
    $statusBorder = match($request->status) {
        'approved'  => '#86efac', 'completed' => '#93c5fd',
        'rejected'  => '#fca5a5', 'cancelled' => '#d1d5db',
        default     => '#fde047',
    };
@endphp

{{-- HEADER --}}
<table style="border-bottom: 3px solid #1e40af; padding-bottom: 10px; margin-bottom: 12px;">
    <tr>
        <td style="width: 70px; vertical-align: middle;">
            @if($companyLogo)
                <img src="{{ asset($companyLogo) }}" style="height:60px; width:auto;" alt="Logo">
            @else
                <div style="width:60px; height:60px; background:#1e3a8a; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <span style="color:#fff; font-size:20pt; font-weight:900;">{{ strtoupper(mb_substr($companyName,0,2)) }}</span>
                </div>
            @endif
        </td>
        <td style="vertical-align: middle; padding-left: 14px;">
            <div style="font-size:14pt; font-weight:900; color:#1e3a8a;">{{ $companyName }}</div>
            @if($companyTagline)<div style="font-size:9pt; color:#4b5563; font-style:italic;">{{ $companyTagline }}</div>@endif
            <div style="font-size:7.5pt; color:#6b7280; margin-top:4px; line-height:1.7;">
                @if($companyAddress)📍 {{ $companyAddress }}&nbsp;@endif
                @if($companyPhone)📞 {{ $companyPhone }}&nbsp;@endif
                @if($companyEmail)✉ {{ $companyEmail }}@endif
            </div>
        </td>
        <td style="vertical-align: top; text-align: right; white-space: nowrap;">
            <div style="background:#f0f4ff; border:1.5px solid #bfdbfe; border-radius:8px; padding:8px 12px; display:inline-block;">
                <div style="font-size:7pt; color:#6b7280; text-transform:uppercase; letter-spacing:0.8px; font-weight:700;">No. Dokumen</div>
                <div style="font-size:15pt; font-weight:900; color:#1e3a8a; font-family:'Courier New',monospace;">{{ $docNumber }}</div>
                <div style="font-size:7pt; color:#9ca3af; border-top:1px solid #dde3ea; padding-top:3px; margin-top:3px;">
                    Dicetak: {{ now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- TITLE --}}
<div style="text-align:center; margin-bottom:10px;">
    <div style="font-size:13pt; font-weight:900; letter-spacing:2px; text-transform:uppercase;">BUKTI PEMINJAMAN BARANG</div>
    <div style="width:60px; height:3px; background:#1e40af; margin:4px auto;"></div>
</div>

{{-- STATUS --}}
<div style="text-align:center; margin-bottom:10px;">
    <span style="display:inline-block; padding:5px 20px; background:{{ $statusBg }}; color:{{ $statusColor }}; border:2px solid {{ $statusBorder }}; border-radius:100px; font-size:10pt; font-weight:800; letter-spacing:1.5px; text-transform:uppercase;">
        ● {{ $statusLabel }}
    </span>
</div>

{{-- INFO BOXES: Peminjam + Persetujuan --}}
<table style="margin-bottom:10px;">
    <tr>
        <td style="width:50%; vertical-align:top; padding-right:6px;">
            <div style="border:1.5px solid #bfdbfe; border-radius:8px; overflow:hidden;">
                <div style="background:#1e3a8a; padding:6px 10px;">
                    <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase;">👤 Informasi Peminjam</span>
                </div>
                <div style="padding:9px 11px; background:#f8fafc;">
                    <table>
                        <tr><td style="color:#6b7280; width:80px; padding:3px 0; font-size:9pt;">Nama</td><td style="color:#111827; font-weight:700; padding:3px 0; font-size:9pt;">: {{ $request->user->name }}</td></tr>
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">Email</td><td style="color:#374151; padding:3px 0; font-size:9pt;">: {{ $request->user->email }}</td></tr>
                        @if($request->user->whatsapp_number)
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">WhatsApp</td><td style="color:#374151; padding:3px 0; font-size:9pt;">: {{ $request->user->whatsapp_number }}</td></tr>
                        @endif
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">Tgl Pengajuan</td><td style="color:#374151; padding:3px 0; font-size:9pt;">: {{ $request->created_at->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</td></tr>
                    </table>
                </div>
            </div>
        </td>
        <td style="width:50%; vertical-align:top; padding-left:6px;">
            <div style="border:1.5px solid #bbf7d0; border-radius:8px; overflow:hidden;">
                <div style="background:#166534; padding:6px 10px;">
                    <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase;">✅ Informasi Persetujuan</span>
                </div>
                <div style="padding:9px 11px; background:#f0fdf4;">
                    <table>
                        <tr><td style="color:#6b7280; width:95px; padding:3px 0; font-size:9pt;">No. Permintaan</td><td style="color:#1e40af; font-weight:900; font-family:monospace; padding:3px 0; font-size:10pt;">: #{{ $request->id }}</td></tr>
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">Disetujui Oleh</td><td style="color:#111827; font-weight:600; padding:3px 0; font-size:9pt;">: {{ $request->approvedBy?->name ?? '—' }}</td></tr>
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">Tgl Approve</td><td style="color:#374151; padding:3px 0; font-size:9pt;">: {{ $request->approved_at ? $request->approved_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') : '—' }}</td></tr>
                        <tr><td style="color:#6b7280; padding:3px 0; font-size:9pt;">Status</td><td style="font-weight:700; color:{{ $statusColor }}; padding:3px 0; font-size:9pt;">: {{ $statusLabel }}</td></tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- DETAIL BARANG --}}
<div style="font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#1e40af; margin-bottom:5px;">📦 Detail Barang yang Dipinjam</div>
<table style="border:1.5px solid #bfdbfe; margin-bottom:10px;">
    <thead>
        <tr style="background:#1e3a8a; color:#fff;">
            <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:30%;">Nama Barang</th>
            <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:17%;">Kode Item</th>
            <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:20%;">Kategori</th>
            <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:16%;">Supplier</th>
            <th style="text-align:center; padding:8px 10px; font-size:8.5pt; font-weight:700; width:17%;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:9px 10px; font-size:10.5pt; font-weight:800; color:#111827;">{{ $request->item->nama }}</td>
            <td style="padding:9px 10px; font-size:9pt; color:#1e40af; font-family:'Courier New',monospace; font-weight:700;">{{ $itemCode }}</td>
            <td style="padding:9px 10px; font-size:9pt; color:#374151;">{{ $request->item->category?->nama ?? '—' }}</td>
            <td style="padding:9px 10px; font-size:9pt; color:#374151;">{{ $request->item->supplier?->nama ?? '—' }}</td>
            <td style="padding:9px 10px; font-size:12pt; font-weight:900; color:#1e40af; text-align:center;">{{ $request->jumlah }}<span style="font-size:8pt; font-weight:400; color:#6b7280;"> unit</span></td>
        </tr>
        <tr style="background:#f8fafc;">
            <td colspan="3" style="padding:7px 10px; font-size:9pt; color:#4b5563;">
                <strong>📍 Lokasi:</strong> {{ $request->item->location?->nama ?? '—' }}
                @if($request->item->keterangan) &nbsp;|&nbsp; <strong>📝 Ket:</strong> {{ Str::limit($request->item->keterangan, 80) }} @endif
            </td>
            <td colspan="2" style="padding:7px 10px; font-size:9pt; color:#4b5563; text-align:right;">
                @if($request->item->harga)<strong>Harga:</strong> <span style="color:#1e40af; font-weight:700;">Rp {{ number_format($request->item->harga,0,',','.') }}</span>@endif
            </td>
        </tr>
        @if($request->kondisi_pinjam)
        <tr style="background:#fffbeb;">
            <td colspan="5" style="padding:6px 10px; font-size:9pt; color:#78350f;"><strong>⚠️ Kondisi saat Dipinjam:</strong> {{ $request->kondisi_pinjam }}</td>
        </tr>
        @endif
    </tbody>
</table>

{{-- JADWAL --}}
<div style="font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#1e40af; margin-bottom:5px;">📅 Jadwal Peminjaman</div>
<table style="margin-bottom:10px;">
    <tr>
        <td style="width:25%; padding-right:5px;">
            <div style="border:1.5px solid #bfdbfe; border-radius:8px; padding:8px; text-align:center; background:#eff6ff;">
                <div style="font-size:7pt; color:#3b82f6; font-weight:700; text-transform:uppercase; margin-bottom:3px;">Tanggal Pinjam</div>
                <div style="font-size:12pt; font-weight:800; color:#1e40af;">{{ $request->tanggal_pinjam->locale('id')->isoFormat('D MMM YYYY') }}</div>
            </div>
        </td>
        <td style="width:25%; padding:0 2.5px;">
            <div style="border:1.5px solid #fca5a5; border-radius:8px; padding:8px; text-align:center; background:#fef2f2;">
                <div style="font-size:7pt; color:#dc2626; font-weight:700; text-transform:uppercase; margin-bottom:3px;">Rencana Kembali</div>
                <div style="font-size:12pt; font-weight:800; color:#dc2626;">{{ $request->tanggal_kembali_rencana->locale('id')->isoFormat('D MMM YYYY') }}</div>
            </div>
        </td>
        <td style="width:25%; padding:0 2.5px;">
            <div style="border:1.5px solid #d1d5db; border-radius:8px; padding:8px; text-align:center; background:#f9fafb;">
                <div style="font-size:7pt; color:#6b7280; font-weight:700; text-transform:uppercase; margin-bottom:3px;">Durasi</div>
                <div style="font-size:14pt; font-weight:900; color:#374151;">{{ $duration }} <span style="font-size:9pt;">Hari</span></div>
            </div>
        </td>
        <td style="width:25%; padding-left:5px;">
            @if($request->tanggal_kembali_aktual)
            <div style="border:1.5px solid #86efac; border-radius:8px; padding:8px; text-align:center; background:#f0fdf4;">
                <div style="font-size:7pt; color:#166534; font-weight:700; text-transform:uppercase; margin-bottom:3px;">✓ Dikembalikan</div>
                <div style="font-size:12pt; font-weight:800; color:#166534;">{{ $request->tanggal_kembali_aktual->locale('id')->isoFormat('D MMM YYYY') }}</div>
            </div>
            @else
            <div style="border:1.5px dashed #d1d5db; border-radius:8px; padding:8px; text-align:center; background:#fafafa;">
                <div style="font-size:7pt; color:#9ca3af; font-weight:700; text-transform:uppercase; margin-bottom:3px;">Dikembalikan</div>
                <div style="font-size:11pt; font-weight:800; color:#d1d5db;">Belum</div>
            </div>
            @endif
        </td>
    </tr>
</table>

{{-- CATATAN --}}
@if($request->keterangan || $request->admin_notes)
<table style="margin-bottom:10px;">
    <tr>
        @if($request->keterangan)
        <td style="vertical-align:top; {{ $request->admin_notes ? 'padding-right:5px;' : '' }} width:{{ $request->admin_notes ? '50%' : '100%' }};">
            <div style="border:1.5px solid #e2e8f0; border-radius:8px; overflow:hidden;">
                <div style="background:#374151; padding:5px 10px;"><span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase;">📝 Catatan Peminjam</span></div>
                <div style="padding:8px 10px; background:#fafafa;"><p style="font-size:9.5pt; color:#374151; margin:0; line-height:1.5;">{{ $request->keterangan }}</p></div>
            </div>
        </td>
        @endif
        @if($request->admin_notes)
        <td style="vertical-align:top; {{ $request->keterangan ? 'padding-left:5px;' : '' }} width:{{ $request->keterangan ? '50%' : '100%' }};">
            <div style="border:1.5px solid #fca5a5; border-radius:8px; overflow:hidden;">
                <div style="background:#dc2626; padding:5px 10px;"><span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase;">⚠️ Catatan Admin</span></div>
                <div style="padding:8px 10px; background:#fff7f7;"><p style="font-size:9.5pt; color:#374151; margin:0; line-height:1.5;">{{ $request->admin_notes }}</p></div>
            </div>
        </td>
        @endif
    </tr>
</table>
@endif

{{-- SYARAT --}}
<div style="border:1.5px solid #fde68a; border-radius:8px; padding:7px 12px; background:#fffbeb; margin-bottom:12px;">
    <div style="font-size:8pt; color:#92400e; font-weight:800; margin-bottom:4px;">⚠️ Syarat &amp; Ketentuan Peminjaman</div>
    <ol style="margin:0; padding-left:16px; font-size:8.5pt; color:#78350f; line-height:1.7;">
        <li>Barang harus dikembalikan dalam kondisi baik sesuai tanggal yang telah disepakati.</li>
        <li>Kerusakan atau kehilangan barang menjadi tanggung jawab penuh peminjam.</li>
        <li>Keterlambatan pengembalian akan dikenakan sanksi sesuai kebijakan perusahaan.</li>
        <li>Peminjam wajib merawat barang pinjaman dan melaporkan kerusakan sesegera mungkin.</li>
    </ol>
</div>

{{-- TANDA TANGAN --}}
<div style="border-top:2px solid #1e40af; padding-top:10px; margin-bottom:8px;">
    <table>
        <tr>
            <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top;">
                <div style="font-size:9pt; font-weight:700; color:#1e40af;">Peminjam</div>
                <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Menyatakan telah menerima barang</div>
                <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                    <div style="font-size:9pt; font-weight:700; color:#111827;">{{ $request->user->name }}</div>
                    <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : ___________________</div>
                </div>
            </td>
            <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top; border-left:1px dashed #e2e8f0; border-right:1px dashed #e2e8f0;">
                <div style="font-size:9pt; font-weight:700; color:#1e40af;">Petugas Gudang</div>
                <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Yang Menyerahkan Barang</div>
                <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                    <div style="font-size:9pt; font-weight:700; color:#111827;">&nbsp;</div>
                    <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : ___________________</div>
                </div>
            </td>
            <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top;">
                <div style="font-size:9pt; font-weight:700; color:#1e40af;">Mengetahui / Menyetujui</div>
                <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Persetujuan Manajemen</div>
                <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                    <div style="font-size:9pt; font-weight:700; color:#111827;">{{ $request->approvedBy?->name ?? '____________________' }}</div>
                    <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : {{ $request->approved_at ? $request->approved_at->locale('id')->isoFormat('D MMM YYYY') : '___________________' }}</div>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<div style="text-align:center; border-top:1px solid #e2e8f0; padding-top:8px; margin-top:10px; font-size:7.5pt; color:#9ca3af; line-height:1.6;">
    Dokumen ini diterbitkan secara otomatis oleh sistem <strong style="color:#6b7280;">{{ $companyName }}</strong>
    &nbsp;·&nbsp; Ref: <strong style="color:#6b7280; font-family:monospace;">{{ $docNumber }}</strong>
    &nbsp;·&nbsp; {{ now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    <br><span style="font-size:7pt; color:#d1d5db;">Dokumen ini sah tanpa tanda tangan basah apabila diverifikasi secara digital melalui sistem {{ $companyName }}.</span>
</div>

<script>
    window.onload = function() {
        setTimeout(function() { window.print(); }, 500);
    };
</script>
</body>
</html>
