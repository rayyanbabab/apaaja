<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $suppliers = Supplier::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->orderBy('company_name')
            ->paginate(10);

        return view('admin.contents.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.contents.suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['company_name'] = $request->nama;

        Supplier::create($data);

        AuditLogger::log('supplier.created', 'Supplier', "Supplier \"{$request->nama}\" ditambahkan");

        return panel_redirect('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        return view('admin.contents.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.contents.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['company_name'] = $request->nama;

        $supplier->update($data);

        AuditLogger::log('supplier.updated', 'Supplier', "Supplier \"{$supplier->company_name}\" diperbarui", $supplier);

        return panel_redirect('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->items()->count() > 0) {
            return panel_redirect('suppliers.index')
                ->with('error', 'Cannot delete supplier. It has associated items.');
        }

        $supplierName = $supplier->company_name;
        $supplier->delete();

        AuditLogger::log('supplier.deleted', 'Supplier', "Supplier \"{$supplierName}\" dihapus");

        return panel_redirect('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
