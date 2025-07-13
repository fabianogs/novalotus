@extends('adminlte::page')

@section('title', 'Editar Unidade')

@section('content_header')
    <h1>Editar Unidade</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Unidade: {{ $unidade->cidade->nome }} - {{ $unidade->cidade->uf }}</h3>
        </div>
        <form action="{{ route('unidades.update', $unidade) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidade_id">Cidade <span class="text-danger">*</span></label>
                            <select class="form-control @error('cidade_id') is-invalid @enderror" 
                                    id="cidade_id" name="cidade_id" required>
                                <option value="">Selecione uma cidade</option>
                                @foreach($cidades as $cidade)
                                    <option value="{{ $cidade->id }}" 
                                            @if(old('cidade_id', $unidade->cidade_id) == $cidade->id) selected @endif>
                                        {{ $cidade->nome }} - {{ $cidade->uf }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cidade_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone', $unidade->telefone) }}" placeholder="(11) 99999-9999">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                                   id="endereco" name="endereco" value="{{ old('endereco', $unidade->endereco) }}" placeholder="Rua, número, bairro">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="imagem">Imagem (271x150 px)</label>
                            
                            @if($unidade->imagem)
                                <div class="mb-2">
                                    <strong>Imagem atual:</strong><br>
                                    <img src="{{ asset('storage/img/unidades/' . $unidade->imagem) }}" 
                                         alt="Imagem atual" 
                                         width="200" 
                                         height="150" 
                                         class="img-thumbnail">
                                </div>
                            @endif
                            
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('imagem') is-invalid @enderror" 
                                           id="imagem" name="imagem" accept="image/*">
                                    <label class="custom-file-label" for="imagem">Escolher nova imagem</label>
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
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('unidades.index') }}" class="btn btn-secondary">Cancelar</a>
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
                        $(previewSelector).html('<strong>Nova imagem:</strong><br><img src="' + e.target.result + '" alt="Preview" width="200" height="150" class="img-thumbnail">');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@stop 