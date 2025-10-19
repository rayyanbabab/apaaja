<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Admin</h1>
            <p class="text-gray-600 mt-1">Kelola informasi akun administrator Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="h-2 w-2 rounded-full bg-green-400"></div>
            <span class="text-sm text-gray-600">Akun Aktif</span>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="col-span-1">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Profil</h3>

                <div class="flex flex-col items-center space-y-4">
                    
                    <div class="relative">
                        <img src="<?php echo e($user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=EBF4FF&color=7F9CF5&size=96'); ?>"
                             alt="<?php echo e($user->name); ?>"
                             class="h-24 w-24 rounded-full object-cover ring-4 ring-gray-100"
                             id="profilePhoto">

                        
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 animate-ping rounded-full bg-green-400"></div>
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 ring-2 ring-white"></div>
                    </div>

                    <div class="text-center">
                        <h4 class="text-lg font-semibold text-gray-900"><?php echo e($user->name); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo e($user->email); ?></p>

                        <span class="mt-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                            Administrator
                        </span>
                    </div>

                    
                    <form action="<?php echo e(route('admin.profile.update-photo')); ?>" method="POST" enctype="multipart/form-data" id="photoForm" class="w-full text-center">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="file" name="profil" id="profilInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(this)">
                        <button type="button"
                                onclick="document.getElementById('profilInput').click()"
                                class="mt-2 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 9h6v6H9z" />
                            </svg>
                            Ganti Foto
                        </button>
                        <?php $__errorArgs = ['profil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </form>
                </div>

                
                <div class="mt-6 border-t border-gray-200 pt-4 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Role</span>
                        <span class="font-medium text-gray-900">Admin</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir login</span>
                        <span class="font-medium text-gray-900"><?php echo e(now()->format('d M Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-2 space-y-6">

            
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">Update Email</h3>
                    <p class="text-sm text-gray-600">Ubah alamat email Anda dengan konfirmasi password</p>
                </div>

                <form action="<?php echo e(route('admin.profile.update-email')); ?>" method="POST" class="p-6 space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Baru</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email', $user->email)); ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan email baru">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="current_password_email" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password_email"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan password saat ini">
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Update Email
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Profil</h3>
                    <p class="text-sm text-gray-600">Ubah nama, bio, dan password</p>
                </div>

                <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" class="p-6 space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" name="name" id="name" value="<?php echo e(old('name', $user->name)); ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Masukkan nama Anda">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-red-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio (opsional)</label>
                            <input type="text" name="bio" id="bio" value="<?php echo e(old('bio', $user->bio)); ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Tulis sesuatu tentang Anda">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" id="password"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Isi jika ingin ganti password">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\DILAN\my-project\resources\views/admin/pages/profile.blade.php ENDPATH**/ ?>