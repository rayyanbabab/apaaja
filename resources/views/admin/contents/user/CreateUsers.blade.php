@extends('admin.layouts.dashboard')

@section('content')
    <div class="form mx-auto space-y-4 p-4 max-w-4xl">
        {{-- Header Section --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
                <p class="text-sm text-gray-600">Create user's account for login</p>
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

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Form --}}
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <form action="{{ route($routePrefix . '.content.savedatausers') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
                </div>

                {{-- Form Content --}}
                <div class="space-y-4 p-4">
                    <div class="space-y-4">

                        {{-- User Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                        class="input-form" placeholder="Enter full name">
                                </div>
                                @error('name')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                        class="input-form" placeholder="Enter email address">
                                </div>
                                @error('email')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Bio Field --}}
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-medium text-gray-700">
                                Bio (Optional)
                            </label>
                            <textarea id="bio" name="bio" rows="3" class="input-form" 
                                placeholder="Enter a brief description about the user...">{{ old('bio') }}</textarea>
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
                                    value="{{ old('whatsapp_number') }}"
                                    class="input-form pl-9" 
                                    placeholder="Contoh: 08123456789 atau 628123456789">
                            </div>
                            @error('whatsapp_number')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Profile Photo Field --}}
                        <div class="space-y-2">
                            <label for="profil" class="block text-sm font-medium text-gray-700">
                                Profile Photo (Optional)
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img id="photo-preview" src="https://ui-avatars.com/api/?name=New User&color=7F9CF5&background=EBF4FF&size=64"
                                        class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                                        alt="Profile Preview"
                                        onerror="this.src='https://ui-avatars.com/api/?name=New User&color=7F9CF5&background=EBF4FF&size=64'">
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="profil" name="profil" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
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

                        {{-- Status Field --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Account Status
                            </label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="space-y-4">

                        {{-- Password Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required class="input-form pr-12"
                                        placeholder="Create a secure password">
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
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required class="input-form pr-12"
                                        placeholder="Confirm your password">
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

                    </div>

                    @php
                        $roles = [
                            'admin' => [
                                'label' => 'Admin',
                                'desc'  => 'Full system access and management',
                            ],
                            'operator' => [
                                'label' => 'Operator',
                                'desc'  => 'Staff gudang – kelola inventory & peminjaman',
                            ],
                            'user' => [
                                'label' => 'User',
                                'desc'  => 'Standard user access to basic features',
                            ],
                        ];
                    @endphp

                    <div class="space-y-4">
                        <h4 class="flex items-center text-sm font-medium text-gray-900">
                            User Role & Permissions
                        </h4>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                            @foreach ($roles as $value => $role)
                                @php
                                    $colors = [
                                        'admin'    => ['border'=>'border-red-200','bg'=>'bg-red-50','hbg'=>'hover:bg-red-100','text'=>'text-red-900','sub'=>'text-red-700','radio'=>'text-red-600 focus:ring-red-500','icon'=>'text-red-600'],
                                        'operator' => ['border'=>'border-emerald-200','bg'=>'bg-emerald-50','hbg'=>'hover:bg-emerald-100','text'=>'text-emerald-900','sub'=>'text-emerald-700','radio'=>'text-emerald-600 focus:ring-emerald-500','icon'=>'text-emerald-600'],
                                        'user'     => ['border'=>'border-blue-200','bg'=>'bg-blue-50','hbg'=>'hover:bg-blue-100','text'=>'text-blue-900','sub'=>'text-blue-700','radio'=>'text-blue-600 focus:ring-blue-500','icon'=>'text-blue-600'],
                                    ];
                                    $c = $colors[$value] ?? $colors['user'];
                                @endphp
                                <div class="flex items-start space-x-3 rounded-lg border {{ $c['border'] }} {{ $c['bg'] }} p-4 {{ $c['hbg'] }} transition-colors">
                                    <input id="role_{{ $value }}" name="role" type="radio"
                                        value="{{ $value }}"
                                        {{ old('role') === $value ? 'checked' : '' }}
                                        class="mt-1 h-4 w-4 border-gray-300 {{ $c['radio'] }}">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            @if($value === 'admin')
                                                <svg class="h-5 w-5 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                                            @elseif($value === 'operator')
                                                <svg class="h-5 w-5 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                                            @else
                                                <svg class="h-5 w-5 {{ $c['icon'] }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                            @endif
                                            <label for="role_{{ $value }}" class="block cursor-pointer text-sm font-semibold {{ $c['text'] }}">{{ $role['label'] }}</label>
                                        </div>
                                        <p class="mt-1 text-xs {{ $c['sub'] }}">{{ $role['desc'] }}</p>
                                        @if($value === 'admin')
                                            <div class="mt-2 flex items-center text-xs text-red-600">
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                High privilege level
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Form Footer --}}
                <div class="rounded-b-lg border-t border-gray-200 bg-gray-50 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <a href="{{ route($routePrefix . '.content.listusers') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </a>

                        <button type="submit" class="create-button">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <style>
        .create-button {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 10 !important;
            background-color: #2563eb !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        .create-button:hover {
            background-color: #1d4ed8 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('profil');
            const photoPreview = document.getElementById('photo-preview');
            const nameInput = document.getElementById('name');
            
            // Update preview when file is selected
            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        photoPreview.src = event.target.result;
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            // Update avatar seed when name changes
            nameInput.addEventListener('input', function(e) {
                if (!fileInput.files || !fileInput.files[0]) {
                    const name = e.target.value || 'New User';
                    photoPreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&color=7F9CF5&background=EBF4FF&size=64`;
                }
            });
        });
        
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
@endsection
