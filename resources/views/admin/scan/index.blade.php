@extends('admin.layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

    {{-- Page Header (Sembunyikan di mobile agar scanner lebih ke tengah atas) --}}
    <div class="mb-4 sm:mb-8 hidden sm:flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100">
                Quick Scan
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-xs sm:text-sm">Scan QR Code barang untuk pengembalian instan, maintenance, dan riwayat.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ─── Scanner Card ─── --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">

                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Kamera Scanner</h2>
                        <p class="text-xs text-gray-400">Arahkan kamera ke QR Code barang</p>
                    </div>
                </div>

                {{-- Camera Viewport --}}
                <div class="p-4">
                    <div id="reader"
                         class="w-full rounded-xl overflow-hidden bg-gray-900 relative"
                         style="min-height: 300px;">
                        {{-- Scan overlay frame --}}
                        <div id="scan-overlay" class="absolute inset-0 hidden">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="relative w-52 h-52">
                                    {{-- Corners --}}
                                    <span class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-indigo-400 rounded-tl-lg"></span>
                                    <span class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-indigo-400 rounded-tr-lg"></span>
                                    <span class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-indigo-400 rounded-bl-lg"></span>
                                    <span class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-indigo-400 rounded-br-lg"></span>
                                    {{-- Scanning line animation --}}
                                    <div id="scan-line" class="absolute left-2 right-2 h-0.5 bg-gradient-to-r from-transparent via-indigo-400 to-transparent opacity-80"
                                         style="animation: scanLine 2s ease-in-out infinite; top: 50%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status & Controls --}}
                    <div class="mt-4 text-center">
                        <p id="scan-status" class="text-sm text-gray-500 dark:text-gray-400 mb-4">Tekan "Mulai Scanner" untuk mengaktifkan kamera.</p>
                        <div class="flex justify-center gap-3">
                            <button id="start-btn"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl text-sm font-semibold shadow-lg shadow-indigo-500/25 transition-all hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Mulai Scanner
                            </button>
                            <button id="stop-btn"
                                class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-sm font-semibold transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Stop
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Manual Input Card ─── --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Manual Code Input --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Input Manual</h2>
                        <p class="text-xs text-gray-400">Masukkan kode barang secara manual</p>
                    </div>
                </div>
                <div class="p-5">
                    <form action="{{ route('scanner.handle', '') }}" method="GET" id="manual-form"
                          onsubmit="handleManualSubmit(event)">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Barang</label>
                        <div class="flex gap-2">
                            <input id="manual-kode" type="text" name="kode" placeholder="cth: ITM-0001"
                                   class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono"
                                   autocomplete="off" autocapitalize="characters" style="text-transform: uppercase">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                Cari
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Format: <span class="font-mono">ITM-XXXX</span></p>
                    </form>
                </div>
            </div>

            {{-- How-to Card --}}
            <div class="bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-indigo-900/20 dark:to-violet-900/20 border border-indigo-100 dark:border-indigo-800/40 rounded-2xl p-5">
                <h3 class="text-sm font-bold text-indigo-800 dark:text-indigo-300 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Cara Penggunaan
                </h3>
                <ol class="space-y-2 text-xs text-indigo-700 dark:text-indigo-300">
                    <li class="flex gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">1</span>
                        <span>Tekan <strong>Mulai Scanner</strong> dan izinkan akses kamera.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">2</span>
                        <span>Arahkan ke label QR Code pada fisik barang.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">3</span>
                        <span>Sistem otomatis membuka halaman aksi cepat barang tersebut.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">4</span>
                        <span>Lakukan <strong>pengembalian</strong>, <strong>maintenance</strong>, atau lihat riwayat dari halaman tersebut.</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes scanLine {
        0%, 100% { top: 10%; }
        50%       { top: 85%; }
    }
</style>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startBtn    = document.getElementById("start-btn");
        const stopBtn     = document.getElementById("stop-btn");
        const scanStatus  = document.getElementById("scan-status");
        const scanOverlay = document.getElementById("scan-overlay");

        let html5QrCode = null;
        let isScanning  = false;

        function setStatus(html, type = 'idle') {
            const colors = { idle: 'text-gray-500', scanning: 'text-indigo-600', success: 'text-green-600', error: 'text-red-500' };
            scanStatus.className = `text-sm mb-4 font-medium ${colors[type] ?? colors.idle}`;
            scanStatus.innerHTML = html;
        }

        const onScanSuccess = (decodedText) => {
            setStatus('✅ QR Code terdeteksi! Memproses...', 'success');

            html5QrCode.stop().then(() => {
                isScanning = false;
                scanOverlay.classList.add('hidden');
                startBtn.classList.remove("hidden");
                stopBtn.classList.add("hidden");

                // Navigate to scan result
                if (decodedText.startsWith('http')) {
                    window.location.href = decodedText;
                } else {
                    window.location.href = "{{ url('/scan') }}/" + encodeURIComponent(decodedText.toUpperCase());
                }
            }).catch(console.error);
        };

        const startScanner = () => {
            setStatus('Membuka kamera...', 'scanning');
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                () => {} // frame error — ignore
            ).then(() => {
                isScanning = true;
                startBtn.classList.add("hidden");
                stopBtn.classList.remove("hidden");
                scanOverlay.classList.remove('hidden');
                setStatus('Mencari QR Code...', 'scanning');
            }).catch((err) => {
                setStatus(`❌ Gagal mengakses kamera: ${err}`, 'error');
            });
        };

        const stopScanner = () => {
            if (isScanning && html5QrCode) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    startBtn.classList.remove("hidden");
                    stopBtn.classList.add("hidden");
                    scanOverlay.classList.add('hidden');
                    setStatus('Scanner dihentikan.', 'idle');
                });
            }
        };

        startBtn.addEventListener("click", startScanner);
        stopBtn.addEventListener("click",  stopScanner);
    });

    function handleManualSubmit(e) {
        e.preventDefault();
        const kode = document.getElementById('manual-kode').value.trim().toUpperCase();
        if (!kode) return;
        window.location.href = "{{ url('/scan') }}/" + encodeURIComponent(kode);
    }
</script>
@endpush
@endsection
