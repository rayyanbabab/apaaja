@extends('emails.layout', [
    'headerColorFrom'  => '#dc2626',
    'headerColorTo'    => '#ef4444',
    'heroEmoji'        => '❌',
    'heroTitle'        => 'Peminjaman Ditolak',
    'heroSubtitle'     => 'Permintaan peminjaman Anda tidak dapat diproses.',
    'iconBg'           => '#fef2f2',
    'msgBoxBg'         => '#fef2f2',
    'msgBoxBorder'     => '#ef4444',
    'ctaColor'         => '#dc2626',
    'emailTitle'       => 'Peminjaman Ditolak',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Kami menyampaikan bahwa permintaan peminjaman barang Anda <strong style="color:#dc2626;">tidak disetujui</strong>. Berikut detailnya:
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
        <span class="info-label">Status</span>
        <span class="info-value" style="color:#dc2626;">❌ Ditolak</span>
    </div>
</div>

@if($borrowing->admin_notes)
<div class="message-box">
    <strong>📝 Alasan Penolakan:</strong><br>
    {{ $borrowing->admin_notes }}
</div>
@else
<div class="message-box">
    Tidak ada catatan khusus dari admin. Silakan hubungi admin untuk informasi lebih lanjut.
</div>
@endif

<p style="color:#6b7280; font-size:13px; margin-bottom:0;">
    Anda dapat mengajukan permintaan peminjaman baru dengan barang atau tanggal yang berbeda.
</p>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Riwayat Peminjaman →</a>
</div>
@endsection
