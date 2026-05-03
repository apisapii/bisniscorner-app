<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
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

        return redirect()->route('dashboard');
    }
}