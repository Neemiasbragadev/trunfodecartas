@extends('layouts.app')

@section('title', 'Jogo de Truco')

@section('content')
<h1>Criar ou Entrar em uma Sala</h1>

<!-- Criar Sala -->
<form action="{{ route('game.create') }}" method="GET">
    @csrf
    <label for="username">Seu Nome:</label>
    <input type="text" name="username" required>
    <button type="submit">Criar Sala</button>
</form>

<!-- Entrar em uma Sala -->
<form action="{{ route('game.join') }}" method="POST">
    @csrf
    <label for="room_id">ID da Sala:</label>
    <input type="text" name="room_id" required>
    <label for="username">Seu Nome:</label>
    <input type="text" name="username" required>
    <button type="submit">Entrar</button>
</form>
@endsection
