<?php
namespace App\Http\Controllers;
use App\Models\BorrowingCart;
use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingCartController extends Controller
{
    public function index()
    {
        $cartItems = BorrowingCart::with(['item', 'item.supplier', 'item.category'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.contents.borrowing.cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
        ]);
        $item = Item::findOrFail($validated['item_id']);
        if ($item->stok_peminjaman < $validated['jumlah']) {
            return back()->withErrors(['error' => 'Stok peminjaman tidak mencukupi. Stok tersedia: '.$item->stok_peminjaman]);
        }
        $cartItem = BorrowingCart::where('user_id', Auth::id())
            ->where('item_id', $validated['item_id'])
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->jumlah + $validated['jumlah'];
            if ($newQuantity > $item->stok_peminjaman) {
                return back()->withErrors(['error' => 'Total jumlah melebihi stok tersedia.']);
            }
            $cartItem->update(['jumlah' => $newQuantity]);
            return back()->with('success', 'Jumlah barang di keranjang berhasil diperbarui!');
        } else {
            BorrowingCart::create([
                'user_id' => Auth::id(),
                'item_id' => $validated['item_id'],
                'jumlah' => $validated['jumlah'],
            ]);
            return back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $cartItem = BorrowingCart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        if ($cartItem->item->stok_peminjaman < $validated['jumlah']) {
            return back()->withErrors(['error' => 'Stok peminjaman tidak mencukupi.']);
        }

        $cartItem->update(['jumlah' => $validated['jumlah']]);

        return back()->with('success', 'Jumlah barang berhasil diperbarui!');
    }

    public function remove($id)
    {
        $cartItem = BorrowingCart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        BorrowingCart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keterangan' => 'nullable|string|max:1000',
            'kondisi_pinjam' => 'nullable|string|max:500',
        ]);

        $cartItems = BorrowingCart::with('item')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Keranjang Anda kosong.']);
        }

        DB::beginTransaction();
        try {
            $batchId = uniqid('BATCH-'.Auth::id().'-', true);
            
            foreach ($cartItems as $cartItem) {
                if ($cartItem->item->stok_peminjaman < $cartItem->jumlah) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Stok '.$cartItem->item->nama.' tidak mencukupi.']);
                }
                BorrowingRequest::create([
                    'user_id' => Auth::id(),
                    'batch_id' => $batchId,
                    'item_id' => $cartItem->item_id,
                    'jumlah' => $cartItem->jumlah,
                    'tanggal_pinjam' => $validated['tanggal_pinjam'],
                    'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
                    'keterangan' => $validated['keterangan'],
                    'kondisi_pinjam' => $validated['kondisi_pinjam'],
                    'status' => 'pending',
                ]);
            }
            BorrowingCart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('user.borrowing.my-requests')
                ->with('success', 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan.']);
        }
    }
}
