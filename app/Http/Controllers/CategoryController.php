<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $categories = Category::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10);

        return view('admin.contents.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.contents.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Category::create($validated);

            AuditLogger::log('category.created', 'Kategori', "Kategori \"{$validated['name']}\" ditambahkan");

            return panel_redirect('categories.index')
                ->with('success', 'Kategori berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Category $category)
    {
        return view('admin.contents.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.contents.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->only(['name', 'description', 'status']);

        $category->update($data);

        AuditLogger::log('category.updated', 'Kategori', "Kategori \"{$category->name}\" diperbarui", $category);

        return panel_redirect('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->items()->count() > 0) {
            return panel_redirect('categories.index')
                ->with('error', 'Cannot delete category. It has associated items.');
        }

        $categoryName = $category->name;
        $category->delete();

        AuditLogger::log('category.deleted', 'Kategori', "Kategori \"{$categoryName}\" dihapus");

        return panel_redirect('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
