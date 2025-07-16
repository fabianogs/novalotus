<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\EspecialistaController;
use App\Http\Controllers\NecessidadeController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\UnidadeController;

// Rota inicial - redireciona para login se não autenticado ou para dashboard se autenticado
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rota para servir imagens dos banners
Route::get('/banners/image/{filename}', [BannerController::class, 'serveImage'])->name('banners.image');

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home'); // Alias para compatibilidade com AdminLTE
    
    // Rotas do CRUD de Banners
    Route::resource('banners', BannerController::class);
    Route::post('/banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    
    // Rotas do CRUD de Cidades
    Route::resource('cidades', CidadeController::class);
    Route::get('/cidades/buscar', [CidadeController::class, 'buscar'])->name('cidades.buscar');
    
    // Rotas do CRUD de Especialidades
    Route::resource('especialidades', EspecialidadeController::class);
    Route::get('/especialidades/buscar', [EspecialidadeController::class, 'buscar'])->name('especialidades.buscar');
    
    // Rotas do CRUD de Especialistas
    Route::resource('especialistas', EspecialistaController::class);
    Route::get('/especialistas/buscar', [EspecialistaController::class, 'buscar'])->name('especialistas.buscar');
    
    // Rotas do CRUD de Necessidades
    Route::resource('necessidades', NecessidadeController::class);
    
    // Rotas do CRUD de Parceiros
    Route::resource('parceiros', ParceiroController::class);
    
    // Rotas do CRUD de Planos
    Route::resource('planos', PlanoController::class);
    
    // Rotas do CRUD de SEO
    Route::resource('seos', SeoController::class);
    
    // Rotas do CRUD de Unidades
    Route::resource('unidades', UnidadeController::class);
    
    // Rotas das Configurações (singleton - apenas 1 registro)
    Route::get('/configs', [ConfigController::class, 'edit'])->name('configs.edit');
    Route::put('/configs', [ConfigController::class, 'update'])->name('configs.update');
    Route::get('/configs/show', [ConfigController::class, 'show'])->name('configs.show');
    
    // Rotas do Sobre (singleton - apenas 1 registro)
    Route::get('/sobre', [SobreController::class, 'edit'])->name('sobre.edit');
    Route::put('/sobre', [SobreController::class, 'update'])->name('sobre.update');
    
    Route::get('/necessidades/buscar', [NecessidadeController::class, 'buscar'])->name('necessidades.buscar');
});
