@extends('adminlte::page')

@section('title', 'Visualizar Configurações')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Configurações do Sistema</h1>
        <div>
            <a href="{{ route('configs.edit') }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Dados da Empresa -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-building"></i> Dados da Empresa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Razão Social:</strong>
                    <p>{{ $config->razao_social ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>CNPJ:</strong>
                    <p>{{ $config->cnpj ?? 'Não informado' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Endereço:</strong>
                    <p>{{ $config->endereco ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Expediente:</strong>
                    <p>{{ $config->expediente ?? 'Não informado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contatos -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-phone"></i> Contatos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Email Principal:</strong>
                    <p>{{ $config->email ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Celular:</strong>
                    <p>{{ $config->celular ?? 'Não informado' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <strong>Telefone 1:</strong>
                    <p>{{ $config->fone1 ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Telefone 2:</strong>
                    <p>{{ $config->fone2 ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-4">
                    <strong>WhatsApp:</strong>
                    <p>{{ $config->whatsapp ?? 'Não informado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Redes Sociais -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-share-alt"></i> Redes Sociais</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Facebook:</strong>
                    <p>
                        @if($config->facebook)
                            <a href="{{ $config->facebook }}" target="_blank">{{ $config->facebook }}</a>
                        @else
                            Não informado
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <strong>Instagram:</strong>
                    <p>
                        @if($config->instagram)
                            <a href="{{ $config->instagram }}" target="_blank">{{ $config->instagram }}</a>
                        @else
                            Não informado
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <strong>Google Maps:</strong>
                    <p>
                        @if($config->maps)
                            <a href="{{ $config->maps }}" target="_blank">{{ $config->maps }}</a>
                        @else
                            Não informado
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Configurações de Email -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-envelope"></i> Configurações de Email</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Email de Destino:</strong>
                    <p>{{ $config->form_email_to ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Email de Cópia:</strong>
                    <p>{{ $config->form_email_cc ?? 'Não informado' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Servidor SMTP:</strong>
                    <p>{{ $config->email_host ?? 'Não informado' }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Porta SMTP:</strong>
                    <p>{{ $config->email_port ?? 'Não informado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentos -->
    @if($config->arquivo_lgpd)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt"></i> Documentos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong>Arquivo LGPD:</strong>
                        <p>
                            <a href="{{ asset('storage/documentos/' . $config->arquivo_lgpd) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i> {{ $config->arquivo_lgpd }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .card-header {
            background-color: #f8f9fa;
        }
        
        strong {
            color: #495057;
        }
        
        p {
            margin-bottom: 1rem;
        }
    </style>
@stop 