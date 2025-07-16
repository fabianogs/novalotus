@extends('adminlte::page')

@section('title', 'Detalhes do Log')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-eye"></i> Detalhes do Log #{{ $activityLog->id }}</h1>
        <div>
            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Informações Básicas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Informações Básicas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $activityLog->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Usuário:</strong></td>
                                    <td>
                                        @if($activityLog->user)
                                            <span class="badge badge-info">{{ $activityLog->user->name }}</span>
                                            <br><small class="text-muted">{{ $activityLog->user->email }}</small>
                                        @else
                                            <span class="badge badge-secondary">Sistema</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ação:</strong></td>
                                    <td>
                                        @switch($activityLog->action)
                                            @case('create')
                                                <span class="badge badge-success">{{ $activityLog->action_name }}</span>
                                                @break
                                            @case('update')
                                                <span class="badge badge-warning">{{ $activityLog->action_name }}</span>
                                                @break
                                            @case('delete')
                                                <span class="badge badge-danger">{{ $activityLog->action_name }}</span>
                                                @break
                                            @case('login')
                                                <span class="badge badge-primary">{{ $activityLog->action_name }}</span>
                                                @break
                                            @case('logout')
                                                <span class="badge badge-secondary">{{ $activityLog->action_name }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ $activityLog->action_name }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Modelo:</strong></td>
                                    <td>
                                        @if($activityLog->model)
                                            <span class="badge badge-info">{{ $activityLog->model_name }}</span>
                                            @if($activityLog->model_id)
                                                <small class="text-muted">(ID: {{ $activityLog->model_id }})</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Data/Hora:</strong></td>
                                    <td>{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>IP:</strong></td>
                                    <td><code>{{ $activityLog->ip_address }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Método:</strong></td>
                                    <td><span class="badge badge-secondary">{{ $activityLog->method }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>URL:</strong></td>
                                    <td><small class="text-muted">{{ $activityLog->url }}</small></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <strong>Descrição:</strong>
                            <p class="mt-2">{{ $activityLog->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valores Antigos e Novos -->
            @if($activityLog->old_values || $activityLog->new_values)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exchange-alt"></i> Alterações</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($activityLog->old_values)
                                <div class="col-md-6">
                                    <h5 class="text-danger"><i class="fas fa-minus-circle"></i> Valores Antigos</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-danger">
                                                <tr>
                                                    <th>Campo</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($activityLog->old_values as $field => $value)
                                                    <tr>
                                                        <td><strong>{{ $field }}</strong></td>
                                                        <td>
                                                            @if(is_array($value))
                                                                <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($activityLog->new_values)
                                <div class="col-md-6">
                                    <h5 class="text-success"><i class="fas fa-plus-circle"></i> Valores Novos</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>Campo</th>
                                                    <th>Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($activityLog->new_values as $field => $value)
                                                    <tr>
                                                        <td><strong>{{ $field }}</strong></td>
                                                        <td>
                                                            @if(is_array($value))
                                                                <pre class="mb-0">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Informações Técnicas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Informações Técnicas</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><strong>User Agent:</strong></label>
                        <textarea class="form-control" rows="4" readonly>{{ $activityLog->user_agent }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>URL Completa:</strong></label>
                        <textarea class="form-control" rows="3" readonly>{{ $activityLog->url }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Timestamp:</strong></label>
                        <input type="text" class="form-control" value="{{ $activityLog->created_at }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    pre {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.5rem;
        font-size: 0.8em;
    }
    
    .table-sm td, .table-sm th {
        padding: 0.25rem;
        font-size: 0.9em;
    }
</style>
@stop 