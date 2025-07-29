<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendamento de sincronização de especialidades
Schedule::command('especialidades:schedule-sync --silent')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(function () {
        \Log::error('Falha na sincronização automática de especialidades');
    });

// Agendamento de sincronização de cidades
Schedule::command('cidades:schedule-sync --silent')
    ->daily()
    ->at('02:30')
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(function () {
        \Log::error('Falha na sincronização automática de cidades');
    });

// Agendamento de sincronização de especialistas
Schedule::command('especialistas:schedule-sync --silent')
    ->daily()
    ->at('03:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(function () {
        \Log::error('Falha na sincronização automática de especialistas');
    });
