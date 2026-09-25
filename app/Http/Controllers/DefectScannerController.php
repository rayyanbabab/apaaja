<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Maintenance;
use App\Models\ToolInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DefectScannerController extends Controller
{
    /* ────────────────────────────────────────────
       GET /admin/defect-scanner
       Daftar semua hasil inspeksi
    ──────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = ToolInspection::with(['item', 'inspector', 'borrowingRequest.user'])
            ->latest();

        if ($verdict = $request->get('verdict')) {
            $query->where('verdict', $verdict);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('item', fn($q) => $q->where('nama', 'like', "%$search%"));
        }

        $inspections = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => ToolInspection::count(),
            'ok'       => ToolInspection::where('verdict', 'ok')->count(),
            'defective'=> ToolInspection::whereIn('verdict', ['damaged', 'critical'])->count(),
            'bak'      => ToolInspection::where('bak_issued', true)->count(),
        ];

        $routePrefix = $this->getRoutePrefix();
        return view('admin.contents.defect-scanner.index', compact('inspections', 'stats', 'routePrefix'));
    }

    protected function getRoutePrefix(): string
    {
        $role = Auth::user()?->role;
        $roleVal = $role instanceof \App\Enums\UsersRole ? $role->value : (string) ($role ?? 'admin');
        return $roleVal === 'operator' ? 'staff' : 'admin';
    }

    /* ────────────────────────────────────────────
       GET /admin/defect-scanner/scan/{borrowingRequest?}
       Halaman kamera + scanner
    ──────────────────────────────────────────── */
    public function scan(Request $request, ?BorrowingRequest $borrowingRequest = null)
    {
        $itemId = $request->get('item_id');
        $item   = $itemId ? Item::find($itemId) : $borrowingRequest?->item;
        $items  = Item::orderBy('nama')->get(['id', 'nama', 'kode']);

        // Cari baseline inspection awal saat peminjaman
        $baselineInspection = null;
        if ($borrowingRequest) {
            $baselineInspection = ToolInspection::with('inspector')
                ->where('borrowing_request_id', $borrowingRequest->id)
                ->where('inspection_stage', 'borrow')
                ->latest()
                ->first();
        }
        if (!$baselineInspection && $item) {
            $baselineInspection = ToolInspection::with('inspector')
                ->where('item_id', $item->id)
                ->where('verdict', 'ok')
                ->latest()
                ->first();
        }

        $routePrefix = $this->getRoutePrefix();
        return view('admin.contents.defect-scanner.scan', compact(
            'borrowingRequest', 'item', 'items', 'baselineInspection', 'routePrefix'
        ));
    }

    /* ────────────────────────────────────────────
       POST /admin/defect-scanner/analyze
       Terima gambar + hasil analisis dari client-side AI,
       simpan, dan optionally trigger maintenance.
    ──────────────────────────────────────────── */
    public function analyze(Request $request)
    {
        $request->validate([
            'item_id'           => 'required|exists:items,id',
            'image_data'        => 'required|string',   // base64 PNG
            'verdict'           => 'required|in:ok,minor_wear,damaged,critical',
            'wear_percentage'   => 'required|integer|min:0|max:100',
            'defect_confidence' => 'required|integer|min:0|max:100',
            'defect_types'      => 'nullable|array',
            'defect_regions'    => 'nullable|array',
            'scan_metrics'      => 'nullable|array',
            'scan_duration_ms'  => 'nullable|string',
            'notes'             => 'nullable|string|max:500',
            'inspection_stage'  => 'nullable|in:borrow,return',
            'borrowing_request_id' => 'nullable|exists:borrowing_requests,id',
        ]);

        // Save image
        $imagePath = null;
        if ($imageData = $request->image_data) {
            $imageData  = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
            $decoded    = base64_decode($imageData);
            $filename   = 'inspections/' . date('Y/m') . '/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($filename, $decoded);
            $imagePath = 'storage/' . $filename;
        }

        $inspection = ToolInspection::create([
            'item_id'              => $request->item_id,
            'inspector_id'         => Auth::id(),
            'borrowing_request_id' => $request->borrowing_request_id,
            'image_path'           => $imagePath,
            'verdict'              => $request->verdict,
            'wear_percentage'      => $request->wear_percentage,
            'defect_confidence'    => $request->defect_confidence,
            'defect_types'         => $request->defect_types ?? [],
            'defect_regions'       => $request->defect_regions ?? [],
            'scan_metrics'         => $request->scan_metrics ?? [],
            'scan_duration_ms'     => $request->scan_duration_ms,
            'notes'                => $request->notes,
            'inspection_stage'     => $request->inspection_stage ?? 'return',
        ]);

        // Auto-trigger maintenance if damaged/critical
        $maintenanceId = null;
        if ($inspection->isDefective()) {
            $item = Item::find($request->item_id);
            $maintenance = Maintenance::create([
                'item_id'       => $item->id,
                'user_id'       => Auth::id(),
                'jumlah'        => 1,
                'status'        => Maintenance::STATUS_IN_REPAIR,
                'kondisi_masuk' => 'damaged',
                'catatan'       => 'AUTO — Terdeteksi cacat oleh AI Defect Scanner. Jenis: '
                                  . $inspection->defect_types_label
                                  . '. Keausan: ' . $inspection->wear_percentage . '%.'
                                  . ($request->notes ? ' Catatan: ' . $request->notes : ''),
                'started_at'    => now(),
            ]);
            $inspection->update([
                'maintenance_triggered' => true,
                'maintenance_id'        => $maintenance->id,
            ]);
            $maintenanceId = $maintenance->id;

            // Mark item condition to prevent unsafe re-borrowing (K3 Lockout)
            $kondisi = ($request->verdict === 'critical') ? 'rusak_berat' : 'rusak_ringan';
            $item->update(['kondisi' => $kondisi]);
        }

        return response()->json([
            'success'        => true,
            'inspection_id'  => $inspection->id,
            'maintenance_id' => $maintenanceId,
            'bak_url'        => $inspection->isDefective()
                ? panel_route('defect-scanner.bak', ['inspection' => $inspection->id])
                : null,
        ]);
    }

    /* ────────────────────────────────────────────
       POST /admin/defect-scanner/{inspection}/issue-bak
       Terbitkan BAK resmi
    ──────────────────────────────────────────── */
    public function issueBak(ToolInspection $inspection)
    {
        if ($inspection->bak_issued) {
            return back()->with('info', 'BAK sudah diterbitkan sebelumnya.');
        }

        $inspection->update([
            'bak_issued'    => true,
            'bak_number'    => ToolInspection::generateBakNumber(),
            'bak_issued_at' => now(),
        ]);

        return back()->with('success', 'BAK ' . $inspection->bak_number . ' berhasil diterbitkan.');
    }

    /* ────────────────────────────────────────────
       GET /admin/defect-scanner/{inspection}/bak
       Cetak / preview BAK
    ──────────────────────────────────────────── */
    public function bak(ToolInspection $inspection)
    {
        $inspection->load(['item.category', 'inspector', 'borrowingRequest.user']);
        $routePrefix = $this->getRoutePrefix();
        return view('admin.contents.defect-scanner.bak', compact('inspection', 'routePrefix'));
    }

    /* ────────────────────────────────────────────
       GET /admin/defect-scanner/{inspection}
       Detail hasil inspeksi
    ──────────────────────────────────────────── */
    public function show(ToolInspection $inspection)
    {
        $inspection->load(['item.category', 'inspector', 'borrowingRequest.user', 'maintenance']);
        $routePrefix = $this->getRoutePrefix();
        return view('admin.contents.defect-scanner.show', compact('inspection', 'routePrefix'));
    }
}
