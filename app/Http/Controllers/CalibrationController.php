<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    public function index(Request $request)
    {
        // Otomatis sinkronkan status kalibrasi untuk semua measuring_tool
        $measuringItems = Item::where('tool_type', 'measuring_tool')->get();
        foreach ($measuringItems as $mItem) {
            $mItem->syncCalibrationStatus();
            if ($mItem->isDirty('calibration_status')) {
                $mItem->save();
            }
        }

        $query = Item::with(['category', 'location']);

        // Filter tipe
        $filterType = $request->get('type', 'all');
        if ($filterType === 'measuring') {
            $query->where('tool_type', 'measuring_tool');
        } elseif ($filterType === 'cutting') {
            $query->where('tool_type', 'cutting_tool');
        } elseif ($filterType === 'dies_mold') {
            $query->where('tool_type', 'dies_mold');
        } elseif ($filterType === 'jig_fixture') {
            $query->where('tool_type', 'jig_fixture');
        } else {
            // Default tampilkan semua perkakas presisi & manufaktur, atau jika belum ada tampilkan semua
            $hasTools = Item::where('tool_type', '!=', 'general')->exists();
            if ($hasTools) {
                $query->where('tool_type', '!=', 'general');
            }
        }

        // Filter status kalibrasi
        if ($request->filled('status')) {
            $query->where('calibration_status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kode', 'like', "%{$s}%")
                  ->orWhere('calibration_certificate_number', 'like', "%{$s}%");
            });
        }

        $items = $query->orderBy('calibration_status', 'asc')
                       ->orderBy('nama', 'asc')
                       ->paginate(15)
                       ->withQueryString();

        // Statistik KPI
        $totalTools = Item::where('tool_type', '!=', 'general')->count();
        $expiredCount = Item::where('tool_type', 'measuring_tool')->where('calibration_status', 'expired')->count();
        $dueSoonCount = Item::where('tool_type', 'measuring_tool')->where('calibration_status', 'due_soon')->count();
        $calibratedCount = Item::where('tool_type', 'measuring_tool')->where('calibration_status', 'calibrated')->count();
        
        $criticalCuttingTools = Item::where('tool_type', 'cutting_tool')
            ->whereNotNull('max_tool_life_hours')
            ->whereRaw('tool_life_hours >= (max_tool_life_hours * 0.8)')
            ->count();

        // Semua item untuk modal konversi tipe jika diperlukan
        $allItems = Item::select('id', 'nama', 'kode', 'tool_type')->orderBy('nama')->get();

        return view('admin.contents.calibration.index', compact(
            'items',
            'totalTools',
            'expiredCount',
            'dueSoonCount',
            'calibratedCount',
            'criticalCuttingTools',
            'allItems'
        ));
    }

    public function updateCalibration(Request $request, Item $item)
    {
        $validated = $request->validate([
            'calibration_due_date'           => 'required|date',
            'calibration_certificate_number' => 'nullable|string|max:100',
            'calibration_notes'              => 'nullable|string|max:500',
        ]);

        $item->calibration_due_date           = $validated['calibration_due_date'];
        $item->calibration_certificate_number = $validated['calibration_certificate_number'];
        $item->calibration_notes              = $validated['calibration_notes'];
        $item->tool_type                      = 'measuring_tool';
        $item->syncCalibrationStatus();
        $item->save();

        return back()->with('success', "Sertifikat kalibrasi untuk '{$item->nama}' berhasil diperbarui! Status: {$item->calibration_status}");
    }

    public function updateToolLife(Request $request, Item $item)
    {
        $validated = $request->validate([
            'additional_hours'    => 'nullable|numeric|min:0',
            'total_hours'         => 'nullable|numeric|min:0',
            'max_tool_life_hours' => 'nullable|numeric|min:1',
        ]);

        if (isset($validated['total_hours'])) {
            $item->tool_life_hours = $validated['total_hours'];
        } elseif (isset($validated['additional_hours'])) {
            $item->tool_life_hours += $validated['additional_hours'];
        }

        if (isset($validated['max_tool_life_hours'])) {
            $item->max_tool_life_hours = $validated['max_tool_life_hours'];
        }

        $item->save();

        return back()->with('success', "Jam pakai mata pahat '{$item->nama}' berhasil diperbarui ({$item->tool_life_hours} jam).");
    }

    public function updateToolType(Request $request, Item $item)
    {
        $validated = $request->validate([
            'tool_type'           => 'required|in:general,cutting_tool,measuring_tool,dies_mold,jig_fixture',
            'max_tool_life_hours' => 'nullable|numeric|min:1',
            'calibration_due_date'=> 'nullable|date',
        ]);

        $item->tool_type = $validated['tool_type'];
        if (isset($validated['max_tool_life_hours'])) {
            $item->max_tool_life_hours = $validated['max_tool_life_hours'];
        }
        if (isset($validated['calibration_due_date'])) {
            $item->calibration_due_date = $validated['calibration_due_date'];
        }

        $item->syncCalibrationStatus();
        $item->save();

        return back()->with('success', "Kategori perkakas untuk '{$item->nama}' berhasil ditetapkan sebagai {$item->tool_type}.");
    }
}
