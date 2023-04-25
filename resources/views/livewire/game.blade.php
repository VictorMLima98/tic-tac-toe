<div class="bg-gray-400 rounded-lg">
    @if ($this->ended())
        <div class="py-4 font-medium text-2xl text-center">
            @if ($this->winner)
                {{ $this->winner }} won!
            @endif

            @if ($this->draw)
                Draw!
            @endif
        </div>
    @else
        <div class="py-4 font-medium text-2xl text-center">{{ $this->currentTeam }} turn</div>
    @endunless
    <div class="grid grid-cols-3 grid-rows-3 divide-x divide-y rounded-md" x-data>
        @foreach ($this->cells as $key => $cell)
            <div wire:click='handleClick({{ $key }})'
                @class([
                    'p-16 bg-white flex items-center justify-center',
                    'bg-gray-200' => $cell['clicked'],
                    'cursor-pointer hover:bg-gray-300' => !$this->ended(),
                    'text-green-500' => in_array($key, $winnerCells)
                ])>
                @if ($cell['clicked'])
                    @switch ($cell['team'])
                        @case (self::TEAM_O)
                            <span class="text-7xl font-bold">O</span>
                        @break
                        @case (self::TEAM_X)
                            <span class="text-7xl font-bold">X</span>
                        @break
                    @endswitch
                @else
                    <span class="text-7xl font-bold">&nbsp;</span>
                @endif
            </div>
        @endforeach
    </div>
</div>