
<div class="container">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="text-lg font-bold mb-4">Buzón de Mensajes</h2>
        </x-slot>
        <div class="flex">
            <!-- Menú lateral -->
            <aside class="w-1/4 p-4 bg-gray-200">
                <ul>
                    <li>
                        <a href="{{ route('inbox', ['view' => 'received']) }}" class="block px-4 py-2 hover:bg-gray-300 {{ request('view') === 'received' ? 'bg-gray-300 font-bold' : '' }}">
                            Recibidos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('inbox', ['view' => 'sent']) }}" class="block px-4 py-2 hover:bg-gray-300 {{ request('view') === 'sent' ? 'bg-gray-300 font-bold' : '' }}">
                            Enviados
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('inbox', ['view' => 'friend-requests']) }}" class="block px-4 py-2 hover:bg-gray-300 {{ request('view') === 'friend-requests' ? 'bg-gray-300 font-bold' : '' }}">
                            Solicitudes de Amistad
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <section class="w-3/4 p-4">
                @if (request('view') === 'sent')
                    <h3 class="text-lg font-bold mb-4">Mensajes Enviados</h3>
                    @if ($sentMessages && $sentMessages->count() > 0)
                        <ul>
                            @foreach ($sentMessages as $message)
                                <li class="border-b border-gray-200 py-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <p class="text-gray-800 font-semibold">{{ $message->recipient->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ $message->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-800">{{ $message->message }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No has enviado mensajes.</p>
                    @endif
                @elseif (request('view') === 'friend-requests')
                    <h3 class="text-lg font-bold mb-4">Solicitudes de Seguimiento Pendientes</h3>
                    @if ($pendingRequests && $pendingRequests->count() > 0)
                        <ul>
                            @foreach ($pendingRequests as $request)
                                <li class="border-b border-gray-200 py-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <p class="text-gray-800 font-semibold">{{ $request->user->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ $request->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div>
                                            <button wire:click="acceptFriendRequest({{ $request->id }})" class="text-green-500 focus:outline-none">Aceptar</button>
                                            <button wire:click="declineFriendRequest({{ $request->id }})" class="text-red-500 focus:outline-none">Rechazar</button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No tienes solicitudes de amistad pendientes.</p>
                    @endif
                @else
                    <h3 class="text-lg font-bold mb-4">Mensajes Recibidos</h3>
                    @if ($receivedMessages && $receivedMessages->count() > 0)
                        <ul>
                            @foreach ($receivedMessages as $message)
                                <li class="border-b border-gray-200 py-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <p class="text-gray-800 font-semibold">{{ $message->sender->name }}</p>
                                            <p class="text-gray-500 text-sm">{{ $message->created_at->diffForHumans() }}</p>
                                        </div>
                                        <button wire:click="toggleReplyForm({{ $message->id }})" class="text-blue-500 focus:outline-none">Responder</button>
                                    </div>
                                    <p class="text-gray-800">{{ $message->message }}</p>
                                    
                                    <!-- Formulario de respuesta -->
                                    @if ($replyToMessageId === $message->id)
                                        <form wire:submit.prevent="replyToMessage({{ $message->id }})" class="mt-2">
                                            <textarea wire:model="replyMessage" rows="3" class="form-textarea w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                            <div class="mt-2">
                                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600">Enviar</button>
                                                <button wire:click="cancelReply" type="button" class="px-3 py-1 ml-2 bg-gray-300 text-gray-800 font-semibold rounded hover:bg-gray-400">Cancelar</button>
                                            </div>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No tienes mensajes recibidos.</p>
                    @endif
                @endif
            </section>
        </div>
    </x-app-layout>
</div>