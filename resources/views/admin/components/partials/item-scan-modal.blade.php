{{-- ════════════════════════════════════════════════════
     Item Barcode Scanner Modal — Shared Partial
     Digunakan di: incoming/create, outgoing/create, borrowings/create

     allowedType: 'peminjaman' | 'stok' | null (semua tipe)
     Callback: window._isSelectCallback(item_data) dipanggil saat match
════════════════════════════════════════════════════ --}}

<div id="itemScanModal"
     style="display:none; position:fixed; inset:0; z-index:9200; background:rgba(15,23,42,0.85); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:12px; box-sizing:border-box;">

    <div style="background:#0f172a; border-radius:24px; width:100%; max-width:480px; height:calc(100dvh - 24px); max-height:680px; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 30px 80px rgba(0,0,0,0.6);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#6d28d9 0%,#4f46e5 100%); padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <h3 style="color:#fff; font-weight:700; font-size:15px; margin:0;">📷 Scan Barcode Barang</h3>
                <p id="is-target-label" style="color:rgba(255,255,255,0.7); font-size:11px; margin:2px 0 0;">Arahkan kamera ke label barang</p>
            </div>
            <button onclick="closeItemScan()" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; padding:7px; cursor:pointer; display:flex; align-items:center; color:#fff; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Type filter badge --}}
        <div id="isTypeBadgeWrap" style="display:none; padding:6px 16px; background:#1e293b; flex-shrink:0;">
            <div id="isTypeBadge" style="display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:700; padding:4px 12px; border-radius:20px; text-transform:uppercase; letter-spacing:.04em;"></div>
        </div>

        {{-- Camera — fills all remaining space --}}
        <div style="position:relative; flex:1; overflow:hidden; background:#000;">
            <div id="isReaderContainer" style="width:100%; height:100%; position:relative; overflow:hidden;">
                <div id="is-reader" style="width:100%; height:100%;"></div>
            </div>
            <div id="isOverlayFrame" style="position:absolute; inset:0; pointer-events:none; display:flex; align-items:center; justify-content:center;">
                <div style="width:220px; height:220px; position:relative;">
                    <div style="position:absolute; top:0; left:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute; top:0; right:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute; bottom:0; left:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute; bottom:0; right:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 0 4px 0;"></div>
                    <div style="position:absolute; left:6px; right:6px; height:2px; background:linear-gradient(90deg,transparent,#818cf8,transparent); border-radius:2px; animation:isScanMove 1.8s ease-in-out infinite; top:0;"></div>
                    <div style="position:absolute; inset:-300px -400px; box-shadow: inset 0 0 0 400px rgba(0,0,0,0.5); pointer-events:none;"></div>
                </div>
            </div>
            <div id="isHintText" style="position:absolute; bottom:12px; left:0; right:0; text-align:center; pointer-events:none;">
                <span style="font-size:12px; color:rgba(255,255,255,0.6); background:rgba(0,0,0,0.4); padding:4px 12px; border-radius:20px;">Posisikan barcode di dalam bingkai</span>
            </div>
        </div>

        {{-- Bottom controls panel --}}
        <div style="background:#1e293b; padding:14px 16px; flex-shrink:0;">
            {{-- Scanning status --}}
            <div id="isStateScanning" style="display:flex; align-items:center; gap:8px; padding:9px 12px; background:#0f172a; border-radius:10px; border:1px solid #334155; margin-bottom:10px;">
                <span style="width:8px; height:8px; border-radius:50%; background:#22c55e; display:inline-block; animation:isBlink 1s ease-in-out infinite; flex-shrink:0;"></span>
                <span id="isStatusTxt" style="font-size:13px; color:#94a3b8;">Menunggu kode...</span>
            </div>
            {{-- Error --}}
            <div id="isStateError" style="display:none; padding:9px 12px; background:#450a0a; border:1px solid #7f1d1d; border-radius:10px; margin-bottom:10px;">
                <p id="isErrMsg" style="font-size:13px; color:#fca5a5; margin:0;"></p>
                <button onclick="isResetScan()" style="margin-top:5px; font-size:12px; color:#818cf8; background:none; border:none; cursor:pointer; font-weight:600; padding:0;">↩ Coba lagi</button>
            </div>
            {{-- Success --}}
            <div id="isStateSuccess" style="display:none; padding:9px 12px; background:#052e16; border:1px solid #166534; border-radius:10px; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div style="width:28px; height:28px; border-radius:50%; background:#166534; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="14" height="14" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p style="font-size:12px; font-weight:700; color:#4ade80; margin:0;">Barang Dipilih!</p>
                        <p id="isSuccessName" style="font-size:11px; color:#86efac; margin:1px 0 0;"></p>
                    </div>
                </div>
            </div>
            {{-- Manual input --}}
            <div style="display:flex; gap:8px; margin-bottom:10px;">
                <input id="is-manual" type="text" placeholder="Input kode manual (ITM-XXXX)" autocomplete="off"
                    style="flex:1; padding:9px 12px; border:1px solid #334155; border-radius:10px; font-size:13px; font-family:monospace; outline:none; text-transform:uppercase; background:#0f172a; color:#e2e8f0;"
                    onkeydown="if(event.key==='Enter') isLookup(this.value)">
                <button onclick="isLookup(document.getElementById('is-manual').value)"
                    style="padding:9px 16px; background:#6d28d9; color:#fff; border:none; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; flex-shrink:0;">Cari</button>
            </div>
            {{-- Footer --}}
            <div style="display:flex; gap:8px;">
                <button onclick="closeItemScan()"
                        style="flex:1; padding:10px; border:1px solid #334155; border-radius:10px; background:#0f172a; font-size:13px; font-weight:600; color:#94a3b8; cursor:pointer;">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes isScanMove { 0%,100%{top:8px} 50%{top:calc(100% - 10px)} }
@keyframes isBlink    { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ── Hide ALL html5-qrcode internal UI ── */
#is-reader > img,
#is-reader > div[style*="text-align"],
#is-reader button, #is-reader select, #is-reader span,
#is-reader__scan_region img,
#is-reader__header_message, #is-reader__status_span,
#is-reader__dashboard, #is-reader__dashboard_section,
#is-reader__dashboard_section_csr, #is-reader__dashboard_section_fsr,
#is-reader__filescan_input,
#is-reader__scan_region > div:not(video) { display: none !important; }

/* ── Video fills camera area completely ── */
#is-reader, #is-reader__scan_region {
    width: 100% !important; height: 100% !important;
    border: none !important; padding: 0 !important; margin: 0 !important;
    position: relative !important; overflow: hidden !important;
    background: #000 !important;
}
#is-reader video {
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
    var _isQr          = null;
    var _isTargetCnt   = null;
    var _isAllowedType = null;
    var _isLookupUrl   = "{{ route('inventory.lookup.shared') }}";

    window.openItemScan = function(cnt, allowedType) {
        // Escape any CSS transform parent — move to body root
        var modal = document.getElementById('itemScanModal');
        if (modal.parentElement !== document.body) document.body.appendChild(modal);
        _isTargetCnt   = cnt;
        _isAllowedType = allowedType || null;

        var label = cnt !== null ? 'Memilih barang untuk Item #' + cnt : 'Arahkan kamera ke label barang';
        document.getElementById('is-target-label').textContent = label;

        var badgeWrap = document.getElementById('isTypeBadgeWrap');
        var badge     = document.getElementById('isTypeBadge');
        if (_isAllowedType) {
            badgeWrap.style.display = '';
            if (_isAllowedType === 'peminjaman') {
                badge.style.cssText = 'display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;background:#2e1065;color:#c4b5fd;border:1px solid #4c1d95;';
                badge.textContent = '🔄 Khusus Barang Peminjaman';
            } else if (_isAllowedType === 'stok') {
                badge.style.cssText = 'display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em;background:#431407;color:#fdba74;border:1px solid #7c2d12;';
                badge.textContent = '📦 Khusus Barang Stok';
            }
        } else {
            badgeWrap.style.display = 'none';
        }

        document.getElementById('is-manual').value = '';
        _isShowState('scanning');
        document.getElementById('isOverlayFrame').style.display = 'flex';
        document.getElementById('isHintText').style.display = '';
        document.getElementById('itemScanModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        _isStartCamera();
    };

    window.closeItemScan = function() {
        _isStopCamera();
        document.getElementById('itemScanModal').style.display = 'none';
        document.body.style.overflow = '';
        _isTargetCnt   = null;
        _isAllowedType = null;
    };

    window.isResetScan = function() {
        document.getElementById('is-manual').value = '';
        _isShowState('scanning');
        document.getElementById('isOverlayFrame').style.display = 'flex';
        document.getElementById('isHintText').style.display = '';
        _isStartCamera();
    };

    function _isStartCamera() {
        var container = document.getElementById('isReaderContainer');
        container.innerHTML = '<div id="is-reader" style="width:100%;height:100%;"></div>';
        if (_isQr) { try { _isQr.clear(); } catch(e) {} }
        _isQr = new Html5Qrcode('is-reader');
        _isQr.start(
            { facingMode: 'environment' },
            { fps: 12, qrbox: { width: 200, height: 200 }, aspectRatio: 1.0 },
            function(decoded) {
                _isStopCamera();
                document.getElementById('isOverlayFrame').style.display = 'none';
                document.getElementById('isHintText').style.display = 'none';
                var kode = decoded;
                var m = decoded.match(/\/scan\/([A-Z0-9-]+)$/i);
                if (m) kode = m[1];
                isLookup(kode);
            },
            function() {}
        ).catch(function(e) {
            _isShowErr('Kamera tidak dapat diakses. (' + e + ')');
        });
    }

    function _isStopCamera() {
        if (_isQr) { _isQr.stop().catch(function(){}); _isQr = null; }
    }

    function _isShowState(state) {
        document.getElementById('isStateScanning').style.display = state === 'scanning' ? 'flex' : 'none';
        document.getElementById('isStateError').style.display    = state === 'error'    ? ''    : 'none';
        document.getElementById('isStateSuccess').style.display  = state === 'success'  ? ''    : 'none';
    }

    function _isShowErr(msg) {
        document.getElementById('isErrMsg').textContent = msg;
        _isShowState('error');
    }

    window.isLookup = async function(kode) {
        kode = (kode || '').trim().toUpperCase();
        if (!kode) return;
        document.getElementById('is-manual').value = kode;
        _isStopCamera();
        document.getElementById('isOverlayFrame').style.display = 'none';
        document.getElementById('isHintText').style.display = 'none';
        document.getElementById('isStatusTxt').textContent = 'Mencari barang...';
        _isShowState('scanning');

        try {
            var res  = await fetch(_isLookupUrl + '?kode=' + encodeURIComponent(kode), { headers: {'X-Requested-With':'XMLHttpRequest'} });
            var data = await res.json();

            if (!data.found) { _isShowErr(data.message || 'Barang tidak ditemukan.'); return; }

            if (_isAllowedType && data.item.type !== _isAllowedType) {
                var typeLabel = data.item.type === 'peminjaman' ? 'Peminjaman' : 'Stok';
                var needLabel = _isAllowedType === 'peminjaman' ? 'Peminjaman' : 'Stok';
                _isShowErr('❌ Barang ini bertipe "' + typeLabel + '". Halaman ini hanya menerima barang bertipe "' + needLabel + '".');
                return;
            }

            if (typeof window._isSelectCallback === 'function') {
                window._isSelectCallback(data.item, _isTargetCnt);
                document.getElementById('isSuccessName').textContent = data.item.nama;
                _isShowState('success');
                setTimeout(closeItemScan, 900);
            } else {
                _isShowErr('Callback tidak terdefinisi.');
            }
        } catch(e) {
            _isShowErr('Error jaringan. Coba lagi.');
        }
    };

    document.getElementById('itemScanModal').addEventListener('click', function(e) {
        if (e.target === this) closeItemScan();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('itemScanModal').style.display !== 'none') closeItemScan();
    });
})();
</script>
