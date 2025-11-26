<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBorrowingController extends Controller
{
    public function index()
    {
        $items = Item::with(['supplier', 'category'])
            ->where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->get();

        return view('user.contents.borrowing.index', compact('items'));
    }

    public function myRequests()
    {
        $requests = BorrowingRequest::with(['item', 'item.supplier', 'approvedBy'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.contents.borrowing.my-requests', compact('requests'));
    }

    public function create($itemId)
    {
        $item = Item::with(['supplier', 'category'])
            ->where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->findOrFail($itemId);

        return view('user.contents.borrowing.create', compact('item'));
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
        $item = Item::findOrFail($validated['item_id']);
        if ($item->stok_peminjaman < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
        }
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
        $request = BorrowingRequest::with(['item.category', 'item.supplier', 'user', 'approvedBy'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.contents.borrowing.show', compact('request'));
    }

    public function showItem($id)
    {
        $item = Item::with(['category', 'supplier'])
            ->where('type', 'peminjaman')
            ->where('stok_peminjaman', '>', 0)
            ->findOrFail($id);

        return view('user.contents.borrowing.show-item', compact('item'));
    }
}