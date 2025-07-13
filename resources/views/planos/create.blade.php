@extends('adminlte::page')

@section('title', 'Novo Plano')

@section('content_header')
    <h1>Novo Plano</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cadastrar Plano</h3>
        </div>
        <form action="{{ route('planos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="titulo">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" 
                                   id="link" name="link" value="{{ old('link') }}" placeholder="https://exemplo.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="link_pdf">Link do PDF</label>
                            <input type="text" class="form-control @error('link_pdf') is-invalid @enderror" 
                                   id="link_pdf" name="link_pdf" value="{{ old('link_pdf') }}" placeholder="https://exemplo.com/arquivo.pdf">
                            @error('link_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="4">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sintese">Síntese</label>
                            <textarea class="form-control @error('sintese') is-invalid @enderror" 
                                      id="sintese" name="sintese" rows="3">{{ old('sintese') }}</textarea>
                            @error('sintese')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="imagem">Imagem</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('imagem') is-invalid @enderror" 
                                           id="imagem" name="imagem" accept="image/*">
                                    <label class="custom-file-label" for="imagem">Escolher arquivo</label>
                                </div>
                            </div>
                            <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, SVG. Tamanho máximo: 2MB</small>
                            @error('imagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagem-preview" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('planos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Atualizar label do arquivo
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Preview da imagem
            $('#imagem').on('change', function() {
                previewImage(this, '#imagem-preview');
            });

            function previewImage(input, previewSelector) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewSelector).html('<img src="' + e.target.result + '" alt="Preview" width="200" height="150" class="img-thumbnail">');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@stop 