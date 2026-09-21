<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $valid ? 'BAP Terverifikasi – ' . ($borrowingRequest->bap_number ?? '') : 'Verifikasi Dokumen Gagal' }} | Artilia Trust Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .verify-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen text-slate-800 antialiased flex items-center justify-center p-4 sm:p-6 selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

    {{-- Ambient background glowing orbs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden no-print">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-xl my-6">

        {{-- Top Brand Indicator --}}
        <div class="text-center mb-6 no-print">
            <div class="inline-flex items-center gap-2 bg-slate-800/80 backdrop-blur-md border border-slate-700/60 px-4 py-1.5 rounded-full shadow-lg">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="text-xs font-bold tracking-wider text-slate-200 uppercase">Artilia Secure Trust Network</span>
            </div>
        </div>

        {{-- Verification Card Container --}}
        <div class="verify-card bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden backdrop-blur-xl">

            @if($valid && $borrowingRequest)

                {{-- Banner Success --}}
                <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-700 text-white p-6 sm:p-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/40 backdrop-blur-md flex items-center justify-center mx-auto mb-3.5 shadow-inner">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Dokumen Sah & Terverifikasi</h1>
                        <p class="text-xs sm:text-sm text-emerald-100 mt-1 font-medium">Berita Acara Peminjaman (BAP) tercatat resmi dalam basis data Artilia</p>
                    </div>
                </div>

                {{-- Body Content --}}
                <div class="p-6 sm:p-8 space-y-6">

                    {{-- Token Certificate Hash --}}
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kunci Digital (Token SHA)</p>
                            <p class="text-xs font-mono font-bold text-slate-800 break-all mt-0.5">{{ $borrowingRequest->bap_token }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-100/80 px-2.5 py-1 rounded-full flex-shrink-0 self-start sm:self-auto">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Verified
                        </span>
                    </div>

                    {{-- Main Document Data --}}
                    <div class="space-y-3">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rincian Dokumen BAP</h2>
                        <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden bg-white text-xs">
                            <div class="flex justify-between py-3 px-4 bg-slate-50/50">
                                <span class="text-slate-500 font-medium">Nomor BAP</span>
                                <span class="font-extrabold text-slate-900 font-mono">{{ $borrowingRequest->bap_number }}</span>
                            </div>
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-slate-500 font-medium">Nama Peminjam</span>
                                <span class="font-bold text-slate-900">{{ $borrowingRequest->user->name }}</span>
                            </div>
                            <div class="flex justify-between py-3 px-4 bg-slate-50/50">
                                <span class="text-slate-500 font-medium">Email / Identitas</span>
                                <span class="text-slate-700">{{ $borrowingRequest->user->email }}</span>
                            </div>
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-slate-500 font-medium">Barang Dipinjam</span>
                                <span class="font-bold text-indigo-700">{{ $borrowingRequest->item->nama }}</span>
                            </div>
                            <div class="flex justify-between py-3 px-4 bg-slate-50/50">
                                <span class="text-slate-500 font-medium">Jumlah Unit</span>
                                <span class="font-bold text-slate-900">{{ $borrowingRequest->jumlah }} Unit</span>
                            </div>
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-slate-500 font-medium">Tanggal Pinjam</span>
                                <span class="text-slate-800">{{ $borrowingRequest->tanggal_pinjam?->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                            <div class="flex justify-between py-3 px-4 bg-slate-50/50">
                                <span class="text-slate-500 font-medium">Rencana Pengembalian</span>
                                <span class="text-slate-800 font-semibold">{{ $borrowingRequest->tanggal_kembali_rencana?->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Signer Information --}}
                    <div class="space-y-3">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Otorisasi & Tanda Tangan</h2>
                        <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="space-y-1 text-center sm:text-left">
                                <p class="text-xs font-bold text-indigo-950">{{ $borrowingRequest->signed_by_name }}</p>
                                <p class="text-[11px] text-indigo-700">Petugas / Penanggung Jawab Inventaris</p>
                                <p class="text-[10px] text-slate-400">{{ $borrowingRequest->signed_at?->isoFormat('dddd, D MMMM YYYY &bull; HH:mm') }} WIB</p>
                            </div>
                            @if($borrowingRequest->signature_data)
                                <div class="bg-white p-2 rounded-xl border border-indigo-200/80 shadow-xs">
                                    <img src="{{ $borrowingRequest->signature_data }}" alt="Tanda Tangan" class="h-14 max-w-[140px] object-contain">
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-2 no-print flex gap-3">
                        <button onclick="window.print()" class="flex-1 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold text-center shadow-md transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Sertifikat Verifikasi
                        </button>
                    </div>

                </div>

            @else

                {{-- Banner Error / Invalid --}}
                <div class="bg-gradient-to-br from-red-600 via-rose-600 to-red-700 text-white p-6 sm:p-8 text-center relative overflow-hidden">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/40 backdrop-blur-md flex items-center justify-center mx-auto mb-3.5 shadow-inner">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Dokumen Tidak Ditemukan / Tidak Valid</h1>
                    <p class="text-xs sm:text-sm text-rose-100 mt-1">Token QR ini tidak terdaftar pada sistem resmi Artilia</p>
                </div>

                <div class="p-6 sm:p-8 text-center space-y-4">
                    <p class="text-xs text-slate-600 leading-relaxed max-w-md mx-auto">
                        Kode verifikasi yang Anda pindai tidak valid, telah kedaluwarsa, atau dokumen belum pernah disahkan oleh petugas inventaris.
                    </p>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-500">
                        Pastikan Anda memindai QR Code asli dari lembar resmi Berita Acara Peminjaman (BAP).
                    </div>
                </div>

            @endif

            {{-- Footer --}}
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 text-center text-[11px] text-slate-400">
                &copy; {{ date('Y') }} Artilia Inventory Management System &bull; Dokumen Elektronik Terotentikasi
            </div>

        </div>

    </div>

</body>
</html>
