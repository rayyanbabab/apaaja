@extends('admin.layouts.dashboard')

@section('content')
    <div class="form mx-auto space-y-4 p-4 max-w-4xl">
        {{-- Header Section --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-sm text-gray-600">Update user information and profile</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route($routePrefix . '.content.listusers') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition 
                class="rounded-md bg-green-50 p-3 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Form --}}
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <form id="update-form-{{ $user->id }}" action="{{ route($routePrefix . '.content.updateusers', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

                <div class="border-b border-gray-200 px-4 py-3">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=48' }}"
                            class="h-12 w-12 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                            alt="Profile"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=48'">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Edit User: {{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <div class="mt-1">
                                @php $uRole = $user->role->value ?? 'user'; @endphp
                                @if($uRole === 'admin')
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                        </svg>
                                        Administrator
                                    </span>
                                @elseif($uRole === 'operator')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                        </svg>
                                        Operator
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        Regular User
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Content --}}
                <div class="space-y-4 p-4">
                    <div class="space-y-4">
                        {{-- Profile Photo Section --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Current Profile Photo --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Current Profile Photo</label>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($user->profil && file_exists(public_path($user->profil)))
                                            <img src="{{ asset($user->profil) }}" alt="Current Profile" 
                                                class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="h-16 w-16 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500">Current profile photo</p>
                                    </div>
                                </div>
                            </div>

                            {{-- New Profile Photo Upload --}}
                            <div class="space-y-2">
                                <label for="profil" class="block text-sm font-medium text-gray-700">
                                    Update Profile Photo
                                </label>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div id="imagePreview" class="hidden">
                                            <img id="previewImg" src="" alt="Preview" 
                                                class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                        </div>
                                        <div id="defaultAvatar" class="h-16 w-16 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" id="profil" name="profil" accept="image/*" 
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            onchange="previewImage(this)">
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                        @error('profil')
                                            <p class="flex items-center text-sm text-red-600">
                                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- User Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                    class="input-form" required>
                                @error('name')
                                    <p class="flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                    class="input-form" required>
                                @error('email')
                                    <p class="flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Bio Field --}}
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700">
                                Bio (Optional)
                            </label>
                            <textarea id="bio" name="bio" rows="3" class="input-form" 
                                placeholder="Enter a brief description about the user...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="flex items-center text-sm text-red-600">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- WhatsApp Number Field --}}
                        <div class="space-y-2">
                            <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">
                                Nomor WhatsApp
                                <span class="text-xs text-gray-400 font-normal ml-1">(Opsional — untuk notifikasi WA)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.117.554 4.107 1.523 5.832L.051 23.999l6.333-1.462A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.626 0 11.999 0zm.001 21.818a9.818 9.818 0 01-5.001-1.368l-.359-.214-3.721.975.993-3.62-.234-.371A9.818 9.818 0 012.182 12c0-5.418 4.4-9.818 9.818-9.818 5.418 0 9.818 4.4 9.818 9.818 0 5.419-4.4 9.818-9.818 9.818z"/>
                                    </svg>
                                </span>
                                <input type="text" id="whatsapp_number" name="whatsapp_number" 
                                    value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                                    class="input-form pl-9" 
                                    placeholder="Contoh: 08123456789 atau 628123456789">
                            </div>
                            @error('whatsapp_number')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Field --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Account Status
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) == '1' ? 'checked' : '' }} {{ ($user->role->value ?? (string) $user->role) === 'admin' ? 'disabled' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active', $user->is_active ?? true) == '0' ? 'checked' : '' }} {{ ($user->role->value ?? (string) $user->role) === 'admin' ? 'disabled' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                            @if(($user->role->value ?? (string) $user->role) === 'admin')
                                <p class="text-xs text-gray-500">Status akun admin tidak bisa diubah.</p>
                            @endif
                            @error('is_active')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password <span class="text-xs text-gray-500">(leave blank to keep current)</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" class="input-form pr-12">
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
                                    <p class="flex items-center text-sm text-red-600">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="input-form pr-12">
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

                        {{-- Role Field --}}
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-medium text-gray-700">User Role</label>
                            @php
                                $selectedRole = old('role', $user->role->value ?? (string) $user->role);
                            @endphp
                            <select id="role" name="role" class="input-form" required {{ ($user->role->value ?? (string) $user->role) === 'admin' ? 'disabled' : '' }}>
                                <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="operator" {{ $selectedRole === 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="user" {{ $selectedRole === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @if(($user->role->value ?? (string) $user->role) === 'admin')
                                <p class="text-xs text-gray-500">Role admin tidak bisa diubah.</p>
                                <input type="hidden" name="role" value="admin">
                                <input type="hidden" name="is_active" value="{{ $user->is_active ? 1 : 0 }}">
                            @endif
                            @error('role')
                                <p class="flex items-center text-sm text-red-600">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="rounded-b-lg border-t border-gray-200 bg-gray-50 px-4 py-4">
                    <div class="flex items-center justify-between">
                        <a href="{{ route($routePrefix . '.content.listusers') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                    Cancel
                </a>

                        <button type="button" onclick="openModal('update-modal-{{ $user->id }}')" class="update-button">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update User
                        </button>
                    </div>
            </div>
        </form>
        </div>
    </div>

    <style>
        .update-button {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 10 !important;
            background-color: #059669 !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        .update-button:hover {
            background-color: #047857 !important;
        }
    </style>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const defaultAvatar = document.getElementById('defaultAvatar');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    defaultAvatar.classList.add('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                defaultAvatar.classList.remove('hidden');
            }
        }

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById(inputId + '_eye');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                input.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
        }
    </script>
@endsection

@push('scripts')
    <x-popup id="update-modal-{{ $user->id }}" title="Konfirmasi Update"
        message="Apakah Anda yakin ingin menyimpan perubahan data user ini?"
        formId="update-form-{{ $user->id }}"
        confirmText="Simpan"
        confirmClass="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl transition-colors shadow-sm"
        cancelText="Batal"
        icon="info" />
@endpush
