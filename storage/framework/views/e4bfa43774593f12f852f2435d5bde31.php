<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Breadcrumb -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Overview</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Last updated:</span>
                        <span class="text-gray-500"><?php echo e(now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowing Requests Notification -->
    <?php if($pendingBorrowingRequests->count() > 0): ?>
        <div class="bg-white shadow-lg border-b-4 border-gray-300">
            <div class="overflow-hidden whitespace-nowrap relative">
                <div class="animate-marquee inline-block py-3 text-base font-medium">
                    <span class="mr-12 text-gray-900">🔔 NOTIFIKASI PEMINJAMAN URGENT:</span>
                    <?php $__currentLoopData = $pendingBorrowingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="mr-12 bg-gray-100 px-3 py-1 rounded-2xl border border-gray-300">
                            📋 <strong class="text-gray-900"><?php echo e($request->user->name); ?></strong> mengajukan peminjaman 
                            <strong class="text-blue-600"><?php echo e($request->item->nama); ?></strong> 
                            (<?php echo e($request->jumlah); ?> unit) - 
                            <em class="text-gray-600"><?php echo e($request->created_at->setTimezone('Asia/Jakarta')->diffForHumans()); ?></em>
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($pendingBorrowingRequests->count() > 1): ?>
                        <span class="mr-12 bg-red-100 text-red-800 px-4 py-2 rounded-2xl font-bold animate-pulse border-2 border-red-300">
                            ⚠️ TOTAL <?php echo e($pendingBorrowingRequests->count()); ?> PERMINTAAN MENUNGGU PERSETUJUAN!
                        </span>
                    <?php endif; ?>
                    <span class="mr-12 bg-white text-red-600 px-4 py-2 rounded-2xl font-bold hover:bg-yellow-200 transition-colors border-2 border-gray-200">
                        👉 <a href="<?php echo e(route('admin.borrowing-requests.pending')); ?>" class="no-underline">
                            KELOLA SEMUA PERMINTAAN SEKARANG
                        </a>
                    </span>
                    <!-- Repeat for continuous scroll -->
                    <span class="mr-12 text-gray-900">🔔 NOTIFIKASI PEMINJAMAN URGENT:</span>
                    <?php $__currentLoopData = $pendingBorrowingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="mr-12 bg-gray-100 px-3 py-1 rounded-2xl border border-gray-300">
                            📋 <strong class="text-gray-900"><?php echo e($request->user->name); ?></strong> mengajukan peminjaman 
                            <strong class="text-blue-600"><?php echo e($request->item->nama); ?></strong> 
                            (<?php echo e($request->jumlah); ?> unit) - 
                            <em class="text-gray-600"><?php echo e($request->created_at->setTimezone('Asia/Jakarta')->diffForHumans()); ?></em>
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <!-- Blinking indicator -->
                <div class="absolute top-2 right-4 w-3 h-3 bg-yellow-400 rounded-2xl animate-ping"></div>
                <div class="absolute top-2 right-4 w-3 h-3 bg-yellow-300 rounded-2xl"></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="green" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-2xl flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-2xl animate-pulse"></div>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">
                            Dashboard Overview
                        </h1>
                        <p class="text-lg text-gray-600 mt-2">Real-time inventory management system</p>
                        <div class="flex items-center mt-3 space-x-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="w-2 h-2 bg-green-500 rounded-2xl mr-2 animate-pulse"></div>
                                <span>System Online</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo e(now()->setTimezone('Asia/Jakarta')->format('H:i:s')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">Total Records</div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e(number_format($jumlahJenisBarang + $userCount + $supplierCount + $categoryCount)); ?></div>
                    <div class="text-sm text-green-600 flex items-center justify-end mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Active</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="mb-8">
            <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Quick Statistics
                    </h2>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-2xl animate-pulse"></div>
                        <span class="text-sm text-gray-500">Live Data</span>
                    </div>
                </div>
                
                <?php echo $__env->make('admin.components.libraries.statisticCard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Line Chart -->
            <div class="lg:col-span-2 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Inventory Movement Chart
                    </h3>
                    <div class="flex space-x-2">
                        <button onclick="updateChart('weekly')" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 <?php echo e($range === 'weekly' ? 'bg-blue-500 text-white' : ''); ?>">
                            Weekly
                        </button>
                        <button onclick="updateChart('monthly')" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 <?php echo e($range === 'monthly' ? 'bg-blue-500 text-white' : ''); ?>">
                            Monthly
                        </button>
                        <button onclick="updateChart('yearly')" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 <?php echo e($range === 'yearly' ? 'bg-blue-500 text-white' : ''); ?>">
                            Yearly
                        </button>
                    </div>
                </div>
                <div id="chart" class="h-96"></div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Data Distribution
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Overview of system data</p>
                </div>
                <div id="pieChart" class="h-96"></div>
                
                <!-- Legend -->
                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-2xl mr-2"></div>
                            <span class="text-gray-600">Items</span>
                        </div>
                        <span class="font-medium text-gray-900"><?php echo e($jumlahJenisBarang); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-2xl mr-2"></div>
                            <span class="text-gray-600">Users</span>
                        </div>
                        <span class="font-medium text-gray-900"><?php echo e($userCount); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-2xl mr-2"></div>
                            <span class="text-gray-600">Suppliers</span>
                        </div>
                        <span class="font-medium text-gray-900"><?php echo e($supplierCount); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-2xl mr-2"></div>
                            <span class="text-gray-600">Categories</span>
                        </div>
                        <span class="font-medium text-gray-900"><?php echo e($categoryCount); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Recent Activities -->
  <?php if($transaksiTerakhir && count($transaksiTerakhir) > 0): ?>
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Activities
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 divide-y divide-gray-200">
                        <?php $__currentLoopData = $transaksiTerakhir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($transaksi->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($transaksi->tipe === 'masuk'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Incoming
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-2xl text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Outgoing
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo e($transaksi->nama); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(number_format($transaksi->jumlah)); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(number_format($transaksi->stok_sekarang)); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
    @keyframes marquee {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    
    .animate-marquee {
        animation: marquee 30s linear infinite;
        will-change: transform;
    }
    
    .animate-marquee:hover {
        animation-play-state: paused;
        cursor: pointer;
    }
    
    /* Pulse animation for urgent notifications */
    @keyframes urgent-pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }
    
    .animate-urgent {
        animation: urgent-pulse 2s ease-in-out infinite;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Chart configuration
    const chartOptions = {
        series: [{
            name: 'Items In',
            data: <?php echo json_encode($chartMasuk, 15, 512) ?>
        }, {
            name: 'Items Out',
            data: <?php echo json_encode($chartKeluar, 15, 512) ?>
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            background: 'transparent'
        },
        colors: ['#10B981', '#EF4444'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.1,
            }
        },
        xaxis: {
            categories: <?php echo json_encode($chartDates, 15, 512) ?>,
            labels: {
                style: {
                    colors: '#6B7280'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#6B7280'
                }
            }
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 5
        },
        legend: {
            position: 'top',
            labels: {
                colors: '#374151'
            }
        },
        tooltip: {
            theme: 'light'
        }
    };

    const chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
    chart.render();

    // Pie Chart configuration
    const pieChartOptions = {
        series: [<?php echo e($jumlahJenisBarang); ?>, <?php echo e($userCount); ?>, <?php echo e($supplierCount); ?>, <?php echo e($categoryCount); ?>],
        chart: {
            type: 'donut',
            height: 350,
            background: 'transparent'
        },
        labels: ['Items', 'Users', 'Suppliers', 'Categories'],
        colors: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6'],
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex]
            },
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#fff']
            },
            dropShadow: {
                enabled: true,
                top: 1,
                left: 1,
                blur: 1,
                color: '#000',
                opacity: 0.45
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Records',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#374151',
                            formatter: function (w) {
                                return <?php echo e($jumlahJenisBarang + $userCount + $supplierCount + $categoryCount); ?>

                            }
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 'bold',
                            color: '#1F2937'
                        }
                    }
                }
            }
        },
        legend: {
            show: false
        },
        tooltip: {
            enabled: true,
            y: {
                formatter: function(val, opts) {
                    const total = <?php echo e($jumlahJenisBarang + $userCount + $supplierCount + $categoryCount); ?>;
                    const percentage = ((val / total) * 100).toFixed(1);
                    return val + ' (' + percentage + '%)';
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                dataLabels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            }
        }]
    };

    const pieChart = new ApexCharts(document.querySelector("#pieChart"), pieChartOptions);
    pieChart.render();

    function updateChart(range) {
        window.location.href = `<?php echo e(route('admin.dashboard')); ?>?range=${range}`;
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Rayyan af\apaaja\resources\views/admin/contents/dashboard.blade.php ENDPATH**/ ?>