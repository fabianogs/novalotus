@extends('adminlte::page')

@section('title', 'Detalhes do Usuário')

@section('content_header')
    <h1>Detalhes do Usuário</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nome:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Email verificado em:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : '-' }}</p>
            <p><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Atualizado em:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@endsection 