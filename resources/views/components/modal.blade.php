@props([
    'title' => null
])

<div
    x-cloak
    x-data="{
        show: @entangle($attributes->wire('model')).defer
    }"
    x-show="show"
    x-on:keydown.escape.window="show = false"
    class="fixed inset-0 z-40 px-4 py-6 overflow-y-auto md:py-24 sm:px-0"
>
    <div x-show="show" class="fixed inset-0 transform" x-on:click="show = false" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
 
    <div x-show="show" class="max-w-lg overflow-hidden transform bg-gray-800 rounded-lg sm:w-full sm:mx-auto">
        @if ($title)
        <div class="p-4 bg-gray-900 text-gray-50">
            {{ $title }}
        </div>
        @endif
        <div class="p-4">
            {{ $slot }}
        </div>
        @if ($footer)
        <div class="p-4 bg-gray-900 text-gray-50">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>