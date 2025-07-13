@extends('adminlte::page')

@section('title', 'Unidades')

@section('content_header')
    <h1>Unidades</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Unidades</h3>
            <div class="card-tools">
                <a href="{{ route('unidades.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nova Unidade
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table id="unidades-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Cidade</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unidades as $unidade)
                        <tr>
                            <td>
                                @if($unidade->imagem)
                                    <img src="{{ asset('storage/img/unidades/' . $unidade->imagem) }}" 
                                         alt="Imagem da unidade" 
                                         width="50" 
                                         height="50" 
                                         class="img-thumbnail">
                                @else
                                    <span class="text-muted">Sem imagem</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $unidade->cidade->nome }} - {{ $unidade->cidade->uf }}</span>
                            </td>
                            <td>
                                @if($unidade->telefone)
                                    {{ $unidade->telefone }}
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>
                                @if($unidade->endereco)
                                    {{ $unidade->endereco }}
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>{{ $unidade->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('unidades.edit', $unidade) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('unidades.destroy', $unidade) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta unidade?')">
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
            $('#unidades-table').DataTable({
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