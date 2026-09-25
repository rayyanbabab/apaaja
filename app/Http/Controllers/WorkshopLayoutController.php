<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\WorkshopNode;
use App\Models\WorkshopZone;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkshopLayoutController extends Controller
{
    /**
     * Tampilan Utama Denah Interaktif Bengkel (Admin & Staff)
     */
    public function index(Request $request)
    {
        $zones = WorkshopZone::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nodes = WorkshopNode::with('location')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Siapkan node dengan data telemetri yang sudah di-cache dalam koleksi
        $nodesWithTelemetry = $nodes->map(function ($node) {
            $telemetry = $node->live_telemetry;
            return [
                'id'             => $node->id,
                'name'           => $node->name,
                'code'           => $node->code,
                'type'           => $node->type,
                'zone'           => $node->zone,
                'location_id'    => $node->location_id,
                'location_name'  => $node->location?->name,
                'pos_x'          => (float) $node->pos_x,
                'pos_y'          => (float) $node->pos_y,
                'width'          => (float) $node->width,
                'height'         => (float) $node->height,
                'rotation'       => (int) $node->rotation,
                'icon'           => $node->icon,
                'color_theme'    => $node->color_theme,
                'description'    => $node->description,
                'telemetry'      => $telemetry,
            ];
        });

        // Metrik Ringkasan Live Telemetry
        $summary = [
            'total_nodes'         => $nodes->count(),
            'operational_nodes'   => $nodesWithTelemetry->where('telemetry.status', 'ready')->count(),
            'active_in_use_nodes' => $nodesWithTelemetry->filter(fn($n) => in_array($n['telemetry']['status'], ['in_use', 'partially_in_use']))->count(),
            'warning_nodes'       => $nodesWithTelemetry->where('telemetry.status', 'warning')->count(),
            'critical_nodes'      => $nodesWithTelemetry->where('telemetry.status', 'critical')->count(),
            'maintenance_nodes'   => $nodesWithTelemetry->where('telemetry.status', 'maintenance')->count(),
            'total_tools_managed' => $nodesWithTelemetry->sum('telemetry.total_items'),
            'total_active_loans'  => $nodesWithTelemetry->sum('telemetry.active_loans_count'),
            'total_low_stock_rop' => $nodesWithTelemetry->sum('telemetry.low_stock_count'),
            'total_calib_alerts'  => $nodesWithTelemetry->sum('telemetry.expired_calibration_count') + $nodesWithTelemetry->sum('telemetry.due_calibration_count'),
            'total_damaged_items' => $nodesWithTelemetry->sum('telemetry.damaged_items_count'),
        ];

        $availableLocations = Location::active()->orderBy('name')->get();

        $role = auth()->user()?->role;
        $roleValue = $role instanceof \App\Enums\UsersRole ? $role->value : (string) ($role ?? 'admin');
        $routePrefix = $roleValue === 'operator' ? 'staff' : 'admin';

        return view('admin.contents.workshop.index', [
            'nodesJson'          => $nodesWithTelemetry,
            'zonesJson'          => $zones,
            'summary'            => $summary,
            'availableLocations' => $availableLocations,
            'routePrefix'        => $routePrefix,
        ]);
    }

    /**
     * Tampilan Pengguna (User / Mahasiswa / Siswa)
     */
    public function userIndex(Request $request)
    {
        $zones = WorkshopZone::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nodes = WorkshopNode::with('location')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nodesWithTelemetry = $nodes->map(function ($node) {
            return [
                'id'             => $node->id,
                'name'           => $node->name,
                'code'           => $node->code,
                'type'           => $node->type,
                'zone'           => $node->zone,
                'location_id'    => $node->location_id,
                'location_name'  => $node->location?->name,
                'pos_x'          => (float) $node->pos_x,
                'pos_y'          => (float) $node->pos_y,
                'width'          => (float) $node->width,
                'height'         => (float) $node->height,
                'rotation'       => (int) $node->rotation,
                'icon'           => $node->icon,
                'color_theme'    => $node->color_theme,
                'description'    => $node->description,
                'telemetry'      => $node->live_telemetry,
            ];
        });

        return view('user.contents.workshop.index', [
            'nodesJson' => $nodesWithTelemetry,
            'zonesJson' => $zones,
        ]);
    }

    /**
     * API Telemetri Real-time (JSON Polling)
     */
    public function apiData(): JsonResponse
    {
        $zones = WorkshopZone::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nodes = WorkshopNode::with('location')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $nodesWithTelemetry = $nodes->map(function ($node) {
            return [
                'id'             => $node->id,
                'name'           => $node->name,
                'code'           => $node->code,
                'type'           => $node->type,
                'zone'           => $node->zone,
                'location_id'    => $node->location_id,
                'location_name'  => $node->location?->name,
                'pos_x'          => (float) $node->pos_x,
                'pos_y'          => (float) $node->pos_y,
                'width'          => (float) $node->width,
                'height'         => (float) $node->height,
                'rotation'       => (int) $node->rotation,
                'icon'           => $node->icon,
                'color_theme'    => $node->color_theme,
                'description'    => $node->description,
                'telemetry'      => $node->live_telemetry,
            ];
        });

        $summary = [
            'total_nodes'         => $nodes->count(),
            'operational_nodes'   => $nodesWithTelemetry->where('telemetry.status', 'ready')->count(),
            'active_in_use_nodes' => $nodesWithTelemetry->filter(fn($n) => in_array($n['telemetry']['status'], ['in_use', 'partially_in_use']))->count(),
            'warning_nodes'       => $nodesWithTelemetry->where('telemetry.status', 'warning')->count(),
            'critical_nodes'      => $nodesWithTelemetry->where('telemetry.status', 'critical')->count(),
            'maintenance_nodes'   => $nodesWithTelemetry->where('telemetry.status', 'maintenance')->count(),
            'total_tools_managed' => $nodesWithTelemetry->sum('telemetry.total_items'),
            'total_active_loans'  => $nodesWithTelemetry->sum('telemetry.active_loans_count'),
            'total_low_stock_rop' => $nodesWithTelemetry->sum('telemetry.low_stock_count'),
            'total_calib_alerts'  => $nodesWithTelemetry->sum('telemetry.expired_calibration_count') + $nodesWithTelemetry->sum('telemetry.due_calibration_count'),
            'total_damaged_items' => $nodesWithTelemetry->sum('telemetry.damaged_items_count'),
            'last_synced_at'      => now()->format('H:i:s'),
        ];

        return response()->json([
            'success'   => true,
            'summary'   => $summary,
            'zones'     => $zones,
            'nodes'     => $nodesWithTelemetry,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Tambah Node / Rak Baru
     */
    public function storeNode(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:50|unique:workshop_nodes,code',
            'type'         => 'required|in:machine,rack,cabinet,workbench,safety_kiosk,logistics_bay',
            'zone'         => 'required|string|max:100',
            'location_id'  => 'nullable|exists:locations,id',
            'pos_x'        => 'required|numeric|min:0|max:200',
            'pos_y'        => 'required|numeric|min:0|max:200',
            'width'        => 'required|numeric|min:2|max:150',
            'height'       => 'required|numeric|min:2|max:150',
            'rotation'     => 'nullable|integer|in:0,90,180,270',
            'icon'         => 'required|string|max:50',
            'color_theme'  => 'required|string|max:30',
            'description'  => 'nullable|string',
        ]);

        $node = WorkshopNode::create($validated);

        AuditLogger::log(
            'workshop.node_created',
            'Workshop Digital Twin',
            "Menambahkan elemen bengkel \"{$node->name}\" ({$node->code}) di zona {$node->zone}"
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Stasiun baru berhasil ditambahkan.', 'node' => $node]);
        }

        return panel_redirect('workshop.index')->with('success', "Stasiun \"{$node->name}\" berhasil ditambahkan ke denah.");
    }

    /**
     * Update Detail Node
     */
    public function updateNode(Request $request, WorkshopNode $node)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:50|unique:workshop_nodes,code,' . $node->id,
            'type'         => 'required|in:machine,rack,cabinet,workbench,safety_kiosk,logistics_bay',
            'zone'         => 'required|string|max:100',
            'location_id'  => 'nullable|exists:locations,id',
            'pos_x'        => 'required|numeric|min:0|max:200',
            'pos_y'        => 'required|numeric|min:0|max:200',
            'width'        => 'required|numeric|min:2|max:150',
            'height'       => 'required|numeric|min:2|max:150',
            'rotation'     => 'nullable|integer|in:0,90,180,270',
            'icon'         => 'required|string|max:50',
            'color_theme'  => 'required|string|max:30',
            'description'  => 'nullable|string',
            'status_override' => 'nullable|string',
        ]);

        $node->update($validated);

        AuditLogger::log(
            'workshop.node_updated',
            'Workshop Digital Twin',
            "Memperbarui elemen bengkel \"{$node->name}\" ({$node->code})"
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data stasiun berhasil diperbarui.', 'node' => $node]);
        }

        return panel_redirect('workshop.index')->with('success', "Data stasiun \"{$node->name}\" berhasil diperbarui.");
    }

    /**
     * Update Posisi Masal Hasil Drag & Drop di Kanvas
     */
    public function updatePositions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nodes'           => 'required|array',
            'nodes.*.id'     => 'required|exists:workshop_nodes,id',
            'nodes.*.pos_x'  => 'required|numeric|min:0|max:200',
            'nodes.*.pos_y'  => 'required|numeric|min:0|max:200',
            'nodes.*.width'  => 'nullable|numeric|min:2|max:150',
            'nodes.*.height' => 'nullable|numeric|min:2|max:150',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['nodes'] as $item) {
                $updateData = [
                    'pos_x' => $item['pos_x'],
                    'pos_y' => $item['pos_y'],
                ];
                if (isset($item['width'])) {
                    $updateData['width'] = $item['width'];
                }
                if (isset($item['height'])) {
                    $updateData['height'] = $item['height'];
                }

                WorkshopNode::where('id', $item['id'])->update($updateData);
            }
        });

        AuditLogger::log(
            'workshop.positions_saved',
            'Workshop Digital Twin',
            'Memperbarui koordinat spasial tata letak denah bengkel 2D'
        );

        return response()->json([
            'success' => true,
            'message' => 'Tata letak koordinat denah berhasil disimpan!',
        ]);
    }

    /**
     * Hapus Node dari Denah
     */
    public function destroyNode(WorkshopNode $node)
    {
        $name = $node->name;
        $node->delete();

        AuditLogger::log(
            'workshop.node_deleted',
            'Workshop Digital Twin',
            "Menghapus elemen \"{$name}\" dari denah bengkel"
        );

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Stasiun \"{$name}\" telah dihapus dari denah.",
            ]);
        }

        return panel_redirect('workshop.index')->with('success', "Stasiun \"{$name}\" telah dihapus dari denah.");
    }

    /**
     * Reset Tata Letak ke Blueprint Standar Bengkel Vokasi
     */
    public function resetDefault()
    {
        WorkshopNode::truncate();

        foreach (WorkshopNode::getDefaultBlueprint() as $blueprint) {
            WorkshopNode::create($blueprint);
        }

        AuditLogger::log(
            'workshop.reset_blueprint',
            'Workshop Digital Twin',
            'Me-reset tata letak denah bengkel ke blueprint standar pabrikasi/vokasi'
        );

        return panel_redirect('workshop.index')->with('success', 'Tata letak denah berhasil di-reset ke blueprint standar bengkel vokasi!');
    }

    // ═════════════════════════════════════════════════════════════
    // CRUD ZONA BENGKEL
    // ═════════════════════════════════════════════════════════════

    /**
     * Tambah Zona Baru
     */
    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:workshop_zones,code',
            'color'       => 'required|string|max:30',
            'pos_x'       => 'required|numeric|min:0|max:200',
            'pos_y'       => 'required|numeric|min:0|max:200',
            'width'       => 'required|numeric|min:2|max:200',
            'height'      => 'required|numeric|min:2|max:200',
            'description' => 'nullable|string',
        ]);

        $zone = WorkshopZone::create($validated);

        AuditLogger::log(
            'workshop.zone_created',
            'Workshop Digital Twin',
            "Menambahkan zona baru \"{$zone->name}\" ({$zone->code})"
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Zona baru berhasil dibuat.', 'zone' => $zone]);
        }

        return panel_redirect('workshop.index')->with('success', "Zona \"{$zone->name}\" berhasil dibuat.");
    }

    /**
     * Update Zona
     */
    public function updateZone(Request $request, WorkshopZone $zone)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:workshop_zones,code,' . $zone->id,
            'color'       => 'required|string|max:30',
            'pos_x'       => 'required|numeric|min:0|max:200',
            'pos_y'       => 'required|numeric|min:0|max:200',
            'width'       => 'required|numeric|min:2|max:200',
            'height'      => 'required|numeric|min:2|max:200',
            'description' => 'nullable|string',
        ]);

        $oldCode = $zone->code;
        $zone->update($validated);

        // Jika kode zona berubah, sinkronkan stasiun terkait
        if ($oldCode !== $validated['code']) {
            WorkshopNode::where('zone', $oldCode)->update(['zone' => $validated['code']]);
        }

        AuditLogger::log(
            'workshop.zone_updated',
            'Workshop Digital Twin',
            "Memperbarui zona \"{$zone->name}\" ({$zone->code})"
        );

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data zona berhasil diperbarui.', 'zone' => $zone]);
        }

        return panel_redirect('workshop.index')->with('success', "Data zona \"{$zone->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus Zona
     */
    public function destroyZone(WorkshopZone $zone)
    {
        $name = $zone->name;
        $code = $zone->code;

        // Cek apakah ada stasiun di zona ini
        $nodesCount = WorkshopNode::where('zone', $code)->count();
        if ($nodesCount > 0) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Zona \"{$name}\" tidak dapat dihapus karena masih menaungi {$nodesCount} stasiun.",
                ], 422);
            }
            return panel_redirect('workshop.index')->with('error', "Zona \"{$name}\" tidak dapat dihapus karena masih menaungi {$nodesCount} stasiun. Pindahkan atau hapus stasiun tersebut terlebih dahulu.");
        }

        $zone->delete();

        AuditLogger::log(
            'workshop.zone_deleted',
            'Workshop Digital Twin',
            "Menghapus zona \"{$name}\" dari denah"
        );

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Zona \"{$name}\" berhasil dihapus.",
            ]);
        }

        return panel_redirect('workshop.index')->with('success', "Zona \"{$name}\" berhasil dihapus.");
    }

    /**
     * Update Posisi Masal Zona
     */
    public function updateZonePositions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zones'          => 'required|array',
            'zones.*.id'     => 'required|exists:workshop_zones,id',
            'zones.*.pos_x'  => 'required|numeric|min:0|max:200',
            'zones.*.pos_y'  => 'required|numeric|min:0|max:200',
            'zones.*.width'  => 'nullable|numeric|min:2|max:200',
            'zones.*.height' => 'nullable|numeric|min:2|max:200',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['zones'] as $item) {
                $updateData = [
                    'pos_x' => $item['pos_x'],
                    'pos_y' => $item['pos_y'],
                ];
                if (isset($item['width'])) {
                    $updateData['width'] = $item['width'];
                }
                if (isset($item['height'])) {
                    $updateData['height'] = $item['height'];
                }

                WorkshopZone::where('id', $item['id'])->update($updateData);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Tata letak zona berhasil disimpan!',
        ]);
    }
}
