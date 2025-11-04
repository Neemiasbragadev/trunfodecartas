# 🎮 Trunfo de Cartas - Jogo Multiplayer Online

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-teal.svg)](https://tailwindcss.com)
[![WebSockets](https://img.shields.io/badge/WebSockets-Pusher-green.svg)](https://pusher.com)

Um jogo de cartas multiplayer moderno e responsivo desenvolvido em Laravel com interface elegante e comunicação em tempo real.

## ✨ Características

### 🎯 Funcionalidades do Jogo
- **Salas Privadas**: Crie ou entre em salas com até 4 jogadores
- **Tempo Real**: Comunicação instantânea via WebSockets (Pusher)
- **Sistema de Turnos**: Controle automático de turnos e rodadas
- **Trunfo Dinâmico**: Sistema inteligente de trunfo
- **Pontuação**: Sistema de pontos com ranking
- **Chat Integrado**: Chat em tempo real durante as partidas

### 🎨 Interface Moderna
- **Design Responsivo**: Funciona perfeitamente em desktop e mobile
- **TailwindCSS**: Interface moderna com animações suaves
- **Tema Escuro**: Visual elegante e confortável
- **Componentes Interativos**: Cartas animadas e efeitos visuais
- **UX Intuitiva**: Interface fácil de usar e entender

### 🔧 Tecnologias
- **Backend**: Laravel 10.x + PHP 8.1+
- **Frontend**: TailwindCSS + JavaScript ES6
- **Banco de Dados**: MySQL
- **Real-time**: Laravel Echo + Pusher
- **API Externa**: Deck of Cards API

## 🚀 Instalação

### Pré-requisitos
- PHP 8.1+
- Composer
- Node.js & npm
- MySQL
- Conta Pusher (para WebSockets)

### Configuração

1. **Clone o repositório**
```bash
git clone https://github.com/Neemiasbragadev/tunfodecartas.git
cd trunfodecartas
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados no .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cardgame
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Configure o Pusher no .env**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=seu_app_id
PUSHER_APP_KEY=sua_app_key
PUSHER_APP_SECRET=seu_app_secret
PUSHER_APP_CLUSTER=seu_cluster

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

6. **Execute as migrations**
```bash
php artisan migrate
```

7. **Compile os assets**
```bash
npm run build
# ou para desenvolvimento
npm run dev
```

8. **Inicie o servidor**
```bash
php artisan serve
```

## 🎮 Como Jogar

### Criando uma Sala
1. Acesse a página inicial
2. Digite seu nome
3. Clique em "Criar Sala"
4. Compartilhe o código da sala com os amigos

### Entrando em uma Sala
1. Digite o código da sala
2. Digite seu nome
3. Clique em "Entrar"
4. Aguarde outros jogadores (máximo 4)

### Durante o Jogo
1. **Cartas são distribuídas automaticamente**
2. **Trunfo é definido aleatoriamente**
3. **Jogue suas cartas em ordem de turno**
4. **Cartas de trunfo vencem cartas normais**
5. **O jogador com a carta mais alta vence a rodada**

## 📊 Estrutura do Banco

### Tabelas Principais
- `game_rooms`: Salas de jogo
- `game_players`: Jogadores nas salas
- `game_moves`: Histórico de jogadas
- `users`: Sistema de usuários

### Relacionamentos
- Uma sala tem muitos jogadores
- Um jogador pertence a uma sala
- Cada jogada registra carta, jogador e rodada

## 🛠️ Arquitetura

### Backend (Laravel)
```
app/
├── Http/Controllers/
│   ├── GameRoomController.php    # Controle das salas
│   └── CardGameController.php    # Lógica do jogo
├── Models/
│   ├── GameRoom.php             # Modelo da sala
│   ├── GamePlayer.php           # Modelo do jogador
│   └── GameMove.php             # Modelo das jogadas
└── Events/
    ├── GameStarted.php          # Evento de início
    ├── PlayerJoined.php         # Evento de entrada
    └── GameAction.php           # Ações do jogo
```

### Frontend
```
resources/
├── views/
│   ├── game/
│   │   ├── lobby.blade.php      # Lobby principal
│   │   └── room.blade.php       # Sala de espera
│   └── cardgame/
│       └── players.blade.php    # Mesa de jogo
├── css/
│   └── app.css                  # Estilos customizados
└── js/
    ├── app.js                   # JavaScript principal
    └── bootstrap.js             # Configuração WebSocket
```

## 🎯 Funcionalidades Implementadas

### ✅ Core Features
- [x] Sistema de salas multiplayer
- [x] Distribuição automática de cartas
- [x] Sistema de turnos
- [x] Lógica de trunfo
- [x] Interface responsiva
- [x] WebSockets em tempo real
- [x] Chat integrado
- [x] Sistema de pontuação

### 🔄 Melhorias Futuras
- [ ] Sistema de ranking global
- [ ] Diferentes modos de jogo
- [ ] Torneios e competições
- [ ] Avatars personalizados
- [ ] Efeitos sonoros
- [ ] Replay de partidas
- [ ] Sistema de amizades

## 🎨 Screenshots

### Lobby Principal
Interface moderna para criar ou entrar em salas

### Sala de Espera
Aguardando jogadores com progresso visual

### Mesa de Jogo
Interface de jogo com cartas, chat e informações

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Desenvolvido por

**Neemias Braga** - [GitHub](https://github.com/Neemiasbragadev)

---

<div align="center">
  <sub>Construído com ❤️ usando Laravel e TailwindCSS</sub>
</div>
