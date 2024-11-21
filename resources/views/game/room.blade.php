@extends('layouts.app')

@section('title', 'Jogo de Truco')

@section('content')
<h1>Sala: {{ $room->room_id }}</h1>

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
    Echo.channel('game-room.{{ $room->room_id }}')
        .listen('GameStarted', (event) => {
            // Redireciona para a mesa
            window.location.href = "{{ route('game.play', $room->room_id) }}";
        });
</script>
@endsection
