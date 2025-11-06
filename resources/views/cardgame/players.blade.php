@extends('layouts.app')

@section('title', 'Mesa de Jogo - Trunfo de Cartas')

@section('content')
<div class="min-h-screen p-4 relative">
    <!-- Header do Jogo -->
    <div class="bg-black/30 backdrop-blur-md rounded-xl p-4 mb-4">
        <div class="flex items-center justify-between text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-red-500 to-blue-500 p-3 rounded-lg">
                    <i class="fas fa-cards-blank text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Sala: {{ $room->room_id }}</h1>
                    <p class="text-sm text-gray-300">Rodada {{ $room->round ?? 1 }}</p>
                </div>
            </div>
            
            <!-- Trunfo -->
            <div class="text-center">
                <p class="text-sm text-gray-300 mb-2">Trunfo</p>
                @if(isset($room->trump))
                    <div class="w-16 h-22 bg-white rounded-lg shadow-lg flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-lg font-bold {{ $room->trump['suit'] === 'HEARTS' || $room->trump['suit'] === 'DIAMONDS' ? 'text-red-500' : 'text-black' }}">
                                {{ $room->trump['value'] }}
                            </div>
                            <div class="text-xl">
                                @if($room->trump['suit'] === 'HEARTS') ♥
                                @elseif($room->trump['suit'] === 'DIAMONDS') ♦
                                @elseif($room->trump['suit'] === 'CLUBS') ♣
                                @else ♠
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Info do Turno -->
            <div class="text-right">
                <p class="text-sm text-gray-300">Turno de</p>
                <p class="text-lg font-bold text-yellow-400">
                    {{ $room->getCurrentPlayer()->username ?? 'Jogador' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Mesa de Jogo -->
    <div class="flex items-center justify-center flex-1">
        <div class="relative">
            <!-- Mesa Central -->
            <div class="game-table w-80 h-80 flex items-center justify-center relative">
                <!-- Cartas jogadas na mesa -->
                <div id="table-cards" class="absolute inset-0 flex items-center justify-center">
                    <div class="grid grid-cols-2 gap-4">
                        @for($i = 0; $i < 4; $i++)
                            @php
                                $move = isset($moves[$i]) ? $moves[$i] : null;
                            @endphp
                            <div id="table-card-{{ $i }}" class="w-16 h-22 border-2 rounded-lg flex items-center justify-center {{ $move ? 'border-white/60 bg-white' : 'border-dashed border-white/30' }}">
                                @if($move && isset($move->card['value']) && isset($move->card['suit']))
                                    <div class="text-center">
                                        <div class="text-sm font-bold {{ in_array($move->card['suit'], ['HEARTS','DIAMONDS']) ? 'text-red-600' : 'text-black' }}">
                                            {{ $move->card['value'] }}
                                        </div>
                                        <div class="text-lg">
                                            @if($move->card['suit'] === 'HEARTS') ♥
                                            @elseif($move->card['suit'] === 'DIAMONDS') ♦
                                            @elseif($move->card['suit'] === 'CLUBS') ♣
                                            @else ♠
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <i class="fas fa-plus text-white/30 text-xl"></i>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Posições dos Jogadores -->
            @foreach($room->players as $index => $player)
                <div class="absolute player-position-{{ $index + 1 }} 
                    {{ $index === 0 ? 'bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full' : '' }}
                    {{ $index === 1 ? 'left-0 top-1/2 transform -translate-x-full -translate-y-1/2' : '' }}
                    {{ $index === 2 ? 'top-0 left-1/2 transform -translate-x-1/2 -translate-y-full' : '' }}
                    {{ $index === 3 ? 'right-0 top-1/2 transform translate-x-full -translate-y-1/2' : '' }}
                ">
                    <div class="bg-black/50 backdrop-blur-md rounded-xl p-4 {{ $room->current_turn == $player->position ? 'turn-indicator' : '' }}">
                        <!-- Avatar e Info -->
                        <div class="text-center mb-4">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-white font-bold text-lg mb-2
                                {{ $index === 0 ? 'bg-gradient-to-br from-red-500 to-red-600' : '' }}
                                {{ $index === 1 ? 'bg-gradient-to-br from-blue-500 to-blue-600' : '' }}
                                {{ $index === 2 ? 'bg-gradient-to-br from-green-500 to-green-600' : '' }}
                                {{ $index === 3 ? 'bg-gradient-to-br from-purple-500 to-purple-600' : '' }}
                            ">
                                {{ strtoupper(substr($player->username, 0, 1)) }}
                            </div>
                            <p class="text-white text-sm font-medium">{{ $player->username }}</p>
                            <p class="text-gray-400 text-xs">{{ count($player->hand ?? []) }} cartas</p>
                            <p class="text-yellow-400 text-sm font-bold">{{ $player->score ?? 0 }} pts</p>
                        </div>
                        
                        <!-- Cartas do Jogador (apenas para o jogador atual) -->
                        @if(auth()->user() && auth()->user()->id === $player->id)
                            <div class="flex space-x-2 justify-center">
                                @foreach($player->hand ?? [] as $cardIndex => $card)
                                    <div 
                                        class="card w-16 h-22 cursor-pointer playable" 
                                        data-card-code="{{ $card['code'] }}"
                                        onclick="playCard('{{ $card['code'] }}')"
                                    >
                                        <div class="bg-white rounded-lg h-full flex flex-col items-center justify-center p-1">
                                            <div class="text-xs font-bold {{ $card['suit'] === 'HEARTS' || $card['suit'] === 'DIAMONDS' ? 'text-red-500' : 'text-black' }}">
                                                {{ $card['value'] }}
                                            </div>
                                            <div class="text-lg">
                                                @if($card['suit'] === 'HEARTS') ♥
                                                @elseif($card['suit'] === 'DIAMONDS') ♦
                                                @elseif($card['suit'] === 'CLUBS') ♣
                                                @else ♠
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Cartas de outros jogadores (verso) -->
                            <div class="flex space-x-1 justify-center">
                                @for($j = 0; $j < count($player->hand ?? []); $j++)
                                    <div class="card-back w-12 h-16 rounded"></div>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chat Sidebar -->
    <div class="fixed right-4 top-1/2 transform -translate-y-1/2 w-80 h-96 bg-black/30 backdrop-blur-md rounded-xl p-4">
        <h3 class="text-white font-bold mb-4 flex items-center">
            <i class="fas fa-comments mr-2"></i>
            Chat
        </h3>
        
        <div id="chat-messages" class="h-64 overflow-y-auto space-y-2 mb-4">
            <!-- Mensagens aparecerão aqui -->
        </div>
        
        <div class="flex space-x-2">
            <input 
                type="text" 
                id="chat-input" 
                placeholder="Digite sua mensagem..."
                class="flex-1 px-3 py-2 bg-white/10 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                maxlength="100"
            >
            <button 
                onclick="sendMessage()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Função para jogar carta
async function playCard(cardCode) {
    if (!confirm('Tem certeza que deseja jogar esta carta?')) return;
    try {
        const res = await fetch(`{{ route('game.play-card', $room->room_id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ card_code: cardCode })
        });
        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await res.text();
            console.error('Resposta não JSON:', text);
            showToast('Falha ao jogar carta (resposta inválida).', 'error');
            return;
        }
        const data = await res.json();
        if (res.ok && data.success) {
            showToast('Carta jogada!', 'success');
            location.reload();
        } else {
            showToast((data && (data.error || data.message)) || 'Erro ao jogar carta', 'error');
        }
    } catch (err) {
        console.error('Erro na requisição:', err);
        showToast('Erro de conexão', 'error');
    }
}

// Função para enviar mensagem
function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (message) {
        // Aqui você implementaria o envio via WebSocket
        addChatMessage('{{ auth()->user()->username ?? "Você" }}', message, true);
        input.value = '';
    }
}

// Função para adicionar mensagem ao chat
function addChatMessage(username, message, isOwn = false) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${isOwn ? 'own' : 'other'}`;
    messageDiv.innerHTML = `
        <div class="text-xs text-gray-400 mb-1">${username}</div>
        <div>${message}</div>
    `;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Enter para enviar mensagem
document.getElementById('chat-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// WebSocket para atualizações em tempo real
if (window.Echo) {
    window.Echo.channel('game.{{ $room->room_id }}')
        .listen('.game.action', (e) => {
            console.log('Game action received:', e);
            if (e.action === 'card_played') {
                location.reload(); // Recarregar para mostrar nova carta
            }
        });
}
@endsection
