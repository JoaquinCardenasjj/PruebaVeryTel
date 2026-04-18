<?php

use App\Http\Controllers\ToolFinanzaController;
use App\Http\Controllers\ToolLaboralController;
use App\Http\Controllers\ToolVidaDiariaController;
use App\Http\Controllers\ToolEducacionController;
use App\Http\Controllers\ToolDesarrolloController;
use App\Http\Controllers\ToolUniversalController;
use Illuminate\Support\Facades\Route;




Route::get('/camera', function () {
    $categoria_actual = 'camera'; // El nombre exacto en tu config

    // Filtramos el catálogo para obtener solo las de esta categoría
    $herramientas = collect(config('tools_catalog_main'))
        ->where('category', $categoria_actual)
        ->sortBy('orden');
    return view('tools.camera', compact('herramientas'));
});

Route::get('/tickets', function () {
    $categoria_actual = 'tickets'; // El nombre exacto en tu config

    // Filtramos el catálogo para obtener solo las de esta categoría
    $herramientas = collect(config('tools_catalog_main'))
        ->where('category', $categoria_actual)
        ->sortBy('orden');
    return view('tools.tickets', compact('herramientas'));
});


Route::get('/get-tool-form-laboral/{slug}', [ToolLaboralController::class, 'renderForm']);

Route::post('/api/get_data', [ToolLaboralController::class, 'getData']);

Route::get('/', function () {
    return view('pages.home');
});
