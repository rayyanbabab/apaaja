<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\SafetyIncident;
use App\Models\Setting;
use App\Models\User;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SafetyController extends Controller
{
    /**
     * Tampilan Utama K3 Safety Interlock & Zero Accident Dashboard
     */
    public function index(Request $request)
    {
        // 1. Metrik Zero Accident
        $lastAccident = SafetyIncident::whereIn('incident_type', ['minor_injury', 'tool_misuse'])
            ->latest('incident_date')
            ->first();

        $zeroAccidentDays = $lastAccident 
            ? Carbon::parse($lastAccident->incident_date)->diffInDays(now()) 
            : 184; // Standar default showcase laboratorium jika belum ada rekam cedera berat

        // 2. Kepatuhan APD Digital (Compliance Rate)
        $riskBorrowingQuery = BorrowingRequest::whereHas('item', function ($q) {
            $q->whereIn('safety_risk_level', ['medium', 'high']);
        });

        $totalRiskBorrowings = (clone $riskBorrowingQuery)->count();
        $verifiedRiskBorrowings = (clone $riskBorrowingQuery)->whereNotNull('safety_verified_at')->count();
        $complianceRate = $totalRiskBorrowings > 0 
            ? round(($verifiedRiskBorrowings / $totalRiskBorrowings) * 100) 
            : 0;

        // 3. Count Ringkasan
        $highRiskCount = Item::where('safety_risk_level', 'high')->count();
        $mediumRiskCount = Item::where('safety_risk_level', 'medium')->count();
        $lowRiskCount = Item::where('safety_risk_level', 'low')->count();
        $totalIncidentsCount = SafetyIncident::count();
        $activeViolationsCount = SafetyIncident::whereIn('status', ['investigating', 'resolved'])->count();

        // 4. Tab & Filter Item Risk Matrix
        $itemQuery = Item::with(['category', 'location']);
        if ($request->filled('risk_level') && in_array($request->risk_level, ['low', 'medium', 'high'])) {
            $itemQuery->where('safety_risk_level', $request->risk_level);
        }
        if ($request->filled('item_search')) {
            $search = $request->item_search;
            $itemQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }
        $items = $itemQuery->orderByRaw("FIELD(safety_risk_level, 'high', 'medium', 'low')")
            ->orderBy('nama', 'asc')
            ->paginate(10, ['*'], 'items_page')
            ->withQueryString();

        // 5. Antrean Verifikasi Fisik APD & Log Clearance
        $clearanceQuery = BorrowingRequest::with(['user', 'item', 'safetyVerifier'])
            ->whereHas('item', function ($q) {
                $q->whereIn('safety_risk_level', ['medium', 'high']);
            });

        if ($request->filled('clearance_status')) {
            if ($request->clearance_status === 'pending_verify') {
                $clearanceQuery->whereNull('safety_verified_at');
            } elseif ($request->clearance_status === 'verified') {
                $clearanceQuery->whereNotNull('safety_verified_at');
            }
        }

        $clearanceRequests = $clearanceQuery->latest()
            ->paginate(8, ['*'], 'clearance_page')
            ->withQueryString();

        // 6. Log Insiden & Pelanggaran K3
        $incidents = SafetyIncident::with(['user', 'item', 'reportedBy'])
            ->latest('incident_date')
            ->paginate(8, ['*'], 'incidents_page')
            ->withQueryString();

        // Master Data untuk Modal
        $apdCatalog = Item::getApdCatalog();
        $students = User::where('role', 'user')->orderBy('name')->get();
        $allToolItems = Item::orderBy('nama')->get();

        return view('admin.contents.safety.index', compact(
            'zeroAccidentDays',
            'complianceRate',
            'totalRiskBorrowings',
            'verifiedRiskBorrowings',
            'highRiskCount',
            'mediumRiskCount',
            'lowRiskCount',
            'totalIncidentsCount',
            'activeViolationsCount',
            'items',
            'clearanceRequests',
            'incidents',
            'apdCatalog',
            'students',
            'allToolItems'
        ));
    }

    /**
     * Perbarui Konfigurasi K3 pada Spesifik Item (Risk Level, Required APD, SOP)
     */
    public function updateItemSafety(Request $request, Item $item)
    {
        $validated = $request->validate([
            'safety_risk_level'   => 'required|in:low,medium,high',
            'required_apd'        => 'nullable|array',
            'required_apd.*'      => 'string',
            'safety_instruction'  => 'nullable|string|max:2000',
            'k3_quiz_required'    => 'nullable|boolean',
        ]);

        $item->update([
            'safety_risk_level'  => $validated['safety_risk_level'],
            'required_apd'       => $validated['required_apd'] ?? [],
            'safety_instruction' => $validated['safety_instruction'] ?? null,
            'k3_quiz_required'   => $request->boolean('k3_quiz_required'),
        ]);

        AuditLogger::log(
            'k3.item_updated',
            'K3 & Safety',
            "Pengaturan K3 alat '{$item->nama}' diperbarui ke tingkat {$item->safety_risk_level}",
            $item
        );

        return back()->with('success', "Protokol K3 untuk alat '{$item->nama}' berhasil diperbarui!");
    }

    /**
     * Verifikasi Fisik APD oleh Toolman / Petugas Lab di Loket
     */
    public function verifyPhysicalApd(Request $request, BorrowingRequest $borrowingRequest)
    {
        if ($borrowingRequest->isSafetyVerified()) {
            return back()->with('info', 'Peminjaman ini sudah diverifikasi fisik sebelumnya.');
        }

        $borrowingRequest->update([
            'safety_verified_by' => Auth::id(),
            'safety_verified_at' => now(),
        ]);

        AuditLogger::log(
            'k3.apd_verified',
            'K3 & Safety',
            "Verifikasi fisik APD disahkan oleh " . Auth::user()->name . " untuk peminjaman #{$borrowingRequest->id} ({$borrowingRequest->user->name})",
            $borrowingRequest
        );

        return back()->with('success', "Pemeriksaan Fisik APD Berhasil Divalidasi! Alat siap diserahterimakan.");
    }

    /**
     * Catat Insiden Baru / Pelanggaran SOP K3
     */
    public function storeIncident(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required|exists:users,id',
            'item_id'              => 'nullable|exists:items,id',
            'borrowing_request_id' => 'nullable|exists:borrowing_requests,id',
            'incident_type'        => 'required|in:minor_injury,near_miss,apd_violation,sop_violation,tool_misuse',
            'incident_date'        => 'required|date',
            'location'             => 'nullable|string|max:255',
            'description'          => 'required|string',
            'action_taken'         => 'nullable|string',
            'penalty_days'         => 'nullable|integer|min:0',
        ]);

        $incident = SafetyIncident::create([
            'user_id'              => $validated['user_id'],
            'item_id'              => $validated['item_id'] ?? null,
            'borrowing_request_id' => $validated['borrowing_request_id'] ?? null,
            'incident_type'        => $validated['incident_type'],
            'incident_date'        => $validated['incident_date'],
            'location'             => $validated['location'] ?? 'Laboratorium Teknik Artilia',
            'description'          => $validated['description'],
            'action_taken'         => $validated['action_taken'] ?? 'Diberikan pembinaan dan pengarahan SOP K3.',
            'penalty_days'         => $validated['penalty_days'] ?? 0,
            'reported_by'          => Auth::id(),
            'status'               => 'resolved',
        ]);

        AuditLogger::log(
            'k3.incident_logged',
            'K3 & Safety',
            "Pencatatan insiden K3 ({$incident->incident_type}) atas praktikan: {$incident->user->name}",
            $incident
        );

        return back()->with('success', 'Laporan insiden / ketidaksesuaian K3 berhasil didokumentasikan.');
    }

    /**
     * Ubah status penanganan insiden K3
     */
    public function updateIncidentStatus(Request $request, SafetyIncident $incident)
    {
        $validated = $request->validate([
            'status'       => 'required|in:investigating,resolved,closed',
            'action_taken' => 'nullable|string',
        ]);

        $incident->update([
            'status'       => $validated['status'],
            'action_taken' => $validated['action_taken'] ?? $incident->action_taken,
        ]);

        return back()->with('success', 'Status penanganan insiden berhasil diperbarui.');
    }

    /**
     * Ekspor Laporan Rekapitulasi & Audit Otomatis K3 (ISO 45001 & Permenaker) ke PDF
     */
    public function exportAuditPdf(Request $request)
    {
        // 1. Metrik Zero Accident
        $lastAccident = SafetyIncident::whereIn('incident_type', ['minor_injury', 'tool_misuse'])
            ->latest('incident_date')
            ->first();

        $zeroAccidentDays = $lastAccident 
            ? Carbon::parse($lastAccident->incident_date)->diffInDays(now()) 
            : 184;

        // 2. Kepatuhan APD Digital (Compliance Rate)
        $riskBorrowingQuery = BorrowingRequest::whereHas('item', function ($q) {
            $q->whereIn('safety_risk_level', ['medium', 'high']);
        });

        $totalRiskBorrowings = (clone $riskBorrowingQuery)->count();
        $verifiedRiskBorrowings = (clone $riskBorrowingQuery)->whereNotNull('safety_verified_at')->count();
        $complianceRate = $totalRiskBorrowings > 0 
            ? round(($verifiedRiskBorrowings / $totalRiskBorrowings) * 100) 
            : 0;

        // 3. Count Ringkasan
        $highRiskCount = Item::where('safety_risk_level', 'high')->count();
        $mediumRiskCount = Item::where('safety_risk_level', 'medium')->count();
        $lowRiskCount = Item::where('safety_risk_level', 'low')->count();
        $totalIncidentsCount = SafetyIncident::count();
        $activeViolationsCount = SafetyIncident::whereIn('status', ['investigating', 'resolved'])->count();

        // 4. Data Matriks Risiko & APD Wajib (HIRADC)
        $riskItems = Item::with(['category', 'location'])
            ->whereIn('safety_risk_level', ['high', 'medium'])
            ->orderByRaw("FIELD(safety_risk_level, 'high', 'medium')")
            ->orderBy('nama', 'asc')
            ->get();

        // 5. Data Buku Register Insiden & Pelanggaran K3
        $incidents = SafetyIncident::with(['user', 'item', 'reportedBy'])
            ->latest('incident_date')
            ->get();

        // 6. Log Verifikasi Clearance APD Terkini
        $recentClearances = BorrowingRequest::with(['user', 'item', 'safetyVerifier'])
            ->whereHas('item', function ($q) {
                $q->whereIn('safety_risk_level', ['medium', 'high']);
            })
            ->whereNotNull('safety_verified_at')
            ->latest('safety_verified_at')
            ->limit(15)
            ->get();

        $apdCatalog = Item::getApdCatalog();

        $company = [
            'name'     => Setting::get('company_name') ?: 'PT. INDONESIA CHEMI-CON',
            'tagline'  => Setting::get('company_tagline') ?: 'Manajemen Keselamatan & Kesehatan Kerja Lab Manufaktur (ISO 45001 / SMK3)',
            'address'  => Setting::get('company_address') ?: 'Jl. Raya Industri Manufaktur & Vokasi Blok C-12, Kawasan Industri',
            'phone'    => Setting::get('company_phone') ?: '(021) 8901234',
            'email'    => Setting::get('company_email') ?: 'hse-audit@artilia.ac.id',
            'logo'     => $this->getLogoBase64(),
        ];

        $docNumber = 'ART/HSE-AUD/' . now()->format('Y/m') . '/' . str_pad((string) rand(10, 999), 3, '0', STR_PAD_LEFT);

        $data = [
            'title'                  => 'Laporan Rekapitulasi & Audit Otomatis K3 (ISO 45001)',
            'docNumber'              => $docNumber,
            'zeroAccidentDays'       => $zeroAccidentDays,
            'complianceRate'         => $complianceRate,
            'totalRiskBorrowings'    => $totalRiskBorrowings,
            'verifiedRiskBorrowings' => $verifiedRiskBorrowings,
            'highRiskCount'          => $highRiskCount,
            'mediumRiskCount'        => $mediumRiskCount,
            'lowRiskCount'           => $lowRiskCount,
            'totalIncidentsCount'    => $totalIncidentsCount,
            'activeViolationsCount'  => $activeViolationsCount,
            'riskItems'              => $riskItems,
            'incidents'              => $incidents,
            'recentClearances'       => $recentClearances,
            'apdCatalog'             => $apdCatalog,
            'company'                => $company,
            'audit_date'             => Carbon::now()->isoFormat('D MMMM Y'),
            'print_time'             => Carbon::now()->format('d/m/Y H:i:s'),
            'printed_by'             => Auth::user()->name ?? 'Administrator K3',
        ];

        $pdf = Pdf::loadView('admin.contents.safety.pdf.audit', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isPhpEnabled', true);

        AuditLogger::log(
            'k3.audit_exported',
            'K3 & Safety',
            "Mengekspor Laporan Rekapitulasi & Audit Otomatis K3 ({$docNumber})"
        );

        $safeDocName = str_replace(['/', '\\'], '-', $docNumber);
        return $pdf->stream("Laporan-Audit-K3-ISO45001-{$safeDocName}.pdf");
    }

    private function getLogoBase64(): string
    {
        $settingLogo = Setting::get('company_logo');
        if ($settingLogo && file_exists(public_path($settingLogo))) {
            $mime = mime_content_type(public_path($settingLogo));
            $data = base64_encode(file_get_contents(public_path($settingLogo)));
            return 'data:' . $mime . ';base64,' . $data;
        }

        $candidates = [
            public_path('logo.png'),
            public_path('artilia.png'),
            public_path('images/logo.png'),
        ];
        foreach ($candidates as $path) {
            if (file_exists($path)) {
                $mime = mime_content_type($path);
                $data = base64_encode(file_get_contents($path));
                return 'data:' . $mime . ';base64,' . $data;
            }
        }
        return '';
    }
}

