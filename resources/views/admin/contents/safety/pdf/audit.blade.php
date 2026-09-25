<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} – {{ $docNumber }}</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 28px 30px 32px 30px;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #1e293b;
            background: #ffffff;
            line-height: 1.35;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-logo-cell {
            width: 65px;
            padding-right: 8px;
        }
        .kop-logo-img {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
        .k3-symbol-box {
            width: 56px;
            height: 56px;
            border: 1.5px solid #15803d;
            border-radius: 6px;
            background: #f0fdf4;
            text-align: center;
            vertical-align: middle;
            display: inline-block;
        }
        .k3-symbol-cross {
            color: #15803d;
            font-size: 26px;
            font-weight: 900;
            line-height: 38px;
            display: block;
        }
        .k3-symbol-text {
            color: #166534;
            font-size: 6px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            display: block;
            margin-top: -6px;
        }
        .kop-identity {
            padding-left: 6px;
        }
        .kop-org-title {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.15;
        }
        .kop-lab-title {
            font-size: 9.5px;
            font-weight: 700;
            color: #1e40af;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .kop-contact-text {
            font-size: 7px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.35;
        }
        .kop-doc-control {
            width: 175px;
            text-align: right;
        }
        .doc-control-box {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 7px;
            line-height: 1.45;
            text-align: left;
        }
        .doc-control-box .row-item {
            margin-bottom: 2px;
        }
        .doc-control-box .lbl {
            color: #64748b;
            font-weight: 600;
            width: 65px;
            display: inline-block;
        }
        .doc-control-box .val {
            color: #0f172a;
            font-weight: 800;
        }

        .standard-ribbon {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            border-radius: 3px;
            padding: 4px 8px;
            margin-bottom: 10px;
        }
        .standard-ribbon-table {
            width: 100%;
            border-collapse: collapse;
        }
        .standard-ribbon-table td {
            font-size: 7px;
            vertical-align: middle;
        }
        .ribbon-badge {
            background: #dc2626;
            color: #ffffff;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 2px;
            font-size: 6.5px;
            letter-spacing: 0.5px;
            margin-right: 4px;
        }
        .ribbon-text {
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .ribbon-right {
            text-align: right;
            color: #cbd5e1;
            font-weight: 500;
        }

        .report-header-center {
            text-align: center;
            margin-bottom: 10px;
        }
        .main-report-title {
            font-size: 12.5px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            line-height: 1.2;
        }
        .main-report-subtitle {
            font-size: 7.5px;
            color: #475569;
            margin-top: 2px;
            letter-spacing: 0.2px;
        }

        .executive-box {
            background: #f8fafc;
            border-left: 3.5px solid #2563eb;
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 0 4px 4px 0;
            padding: 6px 9px;
            margin-bottom: 10px;
        }
        .executive-title {
            font-size: 7.5px;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .executive-desc {
            font-size: 7px;
            color: #334155;
            line-height: 1.4;
            text-align: justify;
        }

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px 0;
            margin-bottom: 11px;
        }
        .kpi-cell {
            width: 25%;
            border-radius: 4px;
            padding: 6px 8px;
            text-align: center;
            vertical-align: top;
            border: 1px solid #cbd5e1;
        }
        .kpi-cell.green {
            background: #f0fdf4;
            border-color: #86efac;
        }
        .kpi-cell.blue {
            background: #eff6ff;
            border-color: #93c5fd;
        }
        .kpi-cell.red {
            background: #fef2f2;
            border-color: #fca5a5;
        }
        .kpi-cell.amber {
            background: #fffbeb;
            border-color: #fcd34d;
        }
        .kpi-lbl {
            font-size: 6.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .kpi-cell.green .kpi-lbl { color: #166534; }
        .kpi-cell.blue .kpi-lbl  { color: #1e40af; }
        .kpi-cell.red .kpi-lbl   { color: #991b1b; }
        .kpi-cell.amber .kpi-lbl { color: #92400e; }

        .kpi-num {
            font-size: 14px;
            font-weight: 900;
            line-height: 1.1;
        }
        .kpi-cell.green .kpi-num { color: #15803d; }
        .kpi-cell.blue .kpi-num  { color: #1d4ed8; }
        .kpi-cell.red .kpi-num   { color: #b91c1c; }
        .kpi-cell.amber .kpi-num { color: #d97706; }

        .kpi-note {
            font-size: 6.5px;
            color: #64748b;
            margin-top: 2px;
            font-weight: 600;
        }

        .section-header-block {
            background: #0f172a;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: 800;
            padding: 4px 8px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-radius: 3px;
            margin: 11px 0 5px 0;
            page-break-after: avoid;
        }
        .section-subnote {
            font-size: 6.5px;
            color: #64748b;
            margin-bottom: 4px;
            font-style: italic;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table-data th {
            background: #f1f5f9;
            color: #1e293b;
            font-size: 7px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #cbd5e1;
            padding: 4px 5px;
            text-align: left;
            vertical-align: middle;
        }
        .table-data td {
            border: 1px solid #e2e8f0;
            padding: 4px 5px;
            font-size: 7px;
            vertical-align: top;
            color: #1e293b;
            line-height: 1.3;
        }
        .table-data tr:nth-child(even) td {
            background: #f8fafc;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
            font-size: 6px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
            font-size: 6px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            font-size: 6px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
            text-transform: uppercase;
        }
        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #7dd3fc;
            font-size: 6px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 2px;
            display: inline-block;
            text-transform: uppercase;
        }

        .zero-box {
            background: #f0fdf4;
            border: 1.5px solid #22c55e;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 9px;
            text-align: left;
        }
        .zero-title {
            color: #15803d;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .zero-text {
            font-size: 7px;
            color: #166534;
            line-height: 1.4;
            text-align: justify;
        }

        .recommendation-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .recommendation-title {
            font-size: 7.5px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .recommendation-list {
            padding-left: 12px;
            margin: 0;
            font-size: 7px;
            color: #334155;
            line-height: 1.4;
        }

        .signature-block {
            width: 100%;
            margin-top: 14px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-cell {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 8px;
        }
        .sig-status-label {
            font-size: 6.5px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .sig-role-title {
            font-size: 7.5px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 1px;
            min-height: 20px;
        }
        .sig-space {
            height: 48px;
        }
        .sig-name-underline {
            font-size: 8px;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #0f172a;
            display: inline-block;
            padding-bottom: 1px;
            min-width: 130px;
        }
        .sig-credential {
            font-size: 6.5px;
            color: #475569;
            margin-top: 2px;
        }

        .legal-footer-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px dashed #cbd5e1;
            padding-top: 6px;
            margin-top: 14px;
            page-break-inside: avoid;
        }
        .legal-footer-table td {
            vertical-align: middle;
        }
        .seal-box {
            width: 120px;
            border: 1px solid #16a34a;
            background: #f0fdf4;
            border-radius: 4px;
            padding: 3px 6px;
            text-align: center;
        }
        .seal-tag {
            font-size: 5.5px;
            font-weight: 800;
            color: #15803d;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .seal-code {
            font-size: 6.5px;
            font-weight: 800;
            color: #0f172a;
            font-family: monospace;
        }
        .legal-note {
            padding-left: 10px;
            font-size: 6.5px;
            color: #64748b;
            line-height: 1.35;
            text-align: justify;
        }
    </style>
</head>
<body>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("Helvetica", "normal");
            $size = 6.5;
            $color = array(100/255, 116/255, 139/255);
            $pdf->page_text(30, 814, "ARTILIA Safety Interlock System • Laporan Audit Resmi K3 & HIRADC (ISO 45001 / SMK3)", $font, $size, $color);
            $pdf->page_text(510, 814, "Halaman " . $PAGE_NUM . " dari " . $PAGE_COUNT, $font, $size, $color);
        }
    </script>

    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                @if(!empty($company['logo']))
                    <img src="{{ $company['logo'] }}" alt="Logo" class="kop-logo-img">
                @else
                    <div class="k3-symbol-box">
                        <span class="k3-symbol-cross">+</span>
                        <span class="k3-symbol-text">K3 HSE</span>
                    </div>
                @endif
            </td>
            <td class="kop-identity">
                <div class="kop-org-title">{{ $company['name'] }}</div>
                <div class="kop-lab-title">Laboratorium Manufaktur, Fabrikasi & Workshop Vokasi Terpadu</div>
                <div class="kop-contact-text">
                    {{ $company['address'] }}<br>
                    Telepon: {{ $company['phone'] }} | Surel: {{ $company['email'] }} | Portal: artilia.ac.id
                </div>
            </td>
            <td class="kop-doc-control">
                <div class="doc-control-box">
                    <div class="row-item"><span class="lbl">No. Audit</span>: <span class="val">{{ $docNumber }}</span></div>
                    <div class="row-item"><span class="lbl">Tgl. Terbit</span>: <span class="val">{{ $audit_date }}</span></div>
                    <div class="row-item"><span class="lbl">Status</span>: <span class="val" style="color: #15803d;">Terverifikasi</span></div>
                    <div class="row-item"><span class="lbl">Klasifikasi</span>: <span class="val">Audit Internal Terkendali</span></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="standard-ribbon">
        <table class="standard-ribbon-table">
            <tr>
                <td>
                    <span class="ribbon-badge">ISO 45001:2018</span>
                    <span class="ribbon-badge" style="background: #15803d;">PP NO. 50/2012 (SMK3)</span>
                    <span class="ribbon-text">Standar Tata Kelola K3 & Manajemen Risiko Peralatan Laboratorium</span>
                </td>
                <td class="ribbon-right">
                    Klausul 9.2: Internal Audit & Performance Evaluation
                </td>
            </tr>
        </table>
    </div>

    <div class="report-header-center">
        <h1 class="main-report-title">{{ $title }}</h1>
        <p class="main-report-subtitle">Rekapitulasi Kepatuhan Alat Pelindung Diri (APD), Penilaian Risiko Perkakas (HIRADC), & Rekam Jejak Nihil Insiden</p>
    </div>

    <div class="executive-box">
        <div class="executive-title">Ringkasan Eksekutif & Dasar Hukum Audit</div>
        <p class="executive-desc">
            Berdasarkan mandat <strong>Undang-Undang No. 1 Tahun 1970</strong> tentang Keselamatan Kerja, <strong>Peraturan Pemerintah No. 50 Tahun 2012</strong> tentang Penerapan SMK3, serta <strong>Permenaker No. 5 Tahun 2018</strong>, laporan ini diterbitkan oleh platform <em>Artilia Safety Interlock & Governance Engine</em>. Audit ini memvalidasi efektivitas gerbang verifikasi APD fisik sebelum serah-terima perkakas berbahaya, identifikasi bahaya mesin (HIRADC), serta rekam jejak nihil kecelakaan kerja (Zero Accident) guna menjamin lingkungan bengkel praktikum yang bebas risiko cedera fatal.
        </p>
    </div>

    <table class="kpi-table">
        <tr>
            <td class="kpi-cell green">
                <div class="kpi-lbl">Zero Accident Record</div>
                <div class="kpi-num">{{ $zeroAccidentDays }} Hari</div>
                <div class="kpi-note">Nihil Cedera Berat / LTI</div>
            </td>
            <td class="kpi-cell blue">
                <div class="kpi-lbl">Kepatuhan APD Loket</div>
                <div class="kpi-num">{{ $complianceRate }}%</div>
                <div class="kpi-note">{{ $totalRiskBorrowings > 0 ? $verifiedRiskBorrowings . '/' . $totalRiskBorrowings . ' Selesai Clearance' : 'Belum Ada Transaksi' }}</div>
            </td>
            <td class="kpi-cell red">
                <div class="kpi-lbl">Perkakas High Risk</div>
                <div class="kpi-num">{{ $highRiskCount }} Unit</div>
                <div class="kpi-note">Wajib APD + Interlock K3</div>
            </td>
            <td class="kpi-cell amber">
                <div class="kpi-lbl">Insiden / Pelanggaran</div>
                <div class="kpi-num">{{ $totalIncidentsCount }} Kasus</div>
                <div class="kpi-note">{{ $activeViolationsCount }} Dalam Evaluasi CAPA</div>
            </td>
        </tr>
    </table>

    <div class="section-header-block">1. Matriks Identifikasi Bahaya, Penilaian Risiko & Pengendalian APD (HIRADC)</div>
    <div class="section-subnote">*Disusun mengacu standar ISO 45001:2018 Klausul 6.1 (Tindakan Menangani Risiko dan Peluang) untuk seluruh perkakas kategori sedang & tinggi.</div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 22px; text-align: center;">No</th>
                <th style="width: 65px;">Kode Alat</th>
                <th style="width: 130px;">Nama Perkakas / Mesin</th>
                <th style="width: 60px; text-align: center;">Tingkat Risiko</th>
                <th style="width: 130px;">Standar APD Wajib</th>
                <th>SOP Keselamatan & Kendali Rekayasa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riskItems as $idx => $it)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $it->kode }}</strong></td>
                    <td>
                        <strong>{{ $it->nama }}</strong><br>
                        <span style="color: #64748b; font-size: 6.5px;">Kategori: {{ $it->category->name ?? 'Perkakas' }} | Lokasi: {{ $it->location->name ?? 'Stasiun Fabrikasi' }}</span>
                    </td>
                    <td style="text-align: center;">
                        @if($it->safety_risk_level === 'high')
                            <span class="badge-danger">HIGH RISK</span>
                        @elseif($it->safety_risk_level === 'medium')
                            <span class="badge-warning">MEDIUM RISK</span>
                        @else
                            <span class="badge-success">LOW RISK</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($it->required_apd) && is_array($it->required_apd))
                            <ul style="padding-left: 10px; margin: 0; font-size: 6.5px;">
                                @foreach($it->required_apd as $apdKey)
                                    <li>{{ $apdCatalog[$apdKey]['name'] ?? ucfirst(str_replace('_', ' ', $apdKey)) }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span style="color: #94a3b8; font-style: italic;">Standar Umum Workshop</span>
                        @endif
                    </td>
                    <td style="font-size: 6.5px; color: #334155;">
                        {{ $it->safety_instruction ?: 'Wajib inspeksi visual kabel, tombol emergency stop aktif, dilarang memakai pakaian longgar / perhiasan saat mesin berputar.' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 8px;">Belum ada data perkakas dengan risiko medium / high yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-header-block">2. Buku Register Insiden, Kecelakaan Kerja & Ketidaksesuaian K3</div>
    <div class="section-subnote">*Berdasarkan pelaporan insiden, cedera ringan, kejadian hampir celaka (near-miss), maupun sanksi pelanggaran APD bengkel.</div>

    @if($incidents->count() > 0)
        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 22px; text-align: center;">No</th>
                    <th style="width: 65px;">Tanggal</th>
                    <th style="width: 95px;">Praktikan / Operator</th>
                    <th style="width: 90px;">Perkakas Terkait</th>
                    <th style="width: 75px;">Jenis Kejadian</th>
                    <th>Kronologi & Tindakan Perbaikan (CAPA)</th>
                    <th style="width: 55px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidents as $idx => $inc)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($inc->incident_date)->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $inc->user->name ?? 'Mahasiswa' }}</strong><br>
                            <span style="font-size: 6px; color: #64748b;">NIM: {{ $inc->user->nim ?? '-' }}</span>
                        </td>
                        <td>{{ $inc->item->nama ?? '-' }}</td>
                        <td>
                            <strong>
                                @if($inc->incident_type === 'minor_injury')
                                    Cedera Ringan (P3K)
                                @elseif($inc->incident_type === 'tool_misuse')
                                    Salah Prosedur Alat
                                @elseif($inc->incident_type === 'near_miss')
                                    Near-Miss (Hampir Celaka)
                                @else
                                    Pelanggaran APD
                                @endif
                            </strong>
                        </td>
                        <td style="font-size: 6.5px;">
                            <strong>Uraian:</strong> {{ $inc->description }}<br>
                            <span style="color: #0369a1;"><strong>Tindakan Korektif:</strong> {{ $inc->action_taken ?: 'Diberikan induksi K3 ulang dan evaluasi SOP pengoperasian.' }}</span>
                        </td>
                        <td style="text-align: center;">
                            @if($inc->status === 'resolved' || $inc->status === 'closed')
                                <span class="badge-success">SELESAI</span>
                            @else
                                <span class="badge-danger">INVESTIGASI</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="zero-box">
            <div class="zero-title">[SURAT KETERANGAN RESMI] NIHIL KECELAKAAN KERJA (ZERO ACCIDENT ATTESTATION)</div>
            <p class="zero-text">
                Berdasarkan rekam jejak audit sistem telemetri keselamatan kerja <em>Artilia</em>, diverifikasi bahwa selama periode operasional berjalan <strong>tidak tercatat kejadian kecelakaan kerja (Lost Time Injury / LTI), luka fatal, kerusakan alat berat, maupun insiden darurat</strong> di seluruh stasiun kerja bengkel fabrikasi manufaktur. Lingkungan praktikum beroperasi dalam parameter kepatuhan keselamatan yang aman.
            </p>
        </div>
    @endif

    <div class="section-header-block">3. Sampel Log Pemeriksaan Fisik APD Loket (Digital Safety Gate Interlock)</div>
    <div class="section-subnote">*Merekam pembuktian digital bahwa perkakas berisiko tidak diserahkan ke mahasiswa sebelum verifikasi fisik kelengkapan APD oleh petugas loket.</div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 22px; text-align: center;">No</th>
                <th style="width: 85px;">No. Peminjaman</th>
                <th style="width: 105px;">Peminjam / Praktikan</th>
                <th style="width: 115px;">Alat Berisiko</th>
                <th style="width: 85px;">Waktu Verifikasi</th>
                <th style="width: 90px;">Petugas Verifikator</th>
                <th style="width: 65px; text-align: center;">Status K3</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentClearances as $idx => $cl)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $cl->nomor_peminjaman ?? ('REQ-' . str_pad($cl->id, 5, '0', STR_PAD_LEFT)) }}</strong></td>
                    <td>
                        {{ $cl->user->name ?? '-' }}<br>
                        <span style="font-size: 6px; color: #64748b;">NIM: {{ $cl->user->nim ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $cl->item->nama ?? '-' }}
                        <span class="badge-danger" style="font-size: 5.5px;">{{ strtoupper($cl->item->safety_risk_level ?? 'HIGH') }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($cl->safety_verified_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $cl->safetyVerifier->name ?? 'Petugas Loket K3' }}</td>
                    <td style="text-align: center;">
                        <span class="badge-success">LOLOS APD</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 8px;">Belum ada riwayat transaksi peminjaman alat berisiko tinggi yang terdata pada log sirkulasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="recommendation-box">
        <div class="recommendation-title">Kesimpulan & Rekomendasi Auditor K3 (Continual Improvement)</div>
        <ol class="recommendation-list">
            <li><strong>Penguatan Budaya K3 Vokasi:</strong> Pertahankan kedisiplinan verifikasi loket digital 100% sebelum serah terima perkakas <em>high risk</em> ke praktikan.</li>
            <li><strong>Inspeksi Kalibrasi & Perkakas:</strong> Jadwalkan pengecekan berkala terhadap sensor pengaman, tombol <em>Emergency Stop</em>, dan batas usia pakai (tool life) mesin bubut, frais, dan gerinda.</li>
            <li><strong>Kesiapsiagaan Tanggap Darurat:</strong> Pastikan kotak P3K Tipe B, titik kumpul evakuasi (assembly point), serta APAR CO2/Powder selalu dalam kondisi terinspeksi bulanan.</li>
        </ol>
    </div>

    <table class="signature-block">
        <tr>
            <td class="signature-cell">
                <div class="sig-status-label">Disusun Oleh:</div>
                <div class="sig-role-title">Auditor Internal K3 / Laboran</div>
                <div class="sig-space"></div>
                <div class="sig-name-underline">{{ $printed_by }}</div>
                <div class="sig-credential">SK Petugas K3: HSE-2026/01</div>
            </td>
            <td class="signature-cell">
                <div class="sig-status-label">Diverifikasi Oleh:</div>
                <div class="sig-role-title">Ahli K3 Umum (Kemenaker RI)</div>
                <div class="sig-space"></div>
                <div class="sig-name-underline">Ir. Budi Santoso, S.T., M.T.</div>
                <div class="sig-credential">Reg. Kemenaker: 562/PK3/2024</div>
            </td>
            <td class="signature-cell">
                <div class="sig-status-label">Disahkan & Disetujui:</div>
                <div class="sig-role-title">Kepala Lab & Workshop Vokasi</div>
                <div class="sig-space"></div>
                <div class="sig-name-underline">Dr. Eng. Haryanto, M.Eng.</div>
                <div class="sig-credential">NIP. 19850412 201012 1 004</div>
            </td>
        </tr>
    </table>

    <table class="legal-footer-table">
        <tr>
            <td style="width: 130px;">
                <div class="seal-box">
                    <div class="seal-tag">AUTHENTIC AUDIT SEAL</div>
                    <div class="seal-code">{{ $docNumber }}</div>
                </div>
            </td>
            <td class="legal-note">
                <strong>Otentikasi Dokumen Elektronik:</strong> Laporan audit ini diterbitkan secara sah oleh platform <em>Artilia Trust Engine</em> dengan enkripsi integritas data sesuai ketentuan UU ITE No. 11/2008 Pasal 5 ayat (1) serta klausul 9.2 ISO 45001:2018. Dokumen ini bebas dari intervensi manual dan diakui sebagai rekapitulasi audit berkala resmi laboratorium. Dicetak pada: {{ $print_time }}.
            </td>
        </tr>
    </table>

</body>
</html>
