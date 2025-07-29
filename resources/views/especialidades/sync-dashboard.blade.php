@extends('adminlte::page')

@section('title', 'Dashboard de Sincronização')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard de Sincronização</h1>
        <div>
            <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Status Geral -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-{{ $stats['status'] === 'ok' ? 'success' : ($stats['status'] === 'warning' ? 'warning' : 'danger') }} alert-dismissible">
                <h5>
                    <i class="icon fas fa-{{ $stats['status'] === 'ok' ? 'check' : ($stats['status'] === 'warning' ? 'exclamation-triangle' : 'times') }}"></i>
                    Status da Sincronização
                </h5>
                @if($stats['status'] === 'ok')
                    <p class="mb-0">✅ Sistema funcionando normalmente</p>
                @elseif($stats['status'] === 'warning')
                    <p class="mb-0">⚠️ Sistema com avisos - verifique os detalhes</p>
                @else
                    <p class="mb-0">❌ Sistema com problemas - ação necessária</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <!-- Total Local -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['local']['total'] }}</h3>
                    <p>Especialidades Locais</p>
                </div>
                <div class="icon">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-{{ $stats['api']['online'] ? 'success' : 'danger' }}">
                <div class="inner">
                    <h3>{{ $stats['api']['online'] ? 'Online' : 'Offline' }}</h3>
                    <p>Status da API</p>
                </div>
                <div class="icon">
                    <i class="fas fa-{{ $stats['api']['online'] ? 'wifi' : 'exclamation-triangle' }}"></i>
                </div>
            </div>
        </div>

        <!-- Atualizadas Hoje -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['local']['atualizadas_hoje'] }}</h3>
                    <p>Atualizadas Hoje</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <!-- Atualizadas Semana -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $stats['local']['atualizadas_semana'] }}</h3>
                    <p>Últimos 7 Dias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes e Ações -->
    <div class="row">
        <!-- Detalhes da API -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-globe"></i>
                        Status da API Externa
                    </h3>
                </div>
                <div class="card-body">
                    @if($stats['api']['online'])
                        <div class="row">
                            <div class="col-6">
                                <strong>Status:</strong> 
                                <span class="badge badge-success">Online</span>
                            </div>
                            <div class="col-6">
                                <strong>Total na API:</strong> {{ $stats['api']['total'] }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <strong>Páginas:</strong> {{ $stats['api']['pages'] }}
                            </div>
                            <div class="col-6">
                                <strong>URL:</strong> 
                                <small class="text-muted">lotus-api.cloud.zielo.com.br</small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <strong>API Offline</strong><br>
                            Erro: {{ $stats['api']['error'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estatísticas Locais -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-server"></i>
                        Estatísticas Locais
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>Total:</strong> {{ $stats['local']['total'] }}
                        </div>
                        <div class="col-6">
                            <strong>Hoje:</strong> {{ $stats['local']['atualizadas_hoje'] }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <strong>Última 7 dias:</strong> {{ $stats['local']['atualizadas_semana'] }}
                        </div>
                        <div class="col-6">
                            <strong>Última atualização:</strong><br>
                            <small class="text-muted">
                                @if($stats['local']['ultima_atualizacao'])
                                    @if($stats['local']['ultima_atualizacao'] instanceof \Carbon\Carbon)
                                        {{ $stats['local']['ultima_atualizacao']->format('d/m/Y H:i:s') }}
                                    @else
                                        {{ $stats['local']['ultima_atualizacao'] }}
                                    @endif
                                @else
                                    Nunca
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão de Sincronização -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-sync-alt"></i>
                        Sincronização Manual
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Clique no botão abaixo para executar uma sincronização manual das especialidades da API externa.
                        Este processo pode levar alguns segundos.
                    </p>
                    
                    <button id="syncButton" class="btn btn-primary btn-lg" onclick="startSync()">
                        <i class="fas fa-sync-alt"></i>
                        Iniciar Sincronização
                    </button>
                    
                    <div id="syncProgress" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 id="syncProgressBar">
                                Sincronizando...
                            </div>
                        </div>
                        <small class="text-muted" id="syncStatus">Iniciando sincronização...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Recentes -->
    @if(!empty($stats['logs']))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i>
                            Logs Recentes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Mensagem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['logs'] as $log)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    @if(is_array($log) && isset($log['timestamp']))
                                                        {{ \Carbon\Carbon::parse($log['timestamp'])->format('d/m/Y H:i:s') }}
                                                    @else
                                                        {{ now()->format('d/m/Y H:i:s') }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <code>
                                                    @if(is_array($log) && isset($log['message']))
                                                        {{ $log['message'] }}
                                                    @else
                                                        {{ $log }}
                                                    @endif
                                                </code>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .small-box {
            margin-bottom: 20px;
        }
        
        .progress {
            height: 25px;
        }
        
        .progress-bar {
            line-height: 25px;
        }
        
        code {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.875em;
        }
    </style>
@stop

@section('js')
    <script>
        function startSync() {
            const button = document.getElementById('syncButton');
            const progress = document.getElementById('syncProgress');
            const progressBar = document.getElementById('syncProgressBar');
            const status = document.getElementById('syncStatus');
            
            // Desabilitar botão
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sincronizando...';
            
            // Mostrar progresso
            progress.style.display = 'block';
            
            // Simular progresso
            let progressValue = 0;
            const progressInterval = setInterval(() => {
                progressValue += Math.random() * 15;
                if (progressValue > 90) progressValue = 90;
                progressBar.style.width = progressValue + '%';
                progressBar.textContent = 'Sincronizando... ' + Math.round(progressValue) + '%';
            }, 500);
            
            // Fazer requisição AJAX
            fetch('{{ route("especialidades.sync-ajax") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                
                if (data.success) {
                    progressBar.style.width = '100%';
                    progressBar.textContent = 'Concluído!';
                    status.textContent = data.message + ' (Duração: ' + data.duration + 's)';
                    
                    // Recarregar página após 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    progressBar.style.width = '100%';
                    progressBar.className = 'progress-bar bg-danger';
                    progressBar.textContent = 'Erro!';
                    status.textContent = data.message;
                    
                    // Reabilitar botão
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-sync-alt"></i> Tentar Novamente';
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressBar.className = 'progress-bar bg-danger';
                progressBar.textContent = 'Erro!';
                status.textContent = 'Erro de conexão: ' + error.message;
                
                // Reabilitar botão
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-sync-alt"></i> Tentar Novamente';
            });
        }
    </script>
@stop