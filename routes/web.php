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
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\TelefoneController;
use App\Http\Controllers\SyncDashboardController;

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
    Route::get('/cidades/sync', [CidadeController::class, 'sync'])->name('cidades.sync');
    Route::get('/cidades/sync/dashboard', [CidadeController::class, 'syncDashboard'])->name('cidades.sync-dashboard');
    Route::post('/cidades/sync/ajax', [CidadeController::class, 'syncAjax'])->name('cidades.sync-ajax');
    
    // Rotas do CRUD de Especialidades (sem criação)
    Route::get('/especialidades', [EspecialidadeController::class, 'index'])->name('especialidades.index');
    Route::get('/especialidades/{especialidade}', [EspecialidadeController::class, 'show'])->name('especialidades.show');
    Route::get('/especialidades/{especialidade}/edit', [EspecialidadeController::class, 'edit'])->name('especialidades.edit');
    Route::put('/especialidades/{especialidade}', [EspecialidadeController::class, 'update'])->name('especialidades.update');
    Route::delete('/especialidades/{especialidade}', [EspecialidadeController::class, 'destroy'])->name('especialidades.destroy');
    Route::get('/especialidades/buscar', [EspecialidadeController::class, 'buscar'])->name('especialidades.buscar');
    Route::get('/especialidades/sync', [EspecialidadeController::class, 'sync'])->name('especialidades.sync');
    Route::get('/especialidades/sync/dashboard', [EspecialidadeController::class, 'syncDashboard'])->name('especialidades.sync-dashboard');
    Route::post('/especialidades/sync/ajax', [EspecialidadeController::class, 'syncAjax'])->name('especialidades.sync-ajax');
    
    // Rotas do CRUD de Especialistas
    Route::resource('especialistas', EspecialistaController::class);
    Route::get('/especialistas/buscar', [EspecialistaController::class, 'buscar'])->name('especialistas.buscar');
    
    // Rotas para Endereços de Especialistas
    Route::get('/especialistas/{especialista}/enderecos', [EnderecoController::class, 'index'])->name('enderecos.index');
    Route::get('/especialistas/{especialista}/enderecos/create', [EnderecoController::class, 'create'])->name('enderecos.create');
    Route::post('/especialistas/{especialista}/enderecos', [EnderecoController::class, 'store'])->name('enderecos.store');
    Route::get('/especialistas/{especialista}/enderecos/{endereco}/edit', [EnderecoController::class, 'edit'])->name('enderecos.edit');
    Route::put('/especialistas/{especialista}/enderecos/{endereco}', [EnderecoController::class, 'update'])->name('enderecos.update');
    Route::delete('/especialistas/{especialista}/enderecos/{endereco}', [EnderecoController::class, 'destroy'])->name('enderecos.destroy');
    
    // Rotas para Telefones de Especialistas
    Route::get('/especialistas/{especialista}/telefones', [TelefoneController::class, 'index'])->name('telefones.index');
    Route::get('/especialistas/{especialista}/telefones/create', [TelefoneController::class, 'create'])->name('telefones.create');
    Route::post('/especialistas/{especialista}/telefones', [TelefoneController::class, 'store'])->name('telefones.store');
    Route::get('/especialistas/{especialista}/telefones/{telefone}/edit', [TelefoneController::class, 'edit'])->name('telefones.edit');
    Route::put('/especialistas/{especialista}/telefones/{telefone}', [TelefoneController::class, 'update'])->name('telefones.update');
    Route::delete('/especialistas/{especialista}/telefones/{telefone}', [TelefoneController::class, 'destroy'])->name('telefones.destroy');
    
    // Rotas do Dashboard Centralizado de Sincronizações
    Route::get('/sync-dashboard', [SyncDashboardController::class, 'index'])->name('sync-dashboard.index');
    Route::post('/sync-dashboard/sync', [SyncDashboardController::class, 'syncAjax'])->name('sync-dashboard.sync-ajax');
    Route::get('/sync-dashboard/status', [SyncDashboardController::class, 'statusAjax'])->name('sync-dashboard.status-ajax');
    
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
    
    // Rotas do CRUD de Usuários
    Route::resource('users', UserController::class);
    
    // Rotas das Configurações (singleton - apenas 1 registro)
    Route::get('/configs', [ConfigController::class, 'edit'])->name('configs.edit');
    Route::put('/configs', [ConfigController::class, 'update'])->name('configs.update');
    Route::get('/configs/show', [ConfigController::class, 'show'])->name('configs.show');
    
    // Rotas do Sobre (singleton - apenas 1 registro)
    Route::get('/sobre', [SobreController::class, 'edit'])->name('sobre.edit');
    Route::put('/sobre', [SobreController::class, 'update'])->name('sobre.update');
    
    // Rotas dos Logs de Atividade
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    
    Route::get('/necessidades/buscar', [NecessidadeController::class, 'buscar'])->name('necessidades.buscar');
});
