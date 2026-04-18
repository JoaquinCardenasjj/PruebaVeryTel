<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Cie10Seeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(storage_path('data/cie10.json'));
        $data = json_decode($json);

        foreach ($data as $item) {
            DB::table('diccionarios')->insert([
                'codigo' => $item->code,
                'descripcion_tecnica' => $item->description,
                'categoria' => 'cie10',
                'created_at' => now(),
            ]);
        }

        $this->command->info('¡Códigos cargados exitosamente en producción!');
    }
}
