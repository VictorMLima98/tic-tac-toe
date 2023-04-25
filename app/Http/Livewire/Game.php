<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Game extends Component
{
    public const TEAM_O = 'O';

    public const TEAM_X = 'X';

    public const CELLS_QUANTITY = 9;

    public const POSSIBLE_WINS = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],

        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],

        [0, 4, 8],
        [2, 4, 6],
    ];

    public array $teams = [
        self::TEAM_O,
        self::TEAM_X,
    ];

    public string $currentTeam;

    public Collection $cells;

    public ?string $winner = null;

    public array $winnerCells = [];

    public bool $draw = false;

    public function mount(): void
    {
        $this->currentTeam = self::TEAM_X;

        $this->cells = collect(range(1, 9))->map(fn (int $cell, int $key) => [
            'key' => $key,
            'team' => null,
        ]);
    }

    public function handleClick(int $key): void
    {
        if ($this->winner) {
            return;
        }

        if ($this->cells[$key]['team']) {
            return;
        }

        $this->cells[$key] = array_merge($this->cells[$key], [
            'team' => $this->currentTeam,
        ]);

        foreach (self::POSSIBLE_WINS as $possibleWin) {
            $clickedCells = $this->cells
                ->where('team', $this->currentTeam)
                ->whereIn('key', $possibleWin)
                ->pluck('key')->toArray();

            if ($clickedCells === $possibleWin) {
                $this->winner = $this->currentTeam;

                $this->winnerCells = $possibleWin;

                return;
            }
        }

        if (count($this->cells->whereNotNull('team')) === self::CELLS_QUANTITY) {
            $this->draw = true;

            return;
        }

        $this->switchTeam();
    }

    public function switchTeam(): void
    {
        $this->currentTeam = match ($this->currentTeam) {
            self::TEAM_O => self::TEAM_X,
            self::TEAM_X => self::TEAM_O,
        };
    }

    public function ended(): bool
    {
        return $this->winner || $this->draw;
    }

    public function render(): View
    {
        return view('livewire.game');
    }
}
