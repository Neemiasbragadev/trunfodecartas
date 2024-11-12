<!-- resources/views/cardgame/players.blade.php -->
@extends('layouts.app')

@section('title', 'Cartas dos Jogadores')

@section('content')
    <h1>Cartas dos Jogadores</h1>

    <div class="table">
        <!-- Jogador 1 (canto superior) -->
        <div class="player player-top">
            <img class="avatar" src="{{ asset('images/avatar1.png') }}" alt="Avatar Jogador 1">
            <div class="deck">
                @foreach ($players[0] as $card)
                    <img class="card-back">
                @endforeach
            </div>
        </div>

        <!-- Jogador 2 (canto esquerdo) -->
        <div class="player player-left">
            <img class="avatar" src="{{ asset('images/avatar2.png') }}" alt="Avatar Jogador 2">
            <div class="deck">
                @foreach ($players[1] as $card)
                    <img class="card-back">
                @endforeach
            </div>
        </div>

        <!-- Centro da Mesa -->
        <div class="play player-center" id="player-center"></div>

        <!-- Jogador 3 (canto direito) -->
        <div class="player player-right">
            <img class="avatar" src="{{ asset('images/avatar3.png') }}" alt="Avatar Jogador 3">
            <div class="deck">
                @foreach ($players[2] as $card)
                    <img class="card-back">
                @endforeach
            </div>
        </div>

        <!-- Jogador Principal (parte inferior) -->
        <div class="player player-bottom">
            <img class="avatar" src="{{ asset('images/avatar4.png') }}" alt="Avatar Jogador Principal">
            <div class="hand">
                @foreach ($players[3] as $card)
                    <img class="card-front player-card" onclick="selecionarCarta()" src="{{ $card['image'] }}" alt="{{ $card['value'] }} de {{ $card['suit'] }}">
                @endforeach
            </div>
        </div>
    </div>

    <form action="{{ route('determineWinner') }}" method="POST">
        @csrf
        <button type="submit" class="button">Determinar Vencedor</button>
    </form>


    
    <script>
       function selecionarCarta(event) {
        // Obtém a carta que foi clicada
        const cartaSelecionada = event.target;

        // Remove a carta da mão do jogador
        cartaSelecionada.parentElement.removeChild(cartaSelecionada);

        // Adiciona a carta na área central
        const playerCenter = document.getElementById('player-center');
        playerCenter.appendChild(cartaSelecionada);
    }

    // Adiciona o evento de clique em cada carta da mão do jogador
    document.querySelectorAll('.player-card').forEach(card => {
        card.addEventListener('click', selecionarCarta);
    });
    </script>
@endsection


