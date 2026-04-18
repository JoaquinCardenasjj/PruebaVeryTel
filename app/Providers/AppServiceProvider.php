<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {

            $tools = collect(config('tools_catalog_main'));

            // 1. Agrupamos por categoría
            $grouped = $tools->groupBy('category');

            // 2. Ordenamos las categorías basándonos en el 'orden' del primer elemento de cada grupo
            $sortedMenu = $grouped->sortBy(function ($items) {
                // Tomamos el valor de 'orden' de la primera herramienta del grupo
                return $items->first()['orden'] ?? 999;
            })->map(function ($items) {
                // 3. Opcional: También ordenamos las herramientas dentro de cada categoría
                return collect($items)->sortBy('orden');
            });

            $view->with('menuTools', $sortedMenu);
        });

        //
    }
}
