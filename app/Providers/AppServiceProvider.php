<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
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
        // Fuerza el idioma español en todo el sistema (panel, validaciones, fechas),
        // sin depender de la configuración del .env en producción.
        $this->app->setLocale('es');
        Carbon::setLocale('es');

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
