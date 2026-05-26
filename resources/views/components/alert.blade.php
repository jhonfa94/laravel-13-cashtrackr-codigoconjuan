@props([
    "type" => "success",
    "message" => "",
])

@php
$colors = [
    "success" => "border-green-400 bg-green-100 text-green-700",
    "error" => "border-red-400 bg-red-100 text-red-700",
    "warning" => "border-yellow-400 bg-yellow-100 text-yellow-700",
    "info" => "border-blue-400 bg-blue-100 text-blue-700",
];
$color = $colors[$type] ?? $colors["success"];

@endphp

@if($message)

    <p class="my-10 text-center border {{ $color }} py-3 text-sm">
        {{ $message }}
    </p>

@endif
