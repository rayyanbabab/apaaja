@extends('emails.layout', [
    'headerColorFrom'  => '#b91c1c',
    'headerColorTo'    => '#dc2626',
    'heroEmoji'        => '🚨',
    'heroTitle'        => 'Barang Belum Dikembalikan!',
    'heroSubtitle'     => 'Anda melewati batas waktu pengembalian. Segera kembalikan.',
    'iconBg'           => '#fef2f2',
    'msgBoxBg'         => '#fef2f2',
    'msgBoxBorder'     => '#dc2626',
    'ctaColor'         => '#b91c1c',
    'emailTitle'       => 'Peringatan: Barang Terlambat Dikembalikan',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Kami mengingatkan bahwa Anda masih memiliki barang yang <strong style="color:#dc2626;">belum dikembalikan</strong> melebihi batas waktu yang ditentukan.
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
        <span class="info-label">Batas Pengembalian</span>
        <span class="info-value">{{ optional($borrowing->tanggal_kembali_rencana)->format('d M Y') ?? '-' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Keterlambatan</span>
        <span class="info-value" style="color:#dc2626; font-weight:700;">{{ $daysOverdue }} hari</span>
    </div>
</div>

<div class="message-box" style="background:#fef2f2; border-left-color:#dc2626;">
    <strong>⚠️ Harap Diperhatikan:</strong><br>
    Keterlambatan pengembalian barang dapat mempengaruhi hak peminjaman Anda ke depannya. Segera kembalikan barang ke tempat yang telah ditentukan.
</div>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Detail Peminjaman →</a>
</div>
@endsection
