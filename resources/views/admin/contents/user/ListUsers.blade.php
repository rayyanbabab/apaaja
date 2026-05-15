@extends('admin.layouts.dashboard')

@section('content')
    <div class="form">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <x-heading-section title="User Management" subtitle="Manage all user accounts and permissions" />
        <div class="flex items-center space-x-3">
                <button onclick="openImportUsersModal()"
                    class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 whitespace-nowrap">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">Import CSV</span>
                    <span class="sm:hidden">Import</span>
                </button>
                <a href="{{ route($routePrefix . '.content.createusers') }}"
                    class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Add New User</span>
                    <span class="sm:hidden">Add</span>
                </a>
            </div>
        </div>

        {{-- Search and Filter Section --}}
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route($routePrefix . '.content.listusers') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select id="role" name="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Filter
                        </button>
                        <a href="{{ route($routePrefix . '.content.listusers') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="success-toast">
                {{ session('success') }}
            </div>
        @endif

        {{-- Import Errors (from CSV import) --}}
        @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">{{ count(session('import_errors')) }} baris dilewati saat import:</p>
                    <ul class="mt-1 space-y-0.5">
                        @foreach(session('import_errors') as $err)
                        <li class="text-xs text-amber-700">&bull; {{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Bulk Actions --}}
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button type="button" id="select-all" class="text-sm text-gray-600 hover:text-gray-900">
                    Select All
                </button>
                <button type="button" id="bulk-delete" class="text-sm text-red-600 hover:text-red-900" style="display: none;">
                    Delete Selected
                </button>
            </div>
            <div class="text-sm text-gray-600">
                Total: {{ $users->total() }} users
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-md">
            <form id="bulk-form" action="{{ route($routePrefix . '.content.bulkdeleteusers') }}" method="POST">
                @csrf
                <table class="table-next">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="th-next w-4">
                                <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="th-next">User</th>
                            <th class="th-next">Role</th>
                            <th class="th-next">Status</th>
                            <th class="th-next">Email</th>
                            <th class="th-next">Bio</th>
                            <th class="th-next">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-next">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="td-next">
                                    @php
                                        $rowRole = is_object($user->role)
                                            ? ($user->role->value ?? 'user')
                                            : ($user->role ?? 'user');
                                    @endphp
                                    <input
                                        type="checkbox"
                                        name="user_ids[]"
                                        value="{{ $user->id }}"
                                        class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        {{ $rowRole === 'admin' ? 'disabled' : '' }}
                                    >
                                </td>

                                <td class="td-next">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                            class="h-10 w-10 rounded-full object-cover border border-gray-200 shadow-sm"
                                            alt="Profile"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="td-next">
                                    @php
                                        $roleValue = is_object($user->role)
                                            ? ($user->role->value ?? 'user')
                                            : ($user->role ?? 'user');
                                    @endphp

                                    @if($roleValue === 'admin')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                            </svg>
                                            Admin
                                        </span>
                                    @elseif(in_array($roleValue, ['operator', 'staff'], true))
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5z" clip-rule="evenodd" />
                                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                            </svg>
                                            Operator
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            User
                                        </span>
                                    @endif
                                </td>

                                <td class="td-next">
                                    @if($user->is_active ?? true)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                                            <svg class="mr-1 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">
                                            <svg class="mr-1 h-2 w-2 fill-current" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="td-next text-sm text-gray-500">{{ $user->email }}</td>

                                <td class="td-next text-sm text-gray-500">{{ $user->bio ?? 'No bio' }}</td>

                                <td class="td-next">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route($routePrefix . '.content.showusers', $user->id) }}"
                                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                            View
                                        </a>
                                        @if($rowRole !== 'admin')
                                            <button type="button"
                                                onclick="openEditRoleModal({{ $user->id }}, '{{ $roleValue }}', '{{ e($user->name) }}')"
                                                class="inline-flex items-center rounded-md border border-indigo-500 bg-white px-2 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                Role
                                            </button>
                                            <form action="{{ route($routePrefix . '.content.togglestatususers', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-yellow-500 bg-white px-2 py-1 text-xs font-medium text-yellow-600 hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                                    @if($user->is_active ?? true)
                                                        Deactivate
                                                    @else
                                                        Activate
                                                    @endif
                                                </button>
                                            </form>
                                            <button type="button"
                                                onclick="openModal('modal-{{ $user->id }}')"
                                                class="inline-flex items-center rounded-md border border-red-500 bg-white px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
                                        <div class="mt-6">
                                            <a href="{{ route($routePrefix . '.content.createusers') }}"
                                                class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                                </svg>
                                                New User
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    @include('admin.components.partials.import-users-modal')

    {{-- ══════════ Edit Role Modal ══════════ --}}
    <div id="edit-role-modal"
         style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px);"
         class="flex items-center justify-center p-4"
         onclick="if(event.target===this) closeEditRoleModal()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" onclick="event.stopPropagation()">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-white font-bold text-base">Edit Role User</h3>
                    <p id="erm-subtitle" class="text-indigo-200 text-xs mt-0.5"></p>
                </div>
                <button onclick="closeEditRoleModal()" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- Body --}}
            <form id="edit-role-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="px-6 py-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Role Baru</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 hover:border-indigo-400 cursor-pointer transition-all has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="role" value="user" class="accent-indigo-600">
                            <div>
                                <div class="font-semibold text-sm text-gray-800">User</div>
                                <div class="text-xs text-gray-500">Dapat meminjam barang</div>
                            </div>
                            <span class="ml-auto inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">User</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-200 hover:border-emerald-400 cursor-pointer transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="role" value="operator" class="accent-emerald-600">
                            <div>
                                <div class="font-semibold text-sm text-gray-800">Operator</div>
                                <div class="text-xs text-gray-500">Dapat mengelola maintenance</div>
                            </div>
                            <span class="ml-auto inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Operator</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 pb-5 flex gap-3">
                    <button type="button" onclick="closeEditRoleModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity shadow-md">
                        Simpan Role
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('scripts')
    {{-- Individual Delete Modals --}}
    @foreach ($users as $user)
        <x-popup id="modal-{{ $user->id }}" title="Delete User"
            message="Are you sure want to delete this user? All data will be permanently removed."
            formId="delete-form-{{ $user->id }}"
            confirmText="Delete"
            confirmClass="text-sm link-primary delete-btn-color"
            cancelText="Cancel" />
        
        <form id="delete-form-{{ $user->id }}" action="{{ route($routePrefix . '.content.deleteusers', $user->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Bulk Delete Modal --}}
    <x-popup id="bulk-delete-modal" title="Delete Selected Users"
        message="Are you sure you want to delete the selected users? All data will be permanently removed and cannot be recovered."
        formId="bulk-form"
        confirmText="Delete Selected"
        confirmClass="text-sm link-primary delete-btn-color"
        cancelText="Cancel" />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const userCheckboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');
            const selectAllButton = document.getElementById('select-all');
            const bulkDeleteButton = document.getElementById('bulk-delete');

            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkActions();
            });

            // Individual checkbox change
            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                    selectAllCheckbox.checked = checkedBoxes.length === userCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < userCheckboxes.length;
                    toggleBulkActions();
                });
            });

            // Select all button
            selectAllButton.addEventListener('click', function() {
                const allChecked = document.querySelectorAll('.user-checkbox:checked').length === userCheckboxes.length;
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                selectAllCheckbox.checked = !allChecked;
                selectAllCheckbox.indeterminate = false;
                toggleBulkActions();
            });

            // Bulk delete button
            bulkDeleteButton.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    openModal('bulk-delete-modal');
                }
            });

            function toggleBulkActions() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    bulkDeleteButton.style.display = 'inline-block';
                    selectAllButton.textContent = 'Deselect All';
                } else {
                    bulkDeleteButton.style.display = 'none';
                    selectAllButton.textContent = 'Select All';
                }
            }
        });
    </script>

    <script>
        // ── Edit Role Modal ──────────────────────────
        var _ermUserId = null;

        function openEditRoleModal(userId, currentRole, userName) {
            _ermUserId = userId;
            document.getElementById('erm-subtitle').textContent = 'User: ' + userName;
            // Set form action (relative path works on any host including ngrok)
            document.getElementById('edit-role-form').action = '/admin/content/update-users/' + userId;
            // Check the current role radio
            var radios = document.querySelectorAll('#edit-role-form input[name="role"]');
            radios.forEach(function(r) { r.checked = (r.value === currentRole); });
            document.getElementById('edit-role-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeEditRoleModal() {
            document.getElementById('edit-role-modal').style.display = 'none';
            document.body.style.overflow = '';
            _ermUserId = null;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEditRoleModal();
        });
    </script>
@endpush
