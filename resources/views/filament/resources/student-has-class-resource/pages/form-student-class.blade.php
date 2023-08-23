<x-filament-panels::page>
    <form action="post" wire:submit="save">
        {{$this->form}}

        <button type="submit" class="mt-4 bg-green-700 shadow w-40 hover:bg-green-500 text-white font-bold py-2 px-1 rounded-lg">Save</button>
    </form>
</x-filament-panels::page>
