<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;

class Rooms extends Component
{
    public bool $showModal = false;

    public Room $room;

    protected array $rules = [
        'room.name' => 'required',
        'room.password' => 'required',
    ];

    public function mount(): void
    {
        $this->room = new Room();
    }

    public function getRoomsProperty(): LengthAwarePaginator
    {
        return Room::query()->paginate(6);
    }

    public function save(): void
    {
        $this->validate();

        try {
            $this->room->save();

            $this->showModal = false;
        } catch (\Throwable $throwable) {
            report($throwable);

            $this->addError('exception', $throwable->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.rooms');
    }
}
