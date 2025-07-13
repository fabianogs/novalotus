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
                        <!-- Nome -->
                        <div class="form-group">
                            <label for="nome">Nome da Especialidade <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $especialidade->nome) }}" 
                                   placeholder="Ex: Cardiologia"
                                   required>
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Validação do formulário
            $('form').on('submit', function(e) {
                const nome = $('#nome').val().trim();
                
                if (!nome) {
                    e.preventDefault();
                    alert('O nome da especialidade é obrigatório');
                    $('#nome').focus();
                    return false;
                }
            });

            // Focar no campo nome quando a página carregar
            $('#nome').focus();
        });
    </script>
@stop 