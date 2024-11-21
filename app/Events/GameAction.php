<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class GameAction implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $roomId;
    public $action;
    public $playerId;

    /**
     * Cria uma nova instância do evento.
     *
     * @param  string  $roomId
     * @param  string  $action
     * @param  int  $playerId
     * @return void
     */
    public function __construct($roomId, $action, $playerId)
    {
        $this->roomId = $roomId;
        $this->action = $action;
        $this->playerId = $playerId;
    }

    /**
     * O canal de transmissão que este evento será emitido.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('game.' . $this->roomId); // Define o canal com base na sala
    }

    /**
     * O nome do evento.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'game.action';
    }
}
