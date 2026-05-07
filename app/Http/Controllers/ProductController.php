<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function ownedProductOrFail(Product $product): Product
    {
        abort_unless((int) $product->umkm_id === (int) Auth::user()->umkm_id, 403);

        return $product;
    }

    public function index()
    {
        // Hanya ambil produk milik UMKM si admin yang login
        $products = Product::with('category')
            ->where('umkm_id', Auth::user()->umkm_id)
            ->orderByDesc('id')
            ->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        if ($user->umkm_id === null) {
            return redirect()->back()->withInput()->withErrors([
                'umkm' => 'Akun ini belum terhubung ke profil UMKM.',
            ]);
        }

        $data = $request->only(['name', 'price', 'stock', 'description', 'category_id']);
        $data['umkm_id'] = $user->umkm_id;

        if ($request->hasFile('image')) {
            // Simpan foto ke folder storage/app/public/products
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product = $this->ownedProductOrFail($product);
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product = $this->ownedProductOrFail($product);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'price', 'stock', 'description', 'category_id']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product = $this->ownedProductOrFail($product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}