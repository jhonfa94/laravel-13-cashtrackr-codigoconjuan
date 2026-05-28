@extends("layouts.app")

@section("title")
    Editar Presupuesto: {{ $budget->name }}
@endsection

@section("actions")
    <div class="mt-10 sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-4xl font-bold">Editar Presupuesto: {{ $budget->name }}</h1>
            <p class="mt-2 text-xl text-gray-500">Realiza ajustes a tu presupuesto</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route("dashboard") }}"
                class="block w-full cursor-pointer rounded-lg bg-amber-500 px-5 py-3 text-center text-xl font-bold text-white">Volver
                a Presupuestos</a>
        </div>
    </div>
@endsection

@section("dashboard-contents")
    <form method="POST" action="{{ route("budgets.update", $budget) }}" class="mx-auto mt-14 max-w-2xl space-y-3" novalidate>
        @csrf
        @method("PUT")

        <x-budget-form :budget="$budget" />

        <input type="submit" value='Guardar Cambios'
            class="w-full cursor-pointer rounded-lg bg-purple-950 p-3 text-xl font-bold text-white hover:bg-purple-800" />
    </form>
@endsection
