<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diccionarios', function (Blueprint $table) {
            $table->id();
            $table->string('categoria'); // 'cie10', 'puc', etc.
            $table->string('codigo')->index(); // Indexado para velocidad
            $table->text('descripcion_tecnica');
            $table->text('explicacion_ia')->nullable(); // Aquí guardaremos lo que genere la IA
            $table->boolean('procesado_ia')->default(false); // Flag para el bot de IA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diccionarios');
    }
};
