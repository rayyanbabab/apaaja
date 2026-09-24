@php
    $companyName = \App\Models\Setting::get('company_name', 'Artilia');
    $companyLogo = \App\Models\Setting::get('company_logo', null);
    $companyTagline = \App\Models\Setting::get('company_tagline');
    if (empty(trim((string)$companyTagline))) {
        $companyTagline = 'Inventory & Tooling Management System';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <script>
        (function () {
            var saved = localStorage.getItem('artilia_dark_mode');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (saved === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
        function toggleDarkMode() {
            var html = document.documentElement;
            var isDark = html.classList.toggle('dark');
            localStorage.setItem('artilia_dark_mode', isDark ? 'dark' : 'light');
            var sun  = document.getElementById('login-dm-sun');
            var moon = document.getElementById('login-dm-moon');
            if (sun && moon) {
                sun.classList.toggle('hidden', !isDark);
                moon.classList.toggle('hidden', isDark);
            }
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $companyName }} — Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/artilia.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .bg-subtle-grid {
            background-image: radial-gradient(rgba(148, 163, 184, 0.15) 1px, transparent 1px);
            background-size: 22px 22px;
        }
        html.dark .bg-subtle-grid {
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 22px 22px;
        }
    </style>
</head>
<body class="min-h-full bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200 flex flex-col selection:bg-blue-600 selection:text-white">

    <button type="button"
            onclick="toggleDarkMode()"
            class="fixed top-4 right-4 sm:top-6 sm:right-6 z-50 p-2.5 rounded-xl bg-white/90 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 backdrop-blur-md text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white shadow-sm hover:shadow transition-all cursor-pointer"
            aria-label="Toggle tema">
        <svg id="login-dm-sun" class="hidden w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <svg id="login-dm-moon" class="w-5 h-5 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>

    <script>
        (function() {
            var sun = document.getElementById('login-dm-sun');
            var moon = document.getElementById('login-dm-moon');
            if (document.documentElement.classList.contains('dark')) {
                if (sun) sun.classList.remove('hidden');
                if (moon) moon.classList.add('hidden');
            }
        })();
    </script>

    <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50 dark:bg-slate-950">

        <div class="w-full lg:w-[480px] xl:w-[520px] 2xl:w-[560px] flex-shrink-0 flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-14 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shadow-xs lg:shadow-none min-h-screen">
            <div>
                <div class="flex items-center gap-3">
                    @if($companyLogo)
                        <div class="h-11 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset($companyLogo) }}" alt="{{ $companyName }}" class="h-6 w-auto max-w-[130px] object-contain">
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-600/20 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight">
                            {{ $companyName }}
                        </h1>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $companyTagline }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="my-auto py-8 w-full max-w-sm sm:max-w-md mx-auto lg:max-w-none">
                <div class="mb-7">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                        Gunakan alamat email dan kata sandi yang terdaftar untuk mengakses sistem inventaris.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/60 flex items-start gap-3 text-red-700 dark:text-red-400 text-xs">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span class="leading-relaxed font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 flex items-start gap-3 text-emerald-700 dark:text-emerald-400 text-xs">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="leading-relaxed font-medium">{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="/login" id="loginForm" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   placeholder="nama@email.com"
                                   class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border border-slate-300 dark:border-slate-700/80 bg-white dark:bg-slate-900/90 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all shadow-xs">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </div>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="block w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-slate-300 dark:border-slate-700/80 bg-white dark:bg-slate-900/90 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition-all shadow-xs">
                            <button type="button"
                                    onclick="togglePasswordVisibility()"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer"
                                    aria-label="Lihat kata sandi">
                                <svg id="pwEyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="pwEyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <div id="capsLockAlert" class="hidden items-center gap-1.5 text-[11px] text-amber-600 dark:text-amber-400 mt-1.5 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Caps Lock aktif</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label for="remember" class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox"
                                   id="remember"
                                   name="remember"
                                   class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500 bg-white dark:bg-slate-900 cursor-pointer">
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                id="submitBtn"
                                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm shadow-sm hover:shadow shadow-blue-600/25 transition-all focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900 active:scale-[0.99] disabled:opacity-70 disabled:cursor-not-allowed cursor-pointer">
                            <span id="btnIcon">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <span id="btnText">Masuk ke Sistem</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Autentikasi Terenkripsi
                    </span>
                    <span>Sesi Terlindungi</span>
                </div>
            </div>

            <div class="text-xs text-slate-500 dark:text-slate-500">
                &copy; {{ date('Y') }} {{ $companyName }}. Hak cipta dilindungi.
            </div>
        </div>

        <div class="hidden lg:flex flex-1 flex-col justify-between p-10 xl:p-14 2xl:p-16 bg-slate-950 text-white relative overflow-hidden bg-subtle-grid">
            <div class="flex items-center justify-between z-10">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-xs font-semibold tracking-wider text-slate-300 uppercase">
                        Sistem Inventaris & Perkakas
                    </span>
                </div>
                <span class="text-xs text-slate-400 font-mono">
                    Artilia Core v2.4
                </span>
            </div>

            <div class="max-w-xl z-10 my-auto py-6">
                <div>
                    <h2 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white leading-tight">
                        Kendali Penuh Atas Perkakas & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">Aset Operasional Anda</span>
                    </h2>
                    <p class="text-sm text-slate-400 mt-3 leading-relaxed">
                        Pencatatan inventaris terpusat, alur peminjaman terstruktur, serta pelacakan kalibrasi berkala untuk memastikan setiap alat siap pakai.
                    </p>
                </div>

                <div class="mt-7 rounded-2xl bg-slate-900/90 border border-slate-800 p-5 shadow-2xl backdrop-blur-md">
                    <div class="flex items-center justify-between pb-3.5 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-slate-200">Status Operasional Inventaris</span>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400 bg-slate-800/80 px-2 py-0.5 rounded border border-slate-700/60">Shift 1 • Aktif</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5 my-3.5">
                        <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                            <span class="text-[11px] text-slate-400 block">Total Unit</span>
                            <span class="text-base font-bold text-white mt-0.5 block">1.420 <span class="text-[10px] font-normal text-slate-400">aset</span></span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                            <span class="text-[11px] text-slate-400 block">Dipinjam</span>
                            <span class="text-base font-bold text-amber-400 mt-0.5 block">28 <span class="text-[10px] font-normal text-slate-400">item</span></span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-950/60 border border-slate-800/80">
                            <span class="text-[11px] text-slate-400 block">Siap Pakai</span>
                            <span class="text-base font-bold text-emerald-400 mt-0.5 block">1.392 <span class="text-[10px] font-normal text-slate-400">unit</span></span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/70 hover:border-slate-700/80 transition-all cursor-default">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-500/15 text-blue-400 flex items-center justify-center font-mono text-[10px] font-bold">
                                    OSC
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-200">Digital Storage Oscilloscope DS1054Z</div>
                                    <div class="text-[11px] text-slate-400">Lab Elektronika • ART-EL-042</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">Dipinjam</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/70 hover:border-slate-700/80 transition-all cursor-default">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-mono text-[10px] font-bold">
                                    MIC
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-200">Micrometer Outside 0-25mm Mitutoyo</div>
                                    <div class="text-[11px] text-slate-400">Lemari Presisi A-02 • ART-QC-018</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Tersedia</span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/70 hover:border-slate-700/80 transition-all cursor-default">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-sky-500/15 text-sky-400 flex items-center justify-center font-mono text-[10px] font-bold">
                                    TRQ
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-200">Torque Wrench Stahlwille 730N/10</div>
                                    <div class="text-[11px] text-slate-400">Jadwal Kalibrasi 28 Sep • ART-MS-009</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">Terkalibrasi</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-4 pt-4 border-t border-slate-800/80">
                    <div>
                        <div class="text-xs font-semibold text-slate-200">Stok & Logistik</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Barang masuk, keluar, dan stock opname berkala.</p>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-200">BAP Digital</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Alur peminjaman dengan penyerahan terstruktur.</p>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-slate-200">Kalibrasi Berkala</div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Jadwal servis dan inspeksi kelayakan operasional.</p>
                    </div>
                </div>
            </div>

            <div class="text-xs text-slate-500 z-10 flex items-center justify-between">
                <span>Infrastruktur Manajemen Perkakas Terintegrasi</span>
                <span class="font-mono text-[11px]">Sistem Berjalan Normal</span>
            </div>
        </div>

    </div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('pwEyeOpen');
        const eyeClosed = document.getElementById('pwEyeClosed');
        const isHidden = input.type === 'password';
        
        input.type = isHidden ? 'text' : 'password';
        if (isHidden) {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    const passwordInput = document.getElementById('password');
    const capsAlert = document.getElementById('capsLockAlert');
    if (passwordInput && capsAlert) {
        passwordInput.addEventListener('keyup', function(e) {
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                capsAlert.classList.remove('hidden');
                capsAlert.classList.add('flex');
            } else {
                capsAlert.classList.remove('flex');
                capsAlert.classList.add('hidden');
            }
        });
    }

    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');
        
        btn.disabled = true;
        btnIcon.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        btnText.textContent = 'Memproses...';
    });
</script>
</body>
</html>
