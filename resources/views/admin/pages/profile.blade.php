@extends('admin.layouts.dashboard')

@section('content')
<style>
/* ── Profile Page Dark Mode ── */
html.dark .prof-card { background-color: #1e293b !important; border-color: #334155 !important; }
html.dark .prof-card-header { border-color: #334155 !important; }
html.dark .prof-title { color: #f1f5f9 !important; }
html.dark .prof-sub   { color: #64748b !important; }
html.dark .prof-label { color: #94a3b8 !important; }
html.dark .prof-info-key   { color: #64748b !important; }
html.dark .prof-info-val   { color: #e2e8f0 !important; }
html.dark .prof-divider    { border-color: #334155 !important; }
html.dark .prof-name  { color: #f1f5f9 !important; }
html.dark .prof-email { color: #94a3b8 !important; }
html.dark .prof-status { color: #94a3b8 !important; }

/* All form inputs in dark mode */
html.dark .prof-card input[type="text"],
html.dark .prof-card input[type="email"],
html.dark .prof-card input[type="password"],
html.dark .prof-card textarea {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #f1f5f9 !important;
    box-shadow: none !important;
}
html.dark .prof-card input[type="text"]::placeholder,
html.dark .prof-card input[type="email"]::placeholder,
html.dark .prof-card input[type="password"]::placeholder {
    color: #475569 !important;
}
html.dark .prof-card input:focus,
html.dark .prof-card textarea:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 1px #3b82f6 !important;
    outline: none !important;
}

/* WA input has pl-9 (icon prefix) */
html.dark .prof-card .text-gray-400 { color: #475569 !important; }

/* Ganti Foto button */
html.dark .prof-btn-photo {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #94a3b8 !important;
}
html.dark .prof-btn-photo:hover {
    background-color: #334155 !important;
    color: #e2e8f0 !important;
}

/* Submit buttons — biru kontras */
html.dark .prof-btn-submit {
    background-color: #2563eb !important;
    color: #ffffff !important;
}
html.dark .prof-btn-submit:hover { background-color: #1d4ed8 !important; }

/* WA button — hijau tetap */
html.dark .prof-btn-wa {
    background-color: #16a34a !important;
    color: #ffffff !important;
}
html.dark .prof-btn-wa:hover { background-color: #15803d !important; }
</style>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="prof-title text-2xl font-bold text-gray-900">Profil Admin</h1>
            <p class="prof-sub text-gray-600 mt-1">Kelola informasi akun administrator Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="h-2 w-2 rounded-full bg-green-400"></div>
            <span class="prof-status text-sm text-gray-600">Akun Aktif</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="col-span-1">
            <div class="prof-card rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="prof-title text-lg font-semibold text-gray-900 mb-4">Foto Profil</h3>

                <div class="flex flex-col items-center space-y-4">
                    <div class="relative">
                        <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=EBF4FF&color=7F9CF5&size=96' }}"
                             alt="{{ $user->name }}"
                             class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-100"
                             id="profilePhoto">

                        <div class="absolute -bottom-1 -right-1 h-6 w-6 animate-ping rounded-full bg-green-400"></div>
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 ring-2 ring-white"></div>
                    </div>

                    <div class="text-center">
                        <h4 class="prof-name text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                        <p class="prof-email text-sm text-gray-600">{{ $user->email }}</p>

                        <span class="mt-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                            Administrator
                        </span>
                    </div>

                    <form action="{{ route($routePrefix . '.profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="w-full text-center">
                        @csrf
                        @method('PATCH')
                        <input type="file" name="profil" id="profilInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(this)">
                        <button type="button"
                                onclick="document.getElementById('profilInput').click()"
                                class="prof-btn-photo mt-2 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 9h6v6H9z" />
                            </svg>
                            Ganti Foto
                        </button>
                        @error('profil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>

                <div class="prof-divider mt-6 border-t border-gray-200 pt-4 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="prof-info-key text-gray-600">Role</span>
                        <span class="prof-info-val font-medium text-gray-900">Admin</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="prof-info-key text-gray-600">Terakhir login</span>
                        <span class="prof-info-val font-medium text-gray-900">{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-2 space-y-6">

            <div class="prof-card rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="prof-card-header border-b border-gray-200 px-6 py-4">
                    <h3 class="prof-title text-lg font-medium text-gray-900">Update Email</h3>
                    <p class="prof-sub text-sm text-gray-600">Ubah alamat email Anda dengan konfirmasi password</p>
                </div>

                <form action="{{ route($routePrefix . '.profile.update-email') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="prof-label block text-sm font-medium text-gray-700 mb-2">Email Baru</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan email baru">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_password_email" class="prof-label block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password_email"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan password saat ini">
                            @error('current_password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="prof-btn-submit inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Update Email
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="prof-card rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="prof-card-header border-b border-gray-200 px-6 py-4">
                    <h3 class="prof-title text-lg font-medium text-gray-900">Edit Profil</h3>
                    <p class="prof-sub text-sm text-gray-600">Ubah nama, bio, dan password</p>
                </div>

                <form action="{{ route($routePrefix . '.profile.update') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="prof-label block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan nama Anda">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bio" class="prof-label block text-sm font-medium text-gray-700 mb-2">Bio (opsional)</label>
                            <input type="text" name="bio" id="bio" value="{{ old('bio', $user->bio) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Tulis sesuatu tentang Anda">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="prof-label block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" id="password"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Isi jika ingin ganti password">
                        </div>

                        <div>
                            <label for="password_confirmation" class="prof-label block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="prof-btn-submit inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <div class="prof-card rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="prof-card-header border-b border-gray-200 px-6 py-4">
                    <h3 class="prof-title text-lg font-medium text-gray-900">Nomor WhatsApp</h3>
                    <p class="prof-sub text-sm text-gray-600">Untuk menerima notifikasi via WhatsApp</p>
                </div>

                <form action="{{ route($routePrefix . '.profile.update-whatsapp') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.117.554 4.107 1.523 5.832L.051 23.999l6.333-1.462A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.626 0 11.999 0zm.001 21.818a9.818 9.818 0 01-5.001-1.368l-.359-.214-3.721.975.993-3.62-.234-.371A9.818 9.818 0 012.182 12c0-5.418 4.4-9.818 9.818-9.818 5.418 0 9.818 4.4 9.818 9.818 0 5.419-4.4 9.818-9.818 9.818z"/>
                            </svg>
                        </span>
                        <input type="text" name="whatsapp_number" id="whatsapp_number"
                               value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm pl-9"
                               placeholder="Contoh: 08123456789 atau 628123456789">
                        @error('whatsapp_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="text-xs text-gray-500">Kosongkan untuk menonaktifkan notifikasi WhatsApp.</p>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="prof-btn-wa inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Nomor WA
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function handlePhotoUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('profilePhoto').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
            document.getElementById('photoForm').submit();
        }
    }
</script>
@endsection
