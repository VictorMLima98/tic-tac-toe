<div class="flex flex-col rounded-lg gap-y-8">
    <div class="flex justify-end">
        <x-secondary-button wire:click="quit">Quit Room</x-secondary-button>
    </div>
    @if ($this->status === self::STATUS_STARTED)
        @if ($this->ended())
            <div class="py-4 text-2xl font-medium text-center">
                @if ($this->winner)
                    {{ $this->winner }} won!
                @endif

                @if ($this->draw)
                    Draw!
                @endif
            </div>
        @else
            <div class="py-4 text-2xl font-medium text-center">{{ $this->currentTeam }} turn</div>
        @endunless
        <div class="grid grid-cols-3 grid-rows-3 divide-x divide-y rounded-md" x-data>
            @foreach ($this->cells as $key => $cell)
                <div wire:click='handleClick({{ $key }})'
                    @class([
                        'p-16 bg-white flex items-center justify-center',
                        'bg-gray-200' => $cell['team'],
                        'cursor-pointer hover:bg-gray-300' => !$this->ended(),
                        'text-green-500' => in_array($key, $winnerCells)
                    ])>
                    @switch ($cell['team'])
                        @case (self::TEAM_O)
                            <span class="font-bold text-7xl">O</span>
                        @break
                        @case (self::TEAM_X)
                            <span class="font-bold text-7xl">X</span>
                        @break
                        @default
                        <span class="font-bold text-7xl">&nbsp;</span>
                    @endswitch
                </div>
            @endforeach
        </div>
    @elseif ($this->status === self::STATUS_WAITING)
        <div class="p-12 text-7xl">Waiting for a player...</div>
    @else
        <button class="p-12 text-white bg-black text-7xl" wire:click='startGame'>Start</button>
    @endif
</div>