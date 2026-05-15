<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\BorrowingRequest;
use App\Models\LoginLog;
use App\Services\AuthService;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccessController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.authentication.Authentication');
    }

    public function login(LoginRequest $request, AuthService $authService)
    {
        $redirect = $authService->login($request->validated());

        if ($redirect === 'inactive') {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ])->withInput();
        }

        if ($redirect) {
            return Inertia::location($redirect);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showDashboard(Request $request)
    {
        $userRole = Auth::user()->role->value ?? 'user';

        // ── Operator: Maintenance-focused dashboard ──────────────────────
        if ($userRole === 'operator') {
            $inRepairCount     = \App\Models\Maintenance::where('status', 'in_repair')->count();
            $totalUnitInRepair = \App\Models\Maintenance::where('status', 'in_repair')->sum('jumlah');
            $completedToday    = \App\Models\Maintenance::where('status', 'completed')
                                    ->whereDate('completed_at', today())->count();
            $completedTotal    = \App\Models\Maintenance::where('status', 'completed')->count();
            $scrappedTotal     = \App\Models\Maintenance::where('status', 'scrapped')->count();

            $recentMaintenances = \App\Models\Maintenance::with(['item.category', 'user'])
                ->latest('started_at')
                ->take(10)
                ->get();

            return view('admin.contents.operator-dashboard', [
                'inRepairCount'      => $inRepairCount,
                'totalUnitInRepair'  => $totalUnitInRepair,
                'completedToday'     => $completedToday,
                'completedTotal'     => $completedTotal,
                'scrappedTotal'      => $scrappedTotal,
                'recentMaintenances' => $recentMaintenances,
            ]);
        }

        // ── Admin full dashboard ─────────────────────────────────────────
        $query = $request->input('query');
        $items = Item::latest()->get();

        $totalMasuk  = Inventory::where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = Inventory::where('tipe', 'keluar')->sum('jumlah');

        $updatedItem = session('updatedItem');

        $totalValue = Item::all()->sum(function ($item) {
            return ($item->stok_total ?? 0) * ($item->harga ?? 0);
        });

        $jumlahJenisBarang    = Item::count();
        $userCount            = User::count();
        $supplierCount        = Supplier::count();
        $categoryCount        = Category::count();
        $borrowableItemsCount = Item::where('type', 'peminjaman')->count();

        $now      = Carbon::now();
        $lastWeek = $now->copy()->subWeek();

        $keluarThisWeek = Inventory::where('tipe', 'keluar')->whereBetween('created_at', [$lastWeek, $now])->sum('jumlah');
        $keluarLastWeek = Inventory::where('tipe', 'keluar')->whereBetween('created_at', [$lastWeek->copy()->subWeek(), $lastWeek])->sum('jumlah');

        $percentageChange = $keluarLastWeek > 0
            ? (($keluarThisWeek - $keluarLastWeek) / $keluarLastWeek) * 100
            : 0;

        $barangMasukTabel  = Inventory::where('tipe', 'masuk')->latest()->take(5)->get();
        $barangKeluarTabel = Inventory::where('tipe', 'keluar')->latest()->take(5)->get();

        $transaksiTerakhir = Inventory::with('item')->latest()->take(5)->get()->map(function ($transaksi) {
            $item = $transaksi->item;
            return (object) [
                'created_at'    => $transaksi->created_at,
                'tipe'          => $transaksi->tipe,
                'nama'          => $item ? $item->nama : '-',
                'jumlah'        => $transaksi->jumlah,
                'stok_sekarang' => $item ? ($item->stok_total ?? 0) : 0,
            ];
        });

        $range = $request->input('range', 'weekly');
        $chartMasuk = $chartKeluar = $chartDates = [];

        if ($range === 'weekly') {
            $start = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date          = $start->copy()->addDays($i)->format('Y-m-d');
                $chartDates[]  = $start->copy()->addDays($i)->format('D');
                $chartMasuk[]  = Inventory::where('tipe', 'masuk')->whereDate('created_at', $date)->sum('jumlah');
                $chartKeluar[] = Inventory::where('tipe', 'keluar')->whereDate('created_at', $date)->sum('jumlah');
            }
        } elseif ($range === 'monthly') {
            $start       = now()->startOfMonth();
            $daysInMonth = now()->endOfMonth()->day;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date          = $start->copy()->day($i)->format('Y-m-d');
                $chartDates[]  = $i;
                $chartMasuk[]  = Inventory::where('tipe', 'masuk')->whereDate('created_at', $date)->sum('jumlah');
                $chartKeluar[] = Inventory::where('tipe', 'keluar')->whereDate('created_at', $date)->sum('jumlah');
            }
        } elseif ($range === 'yearly') {
            for ($month = 1; $month <= 12; $month++) {
                $chartDates[]  = Carbon::create()->month($month)->format('M');
                $chartMasuk[]  = Inventory::where('tipe', 'masuk')->whereMonth('created_at', $month)->whereYear('created_at', now()->year)->sum('jumlah');
                $chartKeluar[] = Inventory::where('tipe', 'keluar')->whereMonth('created_at', $month)->whereYear('created_at', now()->year)->sum('jumlah');
            }
        }

        $loginLogs = LoginLog::with('user')->latest('logged_in_at')->take(5)->get();

        $pendingBorrowingRequests = BorrowingRequest::with(['user', 'item'])
            ->where('status', 'pending')->latest()->get();

        return view('admin.contents.dashboard', [
            'query'                    => $query,
            'items'                    => $items,
            'totalMasuk'               => $totalMasuk,
            'totalKeluar'              => $totalKeluar,
            'totalValue'               => $totalValue,
            'jumlahJenisBarang'        => $jumlahJenisBarang,
            'userCount'                => $userCount,
            'supplierCount'            => $supplierCount,
            'categoryCount'            => $categoryCount,
            'borrowableItemsCount'     => $borrowableItemsCount,
            'updatedItem'              => $updatedItem,
            'loginLogs'                => $loginLogs,
            'percentageChange'         => $percentageChange,
            'barangMasukTabel'         => $barangMasukTabel,
            'barangKeluarTabel'        => $barangKeluarTabel,
            'chartMasuk'               => $chartMasuk,
            'chartKeluar'              => $chartKeluar,
            'range'                    => $range,
            'chartDates'               => $chartDates,
            'transaksiTerakhir'        => $transaksiTerakhir,
            'pendingBorrowingRequests' => $pendingBorrowingRequests,
        ]);
    }

    public function ShowDashboardManage()
    {
        return panel_redirect('dashboard');
    }

    public function ShowDashboardOperation()
    {
        return panel_redirect('dashboard');
    }

    public function ShowDashboardStatistic()
    {
        return panel_redirect('dashboard');
    }

    public function ShowDashboardUser()
    {
        $user = Auth::user();

        $totalBorrowed = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('jumlah');

        $unreturnedItems = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('jumlah');

        $pendingRequestsCount = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $totalRequests = \App\Models\BorrowingRequest::where('user_id', $user->id)->count();

        $rejectedRequests = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        $availableItems = \App\Models\Item::where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->count();

        $borrowingStats = [
            'total_borrowed' => $totalBorrowed,
            'unreturned_items' => $unreturnedItems,
            'pending_requests' => $pendingRequestsCount,
            'total_requests' => $totalRequests,
            'rejected_requests' => $rejectedRequests,
            'available_items' => $availableItems,
        ];

        $recent_activities = \App\Models\BorrowingRequest::where('user_id', $user->id)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($request) use ($user) {
                $statusText = [
                    'pending' => 'Menunggu persetujuan',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                ];

                $userPhoto = $user->profil ? asset($user->profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=40';

                return [
                    'action' => "Meminjam " . ($request->item?->nama ?? 'Item tidak ditemukan') . " ({$request->jumlah} unit)",
                    'status' => $statusText[$request->status] ?? $request->status,
                    'timestamp' => $request->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i'),
                    'status_class' => $this->getStatusClass($request->status),
                    'user_photo' => $userPhoto,
                    'user_name' => $user->name,
                ];
            });

        // Get recent notifications
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->limit(5)->get();

        return view('user.contents.dashboard', compact('user', 'borrowingStats', 'recent_activities', 'notifications'));
    }

    private function getStatusClass($status)
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
