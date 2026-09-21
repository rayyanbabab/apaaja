<?php

namespace App\Http\Controllers;

use App\Models\BorrowingCart;
use App\Models\Item;
use App\Models\ToolingKit;
use App\Models\ToolingKitItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToolingKitController extends Controller
{
    public function index()
    {
        $kits = ToolingKit::with(['kitItems.item'])->latest()->paginate(10);
        $availableItems = Item::where('type', 'peminjaman')
                              ->where('stok_peminjaman', '>', 0)
                              ->orderBy('nama')
                              ->get();

        return view('admin.contents.tooling-kits.index', compact('kits', 'availableItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'           => 'required|string|max:50|unique:tooling_kits,kode',
            'nama'           => 'required|string|max:150',
            'target_machine' => 'nullable|string|max:100',
            'deskripsi'      => 'nullable|string|max:500',
            'item_ids'       => 'required|array|min:1',
            'item_ids.*'     => 'exists:items,id',
            'quantities'     => 'required|array',
            'quantities.*'   => 'integer|min:1',
            'notes'          => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $kit = ToolingKit::create([
                'kode'           => $validated['kode'],
                'nama'           => $validated['nama'],
                'target_machine' => $validated['target_machine'],
                'deskripsi'      => $validated['deskripsi'],
                'is_active'      => true,
            ]);

            foreach ($validated['item_ids'] as $idx => $itemId) {
                ToolingKitItem::create([
                    'tooling_kit_id' => $kit->id,
                    'item_id'        => $itemId,
                    'jumlah'         => $validated['quantities'][$idx] ?? 1,
                    'catatan'        => $validated['notes'][$idx] ?? null,
                ]);
            }

            DB::commit();
            return back()->with('success', "Paket Perkakas (Tooling Kit) '{$kit->nama}' berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat paket perkakas: ' . $e->getMessage()]);
        }
    }

    public function destroy(ToolingKit $toolingKit)
    {
        $nama = $toolingKit->nama;
        $toolingKit->delete();
        return back()->with('success', "Paket '{$nama}' berhasil dihapus.");
    }

    public function toggleStatus(ToolingKit $toolingKit)
    {
        $toolingKit->is_active = !$toolingKit->is_active;
        $toolingKit->save();

        $statusStr = $toolingKit->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Paket '{$toolingKit->nama}' berhasil {$statusStr}.");
    }

    /**
     * Fitur One-Click Kit Checkout untuk User/Teknisi
     */
    public function borrowKit(ToolingKit $toolingKit)
    {
        $toolingKit->load('kitItems.item');

        if (!$toolingKit->is_active) {
            return back()->withErrors(['error' => 'Paket perkakas ini sedang tidak aktif.']);
        }

        if ($toolingKit->kitItems->isEmpty()) {
            return back()->withErrors(['error' => 'Paket ini belum memiliki daftar perkakas.']);
        }

        // 1. Validasi seluruh item dalam paket (stok & kalibrasi)
        foreach ($toolingKit->kitItems as $kitItem) {
            $item = $kitItem->item;

            // Cek proteksi kalibrasi alat ukur
            if (!$item->canBeBorrowedForManufacturing()) {
                $due = $item->calibration_due_date ? $item->calibration_due_date->format('d/m/Y') : '-';
                return back()->withErrors([
                    'error' => "Paket tidak dapat dipinjam karena alat ukur '{$item->nama}' di dalamnya telah melewati batas kalibrasi ({$due})."
                ]);
            }

            // Cek kecukupan stok peminjaman
            if ($item->stok_peminjaman < $kitItem->jumlah) {
                return back()->withErrors([
                    'error' => "Stok untuk '{$item->nama}' tidak mencukupi (dibutuhkan: {$kitItem->jumlah}, tersedia: {$item->stok_peminjaman})."
                ]);
            }
        }

        // 2. Masukkan semua item ke keranjang user
        DB::beginTransaction();
        try {
            foreach ($toolingKit->kitItems as $kitItem) {
                $cartItem = BorrowingCart::where('user_id', Auth::id())
                    ->where('item_id', $kitItem->item_id)
                    ->first();

                if ($cartItem) {
                    $newQty = $cartItem->jumlah + $kitItem->jumlah;
                    $cartItem->update(['jumlah' => $newQty]);
                } else {
                    BorrowingCart::create([
                        'user_id' => Auth::id(),
                        'item_id' => $kitItem->item_id,
                        'jumlah'  => $kitItem->jumlah,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('user.borrowing.cart')->with(
                'success',
                "Seluruh perkakas untuk paket '{$toolingKit->nama}' berhasil dimasukkan ke keranjang Anda!"
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses peminjaman paket: ' . $e->getMessage()]);
        }
    }
}
