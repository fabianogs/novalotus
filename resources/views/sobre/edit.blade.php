@extends('adminlte::page')

@section('title', 'Editar Texto Sobre')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-info-circle"></i> Editar "Quem Somos""</h1>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('sobre.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="texto">Texto</label>
                    <textarea class="form-control @error('texto') is-invalid @enderror" 
                              id="texto" name="texto" rows="10" 
                              placeholder="Digite o texto ...">{{ old('texto', $sobre->texto) }}</textarea>
                    @error('texto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Este texto será exibido na seção "Quem Somos" do site.
                    </small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
@stop

@section('css')
<style>
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
    }
    
    .card-header {
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 200px;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Auto-resize textarea
        $('#texto').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Initialize height
        $('#texto').trigger('input');
    });
</script>
@stop 