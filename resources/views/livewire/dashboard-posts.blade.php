<div>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 my-5">
        ¡No te pierdas lo último que han compartido tus amigos!
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($posts as $post)
            <div class="border border-5 border-gray-500 shadow-lg rounded-lg p-4">
                <p class="text-gray-400 text-sm">Creado por: {{ $post->user->name }}</p>
                <h3 class="font-semibold text-lg text-black mb-2">{{ $post->title }}</h3>
                <p class="text-gray-800">{{ $post->content }}</p>
                
            </div>
        @endforeach
    </div>
</div>