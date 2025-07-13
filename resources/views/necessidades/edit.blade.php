@extends('adminlte::page')

@section('title', 'Editar Necessidade')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Necessidade</h1>
        <a href="{{ route('necessidades.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações da Necessidade</h3>
        </div>
        <form action="{{ route('necessidades.update', $necessidade->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Título -->
                        <div class="form-group">
                            <label for="titulo">Título da Necessidade <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo', $necessidade->titulo) }}" 
                                   placeholder="Ex: Atendimento domiciliar"
                                   required>
                            @error('titulo')
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
                    <i class="fas fa-save"></i> Atualizar Necessidade
                </button>
                <a href="{{ route('necessidades.index') }}" class="btn btn-secondary">
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
                const titulo = $('#titulo').val().trim();
                
                if (!titulo) {
                    e.preventDefault();
                    alert('O título da necessidade é obrigatório');
                    $('#titulo').focus();
                    return false;
                }
            });

            // Focar no campo título quando a página carregar
            $('#titulo').focus();
        });
    </script>
@stop 