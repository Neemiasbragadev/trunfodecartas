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
                <h2>{{ $player->username }} </h2>
                <div class="hand">
                    <div class="player player-bottom">
                        <img class="avatar" src="{{ asset('images/avatar4.png') }}" alt="Avatar Jogador Principal">
                        <div class="hand">
                                @foreach ($player->hand as $card)
                                 <img class="card-front player-card" onclick="selecionarCarta()" src="{{ $card['image'] }}">
                                 @endforeach
                        </div>
                    </div>
                     <!-- Cartas dos outros jogadores -->
                </div>
            </div>
        @endif


        @endforeach
    </div>
@endsection
