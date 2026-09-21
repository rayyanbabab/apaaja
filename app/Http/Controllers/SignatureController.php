<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SignatureController extends Controller
{
    /**
     * Display a list of all BAP documents (both pending signature and signed).
     * Admin only.
     */
    public function index(Request $request)
    {
        $query = BorrowingRequest::with([
            'user', 'item', 'item.category', 'item.location', 'approvedBy'
        ])->whereIn('status', ['approved', 'completed']);

        // Filter by signature status
        if ($request->filled('status')) {
            if ($request->status === 'signed') {
                $query->whereNotNull('signed_at');
            } elseif ($request->status === 'unsigned') {
                $query->whereNull('signed_at');
            }
        }

        // Search query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bap_number', 'like', "%{$search}%")
                  ->orWhere('bap_token', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('item', function ($iq) use ($search) {
                      $iq->where('nama', 'like', "%{$search}%")
                         ->orWhere('kode', 'like', "%{$search}%");
                  });
            });
        }

        $bapRequests = $query->latest('updated_at')->paginate(12)->withQueryString();

        $totalEligible = BorrowingRequest::whereIn('status', ['approved', 'completed'])->count();
        $signedCount   = BorrowingRequest::whereIn('status', ['approved', 'completed'])->whereNotNull('signed_at')->count();
        $unsignedCount = BorrowingRequest::whereIn('status', ['approved', 'completed'])->whereNull('signed_at')->count();

        $user = Auth::user();
        $roleValue = is_object($user->role) ? $user->role->value : $user->role;
        $routePrefix = $roleValue === 'admin' ? 'admin' : 'staff';

        return view('admin.contents.borrowing-requests.bap-index', compact(
            'bapRequests', 'totalEligible', 'signedCount', 'unsignedCount', 'routePrefix'
        ));
    }

    /**
     * Show the BAP (Berita Acara Peminjaman) page with signature pad.
     * Admin only.
     */
    public function showBap($id)
    {
        $borrowingRequest = BorrowingRequest::with([
            'user', 'item', 'item.category', 'item.location',
            'approvedBy',
        ])->findOrFail($id);

        return view('admin.contents.borrowing-requests.bap', compact('borrowingRequest'));
    }

    /**
     * Save the digital signature and lock the BAP.
     * Admin only.
     */
    public function sign(Request $request, $id)
    {
        $borrowingRequest = BorrowingRequest::findOrFail($id);

        // Prevent re-signing
        if ($borrowingRequest->signed_at) {
            return back()->withErrors(['error' => 'BAP ini sudah ditandatangani.']);
        }

        $validated = $request->validate([
            'signature_data'  => 'required|string',   // Base64 PNG from canvas
            'signed_by_name'  => 'required|string|max:255',
        ]);

        // Generate unique BAP number and verification token
        $bapNumber = $this->generateBapNumber();
        $token     = Str::random(48);

        $borrowingRequest->update([
            'signature_data'  => $validated['signature_data'],
            'signed_by_name'  => $validated['signed_by_name'],
            'signed_at'       => now(),
            'bap_number'      => $bapNumber,
            'bap_token'       => $token,
        ]);

        AuditLogger::log(
            'bap.signed',
            'BAP',
            "BAP {$bapNumber} untuk peminjaman #{$borrowingRequest->id} oleh {$borrowingRequest->user->name} ditandatangani secara digital",
            $borrowingRequest
        );

        return redirect()->route(
            $this->panel() . 'borrowing-requests.bap',
            $borrowingRequest->id
        )->with('success', 'BAP berhasil ditandatangani secara digital. Token verifikasi QR telah dibuat.');
    }

    /**
     * Public QR verification endpoint — no authentication required.
     */
    public function verify($token)
    {
        $borrowingRequest = BorrowingRequest::with([
            'user', 'item', 'item.category', 'approvedBy',
        ])->where('bap_token', $token)->first();

        if (! $borrowingRequest) {
            return view('public.bap-verify', ['valid' => false, 'borrowingRequest' => null]);
        }

        return view('public.bap-verify', [
            'valid'           => true,
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    private function generateBapNumber(): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');

        // Count existing BAPs this month
        $count = BorrowingRequest::whereNotNull('bap_number')
            ->whereYear('signed_at', $year)
            ->whereMonth('signed_at', $month)
            ->count();

        $seq = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "BAP/{$year}/{$month}/{$seq}";
    }

    private function panel(): string
    {
        $user = Auth::user();
        if ($user) {
            $roleValue = is_object($user->role) ? $user->role->value : $user->role;
            if ($roleValue === 'admin') {
                return 'admin.';
            }
        }

        return 'admin.';
    }
}
