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
            <a href="{{ route("budgets.create") }}"
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

    @if(count($budgets) > 0)
        <div class="mt-8 flow-root">
            <div class="overflow-x-auto rounded-lg ring-1 ring-gray-300">
                <div class="inline-block min-w-full align-middle">
                    <table class="relative min-w-full">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <span class="sr-only">Presupuestos</span>
                                </th>

                                <th scope="col">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                            @foreach ($budgets as $budget)
                                <tr class="flex items-center justify-between">
                                    <td class="relative px-10 pb-5 pt-10">
                                        <p
                                            class="absolute left-0 top-0 inline-block w-40 rounded-br-2xl px-3 py-1 text-sm font-medium
                                            {{ $budget->isGeneral() ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}
                                            ">
                                            {{ $budget->isGeneral() ? 'General' : 'Proyecto' }}
                                        </p>
                                        <a class="block text-2xl font-bold text-gray-500" href="">{{ $budget->name }}</a>
                                        <p class="text-lg text-gray-500">${{ $budget->amount }}</p>
                                    </td>
                                    <td class="flex justify-end gap-3 px-10 py-6">
                                        <x-budget-drowpdown :budget="$budget" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p class="mt-10 text-center text-xl">No Hay Presupuestos.
            <a href="{{ route("budgets.create") }}" class="text-amber-500">Comienza creando uno</a>
        </p>
    @endif


@endsection
