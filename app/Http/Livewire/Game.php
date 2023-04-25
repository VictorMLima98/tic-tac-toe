<?php

namespace App\Http\Livewire;

use App\Events\CellFilled;
use App\Events\GameDraw;
use App\Events\GameLost;
use App\Events\PlayerConnected;
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

    public const STATUS_NOT_STARTED = 'not_started';

    public const STATUS_WAITING = 'waiting';

    public const STATUS_STARTED = 'started';

    public string $status;

    public string $currentTeam;

    public Collection $cells;

    public ?string $winner = null;

    public array $winnerCells = [];

    public bool $lost = false;

    public bool $draw = false;

    protected $listeners = [
        'echo:game,PlayerConnected' => 'alertPlayerConnected',
        'echo:game,CellFilled' => 'cellFilled',
        'echo:game,GameLost' => 'gameLost',
        'echo:game,GameDraw' => 'gameDraw',
    ];

    public function mount(): void
    {
        $this->status = self::STATUS_NOT_STARTED;

        $this->currentTeam = self::TEAM_X;

        $this->cells = collect(range(1, 9))->map(fn (int $cell, int $key) => [
            'key' => $key,
            'team' => null,
        ]);
    }

    public function startGame(): void
    {
        $this->status = self::STATUS_WAITING;

        broadcast(new PlayerConnected)->toOthers();
    }

    public function alertPlayerConnected(): void
    {
        if ($this->status === self::STATUS_WAITING) {
            $this->status = self::STATUS_STARTED;

            broadcast(new PlayerConnected)->toOthers();
        }
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

                broadcast(new CellFilled($this->cells->toArray(), $this->currentTeam))->toOthers();
                broadcast(new GameLost($this->winner, $this->winnerCells))->toOthers();

                return;
            }
        }

        if (count($this->cells->whereNotNull('team')) === self::CELLS_QUANTITY) {
            $this->draw = true;

            broadcast(new CellFilled($this->cells->toArray(), $this->currentTeam))->toOthers();
            broadcast(new GameDraw())->toOthers();

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

        broadcast(new CellFilled($this->cells->toArray(), $this->currentTeam))->toOthers();
    }

    public function cellFilled(array $data): void
    {
        $this->cells = collect($data['cells']);
        $this->currentTeam = $data['currentTeam'];
    }

    public function gameLost(array $data): void
    {
        $this->lost = true;

        $this->winner = $data['winner'];
        $this->winnerCells = $data['winnerCells'];
    }

    public function gameDraw(): void
    {
        $this->draw = true;
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
