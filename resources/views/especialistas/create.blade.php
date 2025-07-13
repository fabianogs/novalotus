@extends('adminlte::page')

@section('title', 'Novo Especialista')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Criar Novo Especialista</h1>
        <a href="{{ route('especialistas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    {{-- Avisos sobre registros necessários --}}
    @if(!empty($warnings))
        <div class="row mb-3">
            <div class="col-12">
                @foreach($warnings as $warning)
                    <div class="alert alert-{{ $warning['type'] }} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                        {{ $warning['message'] }}
                        <div class="mt-2">
                            <a href="{{ $warning['route'] }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-plus"></i> {{ $warning['button_text'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Especialista</h3>
        </div>
        <form action="{{ route('especialistas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nome -->
                        <div class="form-group">
                            <label for="nome">Nome do Especialista <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome') }}" 
                                   placeholder="Ex: Dr. João Silva"
                                   required>
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Conselho -->
                        <div class="form-group">
                            <label for="conselho">Conselho/Registro</label>
                            <input type="text" 
                                   class="form-control @error('conselho') is-invalid @enderror" 
                                   id="conselho" 
                                   name="conselho" 
                                   value="{{ old('conselho') }}" 
                                   placeholder="Ex: CRM 12345">
                            @error('conselho')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Especialidade -->
                        <div class="form-group">
                            <label for="especialidade_id">Especialidade</label>
                            <div class="d-flex">
                                <select class="form-control @error('especialidade_id') is-invalid @enderror" 
                                        id="especialidade_id" 
                                        name="especialidade_id"
                                        style="flex: 1;">
                                    <option value="">Selecione uma especialidade</option>
                                    @foreach($especialidades as $especialidade)
                                        <option value="{{ $especialidade->id }}" {{ old('especialidade_id') == $especialidade->id ? 'selected' : '' }}>
                                            {{ $especialidade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($especialidades->isEmpty())
                                    <a href="{{ route('especialidades.create') }}" class="btn btn-warning btn-sm ml-2" target="_blank">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </div>
                            @error('especialidade_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if($especialidades->isEmpty())
                                <small class="text-muted">Nenhuma especialidade cadastrada. <a href="{{ route('especialidades.create') }}" target="_blank">Cadastre uma agora</a>.</small>
                            @endif
                        </div>

                        <!-- Cidade -->
                        <div class="form-group">
                            <label for="cidade_id">Cidade</label>
                            <div class="d-flex">
                                <select class="form-control @error('cidade_id') is-invalid @enderror" 
                                        id="cidade_id" 
                                        name="cidade_id"
                                        style="flex: 1;">
                                    <option value="">Selecione uma cidade</option>
                                    @foreach($cidades as $cidade)
                                        <option value="{{ $cidade->id }}" {{ old('cidade_id') == $cidade->id ? 'selected' : '' }}>
                                            {{ $cidade->nome }} - {{ $cidade->uf }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($cidades->isEmpty())
                                    <a href="{{ route('cidades.create') }}" class="btn btn-warning btn-sm ml-2" target="_blank">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </div>
                            @error('cidade_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if($cidades->isEmpty())
                                <small class="text-muted">Nenhuma cidade cadastrada. <a href="{{ route('cidades.create') }}" target="_blank">Cadastre uma agora</a>.</small>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Endereço -->
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                      id="endereco" 
                                      name="endereco" 
                                      rows="3"
                                      placeholder="Ex: Rua das Flores, 123 - Centro">{{ old('endereco') }}</textarea>
                            @error('endereco')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Necessidade -->
                        <div class="form-group">
                            <label for="necessidade_id">Necessidade</label>
                            <div class="d-flex">
                                <select class="form-control @error('necessidade_id') is-invalid @enderror" 
                                        id="necessidade_id" 
                                        name="necessidade_id"
                                        style="flex: 1;">
                                    <option value="">Selecione uma necessidade</option>
                                    @foreach($necessidades as $necessidade)
                                        <option value="{{ $necessidade->id }}" {{ old('necessidade_id') == $necessidade->id ? 'selected' : '' }}>
                                            {{ $necessidade->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($necessidades->isEmpty())
                                    <a href="{{ route('necessidades.create') }}" class="btn btn-warning btn-sm ml-2" target="_blank">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </div>
                            @error('necessidade_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if($necessidades->isEmpty())
                                <small class="text-muted">Nenhuma necessidade cadastrada. <a href="{{ route('necessidades.create') }}" target="_blank">Cadastre uma agora</a>.</small>
                            @endif
                        </div>

                        <!-- Foto -->
                        <div class="form-group">
                            <label for="foto">Foto do Especialista (217x217 px)</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('foto') is-invalid @enderror" 
                                           id="foto" 
                                           name="foto" 
                                           accept="image/*">
                                    <label class="custom-file-label" for="foto">Escolher arquivo</label>
                                </div>
                            </div>
                            @error('foto')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB.
                            </small>
                        </div>

                        <!-- Preview da Foto -->
                        <div class="form-group">
                            <label>Preview da Foto</label>
                            <div class="text-center">
                                <img id="imagePreview" 
                                     src="" 
                                     alt="Preview" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px; display: none;">
                                <div id="noImageText" class="text-muted">
                                    <i class="fas fa-user-circle fa-4x"></i>
                                    <p>Nenhuma foto selecionada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Especialista
                </button>
                <a href="{{ route('especialistas.index') }}" class="btn btn-secondary">
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

        .img-thumbnail {
            border-radius: 8px;
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Preview da imagem
            $('#foto').on('change', function() {
                const file = this.files[0];
                const preview = $('#imagePreview');
                const noImageText = $('#noImageText');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).show();
                        noImageText.hide();
                    };
                    reader.readAsDataURL(file);
                    
                    // Atualizar label do arquivo
                    $('.custom-file-label').text(file.name);
                } else {
                    preview.hide();
                    noImageText.show();
                    $('.custom-file-label').text('Escolher arquivo');
                }
            });

            // Validação do formulário
            $('form').on('submit', function(e) {
                const nome = $('#nome').val().trim();
                
                if (!nome) {
                    e.preventDefault();
                    alert('O nome do especialista é obrigatório');
                    $('#nome').focus();
                    return false;
                }
            });

            // Focar no campo nome quando a página carregar
            $('#nome').focus();
        });
    </script>
@stop 