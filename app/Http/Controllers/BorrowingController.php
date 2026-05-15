<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\BorrowingCompletedNotification;
use App\Notifications\OngoingBorrowingNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BorrowingController extends Controller
{
    private function sendCompletedNotification(Borrowing $borrowing): void
    {
        if (! $borrowing->user) {
            return;
        }

        $alreadySent = $borrowing->user->notifications()
            ->where('type', BorrowingCompletedNotification::class)
            ->where('data->borrowing_id', $borrowing->id)
            ->exists();

        if (! $alreadySent) {
            $borrowing->user->notify(new BorrowingCompletedNotification($borrowing));
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only show active borrowings (not yet returned)
        $borrowings = Borrowing::with(['item', 'item.supplier', 'user'])
            ->where('status', '!=', 'dikembalikan')
            ->latest()
            ->paginate(15);

        $users = User::select('id', 'name')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.index', compact('borrowings', 'users'));
    }


    /**
     * Display borrowing history (all statuses)
     */
    public function history(Request $request)
    {
        $query = Borrowing::with(['item', 'item.supplier', 'item.category', 'user']);
        
        // Apply filters if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }
        if ($request->filled('return_date_from')) {
            $query->whereDate('tanggal_kembali_aktual', '>=', $request->return_date_from);
        }
        if ($request->filled('return_date_to')) {
            $query->whereDate('tanggal_kembali_aktual', '<=', $request->return_date_to);
        }

        $borrowings = $query->latest('tanggal_kembali_aktual')->paginate(15);
        $users = User::select('id', 'name')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.history', compact('borrowings', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $maxBorrowDays = (int) Setting::get('max_borrow_days', 7);

        $items = Item::with(['supplier:id,nama,company_name', 'category:id,name', 'location:id,name,kode,parent_id', 'location.parent:id,name'])
            ->select('id', 'nama', 'stok_peminjaman', 'supplier_id', 'category_id', 'location_id', 'keterangan')
            ->where('stok_peminjaman', '>', 0)
            ->orderBy('nama')
            ->get()
            ->map(function($item) {
                if ($item->location) {
                    $item->location_label = $item->location->parent
                        ? $item->location->parent->name . ' › ' . $item->location->name
                        : $item->location->name;
                    $item->location_kode  = $item->location->kode ?? '';
                } else {
                    $item->location_label = '';
                    $item->location_kode  = '';
                }
                return $item;
            });
        $users = User::select('id', 'name', 'email')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.create', compact('items', 'users', 'maxBorrowDays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $maxBorrowDays = (int) Setting::get('max_borrow_days', 7);

        $validated = $request->validate([
            'item_id'                 => 'required|exists:items,id',
            'user_id'                 => [
                'required',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'user')),
            ],
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keterangan'              => 'nullable|string',
            'kondisi_pinjam'          => 'nullable|string',
        ]);

        $borrowDate = \Carbon\Carbon::parse($validated['tanggal_pinjam']);
        $maxReturnDate = $borrowDate->copy()->addDays($maxBorrowDays);
        if (\Carbon\Carbon::parse($validated['tanggal_kembali_rencana'])->gt($maxReturnDate)) {
            return back()
                ->withErrors([
                    'tanggal_kembali_rencana' => "Tanggal rencana kembali maksimal {$maxBorrowDays} hari dari tanggal pinjam.",
                ])
                ->withInput();
        }

        $item = Item::findOrFail($validated['item_id']);
        $availableStock = $item->getAvailableStokForBorrowing();
        if ($availableStock < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok peminjaman tidak mencukupi. Stok tersedia: ' . $availableStock]);
        }

        $borrowing = Borrowing::create([
            'item_id'                 => $validated['item_id'],
            'user_id'                 => $validated['user_id'],
            'jumlah'                  => $validated['jumlah'],
            'tanggal_pinjam'          => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'keterangan'              => $validated['keterangan'] ?? null,
            'kondisi_pinjam'          => $validated['kondisi_pinjam'] ?? null,
            'status'                  => 'dipinjam',
        ]);
        $item->reduceStok($validated['jumlah'], 'peminjaman');

        $borrowing->loadMissing(['user', 'item']);
        if ($borrowing->user) {
            $borrowing->user->notify(new OngoingBorrowingNotification($borrowing));
        }

        return panel_redirect('borrowings.index')
            ->with('success', 'Peminjaman berhasil dicatat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['item', 'item.supplier', 'item.category', 'user']);

        return view('admin.contents.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        $items = Item::with(['supplier', 'category'])->orderBy('nama')->get();
        $users = User::select('id', 'name', 'email')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.contents.borrowings.edit', compact('borrowing', 'items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'tanggal_kembali_aktual' => 'nullable|date',
            'status'                 => 'required|in:dipinjam,dikembalikan,terlambat',
            'kondisi_kembali'        => 'nullable|string',
            'keterangan'             => 'nullable|string',
        ]);

        $oldStatus = $borrowing->status;

        $borrowing->update([
            'status'                 => $validated['status'],
            'kondisi_kembali'        => $validated['kondisi_kembali'] ?? null,
            'keterangan'             => $validated['keterangan'] ?? null,
            'tanggal_kembali_aktual' => $validated['tanggal_kembali_aktual'] ?? null,
        ]);

        if ($oldStatus !== 'dikembalikan' && $validated['status'] === 'dikembalikan') {
            $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');
            $borrowing->update(['tanggal_kembali_aktual' => now()]);
            $borrowing->loadMissing(['user', 'item']);
            $this->sendCompletedNotification($borrowing);
        }

        return panel_redirect('borrowings.index')
            ->with('success', 'Data peminjaman berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->status === 'dipinjam' || $borrowing->status === 'terlambat') {
            $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');
        }

        $borrowing->delete();

        return panel_redirect('borrowings.index')
            ->with('success', 'Data peminjaman berhasil dihapus');
    }

    /**
     * Return borrowed item
     */
    public function returnItem(Borrowing $borrowing)
    {
        if ($borrowing->status === 'dikembalikan') {
            return back()->withErrors(['error' => 'Barang sudah dikembalikan']);
        }

        // Update the borrowing record and restore stock
        $borrowing->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => now(),
        ]);

        // Add stock back to inventory
        $borrowing->item->addStok($borrowing->jumlah, 'peminjaman');

        $borrowing->loadMissing(['user', 'item']);
        $this->sendCompletedNotification($borrowing);

        return back()->with('success', 'Barang berhasil dikembalikan dan stok telah dikembalikan ke inventaris.');
    }
}