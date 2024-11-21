@extends('layouts.app')

@section('title', 'Mesa de Jogo')

@section('content')
    <h1>Mesa de Jogo - Sala {{ $room->room_id }}</h1>

    <p>
        <strong>Trunfo:</strong>
        @if ($room->trump)
            {{data_get($room, 'trump.value')}} de {{data_get($room, 'trump.suit')}}
        @else
            <em>Trunfo não definido.</em>
        @endif
    </p>

    <div class="table">
        @foreach ($room->players as $player)
            @if ($player->id == auth()->user()->id) <!-- Mão do jogador principal -->
                <div class="player">
                    <h2>{{ $player->username }}</h2>
                    <div class="hand">
                        <div class="player player-bottom">
                            <img class="avatar" src="{{ asset('images/avatar4.png') }}" alt="Avatar Jogador Principal">
                            <div class="hand">
                                @foreach ($player->hand as $card)
                                    <img class="card-front player-card" onclick="selecionarCarta('{{ $card['value'] }}')" src="{{ $card['image'] }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
    <script>
        // Conectar ao Pusher
        Pusher.logToConsole = true;
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        var channel = pusher.subscribe('game.{{ $room->room_id }}');
        channel.bind('game.action', function(data) {
            console.log('Ação do jogador recebida: ', data);

            // Atualiza a UI com base na ação
            if (data.action === 'card_selected') {
                alert('Jogador ' + data.playerId + ' escolheu uma carta!');
                // Aqui você pode atualizar a UI (ex: removendo a carta da mão)
            }
        });

        // Função de seleção de carta
        function selecionarCarta(cardValue) {
            // Envia um evento para o backend via AJAX
            fetch('/game/{{ $room->room_id }}/selecionarCarta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ card_value: cardValue })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            });
        }
    </script>
@endsection
