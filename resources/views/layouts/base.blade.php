<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config("app.name", "Laravel") }} - @yield("title")</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path("build/manifest.json")) || file_exists(public_path("hot")))
        @vite(["resources/css/app.css", "resources/js/app.js"])
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>

<body>

    <header class="bg-purple-950 py-5">
        <div class="mx-auto flex max-w-6xl flex-col items-center lg:flex-row lg:justify-between">
            <div class="max-w-100 w-full">
                <img src="{{ asset("img/logo.svg") }}" alt="Logo" class="w-full">
            </div>


            <nav class="flex flex-col items-center gap-4 lg:flex-row">

                @auth
                    <p class="text-white text-xl"> Hola: {{ auth()->user()->name }}</p>
                    <x-dropdown-menu />
                @else
                    @if (Route::has("login"))
                        <a href="{{ route("login") }}" class="p-2 font-bold uppercase text-white">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route("register") }}"
                            class="border-2 border-amber-500 px-5 py-2 font-bold uppercase text-amber-500">Crear cuenta</a>
                    @endif
                @endauth
            </nav>
        </div>

    </header>

        @if (session('success'))
        <div class="max-w-5xl mx-auto" >
            <x-alert type="success" :message="session('success')" />
        </div>
        @endif

        @if (session('error'))
        <div class="max-w-5xl mx-auto" >
            <x-alert type="error" :message="session('error')" />
        </div>
        @endif

    @yield("contents")
</body>

</html>
