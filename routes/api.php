<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Api\CidadeApiController;
use App\Http\Controllers\Api\EspecialidadeApiController;
use App\Http\Controllers\Api\EspecialistaApiController;
use App\Http\Controllers\Api\NecessidadeApiController;
use App\Http\Controllers\Api\ParceiroApiController;
use App\Http\Controllers\Api\PlanoApiController;
use App\Http\Controllers\SobreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas da API para Banners
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerApiController::class, 'index'])->name('api.banners.index');
    Route::get('/active', [BannerApiController::class, 'active'])->name('api.banners.active');
    Route::get('/{id}', [BannerApiController::class, 'show'])->name('api.banners.show')->where('id', '[0-9]+');
});

// Rotas da API para Cidades
Route::prefix('cidades')->group(function () {
    Route::get('/', [CidadeApiController::class, 'index'])->name('api.cidades.index');
    Route::get('/by-uf', [CidadeApiController::class, 'byUf'])->name('api.cidades.by-uf');
    Route::get('/estados', [CidadeApiController::class, 'estados'])->name('api.cidades.estados');
});

// Rotas da API para Especialidades
Route::prefix('especialidades')->group(function () {
    Route::get('/', [EspecialidadeApiController::class, 'index'])->name('api.especialidades.index');
    Route::get('/{id}', [EspecialidadeApiController::class, 'show'])->name('api.especialidades.show')->where('id', '[0-9]+');
    Route::get('/{id}/especialistas', [EspecialidadeApiController::class, 'especialistas'])->name('api.especialidades.especialistas')->where('id', '[0-9]+');
});

// Rotas da API para Especialistas
Route::prefix('especialistas')->group(function () {
    Route::get('/', [EspecialistaApiController::class, 'index'])->name('api.especialistas.index');
    Route::get('/by-especialidade', [EspecialistaApiController::class, 'byEspecialidade'])->name('api.especialistas.by-especialidade');
    Route::get('/by-cidade', [EspecialistaApiController::class, 'byCidade'])->name('api.especialistas.by-cidade');
    Route::get('/slug/{slug}', [EspecialistaApiController::class, 'findBySlug'])->name('api.especialistas.slug');
    Route::get('/{id}', [EspecialistaApiController::class, 'show'])->name('api.especialistas.show')->where('id', '[0-9]+');
});

// Rotas da API para Necessidades
Route::prefix('necessidades')->group(function () {
    Route::get('/', [NecessidadeApiController::class, 'index'])->name('api.necessidades.index');
    Route::get('/{id}', [NecessidadeApiController::class, 'show'])->name('api.necessidades.show')->where('id', '[0-9]+');
    Route::get('/{id}/especialistas', [NecessidadeApiController::class, 'especialistas'])->name('api.necessidades.especialistas')->where('id', '[0-9]+');
    Route::get('/{id}/parceiros', [NecessidadeApiController::class, 'parceiros'])->name('api.necessidades.parceiros')->where('id', '[0-9]+');
    Route::get('/{id}/profissionais', [NecessidadeApiController::class, 'profissionais'])->name('api.necessidades.profissionais')->where('id', '[0-9]+');
});

// Rotas da API para Parceiros
Route::prefix('parceiros')->group(function () {
    Route::get('/', [ParceiroApiController::class, 'index'])->name('api.parceiros.index');
    Route::get('/by-necessidade', [ParceiroApiController::class, 'byNecessidade'])->name('api.parceiros.by-necessidade');
    Route::get('/by-cidade', [ParceiroApiController::class, 'byCidade'])->name('api.parceiros.by-cidade');
    Route::get('/by-estado', [ParceiroApiController::class, 'byEstado'])->name('api.parceiros.by-estado');
    Route::get('/carrossel', [ParceiroApiController::class, 'carrossel'])->name('api.parceiros.carrossel');
    Route::get('/slug/{slug}', [ParceiroApiController::class, 'findBySlug'])->name('api.parceiros.slug');
    Route::get('/{id}', [ParceiroApiController::class, 'show'])->name('api.parceiros.show')->where('id', '[0-9]+');
});

// Rotas da API para Planos
Route::prefix('planos')->group(function () {
    Route::get('/', [PlanoApiController::class, 'index'])->name('api.planos.index');
    Route::get('/simple', [PlanoApiController::class, 'simple'])->name('api.planos.simple');
    Route::get('/slug/{slug}', [PlanoApiController::class, 'findBySlug'])->name('api.planos.slug');
    Route::get('/{id}', [PlanoApiController::class, 'show'])->name('api.planos.show')->where('id', '[0-9]+');
});

// Rotas da API para Sobre
Route::prefix('sobre')->group(function () {
    Route::get('/', [SobreController::class, 'show'])->name('api.sobre.show');
}); 