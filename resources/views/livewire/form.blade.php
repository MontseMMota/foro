<div class="container">
    <x-app-layout>
        <x-slot name="header">
        </x-slot>
        <h1 class="text-center text-2xl">Lista de Usuarios</h1>
            <div class="flex justify-center m-4 px-6">
                @if (session()->has('error'))
                    <div class="bg-red-300">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session()->has('message'))
                    <div class="bg-green-300">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        <div class="flex justify-center items-center text-center mb-8">
            <table class="table w-full">
                <thead class="border">
                    <tr>
                        <th>Nombre</th>
                        <th>Verificado</th>
                        <th>Añadir amigo</th>
                        <th>Correo Electrónico</th>
                        <th>Cantante Favorito</th>
                        <th>Pasatiempo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forms as $form)
                    <tr>
                        <td>{{ $form->name ?? '' }}</td>
                        <td>{{ $form->is_verified ? 'Si' : 'No' }}</td>
                        
                        <td>
                            @if(auth()->user()->id != $form->id)
                                @php
                                    $friend = auth()->user()->friends()->where('friend_id', $form->id)->first();
                                @endphp
                                @if ($friend && $friend->pivot->status == 'accepted')
                                    <button class="px-3 py-1 bg-green-300" disabled>Le sigues</button>
                                @elseif ($friend && $friend->pivot->status == 'pending')
                                    <button class="px-3 py-1 bg-yellow-300" disabled>Solicitud de seguimiento enviada</button>
                                @else
                                    <button wire:click="addFriend({{ $form->id }})" class="px-3 py-1 bg-purple-300 hover:bg-purple-500">Añadir como amigo</button>
                                @endif
                            @endif
                        </td>
                        
                        <td>{{ $form->email ?? '' }}</td>
                        <td>{{ $form->singer ?? '' }}</td>
                        <td>{{ $form->hobby ?? '' }}</td>
                        <td>
                            @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->id == $form->id))
                                <a href="{{ route('users-edit', ['id' => $form->id]) }}" class="px-3 py-1 bg-amber-200 hover:bg-amber-400">Editar</a>
                                <button wire:click="deleteUser({{ $form->id }})" class="px-3 py-1 bg-red-300 hover:bg-red-500">Borrar</button>
                                
                            @endif
                            @if(auth()->user()->isVerified() && (auth()->user()->id != $form->id))
                                    <button wire:click="startSendingMessage({{ $form->id }})" class="px-3 py-1 bg-blue-300 hover:bg-blue-500">Enviar Mensaje</button>
                            @endif
                        </td>
                    </tr>
                        @if ($showMessageFormFor && $recipientId === $form->id)
                        <tr>
                            <td colspan="5">
                                <div>
                                    <textarea wire:model="message" rows="3" class="form-textarea w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                    <div class="mt-2">
                                        <button wire:click="sendMessage" type="button" class="px-3 py-1 bg-green-400 hover:bg-green-600 rounded-full text-sm font-medium">Enviar</button>
                                        <button wire:click="cancelSendingMessage" type="button" class="px-3 py-1 ml-2 bg-gray-300 text-gray-800 font-semibold rounded hover:bg-gray-400">Cancelar</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('users-create') }}" class="px-3 py-1 bg-green-500 hover:bg-green-700 m-10">Añadir nuevo</a>

        <a href="{{ route('blog') }}" class="px-3 py-1 bg-green-500 hover:bg-green-700 m-10">Ir al blog</a>

    </x-app-layout>


</div>


