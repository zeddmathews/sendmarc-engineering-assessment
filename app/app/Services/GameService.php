<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use App\Jobs\UpdateGameWon;
use App\Enums\MatchStatus;
use Illuminate\Support\Str;

class GameService
{
    protected Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function start(): void
    {
        $this->game->update([
            'match_status' => MatchStatus::Ongoing->value,
        ]);
    }

    public function end(): void
    {
        if ($this->game->match_status !== MatchStatus::Ongoing->value) {
            return;
        }

        if ($this->game->winner_id) {
            $this->game->update(['match_status' => MatchStatus::Completed->value]);
            UpdateGameWon::dispatch($this->game->winner()->first());
            return;
        }

        $this->game->update(['match_status' => MatchStatus::Tied->value]);
    }

    public function destroy(): void
    {
        $this->game->delete();
    }

    public function update(array $data): void
    {
        $this->game->update($data);
    }

    public function assignPoint(Player $player): void
    {
        if ($this->game->winner_id) {
            return;
        }

        if ($this->game->player1_id === $player->id) {
            $this->game->player1_points++;
        } elseif ($this->game->player2_id === $player->id) {
            $this->game->player2_points++;
        }

        $diff = $this->game->player1_points - $this->game->player2_points;

        if ($this->game->player1_points >= 4 && $diff >= 2) {
            $this->game->winner_id = $this->game->player1_id;
            $this->game->match_status = MatchStatus::Completed->value;
        } elseif ($this->game->player2_points >= 4 && $diff <= -2) {
            $this->game->winner_id = $this->game->player2_id;
            $this->game->match_status = MatchStatus::Completed->value;
        }

        $this->game->save();
    }

    public function serialize(): array
    {
        $scoreVisible = $this->game->match_status === MatchStatus::Ongoing->value;

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

        return $terms[$playerPoints] ?? (string) $playerPoints;
    }

    public function isGameOver(): bool
    {
        return (bool) $this->game->winner_id;
    }

    public function winner(): ?Player
    {
        return $this->game->winner_id ? $this->game->winner : null;
    }
}
