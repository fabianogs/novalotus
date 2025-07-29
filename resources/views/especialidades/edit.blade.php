@extends('adminlte::page')

@section('title', 'Editar Especialidade')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Especialidade</h1>
        <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações da Especialidade</h3>
        </div>
        <form action="{{ route('especialidades.update', $especialidade->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- ID -->
                        <div class="form-group">
                            <label for="id">ID da API</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="id" 
                                   value="{{ $especialidade->id }}" 
                                   readonly>
                            <small class="form-text text-muted">
                                ID fornecido pela API externa (não pode ser alterado)
                            </small>
                        </div>

                        <!-- Descrição -->
                        <div class="form-group">
                            <label for="descricao">Descrição da Especialidade <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('descricao') is-invalid @enderror" 
                                   id="descricao" 
                                   name="descricao" 
                                   value="{{ old('descricao', $especialidade->descricao) }}" 
                                   placeholder="Ex: CARDIOLOGIA"
                                   required>
                            @error('descricao')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="slug" 
                                   value="{{ $especialidade->slug }}" 
                                   readonly>
                            <small class="form-text text-muted">
                                Slug gerado automaticamente a partir da descrição
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Informações adicionais -->
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informações</h5>
                            <ul class="mb-0">
                                <li><strong>Criado em:</strong> {{ $especialidade->created_at->format('d/m/Y H:i:s') }}</li>
                                <li><strong>Atualizado em:</strong> {{ $especialidade->updated_at->format('d/m/Y H:i:s') }}</li>
                                <li><strong>Especialistas vinculados:</strong> {{ $especialidade->especialistas->count() }}</li>
                            </ul>
                        </div>

                        @if($especialidade->especialistas->count() > 0)
                            <div class="alert alert-warning">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção</h5>
                                <p class="mb-0">
                                    Esta especialidade possui {{ $especialidade->especialistas->count() }} especialista(s) vinculado(s). 
                                    Alterações podem afetar esses registros.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Atualizar Especialidade
                </button>
                <a href="{{ route('especialidades.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control[readonly] {
            background-color: #e9ecef;
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Validação do formulário
            $('form').on('submit', function(e) {
                const descricao = $('#descricao').val().trim();
                
                if (!descricao) {
                    e.preventDefault();
                    alert('A descrição da especialidade é obrigatória');
                    $('#descricao').focus();
                    return false;
                }
            });

            // Focar no campo descrição quando a página carregar
            $('#descricao').focus();
        });
    </script>
@stop 