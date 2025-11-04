@extends('layouts.app')

@section('title', 'Sala de Espera - Trunfo de Cartas')

@section('content')
<div class="min-h-screen p-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header da Sala -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-2">
                        <i class="fas fa-users mr-2"></i>
                        Sala: {{ $room->room_id }}
                    </h1>
                    <p class="text-gray-300">
                        Anfitrião: <span class="font-semibold text-yellow-400">{{ $room->host }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <div class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-user-friends mr-2"></i>
                        {{ $room->players->count() }}/4 Jogadores
                    </div>
                    <button 
                        onclick="copyRoomId()"
                        class="mt-2 text-sm text-gray-300 hover:text-white transition-colors flex items-center"
                    >
                        <i class="fas fa-copy mr-1"></i>
                        Copiar Código
                    </button>
                </div>
            </div>
        </div>

        <!-- Status do Jogo -->
        <div class="bg-gradient-to-r from-green-600/20 to-blue-600/20 backdrop-blur-sm rounded-xl p-6 mb-6">
            <div class="flex items-center justify-center">
                @if($room->players->count() < 4)
                    <div class="text-center">
                        <div class="animate-pulse text-yellow-400 text-4xl mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-white mb-2">
                            Aguardando Jogadores...
                        </h2>
                        <p class="text-gray-300">
                            Precisamos de {{ 4 - $room->players->count() }} jogador(es) para começar
                        </p>
                        <div class="mt-4">
                            <div class="w-64 bg-gray-700 rounded-full h-2 mx-auto">
                                <div 
                                    class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ ($room->players->count() / 4) * 100 }}%"
                                ></div>
                            </div>
                            <p class="text-sm text-gray-400 mt-2">
                                {{ round(($room->players->count() / 4) * 100) }}% completo
                            </p>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="text-green-400 text-4xl mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-white mb-2">
                            Todos os Jogadores Conectados!
                        </h2>
                        <p class="text-gray-300 mb-4">
                            O jogo começará automaticamente...
                        </p>
                        <div class="animate-spin text-blue-400 text-2xl">
                            <i class="fas fa-spinner"></i>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lista de Jogadores -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="players-container">
            @foreach($room->players as $index => $player)
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 transform hover:scale-105 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg
                                {{ $index === 0 ? 'bg-gradient-to-br from-red-500 to-red-600' : '' }}
                                {{ $index === 1 ? 'bg-gradient-to-br from-blue-500 to-blue-600' : '' }}
                                {{ $index === 2 ? 'bg-gradient-to-br from-green-500 to-green-600' : '' }}
                                {{ $index === 3 ? 'bg-gradient-to-br from-purple-500 to-purple-600' : '' }}
                            ">
                                {{ strtoupper(substr($player->username, 0, 1)) }}
                            </div>
                            
                            <!-- Info do Jogador -->
                            <div>
                                <h3 class="font-semibold text-white flex items-center">
                                    {{ $player->username }}
                                    @if($player->is_host)
                                        <span class="ml-2 bg-yellow-500 text-yellow-900 text-xs px-2 py-1 rounded-full font-bold">
                                            <i class="fas fa-crown mr-1"></i>HOST
                                        </span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-400">
                                    Posição {{ $index + 1 }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="text-green-400">
                            <i class="fas fa-circle text-xs"></i>
                            <span class="text-sm ml-1">Online</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Slots Vazios -->
            @for($i = $room->players->count(); $i < 4; $i++)
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-6 border-2 border-dashed border-gray-600">
                    <div class="flex items-center justify-center h-16">
                        <div class="text-center text-gray-500">
                            <i class="fas fa-user-plus text-2xl mb-2"></i>
                            <p class="text-sm">Aguardando jogador...</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Botão de Sair -->
        <div class="mt-6 text-center">
            <a 
                href="{{ url('/') }}" 
                class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
            >
                <i class="fas fa-sign-out-alt mr-2"></i>
                Sair da Sala
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
function copyRoomId() {
    navigator.clipboard.writeText('{{ $room->room_id }}').then(function() {
        showToast('Código da sala copiado!', 'success');
    }, function() {
        showToast('Erro ao copiar código', 'error');
    });
}

// WebSocket para atualizações em tempo real
const roomId = "{{ $room->room_id }}";
if (window.Echo) {
    window.Echo.channel(`game-room.${roomId}`)
        .listen('player-joined', (e) => {
            console.log('Player joined:', e.players);
            location.reload(); // Recarrega para mostrar novos jogadores
        });
}

// Auto-refresh como fallback
setInterval(function() {
    if ({{ $room->players->count() }} < 4) {
        location.reload();
    }
}, 5000);

// Redirect automático quando sala estiver cheia
@if($room->players->count() === 4)
    setTimeout(function() {
        window.location.href = '{{ route("game.start", $room->room_id) }}';
    }, 2000);
@endif
@endsection
