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
            'game_status' => GameRoom::STATUS_WAITING,
        ]);
        
        $player = GamePlayer::create([
            'username' => $validated['username'],
            'is_host' => true,
            'game_room_id' => $room->id,
            'position' => 1,
        ]);
        
        Auth::login($player);

        return redirect()->route('game.view', $roomId);
    }
     // Exibe os jogadores conectados à sala
     public function view($roomId)
     {
         $room = GameRoom::where('room_id', $roomId)->with('players')->first();
         if (!$room) {
             return redirect()->route('game.create')->with('error', 'Sala não encontrada.');
         }
         // Mantém o usuário na sala; o início do jogo será disparado pelo anfitrião e comunicado por evento
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

        if ($room->isFull()) {
            return back()->with('error', 'A sala está cheia.');
        }

        $position = $room->players()->count() + 1; // Define a posição do jogador

        $player = GamePlayer::create([
            'username' => $validated['username'],
            'is_host' => false,
            'game_room_id'=> $room->id,
            'position' => $position,
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

    if (!$room->canStart()) {
        return back()->with('error', 'Aguardando mais jogadores ou jogo já iniciado.');
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
    $room->update([
        'trump' => $trumpCard,
        'game_status' => GameRoom::STATUS_PLAYING,
        'current_turn' => 1,
        'round' => 1
    ]);

    // Distribui as cartas entre os jogadores
    $players = $room->players()->orderBy('position')->get();
    $hands = [[], [], [], []];

    foreach ($deck as $index => $card) {
        $hands[$index % 4][] = $card; // Distribui as cartas de forma circular entre os jogadores
    }

    foreach ($players as $index => $player) {
        $player->update(['hand' => $hands[$index]]); // Atualiza a mão de cada jogador no banco
    }

    // Dispara evento para todos os clientes e redireciona quem chamou
    event(new GameStarted($room));
    return redirect()->route('game.play', $roomId);
}

    /**
     * Endpoint leve para clientes consultarem o status da sala.
     */
    public function checkGameStatus($roomId)
    {
        $room = GameRoom::where('room_id', $roomId)->withCount('players')->first();
        if (!$room) {
            return response()->json(['error' => 'Sala não encontrada'], 404);
        }
        return response()->json([
            'game_started' => $room->game_status === GameRoom::STATUS_PLAYING,
            'player_count' => $room->players_count,
        ]);
    }


    // Exibe a mesa de jogo
    public function play($roomId)
    {

        $room = GameRoom::where('room_id', $roomId)->with('players')->first();
       // dd($room);
        if (!$room) {
            return redirect()->route('game.create')->with('error', 'Sala não encontrada.');
        }

        // Carrega as cartas jogadas na rodada atual para exibir na mesa
        $moves = \App\Models\GameMove::where('game_room_id', $room->id)
            ->where('round', $room->round)
            ->orderBy('order')
            ->get();

        return view('cardgame.players', compact('room', 'moves'));
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

    public function playCard(Request $request, $roomId)
    {
        $validated = $request->validate([
            'card_code' => 'required|string',
        ]);

        $room = GameRoom::where('room_id', $roomId)->with('players')->first();
        $currentPlayer = auth()->user();

        if (!$currentPlayer || !$currentPlayer->isCurrentTurn()) {
            return response()->json(['error' => 'Não é seu turno'], 403);
        }

        // Verificar se o jogador tem a carta
        $playerHand = $currentPlayer->hand ?? [];
        $cardExists = false;
        $playedCard = null;

        foreach ($playerHand as $card) {
            if ($card['code'] === $validated['card_code']) {
                $cardExists = true;
                $playedCard = $card;
                break;
            }
        }

        if (!$cardExists) {
            return response()->json(['error' => 'Carta não encontrada na sua mão'], 400);
        }

        // Remover carta da mão do jogador
        $currentPlayer->removeCard($validated['card_code']);

        // Registrar a jogada
        $currentMovesCount = \App\Models\GameMove::where('game_room_id', $room->id)
            ->where('round', $room->round)
            ->count();

        \App\Models\GameMove::create([
            'game_room_id' => $room->id,
            'player_id' => $currentPlayer->id,
            'card' => $playedCard,
            'round' => $room->round,
            'order' => $currentMovesCount + 1,
        ]);

        // Próximo turno
        $room->nextTurn();

        // Verificar se a rodada terminou (4 cartas jogadas)
        if ($currentMovesCount + 1 === 4) {
            $this->evaluateRound($room);
        }

        // Broadcast da jogada
        broadcast(new GameAction($roomId, 'card_played', $currentPlayer->id));

        return response()->json([
            'success' => true,
            'message' => 'Carta jogada com sucesso',
            'next_turn' => $room->current_turn
        ]);
    }

    private function evaluateRound($room)
    {
        $moves = \App\Models\GameMove::where('game_room_id', $room->id)
            ->where('round', $room->round)
            ->with('player')
            ->get();

        $trumpSuit = $room->trump['suit'];
        $winningMove = null;
        $highestValue = 0;

        foreach ($moves as $move) {
            $card = $move->card;
            $cardValue = $this->getCardValue($card['value']);
            
            // Cartas de trunfo têm prioridade
            if ($card['suit'] === $trumpSuit) {
                $cardValue += 100; // Bonus para trunfo
            }

            if ($cardValue > $highestValue) {
                $highestValue = $cardValue;
                $winningMove = $move;
            }
        }

        if ($winningMove) {
            $winningMove->update(['is_winner' => true]);
            $winningMove->player->increment('score');
            
            // Definir o vencedor como primeiro jogador da próxima rodada
            $room->update([
                'current_turn' => $winningMove->player->position,
                'round' => $room->round + 1
            ]);
        }
    }

    private function getCardValue($value)
    {
        $values = [
            '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
            '9' => 9, '10' => 10, 'JACK' => 11, 'QUEEN' => 12, 'KING' => 13, 'ACE' => 14
        ];

        return $values[$value] ?? 0;
    }
}
