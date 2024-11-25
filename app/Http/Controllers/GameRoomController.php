<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRoom;
use App\Models\GamePlayer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\GameAction;
use App\Events\GameStarted;
use App\Events\PlayerJoined;

class GameRoomController extends Controller
{

    protected $room;
     // Cria uma sala e adiciona o jogador anfitrião
    public function create(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
        ]);
        $roomId = uniqid('room_'); // Gera um ID único para a sala

        $room = GameRoom::create([       // Adiciona o jogador anfitrião
            'room_id' => $roomId,
            'host' => $validated['username'],
        ]);
        $player = GamePlayer::create([
            'username' => $validated['username'],
            'is_host' => true,
            'game_room_id' => $room->id,

        ]);
        Auth::login($player);

       //dd(Auth::user());

        return redirect()->route('game.view', $roomId);
    }
     // Exibe os jogadores conectados à sala
     public function view($roomId)
     {
         $room = GameRoom::where('room_id', $roomId)->with('players')->first();
        // $room = $this->room;
         if (!$room) {
             return redirect()->route('game.create')->with('error', 'Sala não encontrada.');
         }

         if ($room->players->count() === 4) {

             return redirect()->route('game.start', $roomId); // Redireciona para iniciar o jogo
         }


         return view('game.room', compact('room'));
     }

    // Jogador entra em uma sala existente
    public function join(Request $request)
    {


        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'room_id' => 'required|string|exists:game_rooms,room_id',
        ]);


        $room = GameRoom::where('room_id', $validated['room_id'])->first();

        if ($room->players()->count() >= 4) {
            return back()->with('error', 'A sala está cheia.');
        }

        $player = GamePlayer::create([
            'username' => $validated['username'],
            'is_host' => false,
            'game_room_id'=> $room->id,
        ]);
        Auth::login($player);

        $players = $room->players()->get();
        broadcast(new PlayerJoined($validated['room_id'], $players));


        return redirect()->route('game.view', $validated['room_id']);
    }



    // Inicia o jogo após todos os jogadores se conectarem


    public function start($roomId)
{
    // Carrega a sala com os jogadores
    $room = GameRoom::where('room_id', $roomId)->with('players')->first();

    if ($room->players->count() < 4) {
        return back()->with('error', 'Aguardando mais jogadores.');
    }

    // Solicita um baralho embaralhado da API
    $response = Http::get('https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1');
    $deckData = $response->json();

    if (!isset($deckData['deck_id'])) {
        return back()->with('error', 'Não foi possível obter o baralho da API.');
    }

    $deckId = $deckData['deck_id'];

    // Puxa todas as cartas do baralho
    $drawResponse = Http::get("https://deckofcardsapi.com/api/deck/{$deckId}/draw/?count=52");
    $cardsData = $drawResponse->json();

    if (!isset($cardsData['cards'])) {
        return back()->with('error', 'Não foi possível puxar as cartas do baralho.');
    }

    $deck = $cardsData['cards']; // Baralho de cartas puxado da API

    // Configura o trunfo (primeira carta do baralho, por exemplo)
    $trumpCard = array_shift($deck); // Remove e define a primeira carta como trunfo
    $room->update(['trump' => $trumpCard]); // Salva como JSON automaticamente

    // Distribui as cartas entre os jogadores
    $players = $room->players;
    $hands = [[], [], [], []];

    foreach ($deck as $index => $card) {
        $hands[$index % 4][] = $card; // Distribui as cartas de forma circular entre os jogadores
    }

    foreach ($players as $index => $player) {
        $player->update(['hand' => $hands[$index]]); // Atualiza a mão de cada jogador no banco
    }

    // Redireciona para a tela do jogo
    event(new GameStarted($room));
    return redirect()->route('game.play', $roomId);
}


    // Exibe a mesa de jogo
    public function play($roomId)
    {

        $room = GameRoom::where('room_id', $roomId)->with('players')->first();
       // dd($room);
        if (!$room) {
            return redirect()->route('game.create')->with('error', 'Sala não encontrada.');
        }


        return view('cardgame.players', compact('room'));
    }


    public function selecionarCarta(Request $request, $roomId)
    {
        // Ação do jogador - exemplo: escolha de uma carta
        $playerId = auth()->user()->id;
        $action = 'card_selected';  // Ou o tipo de ação que você está transmitindo

        // Dispara o evento
        broadcast(new GameAction($roomId, $action, $playerId));

        // Retorna uma resposta, como atualizar a interface do jogo
        return response()->json(['status' => 'success']);
    }
}
