<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Maintenance;
use App\Models\User;
use App\Notifications\MaintenanceAssignedNotification;
use App\Notifications\MaintenanceCompletedNotification;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MaintenanceController extends Controller
{
    /* ──────────────────────────── Index ──────────────────────────── */

    public function index(Request $request)
    {
        $query = Maintenance::with(['item.category', 'user'])
            ->latest('started_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by item
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        // Search by item name
        if ($request->filled('search')) {
            $query->whereHas('item', fn($q) => $q->where('nama', 'like', '%' . $request->search . '%'));
        }

        $maintenances = $query->paginate(15)->withQueryString();

        $statistics = [
            'in_repair'  => Maintenance::where('status', 'in_repair')->count(),
            'completed'  => Maintenance::where('status', 'completed')->count(),
            'scrapped'   => Maintenance::where('status', 'scrapped')->count(),
            'total_unit' => Maintenance::where('status', 'in_repair')->sum('jumlah'),
        ];

        $items = Item::orderBy('nama')->get(['id', 'nama', 'kode']);

        return view('admin.contents.maintenance.index', compact('maintenances', 'statistics', 'items'));
    }

    /* ──────────────────────────── Create ──────────────────────────── */

    public function create()
    {
        $items = Item::where('stok_total', '>', 0)
            ->with('category')
            ->orderBy('nama')
            ->get();

        return view('admin.contents.maintenance.create', compact('items'));
    }

    /* ────────────────────────────────────────────────────────────────
     * Store — Admin only.
     * Mencatat maintenance baru dan mengirim notifikasi ke semua Operator.
     * ──────────────────────────────────────────────────────────────── */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'       => 'required|exists:items,id',
            'jumlah'        => 'required|integer|min:1',
            'kondisi_masuk' => 'nullable|string|max:100',
            'catatan'       => 'nullable|string|max:1000',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        // Validasi stok cukup
        $stokType     = $item->type?->value === 'peminjaman' ? 'peminjaman' : 'reguler';
        $stokTersedia = $stokType === 'peminjaman' ? $item->stok_peminjaman : $item->stok_reguler;

        if ($validated['jumlah'] > $stokTersedia) {
            return back()->withInput()
                ->withErrors(['jumlah' => "Jumlah melebihi stok tersedia ({$stokTersedia} unit)."]);
        }

        $maintenance = null;

        DB::transaction(function () use ($validated, $item, $stokType, &$maintenance) {
            // Kurangi stok sementara
            $item->reduceStok($validated['jumlah'], $stokType);

            // Buat record maintenance
            $maintenance = Maintenance::create([
                'item_id'       => $item->id,
                'user_id'       => Auth::id(),
                'jumlah'        => $validated['jumlah'],
                'status'        => Maintenance::STATUS_IN_REPAIR,
                'kondisi_masuk' => $validated['kondisi_masuk'] ?? null,
                'catatan'       => $validated['catatan'] ?? null,
                'started_at'    => now(),
            ]);

            AuditLogger::log(
                'maintenance.created',
                'Maintenance',
                "Barang \"{$item->nama}\" ({$item->kode}) masuk servis — {$maintenance->jumlah} unit",
                $maintenance
            );
        });

        // ── Kirim notifikasi ke semua Operator ─────────────────────────
        $operators = User::where('role', 'operator')->get();

        if ($operators->isNotEmpty()) {
            Notification::send(
                $operators,
                new MaintenanceAssignedNotification($maintenance->load('item'), Auth::user()->name)
            );
        }

        return panel_redirect('maintenance.index')
            ->with('success', "Barang \"{$item->nama}\" ({$validated['jumlah']} unit) berhasil dicatat masuk servis. Operator telah dinotifikasi.");
    }

    /* ──────────────────────────── Show ──────────────────────────── */

    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['item.category', 'item.location', 'user']);
        return view('admin.contents.maintenance.show', compact('maintenance'));
    }

    /* ────────────────────────────────────────────────────────────────
     * Complete — Operator executes.
     * Menyelesaikan servis & memberi tahu Admin.
     * ──────────────────────────────────────────────────────────────── */

    public function complete(Request $request, Maintenance $maintenance)
    {
        // Hanya Operator yang boleh menyelesaikan maintenance
        if (!Auth::user()->hasRole('operator')) {
            abort(403, 'Hanya Operator yang dapat menyelesaikan maintenance.');
        }

        $validated = $request->validate([
            'catatan_selesai' => 'nullable|string|max:1000',
        ]);

        if ($maintenance->status !== Maintenance::STATUS_IN_REPAIR) {
            return back()->with('error', 'Maintenance ini sudah tidak aktif.');
        }

        $item = $maintenance->item;

        DB::transaction(function () use ($maintenance, $validated, $item) {
            $maintenance->selesai($validated['catatan_selesai'] ?? null);

            AuditLogger::log(
                'maintenance.completed',
                'Maintenance',
                "Barang \"{$item->nama}\" ({$item->kode}) selesai servis — {$maintenance->jumlah} unit dikembalikan",
                $maintenance
            );
        });

        // ── Kirim notifikasi balik ke semua Admin ──────────────────────
        $admins = User::where('role', 'admin')->get();

        if ($admins->isNotEmpty()) {
            Notification::send(
                $admins,
                new MaintenanceCompletedNotification(
                    $maintenance->load('item'),
                    Auth::user()->name,
                    'completed'
                )
            );
        }

        return panel_redirect('maintenance.index')
            ->with('success', "Servis barang \"{$item->nama}\" selesai. {$maintenance->jumlah} unit stok dikembalikan.");
    }

    /* ────────────────────────────────────────────────────────────────
     * Scrap — Operator executes.
     * Meng-scrap barang & memberi tahu Admin.
     * ──────────────────────────────────────────────────────────────── */

    public function scrap(Request $request, Maintenance $maintenance)
    {
        // Hanya Operator yang boleh meng-scrap maintenance
        if (!Auth::user()->hasRole('operator')) {
            abort(403, 'Hanya Operator yang dapat meng-scrap maintenance.');
        }

        $validated = $request->validate([
            'catatan_selesai' => 'nullable|string|max:1000',
        ]);

        if ($maintenance->status !== Maintenance::STATUS_IN_REPAIR) {
            return back()->with('error', 'Maintenance ini sudah tidak aktif.');
        }

        $item = $maintenance->item;

        DB::transaction(function () use ($maintenance, $validated, $item) {
            $maintenance->scrap($validated['catatan_selesai'] ?? null);

            AuditLogger::log(
                'maintenance.scrapped',
                'Maintenance',
                "Barang \"{$item->nama}\" ({$item->kode}) dihapuskan (scrap) — {$maintenance->jumlah} unit stok tidak dikembalikan",
                $maintenance
            );
        });

        // ── Kirim notifikasi balik ke semua Admin ──────────────────────
        $admins = User::where('role', 'admin')->get();

        if ($admins->isNotEmpty()) {
            Notification::send(
                $admins,
                new MaintenanceCompletedNotification(
                    $maintenance->load('item'),
                    Auth::user()->name,
                    'scrapped'
                )
            );
        }

        return panel_redirect('maintenance.index')
            ->with('warning', "Barang \"{$item->nama}\" ({$maintenance->jumlah} unit) telah di-scrap. Stok tidak dikembalikan.");
    }
}
