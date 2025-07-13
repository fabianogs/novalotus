@extends('adminlte::page')

@section('title', 'Parceiros')

@section('content_header')
    <h1>Parceiros</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Parceiros</h3>
            <div class="card-tools">
                <a href="{{ route('parceiros.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Parceiro
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table id="parceiros-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nome</th>
                        <th>Cidade</th>
                        <th>Necessidade</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parceiros as $parceiro)
                        <tr>
                            <td>
                                @if($parceiro->logo)
                                    <img src="{{ asset('storage/img/parceiros/' . $parceiro->logo) }}" 
                                         alt="{{ $parceiro->nome }}" 
                                         width="50" 
                                         height="50" 
                                         class="img-thumbnail">
                                @else
                                    <span class="text-muted">Sem logo</span>
                                @endif
                            </td>
                            <td>{{ $parceiro->nome }}</td>
                            <td>
                                @if($parceiro->cidade)
                                    <span class="badge badge-info">{{ $parceiro->cidade->nome }} - {{ $parceiro->cidade->uf }}</span>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>
                                @if($parceiro->necessidade)
                                    <span class="badge badge-success">{{ $parceiro->necessidade->titulo }}</span>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>{{ $parceiro->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('parceiros.edit', $parceiro) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('parceiros.destroy', $parceiro) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este parceiro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#parceiros-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });
        });
    </script>
@stop 