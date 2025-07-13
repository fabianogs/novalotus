@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
    <h1>Planos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Planos</h3>
            <div class="card-tools">
                <a href="{{ route('planos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Plano
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table id="planos-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Link</th>
                        <th>Link PDF</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planos as $plano)
                        <tr>
                            <td>
                                @if($plano->imagem)
                                    <img src="{{ asset('storage/img/planos/' . $plano->imagem) }}" 
                                         alt="{{ $plano->titulo }}" 
                                         width="50" 
                                         height="50" 
                                         class="img-thumbnail">
                                @else
                                    <span class="text-muted">Sem imagem</span>
                                @endif
                            </td>
                            <td>{{ $plano->titulo }}</td>
                            <td>
                                @if($plano->link)
                                    <a href="{{ $plano->link }}" target="_blank" class="btn btn-sm btn-link">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>
                                @if($plano->link_pdf)
                                    <a href="{{ $plano->link_pdf }}" target="_blank" class="btn btn-sm btn-danger">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </td>
                            <td>{{ $plano->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('planos.edit', $plano) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('planos.destroy', $plano) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este plano?')">
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
            $('#planos-table').DataTable({
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