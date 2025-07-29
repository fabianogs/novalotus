@extends('adminlte::page')

@section('title', 'Visualizar Especialista')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Visualizar Especialista</h1>
        <div>
            <a href="{{ route('especialistas.edit', $especialista) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('especialistas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Informações Principais -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-md"></i> Informações do Especialista
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        @if($especialista->foto)
                            <img src="{{ asset('storage/img/especialistas/' . $especialista->foto) }}" 
                                 alt="Foto do {{ $especialista->nome }}" 
                                 class="img-fluid rounded" 
                                 style="max-width: 150px; max-height: 150px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user-md fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $especialista->nome }}</h4>
                        @if($especialista->nome_fantasia)
                            <p class="text-muted mb-2">
                                <strong>Nome Fantasia:</strong> {{ $especialista->nome_fantasia }}
                            </p>
                        @endif
                        
                        @if($especialista->conselho)
                            <p class="mb-2">
                                <strong>Conselho:</strong> {{ $especialista->conselho }}
                                @if($especialista->registro)
                                    - Registro: {{ $especialista->registro }}
                                    @if($especialista->registro_uf)
                                        /{{ $especialista->registro_uf }}
                                    @endif
                                @endif
                            </p>
                        @endif
                        
                        @if($especialista->cidade)
                            <p class="mb-2">
                                <strong>Cidade:</strong> {{ $especialista->cidade->nome }} - {{ $especialista->cidade->uf }}
                            </p>
                        @endif
                        
                        @if($especialista->necessidade)
                            <p class="mb-2">
                                <strong>Necessidade:</strong> {{ $especialista->necessidade->titulo }}
                            </p>
                        @endif
                        
                        @if($especialista->endereco)
                            <p class="mb-2">
                                <strong>Endereço:</strong> {{ $especialista->endereco }}
                            </p>
                        @endif
                        
                        @if($especialista->especialidades->count() > 0)
                            <p class="mb-2">
                                <strong>Especialidades:</strong>
                                @foreach($especialista->especialidades as $especialidade)
                                    <span class="badge badge-primary mr-1">{{ $especialidade->descricao }}</span>
                                @endforeach
                            </p>
                        @endif
                        
                        <p class="mb-0">
                            <small class="text-muted">
                                <strong>Criado em:</strong> {{ $especialista->created_at->format('d/m/Y H:i:s') }} |
                                <strong>Atualizado em:</strong> {{ $especialista->updated_at->format('d/m/Y H:i:s') }}
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Estatísticas -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i> Estatísticas
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Endereços</span>
                        <span class="info-box-number">{{ $especialista->enderecos->count() }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Telefones</span>
                        <span class="info-box-number">{{ $especialista->telefones->count() }}</span>
                    </div>
                </div>
                
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-stethoscope"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Especialidades</span>
                        <span class="info-box-number">{{ $especialista->especialidades->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Endereços -->
@if($especialista->enderecos->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt"></i> Endereços ({{ $especialista->enderecos->count() }})
                    </h3>
                    <div>
                        <a href="{{ route('enderecos.create', $especialista) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Adicionar Endereço
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($especialista->enderecos as $endereco)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="card-title">
                                        <i class="fas fa-map-marker-alt text-danger"></i> 
                                        Endereço #{{ $loop->iteration }}
                                    </h6>
                                    <div class="btn-group">
                                        <a href="{{ route('enderecos.edit', [$especialista, $endereco]) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar endereço">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteEndereco({{ $endereco->id }}, '{{ $endereco->logradouro ?? 'Endereço' }}')"
                                                title="Remover endereço">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                @if($endereco->logradouro)
                                    <p class="mb-1"><strong>Logradouro:</strong> {{ $endereco->logradouro }}</p>
                                @endif
                                
                                @if($endereco->numero)
                                    <p class="mb-1"><strong>Número:</strong> {{ $endereco->numero }}</p>
                                @endif
                                
                                @if($endereco->bairro)
                                    <p class="mb-1"><strong>Bairro:</strong> {{ $endereco->bairro }}</p>
                                @endif
                                
                                @if($endereco->cidade_nome)
                                    <p class="mb-1"><strong>Cidade:</strong> {{ $endereco->cidade_nome }}</p>
                                @endif
                                
                                @if($endereco->uf)
                                    <p class="mb-1"><strong>UF:</strong> {{ $endereco->uf }}</p>
                                @endif
                                
                                @if($endereco->cep)
                                    <p class="mb-1"><strong>CEP:</strong> {{ $endereco->cep }}</p>
                                @endif
                                
                                @if($endereco->complemento)
                                    <p class="mb-1"><strong>Complemento:</strong> {{ $endereco->complemento }}</p>
                                @endif
                                
                                <small class="text-muted">
                                    Atualizado em: {{ $endereco->updated_at->format('d/m/Y H:i:s') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt"></i> Endereços
                    </h3>
                    <a href="{{ route('enderecos.create', $especialista) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Adicionar Endereço
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Nenhum endereço cadastrado. Clique em "Adicionar Endereço" para cadastrar um novo endereço.
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Telefones -->
@if($especialista->telefones->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-phone"></i> Telefones ({{ $especialista->telefones->count() }})
                    </h3>
                    <div>
                        <a href="{{ route('telefones.create', $especialista) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Adicionar Telefone
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($especialista->telefones as $telefone)
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="card-title">
                                        <i class="fas fa-phone text-success"></i> 
                                        Telefone #{{ $loop->iteration }}
                                    </h6>
                                    <div class="btn-group">
                                        <a href="{{ route('telefones.edit', [$especialista, $telefone]) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Editar telefone">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteTelefone({{ $telefone->id }}, '{{ $telefone->numero }}')"
                                                title="Remover telefone">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="mb-2">
                                    <strong>{{ $telefone->numero }}</strong>
                                </p>
                                
                                @if($telefone->observacao)
                                    <p class="mb-2 text-muted">
                                        <small>{{ $telefone->observacao }}</small>
                                    </p>
                                @endif
                                
                                <small class="text-muted">
                                    Atualizado em: {{ $telefone->updated_at->format('d/m/Y H:i:s') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-phone"></i> Telefones
                    </h3>
                    <a href="{{ route('telefones.create', $especialista) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Adicionar Telefone
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Nenhum telefone cadastrado. Clique em "Adicionar Telefone" para cadastrar um novo telefone.
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
.info-box {
    margin-bottom: 15px;
}
.card-body .row .col-md-6 .card,
.card-body .row .col-md-4 .card {
    height: 100%;
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Adicionar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Função para excluir endereço
function deleteEndereco(enderecoId, enderecoNome) {
    if (confirm(`Tem certeza que deseja remover o endereço "${enderecoNome}"?`)) {
        fetch(`/especialistas/{{ $especialista->id }}/enderecos/${enderecoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao remover endereço: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao remover endereço');
        });
    }
}

// Função para excluir telefone
function deleteTelefone(telefoneId, telefoneNumero) {
    if (confirm(`Tem certeza que deseja remover o telefone "${telefoneNumero}"?`)) {
        fetch(`/especialistas/{{ $especialista->id }}/telefones/${telefoneId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao remover telefone: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao remover telefone');
        });
    }
}
</script>
@stop