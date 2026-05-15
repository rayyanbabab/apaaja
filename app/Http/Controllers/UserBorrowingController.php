<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Setting;
use App\Enums\ItemType;
use App\Notifications\BorrowingExpiredNotification;
use App\Notifications\ItemOverdueNotification;
use App\Notifications\OngoingBorrowingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBorrowingController extends Controller
{
    public function index()
    {
        // Show available items for borrowing (type = peminjaman)
        $items = Item::with(['supplier', 'category', 'location'])
            ->where('type', 'peminjaman')  // Use string value
            ->where('stok_peminjaman', '>', 0)
            ->get();

        return view('user.contents.borrowing.index', compact('items'));
    }

    public function myRequests()
    {
        // ── Auto-expire: cancel pending requests whose borrow date has passed ──
        $expiredPending = BorrowingRequest::with(['item'])
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->whereDate('tanggal_pinjam', '<', now()->toDateString())
            ->get();

        foreach ($expiredPending as $borrowing) {
            $borrowing->update(['status' => 'cancelled']);

            // Send notification only once per request
            $alreadyNotified = auth()->user()->notifications()
                ->where('type', BorrowingExpiredNotification::class)
                ->where('data->borrowing_request_id', $borrowing->id)
                ->exists();

            if (!$alreadyNotified) {
                auth()->user()->notify(new BorrowingExpiredNotification($borrowing));
            }
        }

        // ── Overdue check: approved items whose return date has passed (≤ today) ──
        if (Setting::get('enable_overdue_reminder', true)) {
            $overdueItems = BorrowingRequest::with(['item'])
                ->where('user_id', Auth::id())
                ->where('status', 'approved')
                ->whereDate('tanggal_kembali_rencana', '<=', now()->toDateString())
                ->get();

            foreach ($overdueItems as $borrowing) {
                // Only send once per day (24-hour cooldown)
                if ($borrowing->overdue_notified_at && $borrowing->overdue_notified_at->isToday()) {
                    continue;
                }
                auth()->user()->notify(new ItemOverdueNotification($borrowing));
                $borrowing->update(['overdue_notified_at' => now()]);
            }
        }

        // Show user's current borrowing requests (pending and approved only)
        $requests = BorrowingRequest::with(['item', 'item.supplier', 'approvedBy'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->paginate(10);

        // Check for ongoing borrowings and send notifications if needed
        $ongoingBorrowings = BorrowingRequest::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get();

        foreach ($ongoingBorrowings as $borrowing) {
            $hasNotification = auth()->user()->notifications()
                ->where('type', 'App\\Notifications\\OngoingBorrowingNotification')
                ->where('data->borrowing_request_id', $borrowing->id)
                ->exists();

            if (!$hasNotification) {
                auth()->user()->notify(new OngoingBorrowingNotification($borrowing));
            }
        }

        return view('user.contents.borrowing.my-requests', compact('requests'));
    }

    public function history()
    {
        // Show user's borrowing history (rejected, completed, and cancelled)
        $requests = BorrowingRequest::with(['item', 'item.supplier', 'approvedBy'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['rejected', 'completed', 'cancelled'])
            ->latest()
            ->paginate(10);

        return view('user.contents.borrowing.history', compact('requests'));
    }

    public function create($itemId)
    {
        $item = Item::with(['supplier', 'category'])
            ->where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->findOrFail($itemId);

        $maxBorrowDays = Setting::get('max_borrow_days', 7);

        return view('user.contents.borrowing.create', compact('item', 'maxBorrowDays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keterangan' => 'nullable|string|max:1000',
            'kondisi_pinjam' => 'nullable|string|max:500',
        ]);

        // Cek batas maksimal item aktif per user
        $maxItems = Setting::get('max_items_per_user', 3);
        $activeCount = BorrowingRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($activeCount >= $maxItems) {
            return back()->withErrors(['error' => 'Batas peminjaman aktif adalah ' . $maxItems . ' item. Anda saat ini memiliki ' . $activeCount . ' item aktif. Selesaikan atau batalkan peminjaman sebelum mengajukan yang baru.']);
        }

        // Check if item has enough borrowing stock
        $item = Item::findOrFail($validated['item_id']);
        if ($item->stok_peminjaman < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
        }

        // Create borrowing request
        BorrowingRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $validated['item_id'],
            'jumlah' => $validated['jumlah'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
            'keterangan' => $validated['keterangan'],
            'kondisi_pinjam' => $validated['kondisi_pinjam'],
            'status' => 'pending',
        ]);

        return redirect()->route('user.borrowing.my-requests')
            ->with('success', 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.');
    }

    public function show($id)
    {
        $request = BorrowingRequest::with(['item.category', 'item.supplier', 'item.location', 'user', 'approvedBy'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.contents.borrowing.show', compact('request'));
    }

    public function printDetail($id)
    {
        $request = BorrowingRequest::with(['item.category', 'item.supplier', 'item.location', 'user', 'approvedBy'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.contents.borrowing.print', compact('request'));
    }

    public function showItem($id)
    {
        $item = Item::with(['category', 'supplier'])
            ->where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->findOrFail($id);

        return view('user.contents.borrowing.show-item', compact('item'));
    }

    public function cancel($id)
    {
        $request = BorrowingRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('user.borrowing.my-requests')
            ->with('success', 'Permintaan peminjaman berhasil dibatalkan.');
    }
}