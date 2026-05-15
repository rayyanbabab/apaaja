@extends('emails.layout', [
    'headerColorFrom'  => '#16a34a',
    'headerColorTo'    => '#22c55e',
    'heroEmoji'        => '✅',
    'heroTitle'        => 'Peminjaman Disetujui!',
    'heroSubtitle'     => 'Permintaan peminjaman Anda telah disetujui oleh admin.',
    'iconBg'           => '#dcfce7',
    'msgBoxBg'         => '#f0fdf4',
    'msgBoxBorder'     => '#22c55e',
    'ctaColor'         => '#16a34a',
    'emailTitle'       => 'Peminjaman Disetujui',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Kabar baik! Permintaan peminjaman barang Anda telah <strong style="color:#16a34a;">disetujui</strong>. Berikut detail peminjaman Anda:
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Barang</span>
        <span class="info-value">{{ $borrowing->item->nama ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Jumlah</span>
        <span class="info-value">{{ $borrowing->jumlah ?? 1 }} unit</span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal Pinjam</span>
        <span class="info-value">{{ optional($borrowing->tanggal_pinjam)->format('d M Y') ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal Kembali</span>
        <span class="info-value">{{ optional($borrowing->tanggal_kembali_rencana)->format('d M Y') ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color:#16a34a;">✅ Disetujui</span>
    </div>
</div>

@if($borrowing->admin_notes)
<div class="message-box">
    <strong>📝 Catatan Admin:</strong><br>
    {{ $borrowing->admin_notes }}
</div>
@endif

<p style="color:#6b7280; font-size:13px; margin-bottom:0;">
    Silakan ambil barang sesuai jadwal yang telah ditentukan. Pastikan mengembalikan barang tepat waktu.
</p>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Detail Peminjaman →</a>
</div>
@endsection
