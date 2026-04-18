<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class ProcesarConIA extends Command
{
    protected $signature = 'ia:procesar {cantidad=10}';
    protected $description = 'Traduce descripciones médicas con rotación de API Keys';

    public function handle()
    {
        // 1. Cargamos todas las llaves disponibles en un array
        $apiKeys = array_filter([
            env('GEMINI_API_KEY'),
            env('GEMINI_API_KEY2'),
            env('GEMINI_API_KEY3'),
            env('GEMINI_API_KEY4'),
        ]);

        if (empty($apiKeys)) {
            $this->error('❌ No se encontraron API KEYS en el .env');
            return;
        }

        $registros = DB::table('diccionarios')
            ->where('procesado_ia', false)
            ->limit($this->argument('cantidad'))
            ->get();

        if ($registros->isEmpty()) {
            $this->info('Nada pendiente.');
            return;
        }

        $keyIndex = 0; // Empezamos con la primera llave

        foreach ($registros as $item) {
            $success = false;

            // Intentamos procesar el registro actual. Si falla por cuota, probamos la siguiente llave.
            while (!$success && $keyIndex < count($apiKeys)) {
                $currentKey = $apiKeys[$keyIndex];

                try {
                    $this->info("Usando Key " . ($keyIndex + 1) . " para: {$item->codigo}...");

                    $prompt = "Explica qué es '{$item->descripcion_tecnica}' de forma muy breve y humana para un paciente. Máximo 20 palabras.";
                    $url = env('API_URL_GOOGLE') . $currentKey;

                    $response = Http::withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, [
                            'contents' => [['parts' => [['text' => $prompt]]]]
                        ]);

                    // Si la API responde 429 (Cuota excedida)
                    if ($response->status() === 429) {
                        $this->warn("⚠️ Key " . ($keyIndex + 1) . " agotada. Saltando a la siguiente...");
                        $keyIndex++; // Cambiamos de llave
                        continue; // Reintenta el mismo registro con la nueva llave
                    }

                    if ($response->successful()) {
                        $resultado = $response->json('candidates.0.content.parts.0.text');

                        if ($resultado) {
                            DB::table('diccionarios')->where('id', $item->id)->update([
                                'explicacion_ia' => trim($resultado),
                                'procesado_ia' => true,
                                'updated_at' => now()
                            ]);
                            $this->info("✅ Éxito: {$item->codigo}");
                            $success = true; // Salimos del while para ir al siguiente registro
                        }
                    } else {
                        $this->error("❌ Error grave: " . $response->status());
                        break; // Error distinto a cuota, mejor parar este registro
                    }
                } catch (\Exception $e) {
                    $this->error("🚨 Error inesperado: " . $e->getMessage());
                    break;
                }
            }

            if ($keyIndex >= count($apiKeys)) {
                $this->error("🛑 Todas las API Keys han agotado su cuota por ahora.");
                return;
            }

            sleep(4); // Mantenemos el respiro para no saturar
        }
    }
}
