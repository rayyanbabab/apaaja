{{-- ============================================================
     Import Items Modal
     Include di: admin/contents/inventory/index.blade.php
============================================================ --}}

<div id="importItemsModal"
     style="display:none; position:fixed; inset:0; z-index:9500; background:rgba(15,23,42,0.7); backdrop-filter:blur(4px);"
     class="flex items-center justify-center p-4">

    <div style="background:#fff; border-radius:20px; width:100%; max-width:520px; overflow:hidden; box-shadow:0 25px 60px rgba(0,0,0,0.3);">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#0ea5e9 0%,#0284c7 100%); padding:20px 24px; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <h3 style="color:#fff; font-weight:700; font-size:16px; margin:0;">Import Data Barang</h3>
                <p style="color:rgba(255,255,255,0.75); font-size:12px; margin:4px 0 0;">Upload file CSV untuk menambah barang secara massal</p>
            </div>
            <button onclick="document.getElementById('importItemsModal').style.display='none'; document.body.style.overflow='';"
                    style="background:rgba(255,255,255,0.15); border:none; border-radius:8px; padding:7px; cursor:pointer; color:#fff; display:flex;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div style="padding:24px;">

            {{-- Download template --}}
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:14px 16px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <div>
                    <p style="font-size:13px; font-weight:600; color:#0c4a6e; margin:0;">Download Template CSV</p>
                    <p style="font-size:12px; color:#0369a1; margin:4px 0 0;">Gunakan template ini agar format kolom sesuai</p>
                </div>
                <a href="{{ route('admin.import.items.template') }}"
                   style="flex-shrink:0; display:inline-flex; align-items:center; gap:6px; padding:8px 14px; background:#0ea5e9; color:#fff; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
            </div>

            {{-- Format info --}}
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; margin-bottom:20px;">
                <p style="font-size:12px; font-weight:600; color:#475569; margin:0 0 8px;">Kolom CSV yang didukung:</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:4px;">
                    @foreach(['nama *', 'kode', 'kategori', 'supplier', 'lokasi', 'stok_reguler *', 'stok_peminjaman', 'harga', 'keterangan', 'tipe'] as $col)
                    <span style="font-size:11px; font-family:monospace; background:#fff; border:1px solid #e2e8f0; border-radius:4px; padding:2px 6px; color:{{ str_contains($col, '*') ? '#dc2626' : '#374151' }};">
                        {{ $col }}
                    </span>
                    @endforeach
                </div>
                <p style="font-size:11px; color:#94a3b8; margin:8px 0 0;"><span style="color:#dc2626;">*</span> wajib &nbsp;|&nbsp; tipe: <code>stok</code> atau <code>peminjaman</code></p>
            </div>

            {{-- Upload form --}}
            <form action="{{ route('admin.import.items') }}" method="POST" enctype="multipart/form-data" id="importItemsForm">
                @csrf
                <div id="importItemsDropzone"
                     style="border:2px dashed #cbd5e1; border-radius:12px; padding:28px; text-align:center; cursor:pointer; transition:all .2s; position:relative;"
                     ondragover="event.preventDefault(); this.style.borderColor='#0ea5e9'; this.style.background='#f0f9ff';"
                     ondragleave="this.style.borderColor='#cbd5e1'; this.style.background='';"
                     ondrop="handleItemsDrop(event);"
                     onclick="document.getElementById('importItemsFile').click();">
                    <input type="file" name="csv_file" id="importItemsFile" accept=".csv,.xlsx,.xls,text/csv" style="display:none;"
                           onchange="showItemsFileName(this)">
                    <div id="importItemsIcon">
                        <svg width="36" height="36" fill="none" stroke="#94a3b8" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 10px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <p style="font-size:14px; font-weight:600; color:#475569; margin:0;">Drag & drop file CSV/Excel atau klik untuk pilih</p>
                        <p style="font-size:12px; color:#94a3b8; margin:4px 0 0;">Format: .csv atau .xlsx &nbsp;|&nbsp; Maks. 5 MB</p>
                    </div>
                    <div id="importItemsSelected" style="display:none;">
                        <svg width="32" height="32" fill="none" stroke="#0ea5e9" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                        <p id="importItemsFileName" style="font-size:13px; font-weight:600; color:#0284c7; margin:0;"></p>
                        <button type="button" onclick="event.stopPropagation(); resetItemsFile();"
                                style="font-size:11px; color:#64748b; background:none; border:none; cursor:pointer; margin-top:4px;">Ganti file</button>
                    </div>
                </div>

                <div style="display:flex; gap:10px; margin-top:20px;">
                    <button type="button"
                            onclick="document.getElementById('importItemsModal').style.display='none'; document.body.style.overflow='';"
                            style="flex:1; padding:11px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc; font-size:13px; font-weight:600; color:#475569; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit" id="importItemsSubmitBtn"
                            style="flex:2; padding:11px; border:none; border-radius:10px; background:linear-gradient(135deg,#0ea5e9,#0284c7); font-size:13px; font-weight:700; color:#fff; cursor:pointer; box-shadow:0 4px 12px rgba(14,165,233,0.35);">
                        ↑ Import Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openImportItemsModal() {
    document.getElementById('importItemsModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function showItemsFileName(input) {
    if (input.files && input.files[0]) {
        document.getElementById('importItemsIcon').style.display = 'none';
        document.getElementById('importItemsSelected').style.display = 'block';
        document.getElementById('importItemsFileName').textContent = input.files[0].name;
        document.getElementById('importItemsDropzone').style.borderColor = '#0ea5e9';
        document.getElementById('importItemsDropzone').style.background = '#f0f9ff';
    }
}
function resetItemsFile() {
    document.getElementById('importItemsFile').value = '';
    document.getElementById('importItemsIcon').style.display = 'block';
    document.getElementById('importItemsSelected').style.display = 'none';
    document.getElementById('importItemsDropzone').style.borderColor = '#cbd5e1';
    document.getElementById('importItemsDropzone').style.background = '';
}
function handleItemsDrop(e) {
    e.preventDefault();
    document.getElementById('importItemsDropzone').style.borderColor = '#cbd5e1';
    document.getElementById('importItemsDropzone').style.background = '';
    var file = e.dataTransfer.files[0];
    if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx') || file.name.endsWith('.xls') || file.type === 'text/csv')) {
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('importItemsFile').files = dt.files;
        showItemsFileName(document.getElementById('importItemsFile'));
    } else {
        alert('File harus berformat CSV (.csv) atau Excel (.xlsx).');
    }
}
document.getElementById('importItemsModal').addEventListener('click', function(e) {
    if (e.target === this) { this.style.display = 'none'; document.body.style.overflow = ''; }
});
</script>
