@extends('adminlte::page')

@section('title', 'Logs de Atividade')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-history"></i> Logs de Atividade</h1>
        <div>
            <a href="{{ route('activity-logs.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Filtros -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Pesquisar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Descrição, IP, usuário...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="action">Ação</label>
                        <select class="form-control" id="action" name="action">
                            <option value="">Todas</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="model">Modelo</label>
                        <select class="form-control" id="model" name="model">
                            <option value="">Todos</option>
                            @foreach($models as $model)
                                <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                    {{ $model }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="user_id">Usuário</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">Todos</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Período</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control" name="start_date" 
                                       value="{{ request('start_date') }}" placeholder="Data inicial">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" name="end_date" 
                                       value="{{ request('end_date') }}" placeholder="Data final">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Logs -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list"></i> 
                Logs ({{ $logs->total() }} registros)
            </h3>
        </div>
        <div class="card-body p-0">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Ação</th>
                                <th>Modelo</th>
                                <th>Descrição</th>
                                <th>IP</th>
                                <th>Data/Hora</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        @if($log->user)
                                            <span class="badge badge-info">{{ $log->user->name }}</span>
                                        @else
                                            <span class="badge badge-secondary">Sistema</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($log->action)
                                            @case('create')
                                                <span class="badge badge-success">{{ $log->action_name }}</span>
                                                @break
                                            @case('update')
                                                <span class="badge badge-warning">{{ $log->action_name }}</span>
                                                @break
                                            @case('delete')
                                                <span class="badge badge-danger">{{ $log->action_name }}</span>
                                                @break
                                            @case('login')
                                                <span class="badge badge-primary">{{ $log->action_name }}</span>
                                                @break
                                            @case('logout')
                                                <span class="badge badge-secondary">{{ $log->action_name }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ $log->action_name }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($log->model)
                                            <span class="badge badge-info">{{ $log->model_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($log->description, 50) }}</td>
                                    <td>
                                        <small class="text-muted">{{ $log->ip_address }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('activity-logs.show', $log) }}" 
                                           class="btn btn-sm btn-info" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginação -->
                <div class="card-footer">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center text-muted p-4">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Nenhum log encontrado.</p>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
<style>
    .table th {
        background-color: #f4f6f9;
        border-top: none;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .form-group label {
        font-weight: 600;
        font-size: 0.9em;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto-submit form quando mudar filtros
        $('#action, #model, #user_id').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@stop 