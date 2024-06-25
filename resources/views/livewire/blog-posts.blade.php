<div class="container" wire:poll.500ms>
    <x-app-layout>
        <x-slot name="header">
        </x-slot>

        <div class="container p-4 mx-20">
            
            <h1 class="text-center font-bold text-3xl mb-5 underline">Posts</h1>
            
            {{-- Export Fast Excel or import --}}
            <a href="{{ route('export') }}" class="underline p-2 bg-slate-300">Descargar Datos</a>
            <div class="flex">
                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" accept=".xlsx, .xls, .csv">
                    <button type="submit">Importar</button>
                </form>
            </div>
        </div>
        
        @foreach($posts as $post)
        <div class="grid grid-cols-12 gap-4 items-start bg-blue-100 shadow-md rounded-lg p-4 mb-5 mx-20">
            <div class="col-span-1">
                @if($post->user->avatar)
                    <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar" class="h-24 w-24 rounded-md">
                @else
                    <img src="https://via.placeholder.com/150" alt="Avatar" class="h-24 w-24 rounded-md">
                @endif
            </div>
            <div class="col-span-11">
                <p class="font-semibold text-gray-800">{{ $post->user->name ?? 'Invitado' }} dice:</p>
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
                                <button wire:click="toggleLike({{ $post->id }})" class="px-3 py-1 bg-red-300 rounded-full text-sm font-medium mt-3 ml-2">
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
    
            @if($post->image)
                <div class="col-span-12 mt-4">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Imagen del Post" class="rounded-lg">
                </div>
            @endif
            
            <h2 class="col-start-3 col-span-9 font-bold underline">Respuestas:</h2>
            @if ($post->responses->isEmpty())
                <p class="col-start-3 col-span-9">Aún no hay respuestas para este post.</p>
            @else
                @foreach($post->responses as $response)
                    <div class="col-start-3 col-span-9 mt-2 ml-4 border-l-4 border-gray-300 pl-4">
                        <p class="text-gray-800"><strong>{{ $response->user->name ?? 'Invitado' }} responde:</strong></p>
                        <p class="text-gray-700">{{ $response->content }}</p>
                        <p class="text-gray-400 text-sm">Creado el {{ $response->created_at }}</p>
                    </div>
                @endforeach
            @endif
    
            @if($postIdBeingResponded === $post->id)
                <div class="col-start-3 col-span-9 mt-2">
                    <textarea wire:model="responseContent" class="w-full border border-gray-400 p-2 rounded-lg mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-gray-800 font-bold py-2 px-4 mr-4 rounded focus:outline-none focus:shadow-outline" wire:click="toggleEmojiList">😜</button>
                    <button wire:click="submitResponse" class="px-3 py-1 bg-green-400 hover:bg-green-600 rounded-full text-sm font-medium">Enviar Respuesta</button>
                
                    @if($showEmojiList)
                        <div class="mt-2 bg-white shadow-lg rounded-lg border border-gray-200 max-w-md">
                            <div class="grid grid-cols-6 gap-2 p-2 overflow-auto max-h-64">
                                @foreach($emojiList as $emoji)
                                    <button type="button" class="focus:outline-none" wire:click="selectEmoji('{{ $emoji }}')">{{ $emoji }}</button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
        </div>
    @endforeach
    
    <a href="{{ route('blog') }}">
        <button type="submit" class="px-5 py-2 my-10 mx-5 bg-yellow-300 hover:bg-yellow-400 mt-4 rounded-full text-lg font-medium">
            Crear nuevo Post
        </button>
    </a>
    </x-app-layout>
</div>