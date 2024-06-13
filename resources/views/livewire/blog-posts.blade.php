<div class="container" wire:poll.500ms>
    <x-app-layout>
        <x-slot name="header">
        </x-slot>

        <div class="container mx-auto p-4">
            <h1 class="text-center font-bold text-3xl mb-5 underline">Posts</h1>
            @foreach($posts as $post)
            <div class="grid grid-cols-12 gap-4 items-start bg-blue-100 shadow-md rounded-lg p-4 mb-5">
                <p class="col-span-2 font-semibold text-gray-800">{{ $post->user->name ?? 'Invitado' }} dice:</p>
                <div class="col-span-10">
                    @if($editingPostId !== $post->id)
                        <h2 class="font-bold text-lg text-blue-600">{{ $post->title }}</h2>
                        <p class="text-gray-700">{{ $post->content }}</p>
                        <p class="text-gray-400 text-sm">Creado el {{ $post->created_at }}</p>
                        @if(Auth::check() && ($post->user_id == Auth::id() || Auth::user()->role == 'admin'))
                            <button wire:click="startEditing({{ $post->id }})" class="px-3 py-1 bg-amber-200 hover:bg-amber-400 rounded-full text-sm font-medium">Editar</button>
                            <button wire:click="deletePost({{ $post->id }})" class="px-3 py-1 bg-red-300 hover:bg-red-500 rounded-full text-sm font-medium ml-2">Borrar</button>
                        @endif
                        <div class="flex flex-row">
                            <button wire:click="startResponding({{ $post->id }})" class="px-3 py-1 bg-gray-300 hover:bg-gray-500 rounded-full text-sm font-medium mt-3 ml-2">Responder</button>
                            @if(Auth::check())
                            @if ($this->isLiked($post->id))
                                <button wire:click="toggleLike({{ $post->id }})" class="px-3 py-1 bg-red-300 rounded-full text-sm font-medium mt-3 ml-2" >
                                    {{ $post->likes->count() }} &hearts;
                                </button>
                            @else
                                <button wire:click="toggleLike({{ $post->id }})" class="px-3 py-1 bg-gray-300 hover:bg-gray-500 rounded-full text-sm font-medium mt-3 ml-2">
                                    {{ $post->likes->count() }} &hearts;
                                </button>
                            @endif
                        @else
                            <button class="px-3 py-1 bg-gray-300 rounded-full text-sm font-medium mt-3 ml-2" disabled>
                                {{ $post->likes->count() }} &hearts;
                            </button>
                        @endif
                        </div>
                    @else
                        <div class="flex flex-col">
                            <input type="text" wire:model="editedTitle" class="border border-gray-400 p-2 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <textarea wire:model="editedContent" class="border border-gray-400 p-2 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <button wire:click="updatePost" class="px-3 py-1 bg-green-400 hover:bg-green-600 rounded-full text-sm font-medium">Guardar</button>
                        <button wire:click="cancelEditing" class="px-3 py-1 bg-red-300 hover:bg-red-500 rounded-full text-sm font-medium ml-2">Cancelar</button>
                    @endif
                </div>
                
                <h2 class="col-start-3 col-span-9 font-bold underline">Respuestas:</h2>
                @if ($post->responses->isEmpty())
                <p class="col-start-3 col-span-9">Aún no hay respuestas para este post.</p>
                    
                @else
                    
               
                    
                
                @foreach($post->responses as $response)
                <div class="col-start-3 col-span-9  mt-2 ml-4 border-l-4 border-gray-300 pl-4">
                    <p class="text-gray-800"><strong>{{ $response->user->name ?? 'Invitado' }} responde:</strong></p>
                    <p class="text-gray-700">{{ $response->content }}</p>
                    <p class="text-gray-400 text-sm">Creado el {{ $response->created_at }}</p>
                </div>
                @endforeach
                @endif
                
        
                
                @if($postIdBeingResponded === $post->id)
                <div class="col-start-3 col-span-9 mt-2">
                    <textarea wire:model="responseContent" class="w-full border border-gray-400 p-2 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <button wire:click="submitResponse" class="px-3 py-1 bg-green-400 hover:bg-green-600 rounded-full text-sm font-medium">Enviar Respuesta</button>
                </div>
                @endif
                
            </div>
            
            @endforeach
            
            <a href="{{ route('blog') }}"><button type="submit" class="px-5 py-2 bg-yellow-300 hover:bg-yellow-400 mt-4 rounded-full text-lg font-medium">
                Crear nuevo Post
            </button></a>
        </div>
</x-app-layout>
</div>
