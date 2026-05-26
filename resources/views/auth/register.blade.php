@extends("layouts.auth")


@section("title")
    Crear Cuenta
@endsection


@section("auth-contents")
    <form method="POST" action="{{ route("register.store") }}" class="mt-14 space-y-5" novalidate>
        @csrf
        <div class="space-y-2">
            <label class="block text-2xl font-bold" for="name">Nombre</label>

            <input id="name" type="text" placeholder="Tu Nombre" class="w-full rounded-lg border border-gray-300 p-3"
                name="name" value="{{ old("name") }}" />
        </div>

        <x-input-error field="name" />


        <div class="space-y-2">
            <label class="block text-2xl font-bold" for="email">Email</label>

            <input id="email" type="email" placeholder="Email de Registro"
                class="w-full rounded-lg border border-gray-300 p-3" name="email" value="{{ old("email") }}" />
        </div>

        <x-input-error field="email" />

        <div class="space-y-2">
            <label class="block text-2xl font-bold">Password</label>

            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 p-3"
                name="password" />
        </div>

        <x-input-error field="password" />

        <div class="space-y-2">
            <label class="block text-2xl font-bold" for="password_confirmation">Repetir Password</label>

            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 p-3"
                name="password_confirmation" />
        </div>

        <input type="submit" value='Registrarme'
            class="w-full cursor-pointer rounded-lg bg-purple-950 p-3 text-xl font-bold text-white hover:bg-purple-800" />
    </form>
@endsection
