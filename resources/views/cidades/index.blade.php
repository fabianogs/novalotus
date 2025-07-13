@extends('adminlte::page')

@section('title', 'Cidades')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gerenciar Cidades</h1>
        <a href="{{ route('cidades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Cidade
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
            <h3 class="card-title">Lista de Cidades</h3>
        </div>
        <div class="card-body">
            <table id="cidadesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>UF</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cidades as $cidade)
                    <tr>
                        <td>{{ $cidade->nome }}</td>
                        <td>
                            <span class="badge badge-primary">{{ strtoupper($cidade->uf) }}</span>
                        </td>
                        <td>{{ $cidade->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('cidades.edit', $cidade->id) }}" 
                                   class="btn btn-sm btn-warning"
                                   title="Editar cidade"
                                   data-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="openDeleteModal({{ $cidade->id }}, '{{ $cidade->nome }}')"
                                        title="Excluir cidade"
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
                    <h4 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                    </div>
                    <h5 class="text-center mb-3">Você tem certeza?</h5>
                    <p class="text-center">
                        Esta ação não pode ser desfeita. A cidade 
                        <strong>"<span id="cidadeTitle"></span>"</strong> 
                        será excluída permanentemente.
                    </p>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Atenção:</strong> Verifique se esta cidade não possui especialistas, parceiros ou unidades vinculadas antes de excluir.
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        /* Estilo do modal de confirmação */
        #deleteModal .modal-content {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        #deleteModal .modal-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        
        #deleteModal .modal-footer {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        
        #deleteModal .btn {
            min-width: 120px;
        }
        
        /* Animação para o botão de excluir */
        .btn-danger:hover {
            transform: scale(1.05);
            transition: transform 0.2s;
        }
        
        /* Tooltip para botões */
        .btn-group .btn {
            margin-right: 2px;
        }
        
        /* Estilo para o slug */
        code {
            font-size: 0.85em;
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Inicializar DataTable
            $('#cidadesTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                }
            });
        });
        
        // Função para abrir modal de exclusão
        function openDeleteModal(cidadeId, cidadeNome) {
            // Definir título da cidade
            document.getElementById('cidadeTitle').textContent = cidadeNome;
            
            // Definir action do formulário
            document.getElementById('deleteForm').action = `/cidades/${cidadeId}`;
            
            // Abrir modal
            $('#deleteModal').modal('show');
        }
        
        // Loading no botão de excluir
        $(document).on('submit', '#deleteForm', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Excluindo...');
        });
    </script>
@stop 