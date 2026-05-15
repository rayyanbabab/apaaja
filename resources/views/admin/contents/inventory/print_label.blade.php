<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label – {{ $item->kode }} | Artilia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap');

        body { font-family: 'Inter', sans-serif; }

        /* ─── Print-only: hide controls, reset margins ─── */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            @page { margin: 8mm; }
            #label-grid { gap: 4mm !important; padding: 0 !important; }
            .label-card { page-break-inside: avoid; }
        }

        /* ─── Label size presets ─── */
        .size-sm  { width: 40mm; min-height: 30mm; }
        .size-md  { width: 60mm; min-height: 44mm; }
        .size-lg  { width: 80mm; min-height: 60mm; }

        /* ─── QR Scaling inside container ─── */
        .label-card svg {
            display: block;
            width: 100% !important;
            height: auto !important;
            max-width: 100%;
        }

        /* ─── Animated gradient border effect for controls ─── */
        .gradient-border {
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4) border-box;
            border: 2px solid transparent;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen text-gray-900 p-6 md:p-10"
      x-data="{
        size: 'size-md',
        count: 1,
        get countArr() { return Array.from({ length: parseInt(this.count) || 1 }, (_, i) => i); }
      }"
      x-cloak>

    <!-- ─── Controls Panel (no-print) ─── -->
    <div class="no-print max-w-4xl mx-auto mb-8">
        <div class="gradient-border bg-white rounded-2xl shadow-xl p-6">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-end">

                <!-- Item Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900">Cetak Label QR Code</h1>
                            <p class="text-sm text-gray-500">Barang: <span class="font-semibold text-indigo-600">{{ $item->nama }}</span> · <span class="font-mono text-gray-600 text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $item->kode }}</span></p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Pilih ukuran & jumlah label, kemudian klik tombol Print.</p>
                </div>

                <!-- Controls -->
                <div class="flex flex-wrap gap-4 items-end">
                    <!-- Size -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ukuran Label</label>
                        <div class="flex gap-2">
                            <button @click="size='size-sm'" :class="size==='size-sm' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300'"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border transition-all shadow-sm">Kecil<br><span class="text-[10px] opacity-70">40×30mm</span></button>
                            <button @click="size='size-md'" :class="size==='size-md' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300'"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border transition-all shadow-sm">Sedang<br><span class="text-[10px] opacity-70">60×44mm</span></button>
                            <button @click="size='size-lg'" :class="size==='size-lg' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300'"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border transition-all shadow-sm">Besar<br><span class="text-[10px] opacity-70">80×60mm</span></button>
                        </div>
                    </div>

                    <!-- Count -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jumlah Label</label>
                        <div class="flex items-center gap-2 border border-gray-200 rounded-lg p-1 bg-white shadow-sm">
                            <button @click="count = Math.max(1, parseInt(count) - 1)" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-gray-100 text-gray-600 font-bold transition-colors">−</button>
                            <input type="number" x-model="count" min="1" max="50" class="w-14 text-center text-sm font-bold border-0 focus:ring-0 focus:outline-none p-0">
                            <button @click="count = Math.min(50, parseInt(count) + 1)" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-gray-100 text-gray-600 font-bold transition-colors">+</button>
                        </div>
                    </div>

                    <!-- Print button -->
                    <button onclick="window.print()"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl font-semibold text-sm shadow-lg shadow-indigo-500/30 transition-all hover:shadow-indigo-500/50 hover:-translate-y-0.5 active:translate-y-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Label
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Label Grid (printed) ─── -->
    <div id="label-grid" class="max-w-4xl mx-auto flex flex-wrap gap-4 justify-start">
        <template x-for="i in countArr" :key="i">
            <div :class="`label-card ${size} flex flex-col items-center justify-between p-2 rounded-lg border-2 border-dashed border-gray-300 bg-white shadow-sm`">
                <!-- Brand header -->
                <div class="w-full text-center">
                    <span class="text-[7px] font-black uppercase tracking-[0.2em] text-indigo-400">ARTILIA INVENTORY</span>
                </div>
                <!-- QR Code SVG (rendered server-side, one copy) -->
                <div class="qr-wrapper flex-1 flex items-center justify-center w-full py-1 px-2">
                    {!! QrCode::size(120)->margin(1)->errorCorrection('H')->generate(route('scanner.handle', $item->kode)) !!}
                </div>
                <!-- Item info footer -->
                <div class="w-full text-center border-t border-gray-100 pt-1">
                    <div class="text-[9px] font-bold leading-tight text-gray-800 truncate px-1" title="{{ $item->nama }}">{{ Str::limit($item->nama, 30) }}</div>
                    <div class="text-[8px] font-mono text-gray-500 tracking-wide">{{ $item->kode }}</div>
                </div>
            </div>
        </template>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</body>
</html>
