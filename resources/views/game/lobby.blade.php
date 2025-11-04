@extends('layouts.app')

@section('title', 'Trunfo de Cartas - Lobby')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-cards-blank text-3xl text-white"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Trunfo de Cartas</h1>
            <p class="text-gray-300">Jogue com até 4 amigos online</p>
        </div>

        <!-- Formulários -->
        <div class="bg-white/10 backdrop-blur-md rounded-xl p-8 space-y-6">
            <!-- Criar Sala -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Criar Nova Sala
                </h2>
                <form action="{{ route('game.create') }}" method="GET" class="space-y-4">
                    @csrf
                    <div>
                        <label for="username-create" class="block text-sm font-medium text-gray-200 mb-2">
                            Seu Nome
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username-create"
                            required 
                            maxlength="20"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90 placeholder-gray-500"
                            placeholder="Digite seu nome..."
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center"
                    >
                        <i class="fas fa-gamepad mr-2"></i>
                        Criar Sala
                    </button>
                </form>
            </div>

            <!-- Divisor -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-400"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-transparent text-gray-300">OU</span>
                </div>
            </div>

            <!-- Entrar em Sala -->
            <div class="space-y-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Entrar em Sala Existente
                </h2>
                <form action="{{ route('game.join') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-200 mb-2">
                            Código da Sala
                        </label>
                        <input 
                            type="text" 
                            name="room_id" 
                            id="room_id"
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white/90 placeholder-gray-500"
                            placeholder="Ex: room_abc123..."
                        >
                    </div>
                    <div>
                        <label for="username-join" class="block text-sm font-medium text-gray-200 mb-2">
                            Seu Nome
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username-join"
                            required 
                            maxlength="20"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white/90 placeholder-gray-500"
                            placeholder="Digite seu nome..."
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transform hover:scale-105 transition-all duration-200 flex items-center justify-center"
                    >
                        <i class="fas fa-door-open mr-2"></i>
                        Entrar na Sala
                    </button>
                </form>
            </div>
        </div>

        <!-- Instruções -->
        <div class="bg-blue-900/30 backdrop-blur-sm rounded-lg p-6 text-center">
            <h3 class="text-lg font-semibold text-white mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Como Jogar
            </h3>
            <div class="space-y-2 text-sm text-gray-200">
                <p>• Crie uma sala ou entre com um código</p>
                <p>• Aguarde 4 jogadores se conectarem</p>
                <p>• Jogue suas cartas e vença com o trunfo!</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
// Validação em tempo real
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Carregando...';
            
            setTimeout(() => {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = button.dataset.originalText || 'Enviar';
                }
            }, 3000);
        });
    });
    
    // Armazenar texto original dos botões
    document.querySelectorAll('button[type="submit"]').forEach(btn => {
        btn.dataset.originalText = btn.innerHTML;
    });
});
@endsection
