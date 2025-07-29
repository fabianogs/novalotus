@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard <small>Visão Geral do Sistema</small></h1>
@stop

@section('content')
    <!-- Cards de Estatísticas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_especialistas'] }}</h3>
                    <p>Especialistas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{ route('especialistas.index') }}" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_parceiros'] }}</h3>
                    <p>Parceiros</p>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <a href="{{ route('parceiros.index') }}" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['total_unidades'] }}</h3>
                    <p>Unidades</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('unidades.index') }}" class="small-box-footer">
                    Ver todas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['total_cidades'] }}</h3>
                    <p>Cidades</p>
                </div>
                <div class="icon">
                    <i class="fas fa-city"></i>
                </div>
                <a href="{{ route('cidades.index') }}" class="small-box-footer">
                    Ver todas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Segunda linha de estatísticas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Especialidades</span>
                    <span class="info-box-number">{{ $stats['total_especialidades'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-hand-holding-heart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Necessidades</span>
                    <span class="info-box-number">{{ $stats['total_necessidades'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Planos</span>
                    <span class="info-box-number">{{ $stats['total_planos'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-image"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Banners Ativos</span>
                    <span class="info-box-number">{{ $stats['banners_ativos'] }}</span>
                    <span class="progress-description">{{ $stats['banners_inativos'] }} inativos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Seções de Itens Recentes -->
    <div class="row">
        <!-- Especialistas Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie"></i>
                        Especialistas Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('especialistas.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentes['especialistas']->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentes['especialistas'] as $especialista)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if($especialista->foto)
                                            <img src="{{ asset('storage/img/especialistas/' . $especialista->foto) }}" 
                                                 alt="{{ $especialista->nome }}" 
                                                 width="40" height="40" 
                                                 class="rounded-circle mr-3">
                                        @else
                                            <div class="bg-gray-light rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $especialista->nome }}</h6>
                                            <small class="text-muted">
                                                @if($especialista->especialidades->count() > 0)
                                                    {{ $especialista->especialidades->pluck('descricao')->implode(', ') }}
                                                @else
                                                    Sem especialidade
                                                @endif
                                                - 
                                                {{ $especialista->cidade ? $especialista->cidade->nome : 'Sem cidade' }}
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $especialista->created_at->format('d/m') }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted p-3">Nenhum especialista cadastrado ainda.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Parceiros Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-handshake"></i>
                        Parceiros Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('parceiros.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentes['parceiros']->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentes['parceiros'] as $parceiro)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if($parceiro->logo)
                                            <img src="{{ asset('storage/img/parceiros/' . $parceiro->logo) }}" 
                                                 alt="{{ $parceiro->nome }}" 
                                                 width="40" height="40" 
                                                 class="rounded mr-3">
                                        @else
                                            <div class="bg-gray-light rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $parceiro->nome }}</h6>
                                            <small class="text-muted">
                                                {{ $parceiro->cidade ? $parceiro->cidade->nome : 'Sem cidade' }}
                                                @if($parceiro->necessidade)
                                                    - {{ $parceiro->necessidade->titulo }}
                                                @endif
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $parceiro->created_at->format('d/m') }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted p-3">Nenhum parceiro cadastrado ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda linha de itens recentes -->
    <div class="row">
        <!-- Unidades Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-building"></i>
                        Unidades Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('unidades.create') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-plus"></i> Nova
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentes['unidades']->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentes['unidades'] as $unidade)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if($unidade->imagem)
                                            <img src="{{ asset('storage/img/unidades/' . $unidade->imagem) }}" 
                                                 alt="Unidade" 
                                                 width="40" height="40" 
                                                 class="rounded mr-3">
                                        @else
                                            <div class="bg-gray-light rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $unidade->cidade->nome }} - {{ $unidade->cidade->uf }}</h6>
                                            <small class="text-muted">
                                                @if($unidade->telefone)
                                                    {{ $unidade->telefone }}
                                                @elseif($unidade->endereco)
                                                    {{ Str::limit($unidade->endereco, 30) }}
                                                @else
                                                    Sem detalhes adicionais
                                                @endif
                                            </small>
                                        </div>
                                        <small class="text-muted">{{ $unidade->created_at->format('d/m') }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted p-3">Nenhuma unidade cadastrada ainda.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Planos Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list"></i>
                        Planos Recentes
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('planos.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentes['planos']->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentes['planos'] as $plano)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        @if($plano->imagem)
                                            <img src="{{ asset('storage/img/planos/' . $plano->imagem) }}" 
                                                 alt="{{ $plano->titulo }}" 
                                                 width="40" height="40" 
                                                 class="rounded mr-3">
                                        @else
                                            <div class="bg-gray-light rounded mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $plano->titulo }}</h6>
                                            <small class="text-muted">
                                                @if($plano->sintese)
                                                    {{ Str::limit($plano->sintese, 40) }}
                                                @else
                                                    {{ Str::limit($plano->descricao, 40) }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            @if($plano->link)
                                                <a href="{{ $plano->link }}" target="_blank" class="btn btn-xs btn-outline-primary mr-1">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            @if($plano->link_pdf)
                                                <a href="{{ $plano->link_pdf }}" target="_blank" class="btn btn-xs btn-outline-danger">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            <small class="text-muted d-block">{{ $plano->created_at->format('d/m') }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted p-3">Nenhum plano cadastrado ainda.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Usuário -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i>
                        Informações da Sessão
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Usuário:</strong> {{ auth()->user()->name }}</p>
                            <p><strong>E-mail:</strong> {{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Login em:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                            <p><strong>Status:</strong> <span class="badge badge-success">Online</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box .icon {
            top: -10px;
            right: 15px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Dashboard carregado com estatísticas!');
        
        // Atualizar hora atual a cada minuto
        setInterval(function() {
            // Pode ser usado para atualizações em tempo real futuramente
        }, 60000);
    </script>
@stop 