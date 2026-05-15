@extends('emails.layout', [
    'headerColorFrom'  => '#b45309',
    'headerColorTo'    => '#f59e0b',
    'heroEmoji'        => '⚠️',
    'heroTitle'        => 'Stok Barang Hampir Habis!',
    'heroSubtitle'     => 'Diperlukan tindakan segera untuk pengadaan barang.',
    'iconBg'           => '#fffbeb',
    'msgBoxBg'         => '#fffbeb',
    'msgBoxBorder'     => '#f59e0b',
    'ctaColor'         => '#b45309',
    'emailTitle'       => 'Peringatan Stok Minimum',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Sistem mendeteksi bahwa stok barang berikut telah mencapai atau melewati <strong style="color:#b45309;">batas minimum</strong> yang ditetapkan. Segera lakukan pengadaan.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Nama Barang</span>
        <span class="info-value">{{ $item->nama }}</span>
    </div>
    @if($item->kode ?? null)
    <div class="info-row">
        <span class="info-label">Kode Barang</span>
        <span class="info-value">{{ $item->kode }}</span>
    </div>
    @endif
    @if($item->category ?? null)
    <div class="info-row">
        <span class="info-label">Kategori</span>
        <span class="info-value">{{ $item->category->nama ?? '-' }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Stok Saat Ini</span>
        <span class="info-value" style="color:#dc2626; font-weight:700;">{{ $item->stok_total }} unit</span>
    </div>
    <div class="info-row">
        <span class="info-label">Batas Minimum</span>
        <span class="info-value">{{ $threshold }} unit</span>
    </div>
    @if($item->location ?? null)
    <div class="info-row">
        <span class="info-label">Lokasi</span>
        <span class="info-value">{{ $item->location->nama ?? '-' }}</span>
    </div>
    @endif
</div>

<div class="message-box">
    <strong>📦 Tindakan Diperlukan:</strong><br>
    Stok saat ini ({{ $item->stok_total }} unit) telah mencapai batas peringatan ({{ $threshold }} unit). Segera lakukan pemesanan atau pengadaan ulang untuk menghindari kehabisan stok.
</div>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Kelola Inventaris →</a>
</div>
@endsection
