<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'icon' => 'nullable|string|max:32',
            'sort_order' => 'nullable|integer|min:0|max:32767',
        ]);

        $data['slug'] = $request->filled('slug')
            ? Str::slug($data['slug'])
            : $this->uniqueSlugFromName($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
            'icon' => 'nullable|string|max:32',
            'sort_order' => 'nullable|integer|min:0|max:32767',
        ]);

        $data['slug'] = $request->filled('slug')
            ? Str::slug($data['slug'])
            : $this->uniqueSlugFromName($data['name'], $category->id);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus. Produk terkait kini tanpa kategori.');
    }

    private function uniqueSlugFromName(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori-'.Str::lower(Str::random(4));
        $slug = $base;
        $n = 0;
        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $n++;
            $slug = $base.'-'.$n;
        }

        return $slug;
    }
}
