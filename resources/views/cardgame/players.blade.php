<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartas dos Jogadores</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="game-table">

    <h1>Cartas dos Jogadores</h1>

    <div class="table">
        <!-- Jogador 1 (canto superior) -->
        <div class="player player-top">
            <img class="avatar" src="{{ asset('images/avatar1.png') }}" alt="Avatar Jogador 1">
            <div class="deck">
                @foreach ($players[0] as $card)
                    <img class="card-back"  >
                @endforeach
            </div>
        </div>

        <!-- Jogador 2 (canto esquerdo) -->
        <div class="player player-left">
            <img class="avatar" src="{{ asset('images/avatar2.png') }}" alt="Avatar Jogador 2">
            <div class="deck">
                @foreach ($players[1] as $card)
                    <img class="card-back" >
                @endforeach
            </div>
        </div>

        <!-- Jogador 3 (canto direito) -->
        <div class="player player-right">
            <img class="avatar" src="{{ asset('images/avatar3.png') }}" alt="Avatar Jogador 3">
            <div class="deck">
                @foreach ($players[2] as $card)
                    <img class="card-back" >
                @endforeach
            </div>
        </div>

        <!-- Jogador Principal (parte inferior) -->
        <div class="player player-bottom">
            <img class="avatar" src="{{ asset('images/avatar4.png') }}" alt="Avatar Jogador Principal">
            <div class="hand">
                @foreach ($players[3] as $card)
                    <img class="card-front player-card" src="{{ $card['image'] }}" alt="{{ $card['value'] }} de {{ $card['suit'] }}">
                @endforeach
            </div>
        </div>
    </div>

    <form action="{{ route('determineWinner') }}" method="POST">
        @csrf
        <button type="submit" class="button">Determinar Vencedor</button>
    </form>

</body>
</html>
