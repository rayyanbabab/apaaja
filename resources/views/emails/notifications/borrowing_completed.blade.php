@extends('emails.layout', [
    'headerColorFrom'  => '#2563eb',
    'headerColorTo'    => '#3b82f6',
    'heroEmoji'        => '🎉',
    'heroTitle'        => 'Peminjaman Selesai',
    'heroSubtitle'     => 'Terima kasih telah mengembalikan barang tepat waktu.',
    'iconBg'           => '#eff6ff',
    'msgBoxBg'         => '#eff6ff',
    'msgBoxBorder'     => '#3b82f6',
    'ctaColor'         => '#2563eb',
    'emailTitle'       => 'Peminjaman Selesai',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Peminjaman barang Anda telah resmi <strong style="color:#2563eb;">diselesaikan</strong>. Terima kasih telah merawat dan mengembalikan barang dengan baik.
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
        <span class="info-label">Tanggal Selesai</span>
        <span class="info-value">{{ now()->format('d M Y') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color:#2563eb;">✅ Selesai</span>
    </div>
</div>

<div class="message-box">
    Barang telah berhasil dikembalikan dan stok sudah diperbarui. Anda dapat melakukan peminjaman baru kapan saja.
</div>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Dashboard →</a>
</div>
@endsection
