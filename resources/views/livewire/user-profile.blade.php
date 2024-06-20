<div>
    <div class="container mx-auto" wire:poll.500ms>
        <x-app-layout>
            <x-slot name="header">
                @auth
                <div class="mx-20">
                    <div class="bg-blue-300 p-5">
                        <h2 class="text-center text-2xl font-bold font-serif">¡Hola {{ $user->name }}!</h2>
                    </div>
                    <div class="bg-blue-200 p-5">
                        <h3 class="text-center text-xl font-semibold font-serif">Bienvenid@ a tu perfil</h3>
                    </div>
                    <h3 class="my-5">{{ __('Información personal') }}</h3>

                    <div class="relative inline-block">
                        <div class="group">
                            <label for="avatar-upload" class="cursor-pointer">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/150' }}" alt="Avatar" class="h-40 w-40 rounded-md mx-auto mb-4 opacity-80 group-hover:opacity-100">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-gray-800 bg-opacity-50 h-40 w-40 rounded-md text-white text-center">
                                    <span class="text-lg font-bold">Cambiar foto</span>
                                </div>
                            </label>
                            <input id="avatar-upload" type="file" wire:model="avatar" class="hidden">
                        </div>

                        @if ($showAvatarUploader)
                        <form wire:submit.prevent="saveAvatar" class="mt-4" enctype="multipart/form-data">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar Avatar</button>
                        </form>
                        @endif
                    </div>

                    <p>Estado de Verificación: {{ auth()->user()->is_verified ? 'Verificado' : 'No Verificado' }}</p>
            
                    @unless(auth()->user()->is_verified)
                        <form wire:submit.prevent="verifyAccount">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                                Verificar Cuenta
                            </button>
                        </form>
                    @endunless
        
                    <div class="mb-4">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                        @if ($editingBirthDate)
                            <input id="birth_date" type="date" wire:model.defer="birth_date" class="form-input mt-1 block w-full">
                            @error('birth_date') <span class="text-red-500">{{ $message }}</span> @enderror
                            <button wire:click="saveBirthDate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Guardar</button>
                        @else
                            <p>{{ $formatted_birth_date }}</p>
                            <button wire:click="toggleEditingBirthDate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Editar</button>
                        @endif
                    </div>
        
                    <!-- Formulario para Descripción -->
                    <div class="mb-4 relative">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        @if ($editingDescription)
                            <form wire:submit.prevent="saveDescription">
                                <textarea id="description" wire:model.defer="description" class="form-textarea mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                                
                                <!-- Botón para abrir/ocultar la lista de emojis -->
                                <div class="flex flex-row mt-4 relative">
                                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-gray-800 font-bold py-2 px-4 mr-4 rounded focus:outline-none focus:shadow-outline" wire:click="toggleEmojiList">😜</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2 focus:outline-none focus:shadow-outline">Guardar Descripción</button>

                                    <!-- Lista de emojis -->
                                    @if($showEmojiList)
                                    <div class="absolute top-full mt-2 left-0 bg-white shadow-lg rounded-lg border border-gray-200 max-w-md">
                                        <div class="grid grid-cols-6 gap-2 p-2 overflow-auto max-h-64">
                                            @foreach($emojiList as $emoji)
                                            <button type="button" class="focus:outline-none" wire:click="selectEmoji('{{ $emoji }}')">{{ $emoji }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </form>
                        @else
                            <p>{{ $description }}</p>
                            <button wire:click="toggleEditingDescription" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Editar</button>
                        @endif
                    </div>
                       
                    


                    <h3 class="my-5">{{ __('Mis intereses') }}</h3>
                    @if ($user->interests->isEmpty())
                        <p>Actualmente no tienes intereses añadidos.</p>
                    @else
                        <ul class="flex flex-row flex-wrap">
                            @foreach ($user->interests as $interest)
                                <li class="mr-2 mb-2">
                                    {{ $interest->name }}
                                    <button wire:click="removeInterest({{ $interest->id }})" class="text-red-500 ml-1">x</button>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <h3 class="my-5">{{ __('Añade tus intereses a tu perfil') }}</h3>
                    <div class="w-9/12 m-auto">
                        @if (session()->has('error'))
                            <div class="text-red-500 mb-4">{{ session('error') }}</div>
                        @endif

                        <input type="text" wire:model.debounce.300ms="search" placeholder="Buscar intereses..." class="mb-4 p-2 border rounded w-full">

                        <div class="items-center justify-center text-center">
                            @foreach($visibleInterests as $interest)
                                <button wire:click="addInterest({{ $interest->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 m-4 col-start-1 rounded-full focus:outline-none focus:shadow-outline">{{ $interest->name }}</button>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            @if(count($visibleInterests) < count($interests))
                                <button wire:click="loadMore" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">Ver más</button>
                            @endif

                            @if($page > 1)
                                <button wire:click="loadLess" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">Ver menos</button>
                            @endif
                        </div>
                    </div>

                </div>
                @endauth

                @guest
                <div class="text-center mt-10">
                    <h2 class="text-xl font-bold">Lo sentimos, no estás registrado.</h2>
                    <p class="mt-4">Créate un usuario en este <a href="{{ route('users-create') }}" class="text-blue-500 hover:text-blue-700 font-bold focus:outline-none focus:shadow-outline">formulario</a>.</p>
                   
                </div>
                @endguest


            </x-slot>
                </x-app-layout>
                </div>

                
    
</div>


