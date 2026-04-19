<?php

use App\Http\Controllers\ToolFinanzaController;
use App\Http\Controllers\ToolLaboralController;
use App\Http\Controllers\ToolVidaDiariaController;
use App\Http\Controllers\ToolEducacionController;
use App\Http\Controllers\ToolDesarrolloController;
use App\Http\Controllers\ToolUniversalController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/camera', function () {
    $categoria_actual = 'camera'; // Usamos la categoría de cámaras definida en la configuración

    // Traemos las herramientas que pertenecen a esta categoría
    $herramientas = collect(config('tools_catalog_main'))
        ->where('category', $categoria_actual)
        ->sortBy('orden');
    return view('tools.camera', compact('herramientas'));
});

Route::get('/tickets', function () {
    $categoria_actual = 'tickets'; // Usamos la categoría de tickets definida en la configuración

    // Traemos las herramientas que pertenecen a esta categoría
    $herramientas = collect(config('tools_catalog_main'))
        ->where('category', $categoria_actual)
        ->sortBy('orden');
    return view('tools.tickets', compact('herramientas'));
});

Route::get('/api-proxy/cameras', function (Request $request) {
    // Filtramos los parámetros para NO enviar aquellos que estén vacíos o sean null
    $queryParams = array_filter($request->only(['search', 'estado', 'localidad']), function ($value) {
        return !is_null($value) && $value !== '';
    });
    // Solo enviamos a Django lo que realmente tiene un valor
    $response = Http::get('https://tuayudaio.com/api/v1/cameras/', $queryParams);

    return response()->json($response->json(), $response->status());
});

// Obtener una sola cámara para editar
Route::get('/api-proxy/cameras/{id}', function ($id) {
    return Http::get("https://tuayudaio.com/api/v1/cameras/{$id}/")->json();
});

// Crear nueva
Route::post('/api-proxy/cameras', function (Request $request) {
    $response = Http::post("https://tuayudaio.com/api/v1/cameras/", $request->all());
    return response()->json($response->json(), $response->status());
});

// Actualizar existente
Route::put('/api-proxy/cameras/{id}', function (Request $request, $id) {
    $response = Http::put("https://tuayudaio.com/api/v1/cameras/{$id}/", $request->all());
    return response()->json($response->json(), $response->status());
});

Route::delete('/api-proxy/cameras/{id}', function ($id) {
    // Laravel le pide a Django borrar la cámara
    $response = Http::delete("https://tuayudaio.com/api/v1/cameras/{$id}/");

    return response()->json($response->json(), $response->status());
});

Route::get('/api-proxy/tickets', function (Request $request) {
    return Http::get("https://tuayudaio.com/api/v1/tickets/", $request->all())->json();
});

Route::get('/api-proxy/tickets/{id}', function ($id) {
    return Http::get("https://tuayudaio.com/api/v1/tickets/{$id}/")->json();
});

Route::post('/api-proxy/tickets', function (Request $request) {
    $response = Http::post("https://tuayudaio.com/api/v1/tickets/", $request->all());
    return response()->json($response->json(), $response->status());
});

Route::put('/api-proxy/tickets/{id}', function (Request $request, $id) {
    $response = Http::put("https://tuayudaio.com/api/v1/tickets/{$id}/", $request->all());
    return response()->json($response->json(), $response->status());
});

Route::delete('/api-proxy/tickets/{id}', function ($id) {
    // Laravel le pide a Django borrar el ticket
    $response = Http::delete("https://tuayudaio.com/api/v1/tickets/{$id}/");

    return response()->json($response->json(), $response->status());
});

Route::get('/get-tool-form-laboral/{slug}', [ToolLaboralController::class, 'renderForm']);

Route::post('/api/get_data', [ToolLaboralController::class, 'getData']);

Route::get('/', function () {
    return view('pages.home');
});
