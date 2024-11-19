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
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->json('trump')->nullable(); // Armazena o trunfo
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->json('hand')->nullable(); // Armazena a mão do jogador
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_rooms_and_players', function (Blueprint $table) {
            //
        });
    }
};
