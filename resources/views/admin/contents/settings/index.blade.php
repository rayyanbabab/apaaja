@extends('admin.layouts.dashboard')

@section('content')
<style>
/* ══ Settings Page Dark Mode ══ */

/* All cards */
html.dark .settings-card { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .settings-card-header { background-color: transparent !important; border-color: #334155 !important; }
html.dark .settings-card-footer { border-color: #334155 !important; }

/* Section dividers inside cards */
html.dark .settings-divider { border-color: #1e293b !important; }

/* Icon wrappers */
html.dark .settings-icon-indigo { background-color: rgba(99,102,241,0.2) !important; }
html.dark .settings-icon-blue   { background-color: rgba(59,130,246,0.2) !important; }
html.dark .settings-icon-amber  { background-color: rgba(245,158,11,0.2) !important; }
html.dark .settings-icon-red    { background-color: rgba(239,68,68,0.2)  !important; }

/* Headings & labels */
html.dark .settings-section-title { color: #e2e8f0 !important; }
html.dark .settings-section-sub   { color: #64748b !important; }
html.dark .settings-label         { color: #94a3b8 !important; }
html.dark .settings-hint          { color: #64748b !important; }
html.dark .settings-unit          { color: #64748b !important; }

/* Page title */
html.dark .settings-page-title { color: #f1f5f9 !important; }
html.dark .settings-page-sub   { color: #64748b !important; }

/* Toggle row text */
html.dark .settings-toggle-label { color: #e2e8f0 !important; }
html.dark .settings-toggle-desc  { color: #64748b !important; }

/* Toggle switch track (off state) */
html.dark .settings-toggle-track {
    background-color: #374151 !important;
}
/* Toggle track: checked state stays blue */
html.dark input.sr-only.peer:checked + .settings-toggle-track {
    background-color: #2563eb !important;
}
/* Toggle knob stays white for contrast */
html.dark .settings-toggle-track::after { background-color: #f8fafc !important; }

/* Input fields */
html.dark .settings-input {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
}
html.dark .settings-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 1px #3b82f6 !important;
}

/* File upload button */
html.dark .settings-file-btn {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #94a3b8 !important;
}
html.dark .settings-file-btn:hover { background-color: #334155 !important; }

/* Info box (blue tip) */
html.dark .settings-info-box {
    background-color: rgba(37,99,235,0.15) !important;
    border-color: rgba(37,99,235,0.3) !important;
    color: #93c5fd !important;
}

/* WhatsApp toggle row */
html.dark .settings-wa-row {
    background-color: rgba(16,185,129,0.08) !important;
    border-color: rgba(16,185,129,0.2) !important;
}

/* Logo preview box */
html.dark .settings-logo-box {
    border-color: #334155 !important;
    background-color: #0f172a !important;
}

/* Logo placeholder */
html.dark .settings-logo-placeholder {
    border-color: #334155 !important;
    background-color: #0f172a !important;
}
</style>
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="settings-page-title text-2xl font-bold text-gray-900">⚙️ Pengaturan Sistem</h2>
        <p class="settings-page-sub mt-1 text-sm text-gray-500">Kelola konfigurasi peminjaman barang, notifikasi, dan profil perusahaan.</p>
    </div>

    {{-- Success Alerts --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('success_company'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success_company') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════
         SECTION 1: PROFIL PERUSAHAAN (form terpisah)
    ══════════════════════════════════════════════ --}}
    <form id="update-company-form" method="POST" action="{{ route($routePrefix . '.settings.company.update') }}"
          enctype="multipart/form-data" class="space-y-6 mb-8">
        @csrf

        <div class="settings-card rounded-2xl border border-indigo-200 bg-white shadow-sm">
            <div class="settings-card-header border-b border-indigo-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="settings-icon-indigo flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="settings-section-title text-base font-semibold text-gray-900">Profil Perusahaan</h3>
                        <p class="settings-section-sub text-xs text-gray-500">Informasi yang ditampilkan di laporan PDF &amp; Excel</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8 space-y-8">

                {{-- Logo Upload --}}
                <div>
                    <label class="settings-label block text-sm font-medium text-gray-700 mb-2">Logo Perusahaan</label>
                    <div class="flex items-start gap-5">
                        {{-- Preview --}}
                        <div class="flex-shrink-0">
                            @if($settings['company_logo']->value ?? null)
                                <img id="logo-preview"
                                     src="{{ asset($settings['company_logo']->value) }}"
                                     alt="Logo"
                                     class="settings-logo-box h-20 w-20 rounded-xl object-contain border border-gray-200 bg-gray-50 p-1">
                            @else
                                <div id="logo-placeholder"
                                     class="settings-logo-placeholder flex h-20 w-20 items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <img id="logo-preview" src="#" alt="Preview" class="hidden h-20 w-20 rounded-xl object-contain border border-gray-200 bg-gray-50 p-1">
                            @endif
                        </div>
                        {{-- Input --}}
                        <div class="flex-1">
                            <label for="company_logo"
                                   class="settings-file-btn inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition">
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Pilih File Logo
                            </label>
                            <input id="company_logo" name="company_logo" type="file"
                                   accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                                   class="sr-only">
                            <p id="logo-filename" class="settings-hint mt-1 text-xs text-gray-400">PNG, JPG, atau SVG · Maks 2 MB</p>
                            @error('company_logo')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Nama Perusahaan --}}
                <div>
                    <label for="company_name" class="settings-label block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" id="company_name" name="company_name"
                           value="{{ old('company_name', $settings['company_name']->value ?? 'PT. ARTILIA') }}"
                           class="settings-input w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('company_name') border-red-400 @enderror"
                           placeholder="Contoh: PT. Artilia Sentosa">
                    @error('company_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Tagline --}}
                <div>
                    <label for="company_tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline / Keterangan</label>
                    <input type="text" id="company_tagline" name="company_tagline"
                           value="{{ old('company_tagline', $settings['company_tagline']->value ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                           placeholder="Contoh: Inventory Management System">
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="company_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan</label>
                    <textarea id="company_address" name="company_address" rows="2"
                              class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                              placeholder="Jl. Contoh No. 1, Jakarta">{{ old('company_address', $settings['company_address']->value ?? '') }}</textarea>
                </div>

                {{-- Telepon & Email --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" id="company_phone" name="company_phone"
                               value="{{ old('company_phone', $settings['company_phone']->value ?? '') }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                               placeholder="(021) 1234567">
                    </div>
                    <div>
                        <label for="company_email" class="block text-sm font-medium text-gray-700 mb-1">Email Perusahaan</label>
                        <input type="email" id="company_email" name="company_email"
                               value="{{ old('company_email', $settings['company_email']->value ?? '') }}"
                               class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                               placeholder="info@perusahaan.com">
                        @error('company_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

            </div>

            <div class="border-t border-gray-100 px-6 py-4 flex justify-end">
                <button type="button" onclick="openModal('update-company-modal')"
                        style="background:#4F46E5;color:#fff;border:none;cursor:pointer;"
                        class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm transition-colors"
                        onmouseover="this.style.background='#4338CA'" onmouseout="this.style.background='#4F46E5'">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Profil Perusahaan
                </button>
            </div>
        </div>
    </form>

    {{-- ══════════════════════════════════════════════
         SECTION 2: PENGATURAN SISTEM (form asli)
    ══════════════════════════════════════════════ --}}
    <form id="update-settings-form" method="POST" action="{{ route($routePrefix . '.settings.update') }}" class="space-y-6">
        @csrf

        {{-- Peminjaman Settings Card --}}
        <div class="settings-card rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="settings-card-header border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="settings-icon-blue flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="settings-section-title text-base font-semibold text-gray-900">Aturan Peminjaman</h3>
                        <p class="settings-section-sub text-xs text-gray-500">Konfigurasi durasi dan batas jumlah peminjaman</p>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-50 px-6 py-4 space-y-5">
                {{-- Max Borrow Days --}}
                <div class="pt-2 first:pt-0">
                    <label for="max_borrow_days" class="settings-label block text-sm font-medium text-gray-700 mb-1">
                        {{ $settings['max_borrow_days']->label ?? 'Maksimal Hari Peminjaman' }}
                    </label>
                    <p class="settings-hint text-xs text-gray-400 mb-2">{{ $settings['max_borrow_days']->description ?? '' }}</p>
                    <div class="flex items-center gap-3">
                        <input type="number" id="max_borrow_days" name="max_borrow_days"
                            value="{{ old('max_borrow_days', $settings['max_borrow_days']->value ?? 7) }}"
                            min="1" max="365"
                            class="settings-input w-32 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('max_borrow_days') border-red-400 @enderror">
                        <span class="settings-unit text-sm text-gray-500">hari</span>
                    </div>
                    @error('max_borrow_days')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Items Per User --}}
                <div class="pt-5">
                    <label for="max_items_per_user" class="settings-label block text-sm font-medium text-gray-700 mb-1">
                        {{ $settings['max_items_per_user']->label ?? 'Maksimal Item per User' }}
                    </label>
                    <p class="settings-hint text-xs text-gray-400 mb-2">{{ $settings['max_items_per_user']->description ?? '' }}</p>
                    <div class="flex items-center gap-3">
                        <input type="number" id="max_items_per_user" name="max_items_per_user"
                            value="{{ old('max_items_per_user', $settings['max_items_per_user']->value ?? 3) }}"
                            min="1" max="20"
                            class="settings-input w-32 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('max_items_per_user') border-red-400 @enderror">
                        <span class="settings-unit text-sm text-gray-500">item aktif (pending + approved)</span>
                    </div>
                    @error('max_items_per_user')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Notification Settings Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="settings-icon-amber flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="settings-section-title text-base font-semibold text-gray-900">Pengaturan Notifikasi</h3>
                        <p class="settings-section-sub text-xs text-gray-500">Aktifkan/nonaktifkan pengiriman notifikasi reminder otomatis</p>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-50 px-6 py-2">
                {{-- Overdue Reminder Toggle --}}
                <div class="flex items-center justify-between py-4">
                    <div class="flex-1">
                        <p class="settings-toggle-label text-sm font-medium text-gray-800">{{ $settings['enable_overdue_reminder']->label ?? 'Pengingat Terlambat' }}</p>
                        <p class="settings-toggle-desc text-xs text-gray-400 mt-0.5">{{ $settings['enable_overdue_reminder']->description ?? '' }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center ml-4">
                        <input type="checkbox" id="enable_overdue_reminder" name="enable_overdue_reminder" value="1"
                            {{ (old('enable_overdue_reminder', $settings['enable_overdue_reminder']->value ?? 1)) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="settings-toggle-track h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-400 transition-colors after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>

                {{-- Pending Reminder Toggle --}}
                <div class="flex items-center justify-between py-4">
                    <div class="flex-1">
                        <p class="settings-toggle-label text-sm font-medium text-gray-800">{{ $settings['enable_pending_reminder']->label ?? 'Pengingat Pengajuan Pending' }}</p>
                        <p class="settings-toggle-desc text-xs text-gray-400 mt-0.5">{{ $settings['enable_pending_reminder']->description ?? '' }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center ml-4">
                        <input type="checkbox" id="enable_pending_reminder" name="enable_pending_reminder" value="1"
                            {{ (old('enable_pending_reminder', $settings['enable_pending_reminder']->value ?? 1)) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="settings-toggle-track h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-400 transition-colors after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>

                {{-- Low Stock Alert Toggle --}}
                <div class="flex items-center justify-between py-4">
                    <div class="flex-1">
                        <p class="settings-toggle-label text-sm font-medium text-gray-800">{{ $settings['enable_low_stock_alert']->label ?? 'Peringatan Stok Minimum' }}</p>
                        <p class="settings-toggle-desc text-xs text-gray-400 mt-0.5">{{ $settings['enable_low_stock_alert']->description ?? '' }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center ml-4">
                        <input type="checkbox" id="enable_low_stock_alert" name="enable_low_stock_alert" value="1"
                            {{ (old('enable_low_stock_alert', $settings['enable_low_stock_alert']->value ?? 1)) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="settings-toggle-track h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-400 transition-colors after:absolute after:top-0.5 after:left-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>

                {{-- WhatsApp Notifications Toggle --}}
                <div class="settings-wa-row flex items-center justify-between py-4 border-t border-green-50 mt-1 bg-green-50/30 -mx-6 px-6 rounded-b-2xl">
                    <div class="flex-1">
                        <p class="settings-toggle-label text-sm font-medium text-gray-800">📱 Kirim Notifikasi via WhatsApp</p>
                        <p class="settings-toggle-desc text-xs text-gray-400 mt-0.5">Ketika diaktifkan, sistem akan mengirim pesan WhatsApp ke pengguna &amp; admin untuk setiap notifikasi penting. Pastikan nomor WhatsApp sudah diatur di profil pengguna.</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center ml-4">
                        <input type="checkbox" id="enable_whatsapp_notifications" name="enable_whatsapp_notifications" value="1"
                               {{ (old('enable_whatsapp_notifications', $settings['enable_whatsapp_notifications']->value ?? 1)) ? 'checked' : '' }}
                               class="sr-only"
                               onchange="var t=document.getElementById('wa-track'),k=document.getElementById('wa-knob');if(this.checked){t.style.backgroundColor='#22c55e';k.style.transform='translateX(20px)';}else{t.style.backgroundColor='#d1d5db';k.style.transform='translateX(0)';}">
                        <div id="wa-track" style="position:relative;width:44px;height:24px;border-radius:9999px;transition:background-color 0.2s;background-color:{{ (old('enable_whatsapp_notifications', $settings['enable_whatsapp_notifications']->value ?? 1)) ? '#22c55e' : '#d1d5db' }};">
                            <span id="wa-knob" style="position:absolute;top:2px;left:2px;width:20px;height:20px;border-radius:9999px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.25);transition:transform 0.2s;transform:{{ (old('enable_whatsapp_notifications', $settings['enable_whatsapp_notifications']->value ?? 1)) ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                        </div>
                    </label>
                </div>

                {{-- Test WhatsApp Connection (Inline Form) --}}
                <div class="settings-wa-row border-t border-green-50 bg-green-50/10 -mx-6 px-6 py-4 rounded-b-2xl">
                    <p class="settings-toggle-label text-sm font-medium text-gray-800 mb-2">Uji Coba Koneksi Fonnte</p>
                    <div class="flex items-center gap-3">
                        <input type="text" id="test_number" name="test_number" form="test-whatsapp-form"
                               placeholder="Contoh: 081234567890" required
                               value="{{ auth()->user()->whatsapp_number ?? '' }}"
                               class="settings-input w-full max-w-xs rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                        
                        <button type="button" onclick="document.getElementById('test-whatsapp-form').submit();"
                                style="background:#10B981;color:#fff;border:none;cursor:pointer;"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition-colors"
                                onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kirim Pesan Uji Coba
                        </button>
                    </div>
                    <p class="settings-hint text-xs text-gray-400 mt-2">Pastikan server memiliki koneksi internet dan FONNTE_TOKEN valid di `.env`.</p>
                </div>
            </div>
        </div>

        {{-- Stok Settings Card --}}
        <div class="settings-card rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="settings-card-header border-b border-gray-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="settings-icon-red flex h-10 w-10 items-center justify-center rounded-xl bg-red-100">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="settings-section-title text-base font-semibold text-gray-900">Peringatan Stok</h3>
                        <p class="settings-section-sub text-xs text-gray-500">Atur batas minimum stok sebelum notifikasi dikirim ke admin</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5">
                <label for="low_stock_threshold" class="settings-label block text-sm font-medium text-gray-700 mb-1">
                    {{ $settings['low_stock_threshold']->label ?? 'Batas Stok Minimum' }}
                </label>
                <p class="settings-hint text-xs text-gray-400 mb-3">{{ $settings['low_stock_threshold']->description ?? '' }}</p>
                <div class="flex items-center gap-3">
                    <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                        value="{{ old('low_stock_threshold', $settings['low_stock_threshold']->value ?? 5) }}"
                        min="1" max="1000"
                        class="settings-input w-32 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('low_stock_threshold') border-red-400 @enderror">
                    <span class="settings-unit text-sm text-gray-500">unit</span>
                </div>
                @error('low_stock_threshold')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="settings-info-box mt-3 text-xs text-blue-600 bg-blue-50 rounded-lg px-3 py-2 border border-blue-100">
                    💡 Notifikasi akan dikirim otomatis ke admin setiap kali stok item berkurang hingga mencapai batas ini. Cooldown 24 jam per item.
                </p>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="button" onclick="openModal('update-settings-modal')"
                style="background:#2563EB;color:#fff;border:none;cursor:pointer;"
                class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold shadow-sm transition-colors"
                onmouseover="this.style.background='#1D4ED8'" onmouseout="this.style.background='#2563EB'">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengaturan Sistem
            </button>
        </div>
    </form>

    {{-- Form terpisah untuk Test WhatsApp agar tidak mensubmit form setting secara keseluruhan --}}
    <form id="test-whatsapp-form" method="POST" action="{{ route($routePrefix . '.settings.test-whatsapp') }}" class="hidden">
        @csrf
    </form>
</div>

<script>
    // Live logo preview
    document.getElementById('company_logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        document.getElementById('logo-filename').textContent = file.name;

        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            preview.src = ev.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection

@push('scripts')
    <x-popup id="update-company-modal" title="Konfirmasi Update Profil"
        message="Apakah Anda yakin ingin menyimpan perubahan profil perusahaan?"
        formId="update-company-form"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />

    <x-popup id="update-settings-modal" title="Konfirmasi Update Pengaturan"
        message="Apakah Anda yakin ingin menyimpan perubahan pengaturan sistem?"
        formId="update-settings-form"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />
@endpush
