<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Umkm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['umkm', 'category'])->where('stock', '>', 0);
        $this->applyCatalogFilters($query, $request);

        $products = $query->get();
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();
        $umkms = Umkm::query()->orderBy('name')->get();

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
            'umkms' => $umkms,
            'filters' => $request->only(['q', 'category_id', 'min_price', 'max_price', 'umkm_id', 'sort']),
        ]);
    }

    public function byUmkm(Request $request, Umkm $umkm)
    {
        $query = Product::query()->with(['umkm', 'category'])
            ->where('umkm_id', $umkm->id)
            ->where('stock', '>', 0);

        $this->applyCatalogFilters($query, $request, fixedUmkmId: $umkm->id);

        $products = $query->get();
        $categories = Category::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('catalog.umkm', [
            'umkm' => $umkm,
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['q', 'category_id', 'min_price', 'max_price', 'sort']),
        ]);
    }

    private function applyCatalogFilters(Builder $query, Request $request, ?int $fixedUmkmId = null): void
    {
        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function (Builder $qq) use ($q) {
                $qq->where('name', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%')
                    ->orWhereHas('umkm', fn (Builder $u) => $u->where('name', 'like', '%'.$q.'%'));
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->category_id);
        }

        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', (int) $request->min_price);
        }

        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        if ($fixedUmkmId === null && $request->filled('umkm_id')) {
            $query->where('umkm_id', (int) $request->umkm_id);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            'name' => $query->orderBy('name'),
            default => $query->latest('id'),
        };
    }
}
