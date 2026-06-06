@extends("layouts.auth")

@section("title")
    Iniciar Sesión
@endsection


@section("auth-contents")

@if(session('error'))

    <x-alert  type="error" message="{{ session('error') }}" />

@endif


    <form action="{{ route("login.store") }}" method="POST" class="mt-14 space-y-5" novalidate>
        @csrf
        <div class="flex flex-col gap-2">
            <label class="text-2xl font-bold" for="email">Email</label>

            <input id="email" type="email" placeholder="Email de Registro"
                class="w-full rounded-lg border border-gray-300 p-3" name="email" tabindex="1"
                value="{{ old("email") }}" />
        </div>

        <x-input-error :field="'email'"  />

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <label class="text-2xl font-bold">Password</label>
                <a href="{{ route('password.request') }}" class="text-indigo-950" tabindex="3">¿Olvidaste tu Contraseña?</a>
            </div>
            <input type="password" placeholder="Password de Registro" class="w-full rounded-lg border border-gray-300 p-3"
                name="password" tabindex="2" />
        </div>

        <x-input-error :field="'password'" />

        <input type="submit" value='Iniciar Sesión'
            class="w-full cursor-pointer rounded-lg bg-purple-950 p-3 text-xl font-bold text-white hover:bg-purple-800" />
    </form>
@endsection
