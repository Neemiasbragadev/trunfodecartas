<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->string('username');                      // Nome do jogador
            $table->foreignId('game_room_id')->nullable()->constrained('game_rooms')->onDelete('cascade');
            $table->boolean('is_host')->default(false);      // Se o jogador é o anfitrião
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_players');
    }
};
