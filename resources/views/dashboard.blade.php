@extends("layouts.app")


@section("title")
    Administra tus presupuestos
@endsection


@section("actions")
    <div class="mt-10 sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-4xl font-bold">Administra tus Presupuestos</h1>
            <p class="mt-2 text-xl text-gray-500">Administra tus Presupuestos en esta sección</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href=""
                class="block w-full cursor-pointer rounded-lg bg-amber-500 px-5 py-3 text-center text-xl font-bold text-white">Nuevo
                Presupuesto</a>
        </div>
    </div>
@endsection


@section("dashboard-contents")
    {{--
    <h1 class="text-4xl font-bold text-center">Administra tus presupuestos</h1>

    @if (session("success"))
        <x-alert type="success" message="{{ session('success') }}" />
    @endif
    --}}
@endsection
