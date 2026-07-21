<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->filteredQuery($request)->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'category' => null,
            'brands' => Brand::active()->get(),
            'total' => $products->total(),
        ]);
    }

    public function category(Category $category, Request $request)
    {
        abort_unless($category->is_active, 404);

        $ids = $category->children()->pluck('id')->push($category->id);

        $query = $this->filteredQuery($request)->whereIn('category_id', $ids);
        $products = $query->paginate(12)->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'category' => $category,
            'brands' => Brand::active()->get(),
            'total' => $products->total(),
        ]);
    }

    /** Redirección 301 desde las URLs de categoría de WooCommerce. */
    public function legacyCategory(string $prefix, string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        return redirect($category ? route('categories.show', $category) : route('products.index'), 301);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'brand', 'specs', 'sections', 'images']);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->take(4)
            ->get();

        return view('catalog.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    /**
     * Construye la consulta base aplicando buscador, marca y ordenamiento.
     */
    protected function filteredQuery(Request $request)
    {
        return Product::active()
            ->with(['category', 'brand'])
            ->when($request->filled('q'), function ($q) use ($request) {
                // Cada palabra debe coincidir en ALGÚN campo (nombre, categoría,
                // marca, laboratorio, composición, secciones, etiquetas...).
                $words = preg_split('/\s+/', trim((string) $request->input('q'))) ?: [];

                foreach (array_filter($words) as $word) {
                    $like = '%'.$word.'%';
                    $q->where(function ($sub) use ($like) {
                        $sub->where('name', 'like', $like)
                            ->orWhere('sku', 'like', $like)
                            ->orWhere('short_description', 'like', $like)
                            ->orWhere('presentation', 'like', $like)
                            ->orWhere('laboratory', 'like', $like)
                            ->orWhere('tags', 'like', $like)
                            ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like))
                            ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', $like))
                            ->orWhereHas('specs', fn ($s) => $s->where('active_ingredient', 'like', $like))
                            ->orWhereHas('sections', fn ($s) => $s->where('title', 'like', $like)->orWhere('body', 'like', $like));
                    });
                }
            })
            ->when($request->filled('brand'), fn ($q) => $q->where('brand_id', $request->integer('brand')))
            ->when($request->filled('sort'), function ($q) use ($request) {
                match ($request->get('sort')) {
                    'price_asc' => $q->orderBy('price'),
                    'price_desc' => $q->orderByDesc('price'),
                    'latest' => $q->latest(),
                    default => $q->orderBy('name'),
                };
            }, fn ($q) => $q->orderBy('name'));
    }
}
