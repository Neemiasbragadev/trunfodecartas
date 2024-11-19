<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class GamePlayer extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = ['username', 'game_room_id', 'is_host', 'hand'];
    protected $casts = [
        'hand' => 'array',
    ];

    // Relacionamento com a sala
    public function room()
    {
        return $this->belongsTo(GameRoom::class, 'id', 'game_room_id');
    }
}
