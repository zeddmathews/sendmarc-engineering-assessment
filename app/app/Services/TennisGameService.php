<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Str;

class TennisGameService
{
    protected Game $game;

    protected array $pointMap = [0 => 0, 1 => 15, 2 => 30, 3 => 40];

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function serialize(): array
    {
        $scoreVisible = $this->game->match_status === 'ongoing';

        return [
            'id' => $this->game->id,
            'player1' => $this->game->player1,
            'player2' => $this->game->player2,
            'score' => $scoreVisible ? [
                'player1' => $this->getDisplayScore($this->game->player1_points, $this->game->player2_points),
                'player2' => $this->getDisplayScore($this->game->player2_points, $this->game->player1_points),
            ] : null,
            'game_over' => $this->isGameOver(),
            'winner' => $this->game->winner_id,
            'played_at' => $this->game->played_at,
            'match_status' => Str::ucfirst($this->game->match_status),
        ];
    }

    public function pointFor(Player $player): void
    {
        if ($this->isGameOver() || $this->game->match_status !== 'ongoing') return;

        $p1Id = $this->game->player1_id;
        $p2Id = $this->game->player2_id;

        $isPlayer1 = $player->id === $p1Id;

        $pPoints = $isPlayer1 ? $this->game->player1_points : $this->game->player2_points;
        $oPoints = $isPlayer1 ? $this->game->player2_points : $this->game->player1_points;

        if ($pPoints < 3) {
            $pPoints++;
        } else {
            if ($pPoints === 3 && $oPoints < 3) {
                $this->game->winner_id = $player->id;
                $this->game->match_status = 'completed';
            } elseif ($pPoints === 3 && $oPoints === 3) {
                $pPoints = 4;
            } elseif ($pPoints === 4) {
                $this->game->winner_id = $player->id;
                $this->game->match_status = 'completed';
            } elseif ($oPoints === 4) {
                $pPoints = 3;
                $oPoints = 3;
            }
        }

        if ($isPlayer1) {
            $this->game->player1_points = $pPoints;
            $this->game->player2_points = $oPoints;
        } else {
            $this->game->player2_points = $pPoints;
            $this->game->player1_points = $oPoints;
        }

        $this->game->save();
    }

    protected function getDisplayScore(int $playerPoints, int $opponentPoints): string
    {
        $terms = ['Love', 'Fifteen', 'Thirty', 'Forty'];

        if ($playerPoints < 4 && $opponentPoints < 4) {
            if ($playerPoints === $opponentPoints) {
                return $terms[$playerPoints] . ' All';
            }
            return $terms[$playerPoints];
        }

        if ($playerPoints >= 3 && $opponentPoints >= 3) {
            if ($playerPoints === $opponentPoints) {
                return 'Deuce';
            }

            return ($playerPoints > $opponentPoints) ? 'Advantage' : $terms[$playerPoints];
        }

        return $terms[$playerPoints] ?? $playerPoints;
    }


    public function isGameOver(): bool
    {
        return (bool) $this->game->winner_id;
    }

    public function winner(): ?Player
    {
        if (!$this->game->winner_id) return null;
        return $this->game->winner;
    }
}
