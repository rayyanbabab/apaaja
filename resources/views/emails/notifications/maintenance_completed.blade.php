@extends('emails.layout', [
    'headerColorFrom'  => '#0369a1',
    'headerColorTo'    => '#0ea5e9',
    'heroEmoji'        => '🏁',
    'heroTitle'        => 'Maintenance Selesai',
    'heroSubtitle'     => 'Operator telah memperbarui status servis barang.',
    'iconBg'           => '#f0f9ff',
    'msgBoxBg'         => '#f0f9ff',
    'msgBoxBorder'     => '#0ea5e9',
    'ctaColor'         => '#0369a1',
    'emailTitle'       => 'Maintenance Selesai',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>

@if($action === 'completed')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Operator <strong>{{ $operatorName }}</strong> telah <strong style="color:#0369a1;">menyelesaikan servis</strong> barang berikut. Stok barang telah dikembalikan ke inventaris.
</p>
@else
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Operator <strong>{{ $operatorName }}</strong> menyatakan barang berikut <strong style="color:#dc2626;">tidak dapat diperbaiki (scrap)</strong>.
</p>
@endif

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Barang</span>
        <span class="info-value">{{ $maintenance->item->nama ?? 'Barang tidak dikenal' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Jumlah</span>
        <span class="info-value">{{ $maintenance->jumlah }} unit</span>
    </div>
    <div class="info-row">
        <span class="info-label">Operator</span>
        <span class="info-value">{{ $operatorName }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Hasil</span>
        <span class="info-value" style="color:{{ $action === 'completed' ? '#0369a1' : '#dc2626' }}; font-weight:700;">
            {{ $action === 'completed' ? '✅ Selesai Diservis' : '🗑️ Di-scrap' }}
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal Selesai</span>
        <span class="info-value">{{ now()->format('d M Y, H:i') }}</span>
    </div>
</div>

@if($action === 'completed')
<div class="message-box">
    <strong>✅ Stok Dipulihkan:</strong><br>
    Barang telah berhasil diperbaiki dan stok sudah dikembalikan ke inventaris secara otomatis.
</div>
@else
<div class="message-box" style="background:#fef2f2; border-left-color:#dc2626;">
    <strong>⚠️ Catatan:</strong><br>
    Barang dinyatakan tidak dapat diperbaiki dan telah di-scrap. Stok tidak akan dikembalikan ke inventaris.
</div>
@endif

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Detail Maintenance →</a>
</div>
@endsection
