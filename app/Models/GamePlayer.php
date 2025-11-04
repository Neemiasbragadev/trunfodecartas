<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class GamePlayer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'game_room_id', 'is_host', 'hand', 'score', 'position', 'is_active'];
    
    protected $casts = [
        'hand' => 'array',
        'is_host' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'remember_token',
    ];

    // Relacionamento com a sala
    public function room()
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id', 'id');
    }

    // Verificar se é o turno do jogador
    public function isCurrentTurn()
    {
        return $this->room && $this->room->current_turn === $this->position;
    }

    // Contar cartas na mão
    public function getHandCountAttribute()
    {
        return is_array($this->hand) ? count($this->hand) : 0;
    }

    // Verificar se tem cartas
    public function hasCards()
    {
        return $this->hand_count > 0;
    }

    // Remover carta da mão
    public function removeCard($cardCode)
    {
        $hand = $this->hand ?? [];
        $this->hand = array_filter($hand, function($card) use ($cardCode) {
            return $card['code'] !== $cardCode;
        });
        $this->save();
    }
}
