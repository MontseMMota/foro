<div class="container">
    <x-app-layout>
        <x-slot name="header">
        </x-slot>
        <h1 class="text-center text-2xl">Lista de Usuarios</h1>
        <div class="flex justify-center items-center text-center mb-8">
            <table class="table w-full">
                <thead class="border">
                    <tr>
                        <th>Nombre</th>
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
                        <td>{{ $form->email ?? '' }}</td>
                        <td>{{ $form->singer ?? '' }}</td>
                        <td>{{ $form->hobby ?? '' }}</td>
                        <td>
                            @if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->id == $form->id))
                                <a href="{{ route('users-edit', ['id' => $form->id]) }}" class="px-3 py-1 bg-amber-200 hover:bg-amber-400">Editar</a>
                              
                                <button wire:click="deleteUser({{ $form->id }})" class="px-3 py-1 bg-red-300 hover:bg-red-500">Borrar</button>
                            @endif
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('users-create') }}" class="px-3 py-1 bg-green-500 hover:bg-green-700 m-10">Añadir nuevo</a>

        <a href="{{ route('blog') }}" class="px-3 py-1 bg-green-500 hover:bg-green-700 m-10">Ir al blog</a>
    </x-app-layout>
</div>