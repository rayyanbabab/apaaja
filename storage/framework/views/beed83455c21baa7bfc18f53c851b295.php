        <header class="fixed top-0 left-10 right-0 z-50 flex h-16 items-center justify-between border-b border-gray-300 bg-white/95 backdrop-blur-sm px-4 shadow-sm ml-0 md:ml-64">
            <!-- Left Side: User Profile -->
            <div class="flex items-center space-x-3">
                <div class="h-12 w-12">
                    <img src="<?php echo e(Auth::user()->profil ? asset(Auth::user()->profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF&size=48'); ?>"
                        alt="<?php echo e(Auth::user()->name); ?>"
                        class="h-full w-full rounded-full object-cover shadow-sm ring-1 ring-gray-300"
                        onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&color=7F9CF5&background=EBF4FF&size=48'" />
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center space-x-2">
                        <h1 class="text-base font-semibold leading-tight text-slate-900">
                            <?php echo e(Auth::user()->name); ?> panel
                        </h1>
                        <?php if((Auth::user()->role->value ?? 'user') === 'admin'): ?>
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">
                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                                Admin
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                User
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="max-w-[200px] truncate text-sm text-slate-500"><?php echo e(Auth::user()->email); ?></p>
                </div>
            </div>

            <!-- Right Side: Empty for now -->
            <div class="flex items-center space-x-4">
                <!-- Future navigation items can be added here -->
            </div>
        </header>
<?php /**PATH C:\Users\Rayyan af\apaaja\resources\views/admin/components/header.blade.php ENDPATH**/ ?>