<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" href="/artilia.png">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Sidebar Controller -->
    <script>
        function sidebarData() {
            return {
                masterDataOpen: <?php echo e(Route::is('admin.categories.*', 'admin.suppliers.*') ? 'true' : 'false'); ?>,
                inventoryOpen: <?php echo e(Route::is('admin.inventory.*', 'admin.incoming.*', 'admin.outgoing.*') ? 'true' : 'false'); ?>,
                borrowingOpen: <?php echo e(Route::is('admin.borrowings.*', 'admin.borrowing-requests.*') ? 'true' : 'false'); ?>,
                usersOpen: <?php echo e(Route::is('admin.content.listusers', 'admin.content.createusers') ? 'true' : 'false'); ?>,
                init() {
                    console.log("✅ Sidebar Alpine.js siap dipakai");
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

    
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0d6efd">

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #000; border-radius: 20px; }

        /* Sidebar Container */
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 16rem; /* w-64 */
            height: 100vh;
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 50;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            right: 0;
            height: 64px;
            z-index: 40;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Content Area */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }

        /* Desktop layout (>=768px) */
        @media (min-width: 768px) {
            header {
                margin-left: 16rem; /* same as sidebar width */
                width: calc(100% - 16rem);
            }
            .main-content {
                margin-left: 16rem;
                padding-top: 5rem; /* header height */
            }
        }

        /* Mobile layout (<768px) */
        @media (max-width: 767px) {
            .sidebar-container {
                display: none;
            }
            header {
                margin-left: 0;
                width: 100%;
            }
            .main-content {
                margin-left: 0;
                padding-top: 4.5rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Mobile Hamburger Menu (outside sidebar container) -->
    <?php echo $__env->make('admin.components.mobile-hamburger', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Sidebar -->
    <aside class="sidebar-container">
        <?php echo $__env->make('admin.components.sidebar-new', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </aside>

    <!-- Header -->
    <header>
        <?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'admin.components.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
    </header>

    <!-- Main Content -->
    <main class="main-content p-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('Service Worker registered'))
                .catch(error => console.log('SW failed:', error));
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Rayyan af\apaaja\resources\views/admin/layouts/dashboard.blade.php ENDPATH**/ ?>