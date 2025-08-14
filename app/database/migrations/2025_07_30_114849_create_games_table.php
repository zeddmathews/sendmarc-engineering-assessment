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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->dateTime('played_at');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->string('match_status')->default('ongoing');
            $table->unsignedBigInteger('player1_id')->nullable();
            $table->unsignedBigInteger('player2_id')->nullable();
            $table->integer('player1_points')->default(0);
            $table->integer('player2_points')->default(0);
            $table->timestamps();

            $table->foreign('player1_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('player2_id')->references('id')->on('players')->onDelete('cascade');
            $table->foreign('winner_id')->references('id')->on('players')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
