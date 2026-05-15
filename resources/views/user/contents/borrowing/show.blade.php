@extends('user.layouts.dashboard-user')

@section('title', 'Borrowing Request Details')

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

@push('styles')
<style>
/* Sembunyikan receipt di layar biasa */
#print-receipt { display: none; }
</style>
@endpush

@section('user')

{{-- ══════════════════════════════════════════════════════════
     PRINT-ONLY: STRUK / RECEIPT PROFESIONAL
══════════════════════════════════════════════════════════ --}}
<div id="print-receipt" style="font-family:'Arial',Helvetica,sans-serif; font-size:10pt; color:#1a1a1a; background:#fff; padding:0; margin:0;">

    {{-- ══════════════════════════════════════
         HEADER: Logo + Company + Doc Number
    ══════════════════════════════════════ --}}
    <table style="width:100%; border-collapse:collapse; padding-bottom:10px; border-bottom:3px solid #1e40af; margin-bottom:12px;">
        <tr>
            <td style="vertical-align:middle; width:70px; padding:0;">
                @if($companyLogo)
                    <img src="{{ asset($companyLogo) }}" style="height:60px; width:auto; object-fit:contain;" alt="Logo">
                @else
                    <div style="width:60px; height:60px; background:linear-gradient(135deg,#1e3a8a,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <span style="color:#fff; font-size:20pt; font-weight:900; letter-spacing:-1px;">{{ strtoupper(mb_substr($companyName,0,2)) }}</span>
                    </div>
                @endif
            </td>
            <td style="vertical-align:middle; padding-left:14px;">
                <div style="font-size:14pt; font-weight:900; color:#1e3a8a; letter-spacing:-0.5px; line-height:1.1;">{{ $companyName }}</div>
                @if($companyTagline)
                    <div style="font-size:9pt; color:#4b5563; margin-top:2px; font-style:italic;">{{ $companyTagline }}</div>
                @endif
                <div style="font-size:7.5pt; color:#6b7280; margin-top:4px; line-height:1.7;">
                    @if($companyAddress)<span>📍 {{ $companyAddress }}</span>@endif
                    @if($companyPhone)<span style="margin-left:8px;">📞 {{ $companyPhone }}</span>@endif
                    @if($companyEmail)<span style="margin-left:8px;">✉ {{ $companyEmail }}</span>@endif
                </div>
            </td>
            <td style="vertical-align:top; text-align:right; white-space:nowrap; padding:0;">
                <div style="background:#f0f4ff; border:1.5px solid #bfdbfe; border-radius:8px; padding:8px 12px; display:inline-block;">
                    <div style="font-size:7pt; color:#6b7280; text-transform:uppercase; letter-spacing:0.8px; font-weight:700;">No. Dokumen</div>
                    <div style="font-size:15pt; font-weight:900; color:#1e3a8a; font-family:'Courier New',monospace; letter-spacing:1px; line-height:1.2;">{{ $docNumber }}</div>
                    <div style="font-size:7pt; color:#9ca3af; margin-top:3px; border-top:1px solid #dde3ea; padding-top:3px;">
                        Dicetak: {{ now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY, HH:mm') }} WIB
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ══════ DOCUMENT TITLE ══════ --}}
    <div style="text-align:center; margin-bottom:10px; position:relative;">
        <div style="font-size:13pt; font-weight:900; letter-spacing:2px; text-transform:uppercase; color:#111827;">BUKTI PEMINJAMAN BARANG</div>
        <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:4px;">
            <div style="flex:1; height:1.5px; background:#e2e8f0;"></div>
            <div style="width:48px; height:3px; background:linear-gradient(90deg,#1e40af,#3b82f6); border-radius:2px;"></div>
            <div style="flex:1; height:1.5px; background:#e2e8f0;"></div>
        </div>
    </div>

    {{-- ══════ STATUS BADGE ══════ --}}
    <div style="text-align:center; margin-bottom:10px;">
        <span style="display:inline-block; padding:5px 20px; background:{{ $statusBg }}; color:{{ $statusColor }}; border:2px solid {{ $statusBorder }}; border-radius:100px; font-size:10pt; font-weight:800; letter-spacing:1.5px; text-transform:uppercase;">
            ● {{ $statusLabel }}
        </span>
    </div>

    {{-- ══════ TWO COLUMN: Peminjam + Persetujuan ══════ --}}
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            {{-- PEMINJAM --}}
            <td style="width:50%; vertical-align:top; padding-right:6px;">
                <div style="border:1.5px solid #bfdbfe; border-radius:8px; overflow:hidden;">
                    <div style="background:#1e3a8a; padding:6px 10px;">
                        <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.5px;">👤 Informasi Peminjam</span>
                    </div>
                    <div style="padding:9px 11px; background:#f8fafc;">
                        <table style="width:100%; border-collapse:collapse; font-size:9pt;">
                            <tr>
                                <td style="color:#6b7280; width:80px; padding:3px 0; vertical-align:top;">Nama</td>
                                <td style="color:#111827; font-weight:700; padding:3px 0;">: {{ $request->user->name }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">Email</td>
                                <td style="color:#374151; padding:3px 0;">: {{ $request->user->email }}</td>
                            </tr>
                            @if($request->user->whatsapp_number)
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">WhatsApp</td>
                                <td style="color:#374151; padding:3px 0;">: {{ $request->user->whatsapp_number }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">Tgl Pengajuan</td>
                                <td style="color:#374151; padding:3px 0;">: {{ $request->created_at->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            {{-- PERSETUJUAN --}}
            <td style="width:50%; vertical-align:top; padding-left:6px;">
                <div style="border:1.5px solid #bbf7d0; border-radius:8px; overflow:hidden;">
                    <div style="background:#166534; padding:6px 10px;">
                        <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.5px;">✅ Informasi Persetujuan</span>
                    </div>
                    <div style="padding:9px 11px; background:#f0fdf4;">
                        <table style="width:100%; border-collapse:collapse; font-size:9pt;">
                            <tr>
                                <td style="color:#6b7280; width:95px; padding:3px 0; vertical-align:top;">No. Permintaan</td>
                                <td style="color:#1e40af; font-weight:900; font-family:monospace; padding:3px 0; font-size:10pt;">: #{{ $request->id }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">Disetujui Oleh</td>
                                <td style="color:#111827; font-weight:600; padding:3px 0;">: {{ $request->approvedBy?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">Tgl Approve</td>
                                <td style="color:#374151; padding:3px 0;">: {{ $request->approved_at ? $request->approved_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') : '—' }}</td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280; padding:3px 0; vertical-align:top;">Status</td>
                                <td style="padding:3px 0;"><span style="font-weight:700; color:{{ $statusColor }};">: {{ $statusLabel }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ══════ DETAIL BARANG ══════ --}}
    <div style="margin-bottom:10px;">
        <div style="font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#1e40af; margin-bottom:5px; display:flex; align-items:center; gap:6px;">
            <span>📦</span> <span>Detail Barang yang Dipinjam</span>
        </div>
        <table style="width:100%; border-collapse:collapse; border:1.5px solid #bfdbfe; border-radius:8px; overflow:hidden;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1e3a8a,#2563eb); color:#fff;">
                    <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:30%;">Nama Barang</th>
                    <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:17%;">Kode Item</th>
                    <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:20%;">Kategori</th>
                    <th style="text-align:left; padding:8px 10px; font-size:8.5pt; font-weight:700; width:16%;">Supplier</th>
                    <th style="text-align:center; padding:8px 10px; font-size:8.5pt; font-weight:700; width:17%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background:#fff;">
                    <td style="padding:9px 10px; font-size:10.5pt; font-weight:800; color:#111827; border-bottom:1px solid #f1f5f9;">{{ $request->item->nama }}</td>
                    <td style="padding:9px 10px; font-size:9pt; color:#1e40af; font-family:'Courier New',monospace; font-weight:700; border-bottom:1px solid #f1f5f9;">{{ $itemCode }}</td>
                    <td style="padding:9px 10px; font-size:9pt; color:#374151; border-bottom:1px solid #f1f5f9;">{{ $request->item->category?->nama ?? '—' }}</td>
                    <td style="padding:9px 10px; font-size:9pt; color:#374151; border-bottom:1px solid #f1f5f9;">{{ $request->item->supplier?->nama ?? '—' }}</td>
                    <td style="padding:9px 10px; font-size:12pt; font-weight:900; color:#1e40af; text-align:center; border-bottom:1px solid #f1f5f9;">{{ $request->jumlah }}<span style="font-size:8pt; font-weight:400; color:#6b7280;"> unit</span></td>
                </tr>
                {{-- Lokasi & Keterangan --}}
                <tr style="background:#f8fafc;">
                    <td colspan="3" style="padding:7px 10px; font-size:9pt; color:#4b5563;">
                        <span style="font-weight:700;">📍 Lokasi:</span>
                        {{ $request->item->location?->nama ?? '—' }}
                        @if($request->item->keterangan)
                            &nbsp;|&nbsp; <span style="font-weight:700;">📝 Keterangan:</span> {{ Str::limit($request->item->keterangan, 80) }}
                        @endif
                    </td>
                    <td colspan="2" style="padding:7px 10px; font-size:9pt; color:#4b5563; text-align:right;">
                        @if($request->item->harga)
                            <span style="font-weight:700;">Harga Satuan:</span>
                            <span style="color:#1e40af; font-weight:700;">Rp {{ number_format($request->item->harga, 0, ',', '.') }}</span>
                        @endif
                    </td>
                </tr>
                @if($request->kondisi_pinjam)
                <tr style="background:#fffbeb;">
                    <td colspan="5" style="padding:6px 10px; font-size:9pt; color:#78350f;">
                        <span style="font-weight:700;">⚠️ Kondisi Barang saat Dipinjam:</span> {{ $request->kondisi_pinjam }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- ══════ JADWAL PEMINJAMAN ══════ --}}
    <div style="margin-bottom:10px;">
        <div style="font-size:8pt; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#1e40af; margin-bottom:5px;">📅 Jadwal Peminjaman</div>
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:25%; padding-right:5px; vertical-align:top;">
                    <div style="border:1.5px solid #bfdbfe; border-radius:8px; padding:8px; text-align:center; background:#eff6ff;">
                        <div style="font-size:7pt; color:#3b82f6; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px;">Tanggal Pinjam</div>
                        <div style="font-size:11pt; font-weight:800; color:#1e40af;">{{ $request->tanggal_pinjam->locale('id')->isoFormat('D MMM') }}</div>
                        <div style="font-size:9pt; color:#1e40af; font-weight:600;">{{ $request->tanggal_pinjam->locale('id')->isoFormat('YYYY') }}</div>
                    </div>
                </td>
                <td style="width:25%; padding:0 2.5px; vertical-align:top;">
                    <div style="border:1.5px solid #fca5a5; border-radius:8px; padding:8px; text-align:center; background:#fef2f2;">
                        <div style="font-size:7pt; color:#dc2626; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px;">Rencana Kembali</div>
                        <div style="font-size:11pt; font-weight:800; color:#dc2626;">{{ $request->tanggal_kembali_rencana->locale('id')->isoFormat('D MMM') }}</div>
                        <div style="font-size:9pt; color:#dc2626; font-weight:600;">{{ $request->tanggal_kembali_rencana->locale('id')->isoFormat('YYYY') }}</div>
                    </div>
                </td>
                <td style="width:25%; padding:0 2.5px; vertical-align:top;">
                    <div style="border:1.5px solid #d1d5db; border-radius:8px; padding:8px; text-align:center; background:#f9fafb;">
                        <div style="font-size:7pt; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px;">Durasi</div>
                        <div style="font-size:14pt; font-weight:900; color:#374151;">{{ $duration }}</div>
                        <div style="font-size:9pt; color:#6b7280; font-weight:600;">Hari</div>
                    </div>
                </td>
                <td style="width:25%; padding-left:5px; vertical-align:top;">
                    @if($request->tanggal_kembali_aktual)
                    <div style="border:1.5px solid #86efac; border-radius:8px; padding:8px; text-align:center; background:#f0fdf4;">
                        <div style="font-size:7pt; color:#166534; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px;">✓ Dikembalikan</div>
                        <div style="font-size:11pt; font-weight:800; color:#166534;">{{ $request->tanggal_kembali_aktual->locale('id')->isoFormat('D MMM') }}</div>
                        <div style="font-size:9pt; color:#166534; font-weight:600;">{{ $request->tanggal_kembali_aktual->locale('id')->isoFormat('YYYY') }}</div>
                    </div>
                    @else
                    <div style="border:1.5px dashed #d1d5db; border-radius:8px; padding:8px; text-align:center; background:#fafafa;">
                        <div style="font-size:7pt; color:#9ca3af; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:3px;">Dikembalikan</div>
                        <div style="font-size:10pt; font-weight:800; color:#d1d5db;">—</div>
                        <div style="font-size:8pt; color:#d1d5db;">Belum</div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ══════ CATATAN ══════ --}}
    @if($request->keterangan || $request->admin_notes)
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            @if($request->keterangan)
            <td style="vertical-align:top; {{ $request->admin_notes ? 'padding-right:5px;' : '' }} width:{{ $request->admin_notes ? '50%' : '100%' }};">
                <div style="border:1.5px solid #e2e8f0; border-radius:8px; overflow:hidden;">
                    <div style="background:#374151; padding:5px 10px;">
                        <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.5px;">📝 Catatan Peminjam</span>
                    </div>
                    <div style="padding:8px 10px; background:#fafafa;">
                        <p style="font-size:9.5pt; color:#374151; margin:0; line-height:1.5;">{{ $request->keterangan }}</p>
                    </div>
                </div>
            </td>
            @endif
            @if($request->admin_notes)
            <td style="vertical-align:top; {{ $request->keterangan ? 'padding-left:5px;' : '' }} width:{{ $request->keterangan ? '50%' : '100%' }};">
                <div style="border:1.5px solid #fca5a5; border-radius:8px; overflow:hidden;">
                    <div style="background:#dc2626; padding:5px 10px;">
                        <span style="font-size:8pt; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:0.5px;">⚠️ Catatan Admin</span>
                    </div>
                    <div style="padding:8px 10px; background:#fff7f7;">
                        <p style="font-size:9.5pt; color:#374151; margin:0; line-height:1.5;">{{ $request->admin_notes }}</p>
                    </div>
                </div>
            </td>
            @endif
        </tr>
    </table>
    @endif

    {{-- ══════ SYARAT & KETENTUAN ══════ --}}
    <div style="border:1.5px solid #fde68a; border-radius:8px; padding:7px 12px; background:#fffbeb; margin-bottom:12px;">
        <div style="font-size:8pt; color:#92400e; font-weight:800; margin-bottom:4px;">⚠️ Syarat & Ketentuan Peminjaman</div>
        <ol style="margin:0; padding-left:16px; font-size:8.5pt; color:#78350f; line-height:1.7;">
            <li>Barang harus dikembalikan dalam kondisi baik dan berfungsi sesuai tanggal yang telah disepakati.</li>
            <li>Kerusakan atau kehilangan barang selama masa pinjam menjadi tanggung jawab penuh peminjam.</li>
            <li>Keterlambatan pengembalian akan dikenakan sanksi sesuai kebijakan yang berlaku di perusahaan.</li>
            <li>Peminjam wajib merawat barang pinjaman dan melaporkan kerusakan sesegera mungkin.</li>
        </ol>
    </div>

    {{-- ══════ TANDA TANGAN ══════ --}}
    <div style="border-top:2px solid #1e40af; padding-top:10px; margin-bottom:4px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top;">
                    <div style="font-size:9pt; font-weight:700; color:#1e40af; margin-bottom:2px;">Peminjam</div>
                    <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Menyatakan telah menerima barang</div>
                    <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                        <div style="font-size:9pt; font-weight:700; color:#111827;">{{ $request->user->name }}</div>
                        <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : ___________________</div>
                    </div>
                </td>
                <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top; border-left:1px dashed #e2e8f0; border-right:1px dashed #e2e8f0;">
                    <div style="font-size:9pt; font-weight:700; color:#1e40af; margin-bottom:2px;">Petugas Gudang</div>
                    <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Yang Menyerahkan Barang</div>
                    <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                        <div style="font-size:9pt; font-weight:700; color:#111827;">&nbsp;</div>
                        <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : ___________________</div>
                    </div>
                </td>
                <td style="width:33.33%; text-align:center; padding:0 8px; vertical-align:top;">
                    <div style="font-size:9pt; font-weight:700; color:#1e40af; margin-bottom:2px;">Mengetahui / Menyetujui</div>
                    <div style="font-size:7.5pt; color:#9ca3af; margin-bottom:36px;">Persetujuan Manajemen</div>
                    <div style="border-top:1.5px solid #374151; margin:0 10px; padding-top:5px;">
                        <div style="font-size:9pt; font-weight:700; color:#111827;">{{ $request->approvedBy?->name ?? '____________________' }}</div>
                        <div style="font-size:7.5pt; color:#6b7280; margin-top:2px;">Tgl : {{ $request->approved_at ? $request->approved_at->locale('id')->isoFormat('D MMM YYYY') : '___________________' }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ══════ FOOTER ══════ --}}
    <div style="text-align:center; border-top:1px solid #e2e8f0; padding-top:8px; margin-top:10px;">
        <div style="font-size:7.5pt; color:#9ca3af; line-height:1.6;">
            Dokumen ini diterbitkan secara otomatis oleh sistem <strong style="color:#6b7280;">{{ $companyName }}</strong>
            &nbsp;·&nbsp; Ref: <strong style="color:#6b7280; font-family:monospace;">{{ $docNumber }}</strong>
            &nbsp;·&nbsp; {{ now()->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </div>
        <div style="font-size:7pt; color:#d1d5db; margin-top:2px;">
            Dokumen ini sah tanpa tanda tangan basah apabila diverifikasi secara digital melalui sistem {{ $companyName }}.
        </div>
    </div>

</div>


{{-- ══════════════════════════════════════════════════════════
     SCREEN VIEW — tampilan asli
══════════════════════════════════════════════════════════ --}}


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.borrowing.my-requests') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Borrowing Request Details</h1>
                <p class="text-gray-600 mt-2">Complete information about your borrowing request</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Request Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900">Borrowing Information</h3>
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <svg class="w-4 h-4 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Pending Approval
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Approved
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rejected
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Completed
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelled
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ ucfirst($request->status) }}
                                </span>
                        @endswitch
                    </div>
                </div>
                <div class="p-6">
                    <!-- Item Info -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $request->item->nama }}</h4>
                            <!-- Item Code and Category -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if($request->item->id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $itemCode }}
                                    </span>
                                @endif
                                @if($request->item->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $request->item->category->nama }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    <span class="font-medium text-gray-900">{{ $request->jumlah }} unit</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span>Available stock: {{ $request->item->stok_peminjaman }} unit</span>
                                </div>
                            </div>
                            @if($request->item->keterangan)
                                <p class="mt-3 text-sm text-gray-600">{{ $request->item->keterangan }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Request Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</span>
                            <p class="mt-1 text-sm font-semibold text-gray-900">#{{ $request->id }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_pinjam->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Planned Return</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_kembali_rencana->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_pinjam->diffInDays($request->tanggal_kembali_rencana) }} days</p>
                        </div>
                        @if($request->tanggal_kembali_aktual)
                        <div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Actual Return</span>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_kembali_aktual->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Borrowing Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Request Submitted -->
                            <li>
                                <div class="relative pb-8">
                                    @if($request->approved_at || $request->status !== 'pending')
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                                <p class="mt-0.5 text-xs text-gray-500">Borrowing request has been sent</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->created_at->toIso8601String() }}">{{ $request->created_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Approved/Rejected -->
                            @if($request->approved_at)
                            <li>
                                <div class="relative pb-8">
                                    @if($request->status === 'completed')
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($request->status === 'approved' || $request->status === 'completed')
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                            @elseif($request->status === 'rejected')
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                @if($request->status === 'approved' || $request->status === 'completed')
                                                    <p class="text-sm font-medium text-gray-900">Request Approved</p>
                                                    <p class="mt-0.5 text-xs text-gray-500">Borrowing request has been approved by admin</p>
                                                @elseif($request->status === 'rejected')
                                                    <p class="text-sm font-medium text-gray-900">Request Rejected</p>
                                                    <p class="mt-0.5 text-xs text-gray-500">Borrowing request rejected by admin</p>
                                                @endif
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->approved_at->toIso8601String() }}">{{ $request->approved_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <!-- Completed -->
                            @if($request->status === 'completed')
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Borrowing Completed</p>
                                                <p class="mt-0.5 text-xs text-gray-500">Item has been returned and stock updated</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->completed_at->toIso8601String() }}">{{ $request->completed_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <!-- Cancelled -->
                            @if($request->status === 'cancelled')
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Request Cancelled</p>
                                                <p class="mt-0.5 text-xs text-gray-500">Borrowing request cancelled</p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time datetime="{{ $request->updated_at->toIso8601String() }}">{{ $request->updated_at->locale('id')->isoFormat('D MMM YYYY') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Notes Section -->
            @if($request->keterangan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Notes
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $request->keterangan }}</p>
                </div>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($request->admin_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Admin Notes
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $request->admin_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($request->status === 'pending')
                        <form id="cancel-request-form" action="{{ route('user.borrowing.cancel', $request->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                onclick="openModal('modal-cancel-request')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Cancel Request
                        </button>
                        @endif
                        
                        <a href="/user/borrowing/show/{{ $request->id }}/print" target="_blank"
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Details
                        </a>
                        
                        <a href="{{ route('user.borrowing.my-requests') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printReceipt() {
    // Ambil konten receipt
    var content = document.getElementById('print-receipt').innerHTML;

    // Buka window baru khusus untuk print
    var pw = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
    pw.document.write(
        '<!DOCTYPE html>'
        + '<html lang="id">'
        + '<head>'
        + '<meta charset="UTF-8">'
        + '<title>Bukti Peminjaman</title>'
        + '<style>'
        + '  *, *::before, *::after { box-sizing: border-box; }'
        + '  body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 10mm; background:#fff; color:#1a1a1a; }'
        + '  @page { size: A4 portrait; margin: 8mm 10mm; }'
        + '  @media print { body { padding: 0; } }'
        + '  table { border-collapse: collapse; }'
        + '  img { max-width: 100%; }'
        + '</style>'
        + '</head>'
        + '<body>' + content + '</body>'
        + '</html>'
    );
    pw.document.close();
    pw.focus();

    // Tunggu konten selesai dimuat lalu print
    pw.onload = function() {
        setTimeout(function() {
            pw.print();
            pw.close();
        }, 400);
    };

    // Fallback jika onload tidak trigger
    setTimeout(function() {
        if (!pw.closed) {
            pw.print();
            pw.close();
        }
    }, 1500);
}
</script>

@if($request->status === 'pending')
<x-popup id="modal-cancel-request" title="Cancel Request"
    message="Are you sure you want to cancel this borrowing request?"
    formId="cancel-request-form"
    confirmText="Cancel Request"
    confirmClass="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm"
    cancelText="Back" />
@endif
@endpush

@endsection