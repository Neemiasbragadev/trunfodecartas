@extends('layouts.app')

@section('title', 'Jogo de Truco')

@section('content')
<h1>Sala: {{ $room->room_id }}</h1>

<div id="players-container">
    @foreach ($room->players as $player)
        <div>{{ $player->username }}</div>
    @endforeach
</div>

<h2>Jogadores Conectados:</h2>

<ul>
    @foreach ($room->players as $player)
        <li>{{ $player->username }} @if($player->is_host) (Host) @endif</li>
    @endforeach
</ul>

@if ($room->players->count() === 4)
    <form action="{{ route('game.start', $room->room_id) }}" method="POST">
        @csrf
        <button type="submit">Iniciar Partida</button>
    </form>
@else
    <p>Aguardando mais jogadores...</p>
@endif
<script>
    const roomId = "{{ $room->room_id }}";

    window.Echo.channel(`game-room.${roomId}`)
        .listen('player-joined', (e) => {
            console.log('Player joined:', e.players);

            // Atualize os jogadores na sala dinamicamente
            const playersContainer = document.getElementById('players-container');
            playersContainer.innerHTML = '';

            e.players.forEach(player => {
                const playerElement = document.createElement('div');
                playerElement.textContent = player.username;
                playersContainer.appendChild(playerElement);
            });

            // Redirecionar se todos os 4 jogadores estiverem conectados
            if (e.players.length === 4) {
                window.location.href = "{{ route('game.play', $room->room_id) }}";
            }
        });
</script>

@endsection
