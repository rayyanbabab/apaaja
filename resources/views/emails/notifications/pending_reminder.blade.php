@extends('emails.layout', [
    'headerColorFrom'  => '#9333ea',
    'headerColorTo'    => '#a855f7',
    'heroEmoji'        => '⏳',
    'heroTitle'        => 'Ada Pengajuan Menunggu Persetujuan',
    'heroSubtitle'     => 'Beberapa permintaan peminjaman belum diproses lebih dari 24 jam.',
    'iconBg'           => '#faf5ff',
    'msgBoxBg'         => '#faf5ff',
    'msgBoxBorder'     => '#a855f7',
    'ctaColor'         => '#9333ea',
    'emailTitle'       => 'Reminder: Pengajuan Pending',
])

@section('content')
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Halo <strong>{{ $notifiable->name }}</strong>,
</p>
<p style="color:#374151; font-size:14px; margin-bottom:20px;">
    Sistem mengingatkan bahwa terdapat <strong style="color:#9333ea;">{{ $pendingCount }} pengajuan peminjaman</strong> yang masih menunggu persetujuan Anda.
</p>

<div class="info-card">
    <div class="info-row">
        <span class="info-label">Jumlah Pengajuan Pending</span>
        <span class="info-value" style="color:#9333ea; font-size:18px; font-weight:700;">{{ $pendingCount }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Pengajuan Tertua</span>
        <span class="info-value" style="color:#dc2626;">{{ $oldestPendingHours }} jam yang lalu</span>
    </div>
</div>

<div class="message-box">
    <strong>⚡ Perlu Tindakan Segera!</strong><br>
    Pengajuan yang telah menunggu terlalu lama dapat menyebabkan gangguan operasional. Segera tinjau dan proses pengajuan yang masih pending.
</div>

<p style="color:#6b7280; font-size:13px; margin-bottom:0;">
    Reminder ini dikirim otomatis setiap 12 jam selama masih ada pengajuan yang belum diproses lebih dari 24 jam.
</p>

<div class="cta-wrapper">
    <a href="{{ $actionUrl }}" class="cta-btn">Proses Pengajuan Sekarang →</a>
</div>
@endsection
