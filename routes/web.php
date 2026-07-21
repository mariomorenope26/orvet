<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Nota: /install e /instalar se resuelven a nivel Apache (public/.htaccess)
// sirviendo install.php, para que funcionen aunque la app no esté configurada.

// Copias de seguridad (solo administradores autenticados)
Route::middleware('auth')->prefix('admin/backups')->name('admin.backups.')->group(function () {
    Route::get('download/{file}', [\App\Http\Controllers\BackupController::class, 'download'])
        ->where('file', '[A-Za-z0-9._-]+')->name('download');
    Route::post('restore/{file}', [\App\Http\Controllers\BackupController::class, 'restore'])
        ->where('file', '[A-Za-z0-9._-]+')->name('restore');
    Route::post('delete/{file}', [\App\Http\Controllers\BackupController::class, 'delete'])
        ->where('file', '[A-Za-z0-9._-]+')->name('delete');
    Route::post('upload', [\App\Http\Controllers\BackupController::class, 'upload'])->name('upload');
});

Route::get('/nosotros', [PageController::class, 'about'])->name('about');

Route::get('/contacto', [PageController::class, 'contact'])->name('contact');
Route::post('/contacto', [PageController::class, 'sendContact'])->name('contact.send');

Route::get('/politica-de-privacidad', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terminos-de-servicio', [PageController::class, 'terms'])->name('terms');

// Catálogo
Route::get('/productos', [CatalogController::class, 'index'])->name('products.index');
Route::get('/producto/{product:slug}', [CatalogController::class, 'show'])->name('products.show');
Route::get('/categoria/{category:slug}', [CatalogController::class, 'category'])->name('categories.show');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

/*
|--------------------------------------------------------------------------
| Redirecciones 301 desde las URLs antiguas de WordPress / WooCommerce
|--------------------------------------------------------------------------
| Preserva el SEO de orvet.pe. Las fichas de producto conservan la misma
| base (/producto/{slug}) por lo que no requieren redirección.
*/
Route::permanentRedirect('/tienda', '/productos');
Route::permanentRedirect('/shop', '/productos');
Route::permanentRedirect('/about-us', '/nosotros');
Route::permanentRedirect('/contact-us', '/contacto');
Route::permanentRedirect('/faqs', '/contacto');

// Carrito / cuenta (no aplican en modo cotización) -> catálogo
foreach (['carrito', 'cart', 'finalizar-compra', 'checkout', 'mi-cuenta', 'my-account',
          'dashboard', 'my-orders', 'order-tracking', 'wishlist'] as $old) {
    Route::permanentRedirect("/{$old}", '/productos');
}

// Categorías de WooCommerce: /categoria-producto/{slug} y /product-category/{slug}
Route::get('/{prefix}/{slug}', [CatalogController::class, 'legacyCategory'])
    ->where('prefix', 'categoria-producto|product-category');

// Etiquetas antiguas de producto -> catálogo con búsqueda
Route::permanentRedirect('/product-tag/{slug}', '/productos');
