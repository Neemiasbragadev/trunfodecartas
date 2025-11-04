<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'host', 'trump', 'current_turn', 'game_status', 'round', 'winner'];

    protected $casts = [
        'trump' => 'array',
    ];

    const STATUS_WAITING = 'waiting';
    const STATUS_PLAYING = 'playing';
    const STATUS_FINISHED = 'finished';

    // Relacionamento com jogadores
    public function players()
    {
        return $this->hasMany(GamePlayer::class, 'game_room_id', 'id');
    }

    // Verificar se a sala está cheia
    public function isFull()
    {
        return $this->players()->count() >= 4;
    }

    // Verificar se pode iniciar o jogo
    public function canStart()
    {
        return $this->players()->count() === 4 && $this->game_status === self::STATUS_WAITING;
    }

    // Obter jogador atual do turno
    public function getCurrentPlayer()
    {
        return $this->players()->skip($this->current_turn - 1)->first();
    }

    // Próximo turno
    public function nextTurn()
    {
        $this->current_turn = ($this->current_turn % 4) + 1;
        $this->save();
    }
}
