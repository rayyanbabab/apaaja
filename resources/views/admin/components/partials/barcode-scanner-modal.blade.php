{{-- ════════════════════════════════════════════
     Barcode/QR Scanner Modal — Item Lookup
     Full-screen camera design
════════════════════════════════════════════ --}}

<div id="scannerModal"
     style="display:none; position:fixed; inset:0; z-index:9100; background:rgba(15,23,42,0.85); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:12px; box-sizing:border-box;">

    <div style="background:#0f172a; border-radius:24px; width:100%; max-width:480px; height:calc(100dvh - 24px); max-height:680px; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 30px 80px rgba(0,0,0,0.6);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#6d28d9 0%,#4f46e5 100%); padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <h3 style="color:#fff; font-weight:700; font-size:15px; margin:0;">📷 Scan Barcode / QR Barang</h3>
                <p style="color:rgba(255,255,255,0.7); font-size:11px; margin:2px 0 0;">Arahkan kamera ke label / QR barang</p>
            </div>
            <button onclick="closeScannerModal()" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; padding:7px; cursor:pointer; display:flex; align-items:center; color:#fff; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Camera — fills all remaining space --}}
        <div style="position:relative; flex:1; overflow:hidden; background:#000;">
            <div id="smReaderContainer" style="width:100%; height:100%; position:relative; overflow:hidden;">
                <div id="smReader" style="width:100%; height:100%;"></div>
            </div>
            {{-- Corner bracket overlay --}}
            <div id="smOverlayFrame" style="position:absolute; inset:0; pointer-events:none; display:flex; align-items:center; justify-content:center;">
                <div style="width:220px; height:220px; position:relative;">
                    <div style="position:absolute; top:0; left:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute; top:0; right:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute; bottom:0; left:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute; bottom:0; right:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 0 4px 0;"></div>
                    <div style="position:absolute; left:6px; right:6px; height:2px; background:linear-gradient(90deg,transparent,#818cf8,transparent); border-radius:2px; animation:smScanMove 1.8s ease-in-out infinite; top:0;"></div>
                    {{-- Vignette --}}
                    <div style="position:absolute; inset:-300px -400px; box-shadow: inset 0 0 0 400px rgba(0,0,0,0.5); pointer-events:none;"></div>
                </div>
            </div>
            {{-- Hint text --}}
            <div id="smHintText" style="position:absolute; bottom:12px; left:0; right:0; text-align:center; pointer-events:none;">
                <span style="font-size:12px; color:rgba(255,255,255,0.6); background:rgba(0,0,0,0.4); padding:4px 12px; border-radius:20px;">Posisikan barcode di dalam bingkai</span>
            </div>
        </div>

        {{-- Bottom controls panel --}}
        <div style="background:#1e293b; padding:14px 16px; flex-shrink:0;">
            {{-- Scanning --}}
            <div id="smStateScanning" style="display:flex; align-items:center; gap:8px; padding:9px 12px; background:#0f172a; border-radius:10px; border:1px solid #334155; margin-bottom:10px;">
                <span style="width:8px; height:8px; border-radius:50%; background:#22c55e; display:inline-block; animation:smBlink 1s ease-in-out infinite; flex-shrink:0;"></span>
                <span style="font-size:13px; color:#94a3b8;">Menunggu kode...</span>
            </div>
            {{-- Error --}}
            <div id="smStateError" style="display:none; padding:9px 12px; background:#450a0a; border:1px solid #7f1d1d; border-radius:10px; margin-bottom:10px;">
                <p id="smErrMsg" style="font-size:13px; color:#fca5a5; margin:0;"></p>
                <button onclick="smResetScan()" style="margin-top:5px; font-size:12px; color:#818cf8; background:none; border:none; cursor:pointer; font-weight:600; padding:0;">↩ Coba lagi</button>
            </div>
            {{-- Success Result --}}
            <div id="smStateResult" style="display:none; padding:10px 12px; background:#052e16; border:1px solid #166534; border-radius:10px; margin-bottom:10px;">
                <div style="display:flex; gap:8px; align-items:center; margin-bottom:6px;">
                    <div style="width:32px; height:32px; border-radius:8px; overflow:hidden; background:#1e293b; border:1px solid #334155; flex-shrink:0;">
                        <img id="smResImg" src="" alt="" style="width:100%; height:100%; object-fit:cover; display:none;">
                        <div id="smResImgPh" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="#4b5563" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; gap:4px; margin-bottom:2px;">
                            <span id="smResKode" style="font-size:10px; font-weight:700; color:#818cf8; background:#1e1b4b; padding:1px 5px; border-radius:3px; font-family:monospace;"></span>
                            <span id="smResBadge" style="font-size:10px; font-weight:600; padding:1px 5px; border-radius:3px;"></span>
                        </div>
                        <p id="smResNama" style="font-size:13px; font-weight:700; color:#f0fdf4; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"></p>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:repeat(4,1fr); background:#0f172a; border-radius:6px; overflow:hidden; margin-bottom:8px; border:1px solid #1e3a2f;">
                    <div style="padding:5px 4px; text-align:center; border-right:1px solid #1e3a2f;">
                        <div id="smResTotal" style="font-size:14px; font-weight:700; color:#f0fdf4;"></div>
                        <div style="font-size:9px; color:#4b5563; text-transform:uppercase;">Total</div>
                    </div>
                    <div style="padding:5px 4px; text-align:center; border-right:1px solid #1e3a2f;">
                        <div id="smResReg" style="font-size:14px; font-weight:700; color:#4ade80;"></div>
                        <div style="font-size:9px; color:#4b5563; text-transform:uppercase;">Tersedia</div>
                    </div>
                    <div style="padding:5px 4px; text-align:center; border-right:1px solid #1e3a2f;">
                        <div id="smResPinjam" style="font-size:14px; font-weight:700; color:#fbbf24;"></div>
                        <div style="font-size:9px; color:#4b5563; text-transform:uppercase;">Dipinjam</div>
                    </div>
                    <div style="padding:5px 4px; text-align:center;">
                        <div id="smResRepair" style="font-size:14px; font-weight:700; color:#f87171;"></div>
                        <div style="font-size:9px; color:#4b5563; text-transform:uppercase;">Repair</div>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:3px 8px; font-size:11px; margin-bottom:8px;">
                    <div><span style="color:#4b5563;">Kategori: </span><span id="smResCat" style="font-weight:600; color:#cbd5e1;">–</span></div>
                    <div><span style="color:#4b5563;">Lokasi: </span><span id="smResLoc" style="font-weight:600; color:#cbd5e1;">–</span></div>
                    <div><span style="color:#4b5563;">Supplier: </span><span id="smResSup" style="font-weight:600; color:#cbd5e1;">–</span></div>
                    <div><span style="color:#4b5563;">Harga: </span><span id="smResHarga" style="font-weight:600; color:#cbd5e1;">–</span></div>
                </div>
                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                    <a id="smResBtnPrint" href="#" target="_blank" style="font-size:11px; font-weight:600; color:#a78bfa; background:#1e1b4b; padding:5px 10px; border-radius:6px; text-decoration:none;">🖨 Print</a>
                    <a id="smResBtnEdit" href="#" style="font-size:11px; font-weight:600; color:#fff; background:#2563eb; padding:5px 10px; border-radius:6px; text-decoration:none;">Edit</a>
                    <a id="smResBtnShow" href="#" style="font-size:11px; font-weight:700; color:#fff; background:linear-gradient(135deg,#6d28d9,#4f46e5); padding:5px 12px; border-radius:6px; text-decoration:none; margin-left:auto;">Detail →</a>
                </div>
                <p id="smResKet" style="display:none;"></p>
            </div>
            {{-- Manual input --}}
            <div style="display:flex; gap:8px; margin-bottom:10px;">
                <input id="smManualInput" type="text" placeholder="Input kode manual (ITM-XXXX)" autocomplete="off"
                    style="flex:1; padding:9px 12px; border:1px solid #334155; border-radius:10px; font-size:13px; font-family:monospace; outline:none; text-transform:uppercase; background:#0f172a; color:#e2e8f0;"
                    onkeydown="if(event.key==='Enter') smDoLookup(this.value)">
                <button onclick="smDoLookup(document.getElementById('smManualInput').value)"
                    style="padding:9px 16px; background:#6d28d9; color:#fff; border:none; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; flex-shrink:0;">Cari</button>
            </div>
            {{-- Footer buttons --}}
            <div style="display:flex; gap:8px;">
                <button onclick="closeScannerModal()"
                        style="flex:1; padding:10px; border:1px solid #334155; border-radius:10px; background:#0f172a; font-size:13px; font-weight:600; color:#94a3b8; cursor:pointer;">
                    Batal
                </button>
                <button id="smBtnScanAgain" onclick="smResetScan()"
                        style="display:none; flex:1; padding:10px; border:none; border-radius:10px; background:linear-gradient(135deg,#6d28d9,#4f46e5); font-size:13px; font-weight:700; color:#fff; cursor:pointer;">
                    🔄 Scan Lagi
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes smScanMove { 0%,100%{top:8px} 50%{top:calc(100% - 10px)} }
@keyframes smBlink    { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ── Hide ALL html5-qrcode internal UI ── */
#smReader > img,
#smReader > div[style*="text-align"],
#smReader button, #smReader select, #smReader span,
#smReader__scan_region img,
#smReader__header_message, #smReader__status_span,
#smReader__dashboard, #smReader__dashboard_section,
#smReader__dashboard_section_csr, #smReader__dashboard_section_fsr,
#smReader__filescan_input,
#smReader__scan_region > div:not(video) { display: none !important; }

/* ── Video fills camera area completely ── */
#smReader, #smReader__scan_region {
    width: 100% !important; height: 100% !important;
    border: none !important; padding: 0 !important; margin: 0 !important;
    position: relative !important; overflow: hidden !important;
    background: #000 !important;
}
#smReader video {
    position: absolute !important; top: 50% !important; left: 50% !important;
    transform: translate(-50%, -50%) !important;
    min-width: 100% !important; min-height: 100% !important;
    width: auto !important; height: 100% !important;
    object-fit: cover !important; display: block !important;
}
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function() {
    var _smQr = null;
    var _smLookupUrl = "{{ route('inventory.lookup.shared') }}";

    window.openScannerModal = function() {
        // Escape any CSS transform parent — move to body root
        var modal = document.getElementById('scannerModal');
        if (modal.parentElement !== document.body) document.body.appendChild(modal);

        document.getElementById('smManualInput').value = '';
        _smShowState('scanning');
        document.getElementById('smBtnScanAgain').style.display = 'none';
        document.getElementById('smOverlayFrame').style.display = 'flex';
        document.getElementById('smHintText').style.display = '';
        document.getElementById('scannerModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        _smStartCamera();
    };

    window.closeScannerModal = function() {
        _smStopCamera();
        document.getElementById('scannerModal').style.display = 'none';
        document.body.style.overflow = '';
    };

    window.smResetScan = function() {
        document.getElementById('smManualInput').value = '';
        _smShowState('scanning');
        document.getElementById('smBtnScanAgain').style.display = 'none';
        document.getElementById('smOverlayFrame').style.display = 'flex';
        document.getElementById('smHintText').style.display = '';
        _smStartCamera();
    };

    function _smStartCamera() {
        var container = document.getElementById('smReaderContainer');
        container.innerHTML = '<div id="smReader" style="width:100%;height:100%;"></div>';
        if (_smQr) { try { _smQr.clear(); } catch(e) {} }
        _smQr = new Html5Qrcode('smReader');
        _smQr.start(
            { facingMode: 'environment' },
            { fps: 12, qrbox: { width: 200, height: 200 }, aspectRatio: 1.0 },
            function(decoded) {
                _smStopCamera();
                document.getElementById('smOverlayFrame').style.display = 'none';
                document.getElementById('smHintText').style.display = 'none';
                var kode = decoded;
                var m = decoded.match(/\/scan\/([A-Z0-9-]+)$/i);
                if (m) kode = m[1];
                smDoLookup(kode);
            },
            function() {}
        ).catch(function(e) {
            _smShowErr('Kamera tidak dapat diakses. Cek izin kamera browser. (' + e + ')');
        });
    }

    function _smStopCamera() {
        if (_smQr) { _smQr.stop().catch(function(){}); _smQr = null; }
    }

    function _smShowState(state) {
        document.getElementById('smStateScanning').style.display = state === 'scanning' ? 'flex' : 'none';
        document.getElementById('smStateError').style.display    = state === 'error'    ? ''    : 'none';
        document.getElementById('smStateResult').style.display   = state === 'result'   ? ''    : 'none';
    }

    function _smShowErr(msg) {
        document.getElementById('smErrMsg').textContent = msg;
        _smShowState('error');
        document.getElementById('smBtnScanAgain').style.display = '';
    }

    window.smDoLookup = async function(kode) {
        kode = (kode || '').trim().toUpperCase();
        if (!kode) return;
        document.getElementById('smManualInput').value = kode;
        _smStopCamera();
        document.getElementById('smOverlayFrame').style.display = 'none';
        document.getElementById('smHintText').style.display = 'none';
        document.getElementById('smStateScanning').style.display = 'flex';
        document.getElementById('smStateScanning').querySelector('span:last-child').textContent = 'Mencari barang...';
        document.getElementById('smStateError').style.display  = 'none';
        document.getElementById('smStateResult').style.display = 'none';

        try {
            var res  = await fetch(_smLookupUrl + '?kode=' + encodeURIComponent(kode), { headers: {'X-Requested-With':'XMLHttpRequest'} });
            var data = await res.json();
            if (!data.found) { _smShowErr(data.message || 'Barang tidak ditemukan.'); return; }
            _smPopulate(data.item, data.active_borrowings);
            _smShowState('result');
            document.getElementById('smBtnScanAgain').style.display = '';
        } catch(e) {
            _smShowErr('Error jaringan. Coba lagi.');
        }
    };

    function _smPopulate(item, borrows) {
        var img = document.getElementById('smResImg'), ph = document.getElementById('smResImgPh');
        if (item.gambar) { img.src = item.gambar; img.style.display=''; ph.style.display='none'; }
        else { img.style.display='none'; ph.style.display='flex'; }

        document.getElementById('smResKode').textContent   = item.kode;
        document.getElementById('smResNama').textContent   = item.nama;
        document.getElementById('smResTotal').textContent  = item.stok_total;
        document.getElementById('smResReg').textContent    = item.stok_reguler;
        document.getElementById('smResPinjam').textContent = item.stok_peminjaman;
        document.getElementById('smResRepair').textContent = item.stok_in_repair;
        document.getElementById('smResCat').textContent    = item.category  || '–';
        document.getElementById('smResSup').textContent    = item.supplier  || '–';
        document.getElementById('smResLoc').textContent    = item.location  || '–';
        document.getElementById('smResHarga').textContent  = item.harga_fmt || '–';

        var badge = document.getElementById('smResBadge');
        badge.textContent = item.type_label;
        badge.style.cssText = item.type === 'peminjaman'
            ? 'font-size:10px;font-weight:600;padding:1px 5px;border-radius:3px;background:#2e1065;color:#c4b5fd;'
            : 'font-size:10px;font-weight:600;padding:1px 5px;border-radius:3px;background:#431407;color:#fdba74;';

        document.getElementById('smResBtnPrint').href = item.url_print;
        document.getElementById('smResBtnEdit').href  = item.url_edit;
        document.getElementById('smResBtnShow').href  = item.url_show;
    }

    document.getElementById('scannerModal').addEventListener('click', function(e) {
        if (e.target === this) closeScannerModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('scannerModal').style.display !== 'none') closeScannerModal();
    });
})();
</script>
