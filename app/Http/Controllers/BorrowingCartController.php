<?php
namespace App\Http\Controllers;
use App\Models\BorrowingCart;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Setting;
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

        $maxBorrowDays = (int) Setting::get('max_borrow_days', 7);

        // K3 Safety Interlock Check
        $riskItems = $cartItems->filter(function ($ci) {
            return $ci->item && $ci->item->requiresSafetyInterlock();
        });

        $hasSafetyRisk = $riskItems->isNotEmpty();
        $allRequiredApdKeys = [];
        foreach ($riskItems as $ri) {
            if (is_array($ri->item->required_apd)) {
                $allRequiredApdKeys = array_merge($allRequiredApdKeys, $ri->item->required_apd);
            }
        }
        $allRequiredApdKeys = array_values(array_unique($allRequiredApdKeys));
        $apdCatalog = Item::getApdCatalog();

        return view('user.contents.borrowing.cart', compact(
            'cartItems',
            'maxBorrowDays',
            'hasSafetyRisk',
            'riskItems',
            'allRequiredApdKeys',
            'apdCatalog'
        ));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
        ]);
        $item = Item::findOrFail($validated['item_id']);

        if (!$item->canBeBorrowedForManufacturing()) {
            if ($item->isK3Lockout()) {
                return back()->withErrors(['error' => "⛔ PROTOKOL K3 INTERLOCK AKTIF: Alat '{$item->nama}' berstatus Rusak Berat / Terdeteksi Cacat Kritis. Alat ditarik otomatis dari sirkulasi peminjaman demi keselamatan kerja."]);
            }
            $dueStr = $item->calibration_due_date ? $item->calibration_due_date->format('d/m/Y') : '-';
            return back()->withErrors(['error' => "Alat ukur presisi '{$item->nama}' tidak dapat dipinjam karena masa berlaku sertifikat kalibrasi telah kedaluwarsa ({$dueStr}). Harap lakukan kalibrasi ulang untuk menjamin toleransi produk."]);
        }

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
        $cartItems = BorrowingCart::with('item')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Keranjang Anda kosong.']);
        }

        // Cek apakah ada alat berisiko sedang atau tinggi
        $riskItems = $cartItems->filter(function ($ci) {
            return $ci->item && $ci->item->requiresSafetyInterlock();
        });

        $rules = [
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:1000',
            'kondisi_pinjam'          => 'nullable|string|max:500',
        ];

        $messages = [];

        if ($riskItems->isNotEmpty()) {
            $rules['safety_agreement'] = 'required|accepted';
            $rules['safety_apd']       = 'required|array|min:1';
            $messages['safety_agreement.required'] = 'Terdapat alat berisiko K3 dalam keranjang Anda! Wajib menyetujui Pakta Integritas & Keselamatan Kerja Lab.';
            $messages['safety_agreement.accepted'] = 'Anda wajib mencentang persetujuan Pakta Keselamatan K3.';
            $messages['safety_apd.required']       = 'Wajib mengonfirmasi kelengkapan APD yang Anda siapkan sebelum checkout alat berisiko.';
            $messages['safety_apd.min']            = 'Pilih minimal satu checklist APD yang dipersyaratkan.';
        }

        $validated = $request->validate($rules, $messages);

        $maxBorrowDays = (int) Setting::get('max_borrow_days', 7);
        $borrowDate = \Carbon\Carbon::parse($validated['tanggal_pinjam']);
        $maxReturnDate = $borrowDate->copy()->addDays($maxBorrowDays);
        if (\Carbon\Carbon::parse($validated['tanggal_kembali_rencana'])->gt($maxReturnDate)) {
            return back()->withErrors(['tanggal_kembali_rencana' => "Tanggal rencana kembali maksimal {$maxBorrowDays} hari dari tanggal pinjam."])->withInput();
        }

        // Cek batas maksimal item aktif per user
        $maxItems = Setting::get('max_items_per_user', 3);
        $activeCount = BorrowingRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->count();
        $cartCount = $cartItems->count();

        if (($activeCount + $cartCount) > $maxItems) {
            $remaining = max(0, $maxItems - $activeCount);
            return back()->withErrors(['error' => 'Batas peminjaman aktif adalah ' . $maxItems . ' item. Anda saat ini memiliki ' . $activeCount . ' item aktif. Anda hanya bisa menambahkan ' . $remaining . ' item lagi.']);
        }

        DB::beginTransaction();
        try {
            $batchId = uniqid('BATCH-'.Auth::id().'-', true);
            
            foreach ($cartItems as $cartItem) {
                if (!$cartItem->item->canBeBorrowedForManufacturing()) {
                    DB::rollBack();
                    if ($cartItem->item->isK3Lockout()) {
                        return back()->withErrors(['error' => "⛔ PROTOKOL K3 INTERLOCK: Alat '{$cartItem->item->nama}' berstatus Rusak Berat / Terdeteksi Cacat Kritis. Hapus dari keranjang untuk melanjutkan peminjaman."]);
                    }
                    return back()->withErrors(['error' => "Alat ukur presisi '{$cartItem->item->nama}' sudah melewati batas masa berlaku kalibrasi. Hapus dari keranjang untuk melanjutkan."]);
                }
                if ($cartItem->item->stok_peminjaman < $cartItem->jumlah) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Stok '.$cartItem->item->nama.' tidak mencukupi.']);
                }

                $isRisk = $cartItem->item && $cartItem->item->requiresSafetyInterlock();

                BorrowingRequest::create([
                    'user_id'                 => Auth::id(),
                    'batch_id'                => $batchId,
                    'item_id'                 => $cartItem->item_id,
                    'jumlah'                  => $cartItem->jumlah,
                    'tanggal_pinjam'          => $validated['tanggal_pinjam'],
                    'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
                    'keterangan'              => $validated['keterangan'],
                    'kondisi_pinjam'          => $validated['kondisi_pinjam'],
                    'status'                  => 'pending',
                    'safety_agreed_at'        => $isRisk ? now() : null,
                    'safety_apd_checklist'    => $isRisk ? $request->input('safety_apd', []) : null,
                ]);
            }
            BorrowingCart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('user.borrowing.my-requests')
                ->with('success', 'Permintaan peminjaman berhasil diajukan dengan komitmen K3. Menunggu persetujuan admin & verifikasi fisik APD di loket.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage()]);
        }
    }
}
