<div class="container" wire:poll.500ms>
    <x-app-layout>
        <x-slot name="header">
        </x-slot>
        <div>
            <form wire:submit.prevent="createPost" class="mb-5" enctype="multipart/form-data">
                <div class="flex flex-col w-4/5 m-auto">
                    <label for="title" class="my-3 text-lg font-bold">Título del Post:</label>
                    <input type="text" wire:model="title" id="title" class="mb-5">
                    <textarea name="content" wire:model="content" id="content" cols="30" rows="10"></textarea>
                    @error('title') <span>{{ $message }}</span> @enderror

                    <div class="mt-4">
                        <label for="image" class="block text-lg font-bold">Imagen:</label>
                        <input type="file" id="image" wire:model="image" accept="image/*">
                        @error('image') <span>{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="px-5  py-2 bg-yellow-300 hover:bg-yellow-400 mt-4">
                        Crear Post
                    </button>
                </div>
            </form>
        </div>
    </x-app-layout>
</div>

