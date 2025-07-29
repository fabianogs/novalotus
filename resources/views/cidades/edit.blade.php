@extends('adminlte::page')

@section('title', 'Editar Cidade')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Cidade</h1>
        <a href="{{ route('cidades.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações da Cidade</h3>
        </div>
        <form action="{{ route('cidades.update', $cidade->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nome -->
                        <div class="form-group">
                            <label for="nome">Nome da Cidade <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $cidade->nome) }}" 
                                   placeholder="Ex: São Paulo"
                                   required>
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- UF -->
                        <div class="form-group">
                            <label for="uf">UF (Estado) <span class="text-danger">*</span></label>
                            <select class="form-control @error('uf') is-invalid @enderror" 
                                    id="uf" 
                                    name="uf" 
                                    required>
                                <option value="">Selecione o estado</option>
                                <option value="AC" {{ old('uf', $cidade->uf) == 'AC' ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{ old('uf', $cidade->uf) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{ old('uf', $cidade->uf) == 'AP' ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{ old('uf', $cidade->uf) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{ old('uf', $cidade->uf) == 'BA' ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{ old('uf', $cidade->uf) == 'CE' ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{ old('uf', $cidade->uf) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{ old('uf', $cidade->uf) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                <option value="GO" {{ old('uf', $cidade->uf) == 'GO' ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{ old('uf', $cidade->uf) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                <option value="MT" {{ old('uf', $cidade->uf) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MS" {{ old('uf', $cidade->uf) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MG" {{ old('uf', $cidade->uf) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{ old('uf', $cidade->uf) == 'PA' ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{ old('uf', $cidade->uf) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{ old('uf', $cidade->uf) == 'PR' ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{ old('uf', $cidade->uf) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{ old('uf', $cidade->uf) == 'PI' ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{ old('uf', $cidade->uf) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{ old('uf', $cidade->uf) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{ old('uf', $cidade->uf) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{ old('uf', $cidade->uf) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{ old('uf', $cidade->uf) == 'RR' ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{ old('uf', $cidade->uf) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{ old('uf', $cidade->uf) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{ old('uf', $cidade->uf) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{ old('uf', $cidade->uf) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                            </select>
                            @error('uf')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nome Completo -->
                        <div class="form-group">
                            <label for="nome_completo">Nome Completo</label>
                            <input type="text" 
                                   class="form-control @error('nome_completo') is-invalid @enderror" 
                                   id="nome_completo" 
                                   name="nome_completo" 
                                   value="{{ old('nome_completo', $cidade->nome_completo) }}" 
                                   placeholder="Ex: São Paulo - SP"
                                   readonly>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Este campo é preenchido automaticamente pela sincronização da API
                            </small>
                            @error('nome_completo')
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
                    <i class="fas fa-save"></i> Atualizar Cidade
                </button>
                <a href="{{ route('cidades.index') }}" class="btn btn-secondary">
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
                const uf = $('#uf').val();
                
                if (!nome) {
                    e.preventDefault();
                    alert('O nome da cidade é obrigatório');
                    $('#nome').focus();
                    return false;
                }
                
                if (!uf) {
                    e.preventDefault();
                    alert('O estado (UF) é obrigatório');
                    $('#uf').focus();
                    return false;
                }
            });
        });
    </script>
@stop 