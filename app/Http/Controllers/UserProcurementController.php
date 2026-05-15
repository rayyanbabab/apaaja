<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\User;
use App\Notifications\ProcurementSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProcurementController extends Controller
{
    public function index()
    {
        $procurements = ProcurementRequest::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $counts = ProcurementRequest::where('user_id', Auth::id())
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('user.contents.procurement.index', compact('procurements', 'counts'));
    }

    public function create()
    {
        return view('user.contents.procurement.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah'      => 'required|integer|min:1|max:9999',
            'alasan'      => 'nullable|string|max:1000',
        ]);

        $procurement = ProcurementRequest::create([
            'user_id'     => Auth::id(),
            'nama_barang' => $validated['nama_barang'],
            'jumlah'      => $validated['jumlah'],
            'alasan'      => $validated['alasan'] ?? null,
            'status'      => 'pending',
        ]);

        // Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ProcurementSubmittedNotification($procurement->load('user')));
        }

        return redirect()->route('user.procurement.index')
            ->with('success', 'Permintaan pengadaan berhasil diajukan. Admin akan segera meninjaunya.');
    }

    public function show($id)
    {
        $procurement = ProcurementRequest::with(['reviewer', 'item', 'inventory'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.contents.procurement.show', compact('procurement'));
    }

    public function cancel($id)
    {
        $procurement = ProcurementRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $procurement->delete();

        return redirect()->route('user.procurement.index')
            ->with('success', 'Permintaan pengadaan berhasil dibatalkan.');
    }
}
