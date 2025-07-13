@extends('adminlte::page')

@section('title', 'Novo SEO')

@section('content_header')
    <h1>Novo SEO</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cadastrar SEO</h3>
        </div>
        <form action="{{ route('seos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tipo') is-invalid @enderror" 
                                   id="tipo" name="tipo" value="{{ old('tipo') }}" required>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="script">Script</label>
                            <textarea class="form-control @error('script') is-invalid @enderror" 
                                      id="script" name="script" rows="6" placeholder="Cole aqui o código do script SEO">{{ old('script') }}</textarea>
                            @error('script')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" @if(old('status', true)) checked @endif>
                                <label class="custom-control-label" for="status">Ativo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('seos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop 