<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        // Comparte los ajustes y el menú de categorías con todas las vistas públicas.
        View::composer('*', function ($view) {
            if (Schema::hasTable('settings')) {
                $view->with('settings', setting());
            }

            if (Schema::hasTable('categories')) {
                $view->with('navCategories', Category::query()
                    ->active()
                    ->roots()
                    ->with(['children' => fn ($q) => $q->active()])
                    ->get());
            }
        });
    }
}
