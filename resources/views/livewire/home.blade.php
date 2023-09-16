<div>
    <form method="post" wire:submit="save">
    {{ $this->form }}
        <div class="flex justify-center items-center">
        <buttton type="submit" class="text-center bg-green-400 cursor-pointer mt-6 hover:bg-green-500 text-white font-bold py-2 w-full rounded-lg">Save</buttton>
        </div>
    </form>
</div>
