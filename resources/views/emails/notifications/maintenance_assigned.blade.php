@extends('emails.layout', [
    'headerColorFrom'  => '#7c3aed',
    'headerColorTo'    => '#8b5cf6',
    'heroEmoji'        => '🔧',
    'heroTitle'        => 'Tugas Servis Baru',
    'heroSubtitle'     => 'Admin telah mengirim barang untuk diservis. Segera tindaklanjuti.',
    'iconBg'           => '#f5f3ff',
    'msgBoxBg'         => '#f5f3ff',
    'msgBoxBorder'     => '#8b5cf6',
    'ctaColor'         => '#7c3aed',
    'emailTitle'       => 'Tugas Maintenance Baru',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Admin <strong>{{ $adminName }}</strong> telah mengirimkan barang untuk dilakukan <strong style="color:#7c3aed;">servis/maintenance</strong>. Harap segera ditindaklanjuti.
</p>

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
        <span class="info-label">Dikirim oleh</span>
        <span class="info-value">{{ $adminName }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Tanggal Masuk</span>
        <span class="info-value">{{ now()->format('d M Y') }}</span>
    </div>
    @if($maintenance->keterangan ?? null)
    <div class="info-row">
        <span class="info-label">Keterangan</span>
        <span class="info-value">{{ $maintenance->keterangan }}</span>
    </div>
    @endif
</div>

<div class="message-box">
    <strong>📋 Instruksi:</strong><br>
    Segera periksa kondisi barang dan lakukan servis yang diperlukan. Setelah selesai, update status maintenance melalui sistem.
</div>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Lihat Detail Maintenance →</a>
</div>
@endsection
