<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class PlayerJoined implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $roomId;
    public $players;

    public function __construct($roomId, $players)
    {
        $this->roomId = $roomId;
        $this->players = $players;
    }

    public function broadcastOn()
    {
        return new Channel("game-room.{$this->roomId}");
    }

    public function broadcastAs()
    {
        return 'player-joined';
    }
}
