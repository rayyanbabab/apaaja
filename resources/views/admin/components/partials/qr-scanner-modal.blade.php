{{-- ============================================================
     Shared QR Scanner Modal — Scan User QR Code
     Digunakan di: borrowings/create, outgoing/create
     Requires: html5-qrcode lib loaded by the including page
     Callback: window.onQrUserFound(userData) dipanggil setelah scan berhasil
============================================================ --}}

<div id="qrScannerModal"
     style="display:none; position:fixed; inset:0; z-index:9000; background:rgba(15,23,42,0.85); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:12px; box-sizing:border-box;">

    <div style="background:#0f172a; border-radius:24px; width:100%; max-width:480px; height:calc(100dvh - 24px); max-height:640px; display:flex; flex-direction:column; overflow:hidden; box-shadow:0 30px 80px rgba(0,0,0,0.6);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div>
                <h3 style="color:#fff; font-weight:700; font-size:15px; margin:0;">🪪 Scan QR Code User</h3>
                <p style="color:rgba(255,255,255,0.7); font-size:11px; margin:2px 0 0;">Arahkan kamera ke QR Code identitas pengguna</p>
            </div>
            <button onclick="closeQrScanner()" style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; padding:7px; cursor:pointer; display:flex; align-items:center; color:#fff; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Camera — fills all remaining space --}}
        <div style="position:relative; flex:1; overflow:hidden; background:#000;">
            <div id="qrReaderContainer" style="width:100%; height:100%; position:relative; overflow:hidden;">
                <div id="qrReader" style="width:100%; height:100%;"></div>
            </div>
            <div style="position:absolute; inset:0; pointer-events:none; display:flex; align-items:center; justify-content:center;" id="qrOverlayFrame">
                <div style="width:220px; height:220px; position:relative;">
                    <div style="position:absolute; top:0; left:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:4px 0 0 0;"></div>
                    <div style="position:absolute; top:0; right:0; width:40px; height:40px; border-top:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 4px 0 0;"></div>
                    <div style="position:absolute; bottom:0; left:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-left:3px solid #a5b4fc; border-radius:0 0 0 4px;"></div>
                    <div style="position:absolute; bottom:0; right:0; width:40px; height:40px; border-bottom:3px solid #a5b4fc; border-right:3px solid #a5b4fc; border-radius:0 0 4px 0;"></div>
                    <div id="qrScanLine" style="position:absolute; left:6px; right:6px; height:2px; background:linear-gradient(90deg,transparent,#818cf8,transparent); border-radius:2px; animation:qrScanMove 1.8s ease-in-out infinite; top:0;"></div>
                    <div style="position:absolute; inset:-300px -400px; box-shadow: inset 0 0 0 400px rgba(0,0,0,0.5); pointer-events:none;"></div>
                </div>
            </div>
            <div style="position:absolute; bottom:12px; left:0; right:0; text-align:center; pointer-events:none;" id="qrHintText">
                <span style="font-size:12px; color:rgba(255,255,255,0.6); background:rgba(0,0,0,0.4); padding:4px 12px; border-radius:20px;">Posisikan QR Code di dalam bingkai</span>
            </div>
        </div>

        {{-- Bottom controls panel --}}
        <div style="background:#1e293b; padding:14px 16px; flex-shrink:0;">
            {{-- Scanning status --}}
            <div id="qrStatusScanning" style="display:flex; align-items:center; gap:8px; padding:9px 12px; background:#0f172a; border-radius:10px; border:1px solid #334155; margin-bottom:10px;">
                <span style="width:8px; height:8px; border-radius:50%; background:#22c55e; display:inline-block; animation:qrBlink 1s ease-in-out infinite;"></span>
                <span style="font-size:13px; color:#94a3b8;">Menunggu QR code...</span>
            </div>

            {{-- Error state --}}
            <div id="qrStatusError" style="display:none; padding:9px 12px; background:#450a0a; border:1px solid #7f1d1d; border-radius:10px; margin-bottom:10px;">
                <p style="font-size:13px; color:#fca5a5; margin:0;" id="qrErrorText">QR tidak valid.</p>
                <button onclick="resetQrScanner()" style="margin-top:5px; font-size:12px; color:#818cf8; background:none; border:none; cursor:pointer; padding:0; font-weight:600;">↩ Coba lagi</button>
            </div>

            {{-- Success: user card --}}
            <div id="qrStatusSuccess" style="display:none; padding:10px 12px; background:#052e16; border:1px solid #166534; border-radius:10px; margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:#166534; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="16" height="16" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:12px; font-weight:600; color:#4ade80; margin:0;">QR Code Teridentifikasi!</p>
                        <p style="font-size:11px; color:#86efac; margin:2px 0 0;">Klik Pilih User untuk konfirmasi</p>
                    </div>
                </div>
                <div style="background:#0f172a; border-radius:8px; padding:10px; border:1px solid #1e3a2f; display:grid; gap:5px;">
                    <div style="display:flex; align-items:baseline; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#4b5563; min-width:44px;">Nama</span>
                        <span id="qrResultName" style="font-size:14px; font-weight:700; color:#f0fdf4;"></span>
                    </div>
                    <div style="display:flex; align-items:baseline; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#4b5563; min-width:44px;">Email</span>
                        <span id="qrResultEmail" style="font-size:12px; color:#94a3b8;"></span>
                    </div>
                    <div style="display:flex; align-items:baseline; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#4b5563; min-width:44px;">ID</span>
                        <span id="qrResultId" style="font-size:12px; font-family:monospace; color:#818cf8; font-weight:700;"></span>
                    </div>
                    <div style="display:flex; align-items:baseline; gap:6px;">
                        <span style="font-size:11px; font-weight:600; color:#4b5563; min-width:44px;">Role</span>
                        <span id="qrResultRole" style="font-size:11px; background:#1e1b4b; color:#a5b4fc; padding:2px 8px; border-radius:20px; font-weight:600; text-transform:uppercase;"></span>
                    </div>
                </div>
            </div>

            {{-- Footer actions --}}
            <div style="display:flex; gap:8px;">
                <button onclick="closeQrScanner()"
                        style="flex:1; padding:10px; border:1px solid #334155; border-radius:10px; background:#0f172a; font-size:13px; font-weight:600; color:#94a3b8; cursor:pointer;">
                    Batal
                </button>
                <button id="qrConfirmBtn" onclick="confirmQrSelection()"
                        style="flex:2; padding:10px; border:none; border-radius:10px; background:linear-gradient(135deg,#4f46e5,#7c3aed); font-size:13px; font-weight:700; color:#fff; cursor:pointer; display:none; box-shadow:0 4px 14px rgba(79,70,229,0.4);">
                    ✓ Pilih User Ini
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes qrScanMove {
    0%   { top: 8px; }
    50%  { top: calc(100% - 10px); }
    100% { top: 8px; }
}
@keyframes qrBlink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.3; }
}

/* ── Hide ALL html5-qrcode internal UI ── */
#qrReader > img,
#qrReader > div[style*="text-align"],
#qrReader button, #qrReader select, #qrReader span,
#qrReader #html5-qrcode-button-camera-permission,
#qrReader #html5-qrcode-button-camera-stop,
#qrReader #html5-qrcode-button-camera-start,
#qrReader #html5-qrcode-anchor-scan-type-change,
#qrReader__scan_region img,
#qrReader__header_message, #qrReader__status_span,
#qrReader__dashboard, #qrReader__dashboard_section,
#qrReader__dashboard_section_csr, #qrReader__dashboard_section_fsr,
#qrReader__filescan_input,
#qrReader__scan_region > div:not(video) { display: none !important; }

/* ── Video fills camera area completely ── */
#qrReader, #qrReader__scan_region {
    width: 100% !important; height: 100% !important;
    border: none !important; padding: 0 !important; margin: 0 !important;
    position: relative !important; overflow: hidden !important;
    background: #000 !important;
}
#qrReader video {
    position: absolute !important; top: 50% !important; left: 50% !important;
    transform: translate(-50%, -50%) !important;
    min-width: 100% !important; min-height: 100% !important;
    width: auto !important; height: 100% !important;
    object-fit: cover !important; display: block !important;
}
</style>

<script>
(function() {
    var html5QrCode = null;
    var _scannedData = null;
    var _currentContext = null;

    window.openQrScanner = function(context) {
        // Escape any CSS transform parent — move to body root
        var qrModal = document.getElementById('qrScannerModal');
        if (qrModal.parentElement !== document.body) document.body.appendChild(qrModal);
        _currentContext = context || 'default';
        _scannedData = null;
        document.getElementById('qrOverlayFrame').style.display = 'flex';
        document.getElementById('qrHintText').style.display = '';
        document.getElementById('qrScannerModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        showQrScanning();
        startCamera();
    };

    window.closeQrScanner = function() {
        stopCamera();
        document.getElementById('qrScannerModal').style.display = 'none';
        document.body.style.overflow = '';
        _scannedData = null;
    };

    window.resetQrScanner = function() {
        _scannedData = null;
        document.getElementById('qrOverlayFrame').style.display = 'flex';
        document.getElementById('qrHintText').style.display = '';
        showQrScanning();
        startCamera();
    };

    window.showQrError = function(msg) {
        document.getElementById('qrStatusScanning').style.display = 'none';
        document.getElementById('qrStatusSuccess').style.display = 'none';
        document.getElementById('qrStatusError').style.display = 'block';
        document.getElementById('qrErrorText').textContent = msg || 'QR tidak valid.';
        document.getElementById('qrConfirmBtn').style.display = 'none';
    };

    window.confirmQrSelection = function() {
        if (!_scannedData) return;
        stopCamera();
        document.getElementById('qrScannerModal').style.display = 'none';
        document.body.style.overflow = '';
        if (typeof window.onQrUserFound === 'function') {
            window.onQrUserFound(_scannedData);
        }
        _scannedData = null;
    };

    function showQrScanning() {
        document.getElementById('qrStatusScanning').style.display = 'flex';
        document.getElementById('qrStatusError').style.display = 'none';
        document.getElementById('qrStatusSuccess').style.display = 'none';
        document.getElementById('qrConfirmBtn').style.display = 'none';
        document.getElementById('qrOverlayFrame').style.display = 'flex';
    }

    function startCamera() {
        if (!window.Html5Qrcode) {
            showQrError('Library html5-qrcode belum dimuat. Coba refresh halaman.');
            return;
        }
        var container = document.getElementById('qrReaderContainer');
        container.innerHTML = '<div id="qrReader" style="width:100%;height:100%;"></div>';

        html5QrCode = new Html5Qrcode('qrReader');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 12, qrbox: { width: 200, height: 200 }, aspectRatio: 1.0 },
            onScanSuccess,
            function() {}
        ).catch(function(err) {
            showQrError('Kamera tidak dapat diakses. Pastikan izin kamera sudah diberikan. (' + err + ')');
        });
    }

    function stopCamera() {
        if (html5QrCode) {
            html5QrCode.stop().catch(function() {});
            html5QrCode = null;
        }
    }

    function playSuccessBeep() {
        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            function tone(freq, start, dur, vol) {
                var osc = ctx.createOscillator(), gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.type = 'sine'; osc.frequency.setValueAtTime(freq, start);
                gain.gain.setValueAtTime(0, start);
                gain.gain.linearRampToValueAtTime(vol, start + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, start + dur);
                osc.start(start); osc.stop(start + dur);
            }
            var t = ctx.currentTime;
            tone(880, t, 0.12, 0.4);
            tone(1320, t + 0.10, 0.20, 0.35);
            setTimeout(function() { try { ctx.close(); } catch(e){} }, 700);
        } catch(e) {}
    }

    function onScanSuccess(decodedText) {
        try {
            var data = JSON.parse(decodedText);
            if (!data.id || !data.name) {
                showQrError('QR Code bukan milik sistem Artilia atau formatnya tidak dikenali.');
                return;
            }
            _scannedData = data;
            playSuccessBeep();
            stopCamera();
            document.getElementById('qrOverlayFrame').style.display = 'none';
            document.getElementById('qrHintText').style.display = 'none';
            showScanResult(data);
        } catch(e) {
            showQrError('QR Code tidak dapat dibaca sebagai data identitas. Pastikan menggunakan QR Code Artilia.');
        }
    }

    function showScanResult(data) {
        document.getElementById('qrStatusScanning').style.display = 'none';
        document.getElementById('qrStatusError').style.display = 'none';
        document.getElementById('qrStatusSuccess').style.display = 'block';
        document.getElementById('qrConfirmBtn').style.display = 'block';
        document.getElementById('qrResultName').textContent  = data.name  || '-';
        document.getElementById('qrResultEmail').textContent = data.email || '-';
        document.getElementById('qrResultId').textContent    = '#' + String(data.id).padStart(5, '0');
        document.getElementById('qrResultRole').textContent  = data.role  || '-';
    }

    document.getElementById('qrScannerModal').addEventListener('click', function(e) {
        if (e.target === this) closeQrScanner();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('qrScannerModal').style.display !== 'none') {
            closeQrScanner();
        }
    });
})();
</script>
