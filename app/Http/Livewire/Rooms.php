<?php

namespace App\Http\Livewire;

use App\Models\Room;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;

class Rooms extends Component
{
    public bool $showModal = false;

    public User $user;

    public Room $room;

    protected array $rules = [
        'room.name' => 'required',
        'room.password' => 'required',
    ];

    public function mount(): void
    {
        $this->room = new Room();

        $this->user = auth()->user();
    }

    public function getCurrentRoomProperty(): ?Room
    {
        return $this->user->rooms()->first();
    }

    public function getRoomsProperty(): LengthAwarePaginator
    {
        return Room::query()->withCount('users')->paginate(6);
    }

    public function save(): void
    {
        $this->validate();

        try {
            $this->room->save();

            $this->room->users()->attach(auth()->id(), [
                'users_online' => 1,
            ]);

            $this->showModal = false;

            $this->room = new Room();
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
