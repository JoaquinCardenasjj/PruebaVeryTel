<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class ToolLaboralController extends Controller
{
    public function getData(Request $request)
    {
        $pregunta = $request->input('pregunta');
        $apiKeys = array_filter([env('GEMINI_API_KEY'), env('GEMINI_API_KEY2'), env('GEMINI_API_KEY3'), env('GEMINI_API_KEY4')]);

        foreach ($apiKeys as $key) {
            $url = env('API_URL_GOOGLE') . $key;

            try {
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, [
                        'contents' => [
                            ['parts' => [['text' => 'test' . "\nPregunta del usuario: " . $pregunta]]]
                        ]
                    ]);
                if ($response->status() === 429) continue; // Si esta llave falló por cuota, sigue a la otra

                if ($response->successful()) {
                    $texto = $response->json('candidates.0.content.parts.0.text');
                    return response()->json(['respuesta' => $texto]);
                }
            } catch (\Exception $e) {
                continue; // Error de conexión, prueba la siguiente llave
            }
        }

        return response()->json(['respuesta' => 'Lo siento, mis asesores están ocupados. Intenta en un momento.'], 429);
    }
}
