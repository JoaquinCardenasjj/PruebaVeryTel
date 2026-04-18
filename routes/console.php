<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 2. Aquí programas la tarea de la IA (Fuera de cualquier comando)
Schedule::command('ia:procesar 10')->everyTenMinutes();
