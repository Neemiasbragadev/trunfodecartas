<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'host', 'trump'];

    protected $casts = [
        'trump' => 'array',
    ];

    // Relacionamento com jogadores
    public function players()
    {
        return $this->hasMany(GamePlayer::class, 'game_room_id', 'id');
    }

}
