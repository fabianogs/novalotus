@extends('adminlte::page')

@section('title', 'Editar Parceiro')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Parceiro</h1>
        <a href="{{ route('parceiros.index') }}" class="btn btn-secondary">
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
            <h3 class="card-title">Informações do Parceiro</h3>
        </div>
        <form action="{{ route('parceiros.update', $parceiro) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" name="nome" value="{{ old('nome', $parceiro->nome) }}" 
                                   placeholder="Ex: Hospital São José" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidade_id">Cidade</label>
                            <div class="d-flex">
                                <select class="form-control @error('cidade_id') is-invalid @enderror" 
                                        id="cidade_id" name="cidade_id" style="flex: 1;">
                                    <option value="">Selecione uma cidade</option>
                                    @foreach($cidades as $cidade)
                                        <option value="{{ $cidade->id }}" 
                                                @if(old('cidade_id', $parceiro->cidade_id) == $cidade->id) selected @endif>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($cidades->isEmpty())
                                <small class="text-muted">Nenhuma cidade cadastrada. <a href="{{ route('cidades.create') }}" target="_blank">Cadastre uma agora</a>.</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="necessidade_id">Necessidade</label>
                            <div class="d-flex">
                                <select class="form-control @error('necessidade_id') is-invalid @enderror" 
                                        id="necessidade_id" name="necessidade_id" style="flex: 1;">
                                    <option value="">Selecione uma necessidade</option>
                                    @foreach($necessidades as $necessidade)
                                        <option value="{{ $necessidade->id }}" 
                                                @if(old('necessidade_id', $parceiro->necessidade_id) == $necessidade->id) selected @endif>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($necessidades->isEmpty())
                                <small class="text-muted">Nenhuma necessidade cadastrada. <a href="{{ route('necessidades.create') }}" target="_blank">Cadastre uma agora</a>.</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                                   id="endereco" name="endereco" value="{{ old('endereco', $parceiro->endereco) }}"
                                   placeholder="Ex: Rua das Flores, 123 - Centro">
                            @error('endereco')
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
                                      id="descricao" name="descricao" rows="4">{{ old('descricao', $parceiro->descricao) }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">Logo (217x186 px)</label>
                            
                            @if($parceiro->logo)
                                <div class="mb-2">
                                    <strong>Logo atual:</strong><br>
                                    <img src="{{ asset('storage/img/parceiros/' . $parceiro->logo) }}" 
                                         alt="Logo atual" 
                                         width="100" 
                                         height="100" 
                                         class="img-thumbnail">
                                </div>
                            @endif
                            
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    <label class="custom-file-label" for="logo">Escolher novo arquivo</label>
                                </div>
                            </div>
                            <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, SVG. Tamanho máximo: 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="logo-preview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo_carrossel">Logo do Carrossel (220x80 px)</label>
                            
                            @if($parceiro->logo_carrossel)
                                <div class="mb-2">
                                    <strong>Logo do carrossel atual:</strong><br>
                                    <img src="{{ asset('storage/img/parceiros/' . $parceiro->logo_carrossel) }}" 
                                         alt="Logo do carrossel atual" 
                                         width="100" 
                                         height="100" 
                                         class="img-thumbnail">
                                </div>
                            @endif
                            
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo_carrossel') is-invalid @enderror" 
                                           id="logo_carrossel" name="logo_carrossel" accept="image/*">
                                    <label class="custom-file-label" for="logo_carrossel">Escolher novo arquivo</label>
                                </div>
                            </div>
                            <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, SVG. Tamanho máximo: 2MB</small>
                            @error('logo_carrossel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="logo_carrossel-preview" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('parceiros.index') }}" class="btn btn-secondary">Cancelar</a>
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

            // Preview do logo
            $('#logo').on('change', function() {
                previewImage(this, '#logo-preview');
            });

            // Preview do logo do carrossel
            $('#logo_carrossel').on('change', function() {
                previewImage(this, '#logo_carrossel-preview');
            });

            function previewImage(input, previewSelector) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewSelector).html('<strong>Novo arquivo:</strong><br><img src="' + e.target.result + '" alt="Preview" width="100" height="100" class="img-thumbnail">');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@stop 