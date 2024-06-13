<div>
    <x-app-layout>
        <x-slot name="header">
        </x-slot>
    <h2 class="text-2xl font-semibold mb-4 text-center">{{ $formId ? 'Editar Usuario' : 'Agregar Usuario' }}</h2>
        <form wire:submit.prevent="save" class="space-y-4  w-10/12 m-auto">
            @csrf
            

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <div class="form-group">
                <label for="name" class="block font-semibold text-gray-700">Nombre</label>
                <input type="text" id="name" wire:model="name" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="block font-semibold text-gray-700">Correo Electrónico</label>
                <input type="email" id="email" wire:model="email" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="block font-semibold text-gray-700">Password</label>
                <input type="password" id="password" wire:model="password" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="block font-semibold text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="singer" class="block font-semibold text-gray-700">Cantante Favorito</label>
                <input type="text" id="singer" wire:model="singer" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('singer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="hobby" class="block font-semibold text-gray-700">Pasatiempo</label>
                <input type="text" id="hobby" wire:model="hobby" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm">
                @error('hobby') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        
            <button type="submit" wire:click="save" class="bg-amber-600 hover:bg-black text-white font-semibold py-2 px-4 rounded-md">{{ $formId ? 'Actualizar' : 'Guardar' }}</button>
        </form>
        

    </x-app-layout>
</div>