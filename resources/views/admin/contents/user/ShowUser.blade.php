@extends('admin.layouts.dashboard')

@section('content')
    <div class="form mx-auto space-y-6 p-4 max-w-4xl">
        {{-- Header Section --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                <p class="text-sm text-gray-600">View detailed information about this user</p>
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
                <a href="{{ route($routePrefix . '.content.printidcard', $user->id) }}" target="_blank"
                    class="inline-flex items-center rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                    </svg>
                    Cetak Kartu ID
                </a>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="success-toast">
                {{ session('success') }}
            </div>
        @endif

        {{-- User Profile Card --}}
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=64' }}"
                        class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                        alt="Profile"
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=64'">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        <div class="mt-1 flex items-center space-x-2">
                            @php $suRole = $user->role->value ?? 'user'; @endphp
                            @if($suRole === 'admin')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                    </svg>
                                    Administrator
                                </span>
                            @elseif($suRole === 'operator')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                    </svg>
                                    Operator
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                                    <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Regular User
                                </span>
                            @endif
                            @if($user->is_active ?? true)
                                <span class="inline-flex items-center rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nomor WhatsApp</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($user->whatsapp_number)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.117.554 4.107 1.523 5.832L.051 23.999l6.333-1.462A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.626 0 11.999 0zm.001 21.818a9.818 9.818 0 01-5.001-1.368l-.359-.214-3.721.975.993-3.62-.234-.371A9.818 9.818 0 012.182 12c0-5.418 4.4-9.818 9.818-9.818 5.418 0 9.818 4.4 9.818 9.818 0 5.419-4.4 9.818-9.818 9.818z"/>
                                    </svg>
                                    {{ $user->whatsapp_number }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">Belum diisi</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bio</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->bio ?? 'No bio available' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Role &amp; Permissions</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($suRole === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /></svg>
                                    Administrator
                                </span>
                                <p class="mt-1 text-xs text-gray-500">Full system access and user management</p>
                            @elseif($suRole === 'operator')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                    <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                                    Operator
                                </span>
                                <p class="mt-1 text-xs text-gray-500">Staff gudang – kelola inventory & peminjaman</p>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                    Regular User
                                </span>
                                <p class="mt-1 text-xs text-gray-500">Standard user with limited permissions</p>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bergabung Sejak</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end space-x-3">
            @if(($user->role->value ?? (string) $user->role) !== 'admin')
                <form action="{{ route($routePrefix . '.content.togglestatususers', $user->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        @if($user->is_active ?? true)
                            Deactivate User
                        @else
                            Activate User
                        @endif
                    </button>
                </form>
                
                <button type="button"
                    onclick="openModal('delete-modal')"
                    class="inline-flex items-center rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Delete User
                </button>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <x-popup id="delete-modal" title="Delete User"
        message="Are you sure you want to delete this user? All data will be permanently removed and cannot be recovered."
        formId="delete-form"
        confirmText="Delete"
        confirmClass="text-sm link-primary delete-btn-color"
        cancelText="Cancel" />

    <form id="delete-form" action="{{ route($routePrefix . '.content.deleteusers', $user->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endpush
