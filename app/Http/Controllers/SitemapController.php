<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Páginas estáticas
        foreach ([
            ['home', '1.0', 'daily'],
            ['about', '0.7', 'monthly'],
            ['products.index', '0.9', 'daily'],
            ['contact', '0.6', 'monthly'],
            ['blog.index', '0.7', 'weekly'],
            ['privacy', '0.3', 'yearly'],
            ['terms', '0.3', 'yearly'],
        ] as [$route, $priority, $freq]) {
            $urls[] = ['loc' => route($route), 'priority' => $priority, 'changefreq' => $freq];
        }

        // Categorías
        foreach (Category::active()->get() as $category) {
            $urls[] = [
                'loc' => route('categories.show', $category),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $category->updated_at?->toAtomString(),
            ];
        }

        // Productos
        foreach (Product::active()->get() as $product) {
            $urls[] = [
                'loc' => route('products.show', $product),
                'priority' => '0.7',
                'changefreq' => 'weekly',
                'lastmod' => $product->updated_at?->toAtomString(),
            ];
        }

        // Entradas del blog
        foreach (BlogPost::published()->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post),
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => $post->updated_at?->toAtomString(),
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n"
            ."Allow: /\n"
            ."Disallow: /admin\n\n"
            .'Sitemap: '.url('/sitemap.xml')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
