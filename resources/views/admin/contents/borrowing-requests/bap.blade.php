@extends('admin.layouts.dashboard')

@section('title', 'Berita Acara Peminjaman (BAP) Digital – #' . $borrowingRequest->id)

@section('content')
<style>
/* ══ BAP Screen & Dark Mode Styles ══ */
html.dark .bap-panel { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .bap-title { color: #f1f5f9 !important; }
html.dark .bap-sub   { color: #94a3b8 !important; }
html.dark .bap-doc   { background-color: #0f172a !important; border-color: #334155 !important; color: #e2e8f0 !important; }
html.dark .bap-doc-header { background: linear-gradient(to right, rgba(99,102,241,0.15), rgba(59,130,246,0.15)) !important; border-color: #334155 !important; }
html.dark .bap-doc-title  { color: #93c5fd !important; }
html.dark .bap-table-key  { color: #94a3b8 !important; }
html.dark .bap-table-val  { color: #f1f5f9 !important; }
html.dark .bap-divider    { border-color: #334155 !important; }
html.dark .bap-sig-box    { background-color: #1e293b !important; border-color: #475569 !important; }

/* ══ Print Stylesheet (Formal Document Output) ══ */
@media print {
    body * {
        visibility: hidden !important;
    }
    #bap-printable, #bap-printable * {
        visibility: visible !important;
    }
    #bap-printable {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 24px !important;
        border: none !important;
        box-shadow: none !important;
        background: #ffffff !important;
        color: #111827 !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6"
     x-data="{ hasMark: false, drawing: false }"
     x-init="
        const canvas = document.getElementById('sig-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            function resizeCanvas() {
                const ratio = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                ctx.scale(ratio, ratio);
                ctx.strokeStyle = '#1e3a8a';
                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            function getPos(e) {
                const r = canvas.getBoundingClientRect();
                if (e.touches && e.touches.length > 0) {
                    return { x: e.touches[0].clientX - r.left, y: e.touches[0].clientY - r.top };
                }
                return { x: e.clientX - r.left, y: e.clientY - r.top };
            }

            canvas.addEventListener('mousedown', (e) => { 
                drawing = true; 
                ctx.beginPath(); 
                const p = getPos(e); 
                ctx.moveTo(p.x, p.y); 
            });
            canvas.addEventListener('mousemove', (e) => { 
                if (!drawing) return; 
                const p = getPos(e); 
                ctx.lineTo(p.x, p.y); 
                ctx.stroke(); 
                hasMark = true; 
                $data.hasMark = true; 
            });
            window.addEventListener('mouseup', () => drawing = false);

            canvas.addEventListener('touchstart', (e) => { 
                e.preventDefault(); 
                drawing = true; 
                ctx.beginPath(); 
                const p = getPos(e); 
                ctx.moveTo(p.x, p.y); 
            }, { passive: false });

            canvas.addEventListener('touchmove', (e) => { 
                e.preventDefault(); 
                if (!drawing) return; 
                const p = getPos(e); 
                ctx.lineTo(p.x, p.y); 
                ctx.stroke(); 
                hasMark = true; 
                $data.hasMark = true; 
            }, { passive: false });

            window.addEventListener('touchend', () => drawing = false);

            document.getElementById('btn-clear-sig')?.addEventListener('click', () => { 
                ctx.clearRect(0, 0, canvas.width, canvas.height); 
                hasMark = false; 
                $data.hasMark = false; 
            });

            document.getElementById('btn-sign-submit')?.addEventListener('click', () => {
                document.getElementById('sig-input').value = canvas.toDataURL('image/png');
                document.getElementById('sig-form').submit();
            });
        }
     ">

    {{-- ════════════════════ PAGE HEADER (No Print) ════════════════════ --}}
    <div class="bap-panel bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="bap-title text-xl font-bold text-gray-900 tracking-tight">Berita Acara Peminjaman (BAP) Digital</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                            E-Signature & QR
                        </span>
                        @if($borrowingRequest->isSigned())
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Sah & Ditandatangani
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                                Menunggu Tanda Tangan
                            </span>
                        @endif
                    </div>
                    <p class="bap-sub text-sm text-gray-500 mt-0.5">
                        Permintaan #{{ $borrowingRequest->id }} &bull; {{ $borrowingRequest->user->name }} &bull; {{ $borrowingRequest->item->nama }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($borrowingRequest->isSigned())
                    <button onclick="window.print()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Dokumen BAP
                    </button>
                @endif
                <a href="{{ panel_route('borrowing-requests.show', [$borrowingRequest->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl border border-gray-200 shadow-sm transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 no-print">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm no-print space-y-1">
            @foreach($errors->all() as $e) <div>&bull; {{ $e }}</div> @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ════ BAP FORMAL DOCUMENT PREVIEW (2/3 width) ════ --}}
        <div class="xl:col-span-2 space-y-5">

            <div class="bap-doc bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="bap-printable">
                
                {{-- Formal Kop Surat --}}
                <div class="p-6 sm:p-8 border-b-2 border-gray-800 bg-white">
                    <div class="flex items-center justify-between gap-4 pb-4">
                        <div class="w-16 h-16 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-2xl shadow-sm flex-shrink-0">
                            A
                        </div>
                        <div class="text-center flex-1">
                            <h2 class="text-sm font-bold tracking-wider text-gray-900 uppercase">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h2>
                            <h3 class="text-base font-extrabold text-indigo-900 uppercase tracking-tight mt-0.5">SISTEM MANAJEMEN INVENTARIS ARTILIA</h3>
                            <p class="text-xs text-gray-500 mt-1">Laboratorium & Bengkel Terpadu &bull; Portal Verifikasi Dokumen Digital</p>
                        </div>
                        <div class="w-16 h-16 flex items-center justify-center flex-shrink-0">
                            @if($borrowingRequest->isSigned())
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&margin=4&data={{ urlencode(route('bap.verify', $borrowingRequest->bap_token)) }}"
                                     alt="QR Verifikasi" class="w-16 h-16 rounded border border-gray-200">
                            @else
                                <div class="w-16 h-16 border-2 border-dashed border-gray-200 rounded flex items-center justify-center text-[10px] text-gray-400 text-center">
                                    QR Token
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="border-t-2 border-gray-900 mt-1"></div>
                    <div class="border-t border-gray-400 mt-0.5"></div>
                </div>

                {{-- Document Title & Meta --}}
                <div class="bap-doc-header px-6 py-5 text-center bg-gradient-to-r from-indigo-50/70 via-blue-50/50 to-indigo-50/70 border-b border-gray-100">
                    <h2 class="bap-doc-title text-base font-extrabold text-indigo-950 uppercase tracking-widest">
                        BERITA ACARA PEMINJAMAN ALAT & PERKAKAS
                    </h2>
                    @if($borrowingRequest->bap_number)
                        <p class="text-sm font-bold text-indigo-700 mt-1">Nomor: {{ $borrowingRequest->bap_number }}</p>
                    @else
                        <p class="text-xs text-amber-600 font-medium mt-1">Nomor Dokumen akan diterbitkan saat penandatanganan</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-0.5">
                        Tanggal Dokumen: {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                </div>

                {{-- Document Content --}}
                <div class="p-6 sm:p-8 space-y-6">
                    <p class="text-xs text-gray-700 leading-relaxed text-justify">
                        Pada hari ini, <strong>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</strong>, telah disepakati dan diserahterimakan alat/perkakas laboratorium dari Penanggung Jawab Inventaris kepada pemohon peminjam dengan rincian data sebagai berikut:
                    </p>

                    {{-- Data Table --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <table class="w-full text-xs text-left">
                            <tbody class="divide-y divide-gray-200">
                                <tr class="bg-gray-50/60">
                                    <th class="py-2.5 px-4 font-semibold text-gray-600 w-1/3">Nama Peminjam</th>
                                    <td class="py-2.5 px-4 font-bold text-gray-900">{{ $borrowingRequest->user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Email / Identitas</th>
                                    <td class="py-2.5 px-4 text-gray-800">{{ $borrowingRequest->user->email }}</td>
                                </tr>
                                <tr class="bg-gray-50/60">
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Nama Barang / Perkakas</th>
                                    <td class="py-2.5 px-4 font-bold text-indigo-900">{{ $borrowingRequest->item->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Kode & Kategori</th>
                                    <td class="py-2.5 px-4 text-gray-800">
                                        <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $borrowingRequest->item->kode ?? 'ITM-'.str_pad($borrowingRequest->item->id, 4, '0', STR_PAD_LEFT) }}</span> &bull; {{ $borrowingRequest->item->category->nama ?? 'Umum' }}
                                    </td>
                                </tr>
                                <tr class="bg-gray-50/60">
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Jumlah Dipinjam</th>
                                    <td class="py-2.5 px-4 font-bold text-gray-900">{{ $borrowingRequest->jumlah }} Unit</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Periode Peminjaman</th>
                                    <td class="py-2.5 px-4 text-gray-800 font-medium">
                                        {{ $borrowingRequest->tanggal_pinjam?->isoFormat('D MMMM YYYY') }} s/d {{ $borrowingRequest->tanggal_kembali_rencana?->isoFormat('D MMMM YYYY') }}
                                    </td>
                                </tr>
                                <tr class="bg-gray-50/60">
                                    <th class="py-2.5 px-4 font-semibold text-gray-600">Keterangan / SPK</th>
                                    <td class="py-2.5 px-4 text-gray-700 italic">{{ $borrowingRequest->keterangan ?: 'Peminjaman operasional laboratorium' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Clauses --}}
                    <div class="bg-gray-50/75 rounded-xl p-4 border border-gray-200 text-[11px] text-gray-600 space-y-1.5">
                        <p class="font-bold text-gray-800 uppercase tracking-wide">Ketentuan & Tanggung Jawab:</p>
                        <ol class="list-decimal list-inside space-y-1 text-gray-600">
                            <li>Peminjam bertanggung jawab penuh atas keutuhan, kebersihan, dan keselamatan alat yang dipinjam.</li>
                            <li>Kerusakan atau kehilangan akibat kelalaian wajib diganti sesuai dengan spesifikasi dan standar institusi.</li>
                            <li>Pengembalian wajib dilakukan tepat waktu sesuai tanggal rencana kembali yang tercantum dalam BAP ini.</li>
                            <li>Dokumen ini sah dan memiliki kekuatan verifikasi digital melalui pemindaian QR Code resmi.</li>
                        </ol>
                    </div>

                    {{-- Signatures Side-by-side --}}
                    <div class="pt-4 grid grid-cols-2 gap-8 text-center text-xs">
                        <div class="space-y-1">
                            <p class="font-semibold text-gray-700">Pihak Peminjam,</p>
                            <div class="h-20 flex items-end justify-center pb-2">
                                <span class="text-xs text-gray-400 italic">( Tanda Tangan Fisik )</span>
                            </div>
                            <div class="border-b border-gray-400 w-3/4 mx-auto"></div>
                            <p class="font-bold text-gray-900 mt-1">{{ $borrowingRequest->user->name }}</p>
                            <p class="text-[11px] text-gray-500">Peminjam</p>
                        </div>

                        <div class="space-y-1">
                            <p class="font-semibold text-gray-700">Penanggung Jawab / Admin,</p>
                            <div class="h-20 flex items-center justify-center">
                                @if($borrowingRequest->isSigned())
                                    <img src="{{ $borrowingRequest->signature_data }}" alt="TTD Digital"
                                         class="max-h-16 max-w-full object-contain">
                                @else
                                    <span class="text-xs text-amber-600 font-medium italic">[ Menunggu TTD Digital ]</span>
                                @endif
                            </div>
                            <div class="border-b border-gray-400 w-3/4 mx-auto"></div>
                            @if($borrowingRequest->isSigned())
                                <p class="font-bold text-emerald-800 mt-1">{{ $borrowingRequest->signed_by_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $borrowingRequest->signed_at?->isoFormat('D MMM YYYY, HH:mm') }} WIB</p>
                            @else
                                <p class="font-bold text-gray-800 mt-1">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-400">Petugas Inventaris</p>
                            @endif
                        </div>
                    </div>

                    {{-- Verification Seal (if signed) --}}
                    @if($borrowingRequest->isSigned())
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 text-emerald-700 font-bold">
                                &check;
                            </div>
                            <div>
                                <p class="font-bold text-emerald-900">Dokumen Telah Terotentikasi Secara Digital</p>
                                <p class="text-[11px] text-emerald-700">Token ID: <code class="font-mono font-bold bg-emerald-100/80 px-1.5 py-0.5 rounded text-[10px]">{{ $borrowingRequest->bap_token }}</code></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-100 px-2 py-1 rounded-md">Artilia Secure ID</span>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ════ RIGHT PANEL: Signature Pad or QR Actions (No Print) ════ --}}
        <div class="space-y-5 no-print">

            @if(! $borrowingRequest->isSigned())
                {{-- Digital Signature Pad --}}
                <div class="bap-panel bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="bap-title text-sm font-bold text-gray-900">Tanda Tangan Digital</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Gambar tanda tangan Anda pada canvas</p>
                        </div>
                        <div class="w-8 h-8 bg-indigo-50 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Canvas Box --}}
                    <div class="rounded-xl border-2 border-dashed border-gray-300 bg-slate-50 relative overflow-hidden" style="height: 170px;">
                        <canvas id="sig-canvas"
                                style="width: 100%; height: 170px; cursor: crosshair; touch-action: none; display: block;">
                        </canvas>
                        <div class="absolute bottom-2 left-0 right-0 text-center pointer-events-none" x-show="!hasMark">
                            <span class="text-[11px] text-gray-400 bg-white/80 px-2.5 py-0.5 rounded-full border border-gray-200 shadow-xs">
                                ✍️ Gambar tanda tangan di sini
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button" id="btn-clear-sig"
                                class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-semibold px-2 py-1 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Bersihkan Canvas
                        </button>
                        <span class="text-[11px]" :class="hasMark ? 'text-emerald-600 font-bold' : 'text-gray-400'">
                            <span x-show="!hasMark">Belum digambar</span>
                            <span x-show="hasMark">Siap ditandatangani &check;</span>
                        </span>
                    </div>

                    <form id="sig-form"
                          action="{{ panel_route('borrowing-requests.sign', [$borrowingRequest->id]) }}"
                          method="POST"
                          class="space-y-3.5 pt-3 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="signature_data" id="sig-input">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Petugas Penandatangan *</label>
                            <input type="text" name="signed_by_name"
                                   value="{{ auth()->user()->name }}"
                                   class="w-full text-xs rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 bg-gray-50 text-gray-800 font-medium"
                                   required placeholder="Nama lengkap petugas...">
                        </div>
                        <button type="button" id="btn-sign-submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-150"
                                :class="!hasMark ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Kunci Dokumen & Terbitkan BAP
                        </button>
                    </form>
                </div>

                {{-- Advisory Box --}}
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold">Keabsahan Tanda Tangan</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">Setelah ditandatangani, nomor BAP dan token verifikasi publik akan digenerate otomatis dan terkunci permanen.</p>
                    </div>
                </div>

            @else
                {{-- Verified QR Panel --}}
                <div class="bap-panel bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div>
                            <h3 class="bap-title text-sm font-bold text-gray-900">QR Verifikasi Publik</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Scan langsung tanpa perlu login</p>
                        </div>
                        <div class="w-8 h-8 bg-emerald-50 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>

                    @php $verifyUrl = route('bap.verify', $borrowingRequest->bap_token); @endphp
                    <div class="flex justify-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=8&data={{ urlencode($verifyUrl) }}"
                             alt="QR Verifikasi BAP"
                             class="rounded-xl w-44 h-44 shadow-xs">
                    </div>

                    <div class="space-y-2 text-xs divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-400">No. BAP</span>
                            <span class="font-bold text-gray-900">{{ $borrowingRequest->bap_number }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-400">Petugas TTD</span>
                            <span class="font-semibold text-gray-900">{{ $borrowingRequest->signed_by_name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-gray-400">Waktu TTD</span>
                            <span class="text-gray-700">{{ $borrowingRequest->signed_at?->isoFormat('D MMM YYYY, HH:mm') }} WIB</span>
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <a href="{{ $verifyUrl }}" target="_blank"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka Link Verifikasi Publik
                        </a>
                        <button onclick="window.print()"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Cetak / Unduh PDF
                        </button>
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
