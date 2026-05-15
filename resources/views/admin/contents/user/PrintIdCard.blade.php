<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card – {{ $user->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    @php
        $role = $user->role->value ?? 'user';
        $orgName    = \App\Models\Setting::get('company_name', 'Artilia');
        $orgAddress = \App\Models\Setting::get('company_address', '');
        $orgPhone   = \App\Models\Setting::get('company_phone', '');
        $orgLogo    = \App\Models\Setting::get('company_logo', '');

        $roleConfig = match($role) {
            'admin'    => [
                'label'       => 'ADMINISTRATOR',
                'color1'      => '#1e1b4b',
                'color2'      => '#312e81',
                'color3'      => '#4338ca',
                'accent'      => '#818cf8',
                'badge'       => '#6366f1',
                'badgetxt'    => '#ffffff',
                'stripe'      => '#a5b4fc',
                'icon'        => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            ],
            'operator' => [
                'label'       => 'OPERATOR / STAFF',
                'color1'      => '#052e16',
                'color2'      => '#14532d',
                'color3'      => '#16a34a',
                'accent'      => '#4ade80',
                'badge'       => '#22c55e',
                'badgetxt'    => '#052e16',
                'stripe'      => '#86efac',
                'icon'        => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            ],
            default    => [
                'label'       => 'ANGGOTA',
                'color1'      => '#0c1a2e',
                'color2'      => '#1e3a5f',
                'color3'      => '#1d4ed8',
                'accent'      => '#60a5fa',
                'badge'       => '#3b82f6',
                'badgetxt'    => '#ffffff',
                'stripe'      => '#93c5fd',
                'icon'        => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            ],
        };

        $userId = str_pad($user->id, 6, '0', STR_PAD_LEFT);
        $issuedDate = $user->created_at ? $user->created_at->format('d/m/Y') : now()->format('d/m/Y');
    @endphp

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e8eaf0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 20px;
        }

        /* ─── Screen toolbar ──────────────────────────── */
        .screen-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            background: #fff;
            border-radius: 14px;
            padding: 14px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }
        .screen-toolbar h1 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.18s;
        }
        .btn-print {
            background: {{ $roleConfig['color3'] }};
            color: #fff;
        }
        .btn-print:hover { opacity: 0.88; }
        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-back:hover { background: #e2e8f0; }

        /* ─── Cards wrapper ───────────────────────────── */
        .cards-wrapper {
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* ─── ID Card ──────────────────────────────────── */
        .id-card {
            width: 340px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.22), 0 4px 16px rgba(0,0,0,0.12);
            position: relative;
            background: #fff;
        }

        /* Front card */
        .card-front { }

        /* Header band */
        .card-header {
            background: linear-gradient(135deg, {{ $roleConfig['color1'] }} 0%, {{ $roleConfig['color3'] }} 100%);
            padding: 24px 24px 20px;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 130px; height: 130px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -20px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .org-row {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .org-logo {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: -0.5px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .org-logo img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .org-info { flex: 1; }
        .org-name {
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
            line-height: 1.3;
        }
        .org-sub {
            color: {{ $roleConfig['accent'] }};
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .divider-line {
            height: 1px;
            background: rgba(255,255,255,0.15);
            margin: 14px 0;
            position: relative;
            z-index: 1;
        }

        .card-type-label {
            color: rgba(255,255,255,0.7);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }
        .card-type-title {
            color: #fff;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-top: 2px;
            position: relative;
            z-index: 1;
        }

        /* Photo + name strip */
        .card-body {
            background: #fff;
            padding: 0;
        }

        .photo-strip {
            display: flex;
            align-items: flex-end;
            padding: 20px 20px 0;
            gap: 16px;
        }
        .photo-container {
            position: relative;
            flex-shrink: 0;
        }
        .photo-frame {
            width: 90px; height: 90px;
            border-radius: 14px;
            border: 3px solid {{ $roleConfig['color3'] }};
            overflow: hidden;
            background: #f1f5f9;
        }
        .photo-frame img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .status-dot {
            position: absolute;
            bottom: -4px; right: -4px;
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 3px solid #fff;
            background: {{ $user->is_active ? '#22c55e' : '#ef4444' }};
        }

        .name-section { flex: 1; padding-bottom: 4px; }
        .user-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            word-break: break-word;
        }
        .user-email {
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
            word-break: break-all;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: {{ $roleConfig['badge'] }};
            color: {{ $roleConfig['badgetxt'] }};
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-top: 8px;
        }
        .role-badge svg {
            width: 11px; height: 11px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Info grid */
        .info-grid {
            padding: 18px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 16px;
        }
        .info-item {}
        .info-label {
            font-size: 8.5px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .info-value {
            font-size: 11.5px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
        }

        /* Barcode strip */
        .barcode-strip {
            background: linear-gradient(135deg, {{ $roleConfig['color1'] }} 0%, {{ $roleConfig['color2'] }} 100%);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .barcode-left { }
        .id-number {
            color: {{ $roleConfig['accent'] }};
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .id-value {
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 3px;
            margin-top: 2px;
        }
        .barcode-bars {
            display: flex;
            align-items: flex-end;
            gap: 2.5px;
            height: 32px;
        }
        .barcode-bars span {
            display: block;
            width: 2px;
            background: rgba(255,255,255,0.7);
            border-radius: 2px;
        }

        /* Stripe decoration */
        .stripe-row {
            height: 6px;
            display: flex;
        }
        .stripe-row span {
            flex: 1;
            background: {{ $roleConfig['stripe'] }};
            opacity: 0.4;
        }
        .stripe-row span:nth-child(2n) { opacity: 0.7; }
        .stripe-row span:nth-child(3n) { opacity: 1; }

        /* QR code */
        .qr-wrap {
            background: #fff;
            border-radius: 6px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .qr-wrap canvas {
            display: block;
            border-radius: 2px;
        }
        .back-qr-section {
            border-top: 1px solid #f1f5f9;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
        }
        .back-qr-info { flex: 1; }
        .back-qr-title {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .back-qr-sub {
            font-size: 8.5px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.4;
        }

        /* ─── Back card ──────────────────────────────── */
        .card-back {
            margin-top: 12px;
        }

        .back-header {
            background: linear-gradient(135deg, {{ $roleConfig['color1'] }} 0%, {{ $roleConfig['color2'] }} 100%);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-header-title {
            color: rgba(255,255,255,0.7);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .back-body {
            background: #fff;
            padding: 16px 20px;
        }
        .info-row {
            display: flex;
            align-items: baseline;
            gap: 6px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row-label {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 90px;
        }
        .info-row-value {
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            flex: 1;
        }

        .back-footer {
            background: linear-gradient(135deg, {{ $roleConfig['color1'] }} 0%, {{ $roleConfig['color3'] }} 100%);
            padding: 12px 20px;
            text-align: center;
        }
        .back-footer-text {
            color: rgba(255,255,255,0.6);
            font-size: 8.5px;
            letter-spacing: 0.5px;
        }
        .back-footer-org {
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* ─── Card label ─────────────────────────────── */
        .card-label {
            text-align: center;
            color: #64748b;
            font-size: 11px;
            font-weight: 500;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }

        /* ─── Print styles ───────────────────────────── */

        /* Force color printing for ALL elements */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                background: white;
                padding: 0;
                display: block;
            }

            .screen-toolbar {
                display: none !important;
            }

            .cards-wrapper {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 12mm;
                justify-content: center;
                padding-top: 8mm;
            }

            .card-group {
                break-inside: avoid;
            }

            .id-card {
                box-shadow: none;
                border: 0.5pt solid #d1d5db;
                width: 85.6mm; /* standard ID card width */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Ensure all colored elements print correctly */
            .card-header,
            .barcode-strip,
            .back-header,
            .back-footer,
            .stripe-row span,
            .role-badge,
            .status-dot {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .btn { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Screen Toolbar --}}
    <div class="screen-toolbar">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ $roleConfig['color3'] }}" stroke-width="2" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
        </svg>
        <h1>Kartu Identitas – {{ $user->name }}</h1>
        <a href="{{ url()->previous() }}" class="btn btn-back">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak / Print
        </button>
    </div>

    {{-- Cards --}}
    <div class="cards-wrapper">

        {{-- ════ CARD GROUP ════ --}}
        <div class="card-group">

            {{-- ── Front Card ── --}}
            <div class="id-card card-front">

                {{-- Stripe decoration --}}
                <div class="stripe-row">
                    @for($i=0;$i<20;$i++)<span></span>@endfor
                </div>

                {{-- Header --}}
                <div class="card-header">
                    <div class="org-row">
                        <div class="org-logo">
                            @if($orgLogo && file_exists(public_path($orgLogo)))
                                <img src="{{ asset($orgLogo) }}" alt="Logo">
                            @else
                                {{ strtoupper(substr($orgName, 0, 2)) }}
                            @endif
                        </div>
                        <div class="org-info">
                            <div class="org-name">{{ $orgName }}</div>
                            @if($orgAddress)
                                <div class="org-sub">{{ Str::limit($orgAddress, 35) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="divider-line"></div>
                    <div class="card-type-label">Kartu Identitas</div>
                    <div class="card-type-title">{{ $roleConfig['label'] }}</div>
                </div>

                {{-- Body --}}
                <div class="card-body">

                    {{-- Photo + name --}}
                    <div class="photo-strip">
                        <div class="photo-container">
                            <div class="photo-frame">
                                @if($user->profil)
                                    <img src="{{ asset($user->profil) }}"
                                         alt="{{ $user->name }}"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=ffffff&background={{ ltrim($roleConfig['color3'], '#') }}&size=200'">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=ffffff&background={{ ltrim($roleConfig['color3'], '#') }}&size=200"
                                         alt="{{ $user->name }}">
                                @endif
                            </div>
                            <div class="status-dot"></div>
                        </div>
                        <div class="name-section">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-email">{{ $user->email }}</div>
                            <div class="role-badge">
                                <svg viewBox="0 0 24 24">
                                    <path d="{{ $roleConfig['icon'] }}"/>
                                </svg>
                                {{ $roleConfig['label'] }}
                            </div>
                        </div>
                    </div>

                    {{-- Info grid --}}
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID Pengguna</div>
                            <div class="info-value">#{{ $userId }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value" style="color: {{ $user->is_active ? '#16a34a' : '#dc2626' }}">
                                {{ $user->is_active ? '● Aktif' : '● Nonaktif' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Bergabung</div>
                            <div class="info-value">{{ $issuedDate }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">No. WhatsApp</div>
                            <div class="info-value">{{ $user->whatsapp_number ?: '-' }}</div>
                        </div>
                    </div>

                    {{-- Barcode / QR strip --}}
                    <div class="barcode-strip">
                        <div class="barcode-left">
                            <div class="id-number">Nomor Kartu</div>
                            <div class="id-value">ID-{{ $userId }}-{{ strtoupper(substr($role, 0, 3)) }}</div>
                        </div>
                        {{-- QR Code (small, on front card) --}}
                        <div class="qr-wrap" id="qr-front-wrap">
                            <canvas id="qr-front"></canvas>
                        </div>
                    </div>

                    {{-- Bottom stripe --}}
                    <div class="stripe-row">
                        @for($i=0;$i<20;$i++)<span></span>@endfor
                    </div>

                </div>
            </div>
            <div class="card-label">Tampak Depan</div>

            {{-- ── Back Card ── --}}
            <div class="id-card card-back" style="margin-top: 16px;">

                <div class="stripe-row">
                    @for($i=0;$i<20;$i++)<span></span>@endfor
                </div>

                <div class="back-header">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="{{ $roleConfig['accent'] }}" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <div class="back-header-title">Informasi Kartu</div>
                    </div>
                </div>

                <div class="back-body">
                    <div class="info-row">
                        <span class="info-row-label">Nama</span>
                        <span class="info-row-value">{{ $user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Email</span>
                        <span class="info-row-value">{{ $user->email }}</span>
                    </div>
                    @if($user->whatsapp_number)
                    <div class="info-row">
                        <span class="info-row-label">WhatsApp</span>
                        <span class="info-row-value">{{ $user->whatsapp_number }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-row-label">Role</span>
                        <span class="info-row-value">{{ $roleConfig['label'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">ID Kartu</span>
                        <span class="info-row-value">ID-{{ $userId }}-{{ strtoupper(substr($role, 0, 3)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Status</span>
                        <span class="info-row-value" style="color: {{ $user->is_active ? '#16a34a' : '#dc2626' }}">
                            {{ $user->is_active ? '● Aktif' : '● Nonaktif' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Bergabung</span>
                        <span class="info-row-value">{{ $issuedDate }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-row-label">Masa Berlaku</span>
                        <span class="info-row-value">Tidak Terbatas</span>
                    </div>
                    @if($user->bio)
                    <div class="info-row" style="flex-direction: column; gap: 4px;">
                        <span class="info-row-label">Keterangan</span>
                        <span class="info-row-value" style="color: #64748b; font-size: 10px;">{{ $user->bio }}</span>
                    </div>
                    @endif
                    @if($orgPhone)
                    <div class="info-row">
                        <span class="info-row-label">Telepon Org</span>
                        <span class="info-row-value">{{ $orgPhone }}</span>
                    </div>
                    @endif
                </div>

                {{-- QR Code section (back card) --}}
                <div class="back-qr-section">
                    <div class="qr-wrap" id="qr-back-wrap">
                        <canvas id="qr-back"></canvas>
                    </div>
                    <div class="back-qr-info">
                        <div class="back-qr-title">QR Identitas Digital</div>
                        <div class="back-qr-sub">
                            Scan untuk verifikasi saat<br>
                            peminjaman atau barang keluar.
                        </div>
                    </div>
                </div>

                <div class="back-footer">
                    <div class="back-footer-text">Kartu ini adalah milik {{ $orgName }}</div>
                    <div class="back-footer-org">{{ strtoupper($orgName) }}</div>
                </div>

                <div class="stripe-row">
                    @for($i=0;$i<20;$i++)<span></span>@endfor
                </div>

            </div>
            <div class="card-label">Tampak Belakang</div>

        </div>{{-- /card-group --}}

    </div>{{-- /cards-wrapper --}}

<script>
    // Data identitas user yang di-encode ke QR
    var qrData = JSON.stringify({
        id:    {{ $user->id }},
        name:  @json($user->name),
        email: @json($user->email),
        role:  @json($user->role->value ?? 'user'),
        card:  'ID-{{ $userId }}-{{ strtoupper(substr($role, 0, 3)) }}',
        app:   'artilia'
    });

    // Helper: render QR ke canvas lalu ganti dengan <img> agar bisa tercetak
    function renderQr(canvasId, wrapId, size, fg, bg) {
        var canvas = document.getElementById(canvasId);
        if (!canvas) return;

        new QRious({
            element:    canvas,
            value:      qrData,
            size:       size,
            foreground: fg,
            background: bg,
            level:      'H'
        });

        // Ganti canvas → img agar print-safe
        var img   = document.createElement('img');
        img.src   = canvas.toDataURL('image/png');
        img.width  = size;
        img.height = size;
        img.style.display = 'block';
        img.style.borderRadius = '2px';

        var wrap = document.getElementById(wrapId);
        if (wrap) {
            wrap.innerHTML = '';
            wrap.appendChild(img);
        }
    }

    // Kedua QR: gelap di atas putih agar mudah di-scan kamera
    // (.qr-wrap sudah punya background #fff, jadi tetap terlihat bagus di strip gelap)
    renderQr('qr-front', 'qr-front-wrap', 90, '{{ $roleConfig["color1"] }}', '#ffffff');
    renderQr('qr-back',  'qr-back-wrap',  110, '{{ $roleConfig["color1"] }}', '#ffffff');
</script>
</body>
</html>
