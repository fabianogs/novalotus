@extends('adminlte::page')

@section('title', 'Necessidades')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gerenciar Necessidades</h1>
        <a href="{{ route('necessidades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Necessidade
        </a>
    </div>
@stop

@section('content')
    <!-- Mensagens de sucesso/erro -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Necessidades</h3>
        </div>
        <div class="card-body">
            <table id="necessidadesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($necessidades as $necessidade)
                    <tr>
                        <td>{{ $necessidade->titulo }}</td>
                        <td>{{ $necessidade->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('necessidades.edit', $necessidade->id) }}" 
                                   class="btn btn-sm btn-warning"
                                   title="Editar necessidade"
                                   data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="openDeleteModal({{ $necessidade->id }}, '{{ $necessidade->titulo }}')"
                                        title="Excluir necessidade"
                                        data-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a necessidade <strong id="deleteItemName"></strong>?</p>
                    <p class="text-muted">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        
        .btn-group {
            gap: 2px;
        }
        
        .modal-content {
            border-radius: 8px;
        }
        
        .modal-header {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $(function() {
            // Configurar DataTables
            $('#necessidadesTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [2] }
                ]
            });

            // Configurar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Configurar modal de exclusão
            window.openDeleteModal = function(id, name) {
                $('#deleteItemName').text(name);
                $('#deleteForm').attr('action', `/necessidades/${id}`);
                $('#deleteModal').modal('show');
            };

            // Auto-dismiss alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        });
    </script>
@stop 