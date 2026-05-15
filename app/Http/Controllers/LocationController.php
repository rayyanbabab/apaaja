<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $locations = Location::with('parent')
            ->withCount('items')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalLocations   = Location::count();
        $activeLocations  = Location::where('status', 'active')->count();
        $inactiveLocations = Location::where('status', 'inactive')->count();
        $totalItems       = Location::withCount('items')->get()->sum('items_count');

        return view('admin.contents.locations.index', compact(
            'locations', 'search',
            'totalLocations', 'activeLocations', 'inactiveLocations', 'totalItems'
        ));
    }

    public function create()
    {
        $parents = Location::active()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.contents.locations.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'kode'      => 'nullable|string|max:20|unique:locations,kode',
            'deskripsi' => 'nullable|string',
            'parent_id' => 'nullable|exists:locations,id',
            'status'    => 'required|in:active,inactive',
        ]);

        try {
            $location = Location::create($validated);

            AuditLogger::log(
                'location.created',
                'Lokasi',
                "Lokasi \"{$location->name}\" ditambahkan"
            );

            return panel_redirect('locations.index')
                ->with('success', "Lokasi \"{$location->name}\" berhasil ditambahkan.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Location $location)
    {
        $location->load(['parent', 'children.items', 'items.category']);

        $items = $location->items()->with('category')->paginate(10);

        return view('admin.contents.locations.show', compact('location', 'items'));
    }

    public function edit(Location $location)
    {
        // Exclude self and own children to avoid circular parent
        $parents = Location::active()
            ->whereNull('parent_id')
            ->where('id', '!=', $location->id)
            ->orderBy('name')
            ->get();

        return view('admin.contents.locations.edit', compact('location', 'parents'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'kode'      => 'nullable|string|max:20|unique:locations,kode,' . $location->id,
            'deskripsi' => 'nullable|string',
            'parent_id' => 'nullable|exists:locations,id',
            'status'    => 'required|in:active,inactive',
        ]);

        // Prevent assigning self as parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $location->id) {
            return redirect()->back()
                ->with('error', 'Lokasi tidak dapat menjadi induk dari dirinya sendiri.')
                ->withInput();
        }

        $location->update($validated);

        AuditLogger::log(
            'location.updated',
            'Lokasi',
            "Lokasi \"{$location->name}\" diperbarui",
            $location
        );

        return panel_redirect('locations.index')
            ->with('success', "Lokasi \"{$location->name}\" berhasil diperbarui.");
    }

    public function destroy(Location $location)
    {
        if ($location->items()->count() > 0) {
            return panel_redirect('locations.index')
                ->with('error', "Tidak dapat menghapus lokasi \"{$location->name}\" karena masih ada barang yang ditempatkan di sini.");
        }

        if ($location->children()->count() > 0) {
            return panel_redirect('locations.index')
                ->with('error', "Tidak dapat menghapus lokasi \"{$location->name}\" karena masih memiliki sub-lokasi.");
        }

        $name = $location->name;
        $location->delete();

        AuditLogger::log('location.deleted', 'Lokasi', "Lokasi \"{$name}\" dihapus");

        return panel_redirect('locations.index')
            ->with('success', "Lokasi \"{$name}\" berhasil dihapus.");
    }
}
