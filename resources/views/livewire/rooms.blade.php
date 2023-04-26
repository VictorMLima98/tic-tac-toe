<ul role="list" class="p-4 space-y-6 rounded-md bg-gray-50">
    @if ($this->currentRoom)
        <livewire:game :room="$this->currentRoom" />
    @else
        <div class="flex justify-end">
            <x-secondary-button wire:click="$set('showModal', true)">New Room</x-primary-button>
        </div>
        @forelse ($this->rooms as $room)
            <li class="flex items-center justify-between gap-x-24">
                <div class="min-w-0">
                    <div class="flex items-start gap-x-3">
                        <p class="text-sm font-semibold leading-6 text-gray-900">{{ $room->name }}</p>
                        <span class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-green-50 bg-green-700 ring-green-600/20">
                            1/2
                        </span>
                    </div>
                </div>
                <div class="flex items-center flex-none gap-x-4">
                    <div class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block">
                        Join
                    </div>
                </div>
            </li>
        @empty
            <span>No room found.</span>
            
            <span class="text-sm font-bold text-gray-900 transition-all cursor-pointer hover:text-gray-600" wire:click="$set('showModal', true)">CREATE ONE</span>
        @endforelse

        {{ $this->rooms->links() }}

        <x-modal wire:model='showModal' name='createRoomModal' title="Create Room">
            <div class="flex flex-col gap-y-2">
                <div>
                    <x-input-label for="name" :value="__('Room Name')" />
                    <x-text-input id="name" wire:model='room.name' name="name" type="text" class="block w-full mt-1" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('room.name')" />
                </div>
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" wire:model='room.password' name="password" type="password" class="block w-full mt-1" autocomplete="password" />
                    <x-input-error :messages="$errors->get('room.password')" class="mt-2" />
                </div>

                <x-slot:footer>
                    <div class="flex justify-end gap-x-4">
                        <x-secondary-button wire:click="$set('showModal', false)">Cancel</x-secondary-button>
                        <x-primary-button wire:click='save'>Create</x-primary-button>
                    </div>
                </x-slot>
            </div>
        </x-modal>
    @endif
</ul>
