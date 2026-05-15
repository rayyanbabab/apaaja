@extends('emails.layout', [
    'headerColorFrom'  => '#ea580c',
    'headerColorTo'    => '#f97316',
    'heroEmoji'        => '⏰',
    'heroTitle'        => 'Permintaan Peminjaman Kedaluwarsa',
    'heroSubtitle'     => 'Permintaan Anda dibatalkan secara otomatis oleh sistem.',
    'iconBg'           => '#fff7ed',
    'msgBoxBg'         => '#fff7ed',
    'msgBoxBorder'     => '#f97316',
    'ctaColor'         => '#ea580c',
    'emailTitle'       => 'Peminjaman Kedaluwarsa',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Permintaan peminjaman barang Anda telah <strong style="color:#ea580c;">dibatalkan secara otomatis</strong> karena tanggal pinjam telah terlewat tanpa mendapat persetujuan admin.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Barang</span>
        <span class="info-value">{{ $borrowing->item->nama ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal Pinjam (Rencana)</span>
        <span class="info-value">{{ optional($borrowing->tanggal_pinjam)->format('d M Y') ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Hari Terlambat</span>
        <span class="info-value" style="color:#ea580c;">{{ $daysPast }} hari</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color:#ea580c;">🚫 Dibatalkan</span>
    </div>
</div>

<div class="message-box">
    <strong>ℹ️ Informasi:</strong><br>
    Sistem secara otomatis membatalkan permintaan peminjaman yang tanggal pinjamnya telah terlewat lebih dari 1 hari tanpa persetujuan. Silakan ajukan permintaan baru jika masih membutuhkan barang tersebut.
</div>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Ajukan Peminjaman Baru →</a>
</div>
@endsection
