@extends('adminlte::page')

@section('title', 'Editar Banner')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Banner</h1>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Banner</h3>
        </div>
        <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Título -->
                        <div class="form-group">
                            <label for="titulo">Título <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo', $banner->titulo) }}" 
                                   required>
                            @error('titulo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="url" 
                                   class="form-control @error('link') is-invalid @enderror" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link', $banner->link) }}" 
                                   placeholder="https://exemplo.com">
                            @error('link')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Ativo -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="ativo" 
                                       name="ativo" 
                                       value="1" 
                                       {{ old('ativo', $banner->ativo) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="ativo">Banner Ativo</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Imagem Atual -->
                        <div class="form-group">
                            <label>Imagem Atual</label>
                            <div class="text-center">
                                @if($banner->imagem)
                                    <img id="currentImage" 
                                         src="{{ asset('storage/img/banners/' . $banner->imagem) }}" 
                                         alt="{{ $banner->titulo }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 300px; max-height: 200px;">
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x"></i>
                                        <p>Nenhuma imagem cadastrada</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Nova Imagem -->
                        <div class="form-group">
                            <label for="imagem">Nova Imagem (1360x580 px)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('imagem') is-invalid @enderror" 
                                           id="imagem" 
                                           name="imagem" 
                                           accept="image/*">
                                    <label class="custom-file-label" for="imagem">Escolher arquivo</label>
                                </div>
                            </div>
                            @error('imagem')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                Deixe em branco para manter a imagem atual. Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB.
                            </small>
                        </div>

                        <!-- Preview da Nova Imagem -->
                        <div class="form-group">
                            <label>Preview da Nova Imagem</label>
                            <div class="text-center">
                                <img id="imagePreview" 
                                     src="" 
                                     alt="Preview" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px; max-height: 200px; display: none;">
                                <div id="noImageText" class="text-muted" style="display: none;">
                                    <i class="fas fa-image fa-3x"></i>
                                    <p>Preview da nova imagem</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Atualizar Banner
                </button>
                <a href="{{ route('banners.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .custom-file-label::after {
            content: "Buscar";
        }
        
        #imagePreview, #currentImage {
            border: 2px dashed #ddd;
            padding: 10px;
            border-radius: 8px;
        }
        
        #noImageText {
            border: 2px dashed #ddd;
            padding: 40px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Preview da nova imagem
            $('#imagem').on('change', function(event) {
                const file = event.target.files[0];
                const preview = $('#imagePreview');
                const noImageText = $('#noImageText');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).show();
                        noImageText.hide();
                    }
                    reader.readAsDataURL(file);
                    
                    // Atualizar o label do arquivo
                    const fileName = file.name;
                    $(this).next('.custom-file-label').text(fileName);
                } else {
                    preview.hide();
                    noImageText.hide();
                    $(this).next('.custom-file-label').text('Escolher arquivo');
                }
            });

            // Validação do formulário
            $('form').on('submit', function(e) {
                const titulo = $('#titulo').val().trim();
                
                if (!titulo) {
                    e.preventDefault();
                    toastr.error('O título é obrigatório');
                    $('#titulo').focus();
                    return false;
                }
            });
        });
    </script>
@stop 