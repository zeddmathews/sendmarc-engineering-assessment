<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Game;
use App\Models\PlayerStats;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller
{
    public function simulate(Request $request)
    {
        $request->validate([
            'player1_id' => 'required|exists:players,id',
            'player2_id' => 'required|exists:players,id|different:player1_id',
        ]);

        DB::beginTransaction();

        try {
            $player1 = Player::findOrFail($request->input('player1_id'));
            $player2 = Player::findOrFail($request->input('player2_id'));

            $result = $this->runSimulation($player1, $player2);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'result' => $result,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Simulation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function runSimulation(Player $player1, Player $player2)
    {
        $game = new Game();
        $game->player1_id = $player1->id;
        $game->player2_id = $player2->id;
        $game->score = '6-4, 3-6, 7-5';
        $game->winner_id = $player1->id;
        $game->save();

        $stats1 = new PlayerStats([
            'player_id' => $player1->id,
            'games_id' => $game->id,
            'aces' => rand(0, 5),
            'double_faults' => rand(0, 3),
            'first_serves_in' => rand(10, 20),
            'first_serves_out' => rand(1, 5),
            'points_won' => rand(40, 100),
            'break_points_won' => rand(1, 5),
        ]);

        $stats1->save();

        $stats2 = new PlayerStats([
            'player_id' => $player2->id,
            'games_id' => $game->id,
            'aces' => rand(0, 5),
            'double_faults' => rand(0, 3),
            'first_serves_in' => rand(10, 20),
            'first_serves_out' => rand(1, 5),
            'points_won' => rand(40, 100),
            'break_points_won' => rand(1, 5),
        ]);
        $stats2->save();

        return $game->load('playerStats');
    }
}
