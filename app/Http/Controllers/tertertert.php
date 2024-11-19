<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameRoom;

class GameRoomController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $roomId = uniqid('room_'); // Gera um ID único para a sala

        $room = GameRoom::create([
            'room_id' => $roomId,
            'host' => $validated['username'],
        ]);

        // Adiciona o jogador principal como anfitrião
        $room->players()->create([
            'username' => $validated['username'],
            'is_host' => true,
        ]);

        return redirect()->route('game.view', $roomId);
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'room_id' => 'required|string|exists:game_rooms,room_id',
        ]);

        $room = GameRoom::where('room_id', $validated['room_id'])->first();

        if ($room->players()->count() >= 4) {
            return back()->with('error', 'A sala está cheia.');
        }

        $room->players()->create([
            'username' => $validated['username'],
        ]);

        return redirect()->route('game.view', $validated['room_id']);
    }

    public function view2($roomId)
    {
        $room = GameRoom::where('room_id', $roomId)->with('players')->first();

        if (!$room) {
            return redirect()->route('game.create')->with('error', 'Sala não encontrada.');
        }

        return view('game.room', compact('room'));
    }

    public function start2(Request $request, $roomId)
    {
        $room = GameRoom::where('room_id', $roomId)->with('players')->first();

        if ($room->players->count() < 4) {
            return back()->with('error', 'Aguardando mais jogadores.');
        }

        // Lógica para iniciar o jogo
        return redirect()->route('game.play', $roomId);
    }

}
