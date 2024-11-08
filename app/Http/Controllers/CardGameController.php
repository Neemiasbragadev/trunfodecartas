<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CardGameController extends Controller
{
    private $deckId;
    private $players = [];

    public function __construct()
    {
        $this->deckId = session()->get('deck_id');
    }

    // Página inicial do jogo
    public function index()
    {
        return view('cardgame.index', ['players' => $this->players]);
    }

    // Embaralhar o baralho
    public function shuffle(Request $request)
    {
        $response = Http::get('https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1');
        $data = $response->json();
        session(['deck_id' => $data['deck_id']]);

        return redirect()->route('home')->with('message', 'Baralho embaralhado!');
    }

    // Distribuir as cartas para os jogadores
    public function deal(Request $request)
    {
        $deckId = session('deck_id');
        if (!$deckId) {
            return back()->with('error', 'Embaralhe o baralho primeiro!');
        }
    
        // Distribuir 5 cartas para cada um dos 4 jogadores
        $response = Http::get("https://deckofcardsapi.com/api/deck/{$deckId}/draw/?count=20");
        $cards = $response->json()['cards'];
        $players = $this->distributeCards($cards);
    
        // Passar as cartas dos jogadores para a nova view
        return view('cardgame.players', ['players' => $players]);
    }
    

    // Função para distribuir as cartas entre os jogadores
    private function distributeCards($cards)
    {
        $players = [];
        for ($i = 0; $i < 4; $i++) {
            $players[$i] = array_slice($cards, $i * 5, 5); // 5 cartas para cada jogador
        }
        return $players;
    }

    // Função para verificar quem tem a maior carta
    public function determineWinner()
    {
        $trufo = session('trufo');
        if (!$trufo) {
            return back()->with('error', 'O trufo não foi selecionado!');
        }
    
        $players = session('players');
        if (!$players || !is_array($players)) {
            return back()->with('error', 'As cartas dos jogadores não foram distribuídas corretamente.');
        }
    
        $winningPlayer = null;
        $highestCard = null;
    
        foreach ($players as $playerIndex => $hand) {
            foreach ($hand as $card) {
                if ($card['suit'] == $trufo) {
                    if (!$highestCard || $this->compareCards($card, $highestCard)) {
                        $highestCard = $card;
                        $winningPlayer = $playerIndex + 1;
                    }
                }
            }
        }
    
        return redirect()->back()->with('message', 'O vencedor é o Jogador ' . $winningPlayer);
    }
    
    // Função auxiliar para comparar cartas com base em seu valor
    private function compareCards($card1, $card2)
    {
        $cardValues = [
            'ACE' => 14, 'KING' => 13, 'QUEEN' => 12, 'JACK' => 11,
            '10' => 10, '9' => 9, '8' => 8, '7' => 7, '6' => 6,
            '5' => 5, '4' => 4, '3' => 3, '2' => 2
        ];
    
        return $cardValues[$card1['value']] > $cardValues[$card2['value']];
    }
    

    // Função para encontrar o jogador com a melhor carta
    private function getBestPlayer()
    {
        $bestCardValue = 0;
        $bestPlayer = null;
        $bestHand = null;

        foreach ($this->players as $playerIndex => $hand) {
            foreach ($hand as $card) {
                $cardValue = $this->cardValue($card['value']);
                if ($cardValue > $bestCardValue) {
                    $bestCardValue = $cardValue;
                    $bestPlayer = ['player' => $playerIndex + 1, 'card' => $card];
                    $bestHand = $hand;
                }
            }
        }

        return $bestPlayer;
    }

    // Função para converter o valor das cartas (para comparação)
    private function cardValue($card)
    {
        $values = [
            '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8,
            '9' => 9, '10' => 10, 'J' => 11, 'Q' => 12, 'K' => 13, 'A' => 14
        ];

        return $values[$card] ?? 0;
    }

    public function selectTrufo(Request $request)
    {
        $trufo = $request->input('trufo');
        session(['trufo' => $trufo]);  // Armazena o trufo na sessão
        return redirect()->back()->with('message', 'Trufo selecionado: ' . ucfirst(strtolower($trufo)));    
    }
    
}
