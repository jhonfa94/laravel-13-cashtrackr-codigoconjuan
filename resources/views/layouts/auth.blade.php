@extends("layouts.base")


@section("contents")
    <main class="max-w-2xl mx-auto mt-10 rounded-xl border border-gray-400 p-10 shadow-lg">
        <h1 class="font-bold text-4xl">
            @yield("title")
        </h1>

        @yield("auth-contents")
    </main>

@endsection
