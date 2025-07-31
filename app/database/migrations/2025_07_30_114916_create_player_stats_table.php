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
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('game_id');
            $table->integer('aces')->default(0); # Build randomizer for when clicking button for potential aces
            $table->integer('double_faults')->default(0); # Build randomizer for when clicking button for potential double faults
            $table->integer('first_serve_in')->default(0); # For calculating accuracy of first serves
            $table->integer('first_serve_out')->default(0); # Build randomizer for when clicking button for potential first serves in or out
            $table->integer('points_won')->default(0);
            $table->integer('break_points_won')->default(0);

            $table->timestamps();

            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');

            $table->unique(['player_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
