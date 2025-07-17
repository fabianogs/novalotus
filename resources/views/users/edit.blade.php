@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <h1>Editar Usuário</h1>
@endsection

@section('content')
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        @include('users._form', ['user' => $user])
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection 