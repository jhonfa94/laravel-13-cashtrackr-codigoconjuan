@extends("layouts.auth")


@section("title")
    Confirma tu Cuenta
@endsection


@section("auth-contents")

    <p class="mt-5 text-lg">
        Tu cuenta esta lista, solo necesitas confirmar tu correo electrónico
    </p>

    @if (session("success"))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif


    <form action="{{ route('verification.send') }}" method="POST">
        @csrf
        <input
            type="submit"
            class="bg-amber-500 w-full  text-center mt-5 px-5 py-2 uppercase font-bold cursor-pointer"
            value='Reenviar Correo de verificación' />

    </form>




@endsection
