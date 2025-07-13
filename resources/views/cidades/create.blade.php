@extends('adminlte::page')

@section('title', 'Nova Cidade')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Criar Nova Cidade</h1>
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
        <form action="{{ route('cidades.store') }}" method="POST">
            @csrf
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
                                   value="{{ old('nome') }}" 
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
                                <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>Acre</option>
                                <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>Pará</option>
                                <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                            </select>
                            @error('uf')
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
                    <i class="fas fa-save"></i> Salvar Cidade
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