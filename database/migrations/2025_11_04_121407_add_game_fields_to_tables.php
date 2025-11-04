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
        // Adicionar campos na tabela game_rooms
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->integer('current_turn')->default(1);
            $table->string('game_status')->default('waiting');
            $table->integer('round')->default(1);
            $table->string('winner')->nullable();
        });

        // Adicionar campos na tabela game_players
        Schema::table('game_players', function (Blueprint $table) {
            $table->integer('score')->default(0);
            $table->integer('position')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->dropColumn(['current_turn', 'game_status', 'round', 'winner']);
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->dropColumn(['score', 'position', 'is_active']);
        });
    }
};
