@extends('adminlte::page')

@section('title', 'Dashboard de Sincronizações')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-sync-alt"></i> Dashboard de Sincronizações
        </h1>
        <div>
            <button class="btn btn-info" onclick="refreshStatus()">
                <i class="fas fa-refresh"></i> Atualizar Status
            </button>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Especialidades -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-stethoscope"></i> Especialidades
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-stethoscope"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Local</span>
                        <span class="info-box-number">{{ $stats['especialidades']['total'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizadas Hoje</span>
                        <span class="info-box-number">{{ $stats['especialidades']['atualizadas_hoje'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizadas na Semana</span>
                        <span class="info-box-number">{{ $stats['especialidades']['atualizadas_semana'] }}</span>
                    </div>
                </div>
                
                @if($stats['especialidades']['ultima_atualizacao'])
                    <p class="mb-2">
                        <strong>Última atualização:</strong><br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($stats['especialidades']['ultima_atualizacao'])->format('d/m/Y H:i:s') }}
                        </small>
                    </p>
                @endif
                
                <!-- Status da API -->
                <div class="mt-3">
                    <h6><strong>Status da API:</strong></h6>
                    @if($apiStatus['especialidades']['status'] === 'online')
                        <span class="badge badge-success">
                            <i class="fas fa-circle"></i> Online
                        </span>
                        <small class="text-muted d-block">
                            Total na API: {{ $apiStatus['especialidades']['total'] }} | 
                            Páginas: {{ $apiStatus['especialidades']['pages'] }}
                        </small>
                    @elseif($apiStatus['especialidades']['status'] === 'error')
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-triangle"></i> Erro
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['especialidades']['message'] }}</small>
                    @else
                        <span class="badge badge-danger">
                            <i class="fas fa-times-circle"></i> Offline
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['especialidades']['message'] }}</small>
                    @endif
                </div>
                
                <!-- Botão de Sincronização -->
                <div class="mt-3">
                    <button class="btn btn-primary btn-block" onclick="syncEntity('especialidades')">
                        <i class="fas fa-sync-alt"></i> Sincronizar Especialidades
                    </button>
                    <div class="progress mt-2" id="progress-especialidades" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cidades -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt"></i> Cidades
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Local</span>
                        <span class="info-box-number">{{ $stats['cidades']['total'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizadas Hoje</span>
                        <span class="info-box-number">{{ $stats['cidades']['atualizadas_hoje'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizadas na Semana</span>
                        <span class="info-box-number">{{ $stats['cidades']['atualizadas_semana'] }}</span>
                    </div>
                </div>
                
                @if($stats['cidades']['ultima_atualizacao'])
                    <p class="mb-2">
                        <strong>Última atualização:</strong><br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($stats['cidades']['ultima_atualizacao'])->format('d/m/Y H:i:s') }}
                        </small>
                    </p>
                @endif
                
                <!-- Status da API -->
                <div class="mt-3">
                    <h6><strong>Status da API:</strong></h6>
                    @if($apiStatus['cidades']['status'] === 'online')
                        <span class="badge badge-success">
                            <i class="fas fa-circle"></i> Online
                        </span>
                        <small class="text-muted d-block">
                            Total na API: {{ $apiStatus['cidades']['total'] }} | 
                            Páginas: {{ $apiStatus['cidades']['pages'] }}
                        </small>
                    @elseif($apiStatus['cidades']['status'] === 'error')
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-triangle"></i> Erro
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['cidades']['message'] }}</small>
                    @else
                        <span class="badge badge-danger">
                            <i class="fas fa-times-circle"></i> Offline
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['cidades']['message'] }}</small>
                    @endif
                </div>
                
                <!-- Botão de Sincronização -->
                <div class="mt-3">
                    <button class="btn btn-primary btn-block" onclick="syncEntity('cidades')">
                        <i class="fas fa-sync-alt"></i> Sincronizar Cidades
                    </button>
                    <div class="progress mt-2" id="progress-cidades" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Especialistas -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-md"></i> Especialistas
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-user-md"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Local</span>
                        <span class="info-box-number">{{ $stats['especialistas']['total'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizados Hoje</span>
                        <span class="info-box-number">{{ $stats['especialistas']['atualizados_hoje'] }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atualizados na Semana</span>
                        <span class="info-box-number">{{ $stats['especialistas']['atualizados_semana'] }}</span>
                    </div>
                </div>
                
                @if($stats['especialistas']['ultima_atualizacao'])
                    <p class="mb-2">
                        <strong>Última atualização:</strong><br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($stats['especialistas']['ultima_atualizacao'])->format('d/m/Y H:i:s') }}
                        </small>
                    </p>
                @endif
                
                <!-- Status da API -->
                <div class="mt-3">
                    <h6><strong>Status da API:</strong></h6>
                    @if($apiStatus['especialistas']['status'] === 'online')
                        <span class="badge badge-success">
                            <i class="fas fa-circle"></i> Online
                        </span>
                        <small class="text-muted d-block">
                            Total na API: {{ $apiStatus['especialistas']['total'] }} | 
                            Páginas: {{ $apiStatus['especialistas']['pages'] }}
                        </small>
                    @elseif($apiStatus['especialistas']['status'] === 'error')
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-triangle"></i> Erro
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['especialistas']['message'] }}</small>
                    @else
                        <span class="badge badge-danger">
                            <i class="fas fa-times-circle"></i> Offline
                        </span>
                        <small class="text-muted d-block">{{ $apiStatus['especialistas']['message'] }}</small>
                    @endif
                </div>
                
                <!-- Botão de Sincronização -->
                <div class="mt-3">
                    <button class="btn btn-primary btn-block" onclick="syncEntity('especialistas')">
                        <i class="fas fa-sync-alt"></i> Sincronizar Especialistas
                    </button>
                    <div class="progress mt-2" id="progress-especialistas" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logs Recentes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Logs Recentes de Sincronização
                </h3>
            </div>
            <div class="card-body">
                @if(count($recentLogs) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Entidade</th>
                                    <th>Status</th>
                                    <th>Duração</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log['timestamp'] }}</td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst($log['entity']) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log['status'] === 'success')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Sucesso
                                                </span>
                                            @elseif($log['status'] === 'error')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times"></i> Erro
                                                </span>
                                            @elseif($log['status'] === 'partial')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> Parcial
                                                </span>
                                            @elseif($log['status'] === 'running')
                                                <span class="badge badge-info">
                                                    <i class="fas fa-spinner fa-spin"></i> Executando
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $log['duration'] }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log['message'] }}
                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Nenhum log de sincronização encontrado.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.info-box {
    margin-bottom: 15px;
}
.progress {
    height: 20px;
}
</style>
@stop

@section('js')
<script>
// Função para sincronizar uma entidade
function syncEntity(entity) {
    const button = event.target;
    const progressBar = document.getElementById(`progress-${entity}`);
    const progressBarInner = progressBar.querySelector('.progress-bar');
    
    // Desabilitar botão e mostrar progresso
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sincronizando...';
    progressBar.style.display = 'block';
    
    // Animar progresso
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBarInner.style.width = progress + '%';
    }, 200);
    
    // Fazer requisição AJAX
    fetch('{{ route("sync-dashboard.sync-ajax") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ entity: entity })
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        progressBarInner.style.width = '100%';
        
        setTimeout(() => {
            // Reabilitar botão
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-sync-alt"></i> Sincronizar ' + entity.charAt(0).toUpperCase() + entity.slice(1);
            progressBar.style.display = 'none';
            progressBarInner.style.width = '0%';
            
            // Mostrar resultado
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
                
                // Atualizar status após 2 segundos
                setTimeout(() => {
                    refreshStatus();
                }, 2000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: data.message
                });
            }
        }, 1000);
    })
    .catch(error => {
        clearInterval(progressInterval);
        progressBarInner.style.width = '100%';
        
        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-sync-alt"></i> Sincronizar ' + entity.charAt(0).toUpperCase() + entity.slice(1);
            progressBar.style.display = 'none';
            progressBarInner.style.width = '0%';
            
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro de conexão. Tente novamente.'
            });
        }, 1000);
    });
}

// Função para atualizar status
function refreshStatus() {
    fetch('{{ route("sync-dashboard.status-ajax") }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar status:', error);
    });
}

// Auto-atualizar a cada 30 segundos
setInterval(refreshStatus, 30000);
</script>
@stop