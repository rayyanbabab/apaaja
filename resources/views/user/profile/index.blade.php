@extends('user.layouts.dashboard-user')

@section('user')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-8">
        {{-- Header Section --}}
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">My Profile</h1>
                <p class="text-gray-600 dark:text-slate-400 mt-1">Manage your account information</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <div class="h-2 w-2 rounded-full bg-green-400"></div>
                    <span class="text-sm text-gray-600 dark:text-slate-400">Account Active</span>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- Profile Avatar Section --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-xl border border-white/20 dark:border-slate-700 bg-white/80 backdrop-blur-md p-6">
                    <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-slate-100">Profile Photo</h3>

                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=96' }}"
                                alt="{{ $user->name }}"
                                class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-100"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=96'"
                                id="profilePhoto">
                            <div class="absolute -bottom-1 -right-1 h-6 w-6 animate-ping rounded-full bg-green-400"></div>
                            <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 ring-2 ring-white">
                            </div>
                        </div>

                        <div class="text-center">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-slate-100">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-slate-400">{{ $user->email }}</p>
                            @if(($user->role->value ?? 'user') === 'admin')
                                <span class="mt-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                    </svg>
                                    Administrator
                                </span>
                            @else
                                <span class="mt-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Regular User
                                </span>
                            @endif
                        </div>

                        {{-- Profile Photo Upload Section --}}
                        <div class="w-full space-y-4">
                            <form action="{{ route('user.profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                                @csrf
                                @method('PATCH')
                                <input type="file" name="profil" id="profilInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(this)">
                                <button type="button" onclick="document.getElementById('profilInput').click()"
                                    class="inline-flex items-center rounded-md border border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800 px-3 py-2 text-sm font-medium text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 w-full justify-center transition-colors">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z" />
                                    </svg>
                                    Change Photo
                                </button>
                            </form>
                            @error('profil')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Account Stats --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-slate-700/50 pt-6">
                        <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-slate-100">Account Statistics</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-slate-400">Role</span>
                                @if(($user->role->value ?? 'user') === 'admin')
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                        </svg>
                                        Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        Regular User
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-slate-400">Last login</span>
                                <span class="font-medium text-gray-900 dark:text-slate-100">{{ now()->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QR Code Card --}}
                @if(($user->role->value ?? 'user') === 'user')
                <div class="rounded-xl border border-white/20 dark:border-indigo-500/30 bg-white/80 dark:bg-indigo-900/20 backdrop-blur-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-semibold text-indigo-900 dark:text-indigo-200">QR Code Identitas</h3>
                            <p class="text-xs text-indigo-500 dark:text-indigo-300/80 mt-0.5">Untuk peminjaman & barang keluar</p>
                        </div>
                        <div class="h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- QR Code Display --}}
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative group cursor-pointer" onclick="openQrModal()" title="Klik untuk perbesar">
                            <div class="bg-white rounded-xl p-3 shadow-md ring-2 ring-indigo-100 group-hover:ring-indigo-400 transition-all duration-200">
                                <canvas id="qrcode-canvas" width="160" height="160" style="display:block;"></canvas>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-indigo-900/60 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </div>
                        </div>

                        {{-- User info label --}}
                        <div class="text-center">
                            <p class="text-xs font-mono text-indigo-700 dark:text-indigo-200 bg-indigo-100 dark:bg-indigo-500/30 rounded-md px-3 py-1 tracking-wider">
                                ID #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-xs text-indigo-500 dark:text-indigo-300 mt-1.5">Tunjukkan ke admin/operator saat <br>meminjam atau mengambil barang</p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 w-full">
                            <button onclick="openQrModal()"
                                class="flex-1 inline-flex items-center justify-center rounded-lg border border-indigo-300 dark:border-indigo-500/50 bg-white dark:bg-indigo-900/50 px-3 py-2 text-xs font-medium text-indigo-700 dark:text-indigo-200 hover:bg-indigo-50 dark:hover:bg-indigo-800/50 transition-colors duration-150">
                                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Perbesar
                            </button>
                            <button onclick="downloadQr()"
                                class="flex-1 inline-flex items-center justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500 px-3 py-2 text-xs font-medium text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-150">
                                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Unduh
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Profile Form Section --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Email Update --}}
                <div class="rounded-xl border border-white/20 dark:border-slate-700 bg-white/80 backdrop-blur-md">
                    <div class="border-b border-gray-200 dark:border-slate-700/50 px-6 py-4 bg-transparent">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">Email Address</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Update your email address</p>
                    </div>

                    <form action="{{ route('user.profile.update-email') }}" method="POST" class="space-y-6 p-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    Email Address
                                </label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-gray-900 dark:focus:border-slate-400 focus:ring-gray-900 dark:focus:ring-slate-400 sm:text-sm placeholder-gray-400 dark:placeholder-slate-400"
                                    placeholder="Enter your email address">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="current_password_email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    Current Password
                                </label>
                                <input type="password" id="current_password_email" name="current_password"
                                    class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-gray-900 dark:focus:border-slate-400 focus:ring-gray-900 dark:focus:ring-slate-400 sm:text-sm placeholder-gray-400 dark:placeholder-slate-400"
                                    placeholder="Enter current password">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-900 dark:bg-slate-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-800 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-slate-500 focus:ring-offset-2 dark:ring-offset-slate-900">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Email
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Security Settings --}}
                <div class="rounded-xl border border-white/20 dark:border-slate-700 bg-white/80 backdrop-blur-md">
                    <div class="border-b border-gray-200 dark:border-slate-700/50 px-6 py-4 bg-transparent">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">Security</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Manage your password and confirm your new password</p>
                    </div>

                    <form action="{{ route('user.profile.update-password') }}" method="POST" class="space-y-6 p-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div>
                                <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    Current Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password"
                                        class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-gray-900 dark:focus:border-slate-400 focus:ring-gray-900 dark:focus:ring-slate-400 sm:text-sm pr-12 placeholder-gray-400 dark:placeholder-slate-400"
                                        placeholder="Enter current password">
                                    <button 
                                        type="button" 
                                        onclick="togglePassword('current_password')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg id="current_password_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    New Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-gray-900 dark:focus:border-slate-400 focus:ring-gray-900 dark:focus:ring-slate-400 sm:text-sm pr-12 placeholder-gray-400 dark:placeholder-slate-400"
                                        placeholder="Enter new password">
                                    <button 
                                        type="button" 
                                        onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg id="password_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700 dark:text-slate-200">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-gray-900 dark:focus:border-slate-400 focus:ring-gray-900 dark:focus:ring-slate-400 sm:text-sm pr-12 placeholder-gray-400 dark:placeholder-slate-400"
                                        placeholder="Confirm new password">
                                    <button 
                                        type="button" 
                                        onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg id="password_confirmation_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-900 dark:bg-slate-800 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-800 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-slate-500 focus:ring-offset-2 dark:ring-offset-slate-900">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
                {{-- WhatsApp Number --}}
                <div class="rounded-xl border border-white/20 dark:border-slate-700 bg-white/80 backdrop-blur-md">
                    <div class="border-b border-green-100 dark:border-slate-700/50 px-6 py-4 bg-transparent">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    <path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.117.554 4.107 1.523 5.832L.051 23.999l6.333-1.462A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.626 0 11.999 0zm.001 21.818a9.818 9.818 0 01-5.001-1.368l-.359-.214-3.721.975.993-3.62-.234-.371A9.818 9.818 0 012.182 12c0-5.418 4.4-9.818 9.818-9.818 5.418 0 9.818 4.4 9.818 9.818 0 5.419-4.4 9.818-9.818 9.818z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-slate-100">Nomor WhatsApp</h3>
                                <p class="text-sm text-gray-600 dark:text-slate-400">Untuk menerima notifikasi peminjaman & pengingat via WA</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('user.profile.update-whatsapp') }}" method="POST" class="space-y-4 p-6">
                        @csrf
                        @method('PATCH')

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    <path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.117.554 4.107 1.523 5.832L.051 23.999l6.333-1.462A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.626 0 11.999 0zm.001 21.818a9.818 9.818 0 01-5.001-1.368l-.359-.214-3.721.975.993-3.62-.234-.371A9.818 9.818 0 012.182 12c0-5.418 4.4-9.818 9.818-9.818 5.418 0 9.818 4.4 9.818 9.818 0 5.419-4.4 9.818-9.818 9.818z"/>
                                </svg>
                            </span>
                            <input type="text" id="whatsapp_number" name="whatsapp_number"
                                value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                                class="block w-full rounded-md border-gray-300 dark:border-slate-600 bg-white/50 dark:bg-slate-900/60 text-gray-900 dark:text-slate-100 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm pl-9 placeholder-gray-400 dark:placeholder-slate-400"
                                placeholder="Contoh: 08123456789 atau 628123456789">
                            @error('whatsapp_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($user->whatsapp_number)
                            <div class="flex items-center gap-2 text-sm text-green-700 dark:text-emerald-300 bg-green-50 dark:bg-emerald-900/30 border border-transparent dark:border-emerald-800/50 rounded-lg px-3 py-2">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Notifikasi WA aktif ke <strong class="ml-1">{{ $user->whatsapp_number }}</strong>
                            </div>
                        @else
                            <p class="text-xs text-gray-500 dark:text-slate-400">Isi nomor untuk mengaktifkan notifikasi WhatsApp. Kosongkan untuk menonaktifkan.</p>
                        @endif

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-md border border-transparent bg-green-600 dark:bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 dark:hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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
</div>

{{-- QR Code Fullscreen Modal --}}
<div id="qrModal" class="fixed inset-0 z-50 items-center justify-center p-4" style="display:none;" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" onclick="closeQrModal()"></div>

    {{-- Modal Card --}}
    <div class="relative z-10 w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-white font-bold text-lg">QR Code Identitas</h3>
                <p class="text-indigo-200 text-xs mt-0.5">Scan untuk verifikasi peminjaman</p>
            </div>
            <button onclick="closeQrModal()" class="text-white/80 hover:text-white transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-8 flex flex-col items-center space-y-5">
            {{-- Big QR --}}
            <div class="bg-white p-4 rounded-xl shadow-lg ring-2 ring-indigo-100">
                <canvas id="qrcode-modal-canvas" width="220" height="220" style="display:block;"></canvas>
            </div>

            {{-- User Info Block --}}
            <div class="w-full bg-gray-50 rounded-xl p-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">ID Pengguna</span>
                    <span class="font-mono font-semibold text-indigo-600">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Email</span>
                    <span class="text-gray-700 text-xs truncate max-w-[160px]">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Peran</span>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                        Regular User
                    </span>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="flex items-start gap-2 text-xs text-gray-500 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                <svg class="h-4 w-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Tunjukkan QR ini kepada admin atau operator saat proses <strong>peminjaman barang</strong> atau <strong>pengambilan barang keluar</strong>.</span>
            </div>

            {{-- Download Button --}}
            <button onclick="downloadQr()"
                class="w-full inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-sm font-semibold text-white hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-indigo-200 transition-all duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh QR Code
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    // ─── Data user (dari PHP) ─────────────────────────────────────────────────
    var userId    = {{ $user->id }};
    var userName  = @json($user->name);
    var userEmail = @json($user->email);
    var userRole  = @json($user->role->value ?? 'user');
    var userId5   = '{{ str_pad($user->id, 5, "0", STR_PAD_LEFT) }}';

    var qrPayload = JSON.stringify({
        id:    userId,
        name:  userName,
        email: userEmail,
        role:  userRole,
        app:   'artilia'
    });

    // ─── Render QR kecil (160×160) langsung ke <canvas> di HTML ──────────────
    var cvSmall = document.getElementById('qrcode-canvas');
    if (cvSmall && typeof QRious !== 'undefined') {
        new QRious({
            element:    cvSmall,
            value:      qrPayload,
            size:       160,
            foreground: '#1e1b4b',
            background: '#ffffff',
            level:      'H'
        });
    }

    // ─── Render QR besar (220×220) untuk modal ────────────────────────────────
    var cvModal = document.getElementById('qrcode-modal-canvas');
    if (cvModal && typeof QRious !== 'undefined') {
        new QRious({
            element:    cvModal,
            value:      qrPayload,
            size:       220,
            foreground: '#1e1b4b',
            background: '#ffffff',
            level:      'H'
        });
    }

    // ─── Modal helpers ────────────────────────────────────────────────────────
    function openQrModal() {
        var modal = document.getElementById('qrModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeQrModal() {
        var modal = document.getElementById('qrModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQrModal();
    });

    // ─── Download QR PNG berbranding ──────────────────────────────────────────
    function downloadQr() {
        if (typeof QRious === 'undefined') { alert('Library QR belum dimuat.'); return; }

        var qrCanvas = document.createElement('canvas');
        new QRious({
            element:    qrCanvas,
            value:      qrPayload,
            size:       400,
            foreground: '#1e1b4b',
            background: '#ffffff',
            level:      'H'
        });

        var pad = 40, hdr = 64, ftr = 70;
        var W   = qrCanvas.width + pad * 2;
        var H   = qrCanvas.height + pad * 2 + hdr + ftr;
        var out = document.createElement('canvas');
        out.width = W; out.height = H;
        var ctx = out.getContext('2d');

        ctx.fillStyle = '#eef2ff';
        ctx.fillRect(0, 0, W, H);

        var grad = ctx.createLinearGradient(0, 0, W, 0);
        grad.addColorStop(0, '#4f46e5');
        grad.addColorStop(1, '#7c3aed');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, W, hdr);

        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 20px Arial, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Artilia \u2014 QR Identitas Pengguna', W / 2, hdr / 2);

        ctx.drawImage(qrCanvas, pad, hdr + pad);

        ctx.fillStyle = '#c7d2fe';
        ctx.fillRect(0, hdr + pad * 2 + qrCanvas.height, W, 1);

        ctx.fillStyle = '#3730a3';
        ctx.font = 'bold 15px Arial, sans-serif';
        ctx.textBaseline = 'top';
        ctx.fillText('#' + userId5 + '  \u00b7  ' + userName, W / 2, hdr + pad * 2 + qrCanvas.height + 14);

        ctx.fillStyle = '#6366f1';
        ctx.font = '12px Arial, sans-serif';
        ctx.fillText(userEmail, W / 2, hdr + pad * 2 + qrCanvas.height + 36);

        ctx.fillStyle = '#a5b4fc';
        ctx.font = '11px Arial, sans-serif';
        ctx.fillText('Untuk peminjaman & barang keluar', W / 2, hdr + pad * 2 + qrCanvas.height + 56);

        var link = document.createElement('a');
        link.download = 'qr-artilia-' + userId5 + '.png';
        link.href = out.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ─── Photo upload ─────────────────────────────────────────────────────────
    function handlePhotoUpload(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePhoto').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById('photoForm').submit();
        }
    }

    // ─── Toggle password visibility ───────────────────────────────────────────
    function togglePassword(inputId) {
        var input   = document.getElementById(inputId);
        var eyeIcon = document.getElementById(inputId + '_eye');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
</script>
@endpush