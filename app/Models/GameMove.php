<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMove extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_room_id',
        'player_id', 
        'card',
        'round',
        'order',
        'is_winner'
    ];

    protected $casts = [
        'card' => 'array',
        'is_winner' => 'boolean',
    ];

    public function gameRoom()
    {
        return $this->belongsTo(GameRoom::class);
    }

    public function player()
    {
        return $this->belongsTo(GamePlayer::class);
    }
}
