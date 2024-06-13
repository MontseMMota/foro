<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>

        
        @livewireStyles
    </head>
    <x-app-layout>
        <x-slot name="header">
        </x-slot>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <h1 class="text-4xl text-bold text-center mt-4">WELCOME!</h1>
        <div class="links flex items-center justify-center mt-6">
            <a href="{{ route('form') }}">
                <button type="button" class=" px-4 py-2 bg-zinc-300">Ir al registro</button>
            </a>
        </div>
        @livewireScripts
    </body>
</x-app-layout>
</html>


