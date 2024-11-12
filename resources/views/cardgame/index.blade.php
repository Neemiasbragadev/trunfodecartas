<!-- resources/views/cardgame/index.blade.php -->
@extends('layouts.app')

@section('title', 'Jogo de Truco')

@section('content')
    <h1>Cartas dos Jogadores</h1>

    @if (session('trufo'))
        <div class="trufo-display">
            <span>Trufo Selecionado: {{ session('trufo') }}</span>
        </div>
    @endif

    @if (session('message'))
        <div class="message">{{ session('message') }}</div>
    @endif
    
    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <!-- Selecionar o Trufo -->
    <form action="{{ route('selectTrufo') }}" method="POST">
        @csrf
        <label for="trufo">Escolha o Trufo:</label>
        <select name="trufo" id="trufo" required>
            <option value="HEARTS">Copas (♥)</option>
            <option value="DIAMONDS">Ouros (♦)</option>
            <option value="CLUBS">Paus (♣)</option>
            <option value="SPADES">Espadas (♠)</option>
        </select>
        <button type="submit">Confirmar Trufo</button>
    </form>

    <!-- Embaralhar e Distribuir Cartas -->
    <form action="{{ route('shuffle') }}" method="POST" style="margin-top: 20px;">
        @csrf
        <button type="submit">Embaralhar Baralho</button>
    </form>

    <form action="{{ route('deal') }}" method="POST" style="margin-top: 10px;">
        @csrf
        <button type="submit">Distribuir Cartas</button>
    </form>
@endsection
