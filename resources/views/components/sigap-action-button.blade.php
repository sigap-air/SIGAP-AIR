@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'flex w-full items-center justify-center rounded-xl py-3.5 text-center text-sm font-bold transition';
    $classes = $variant === 'primary'
        ? $base . ' bg-brand text-white shadow-sm hover:bg-brand-dark active:opacity-90'
        : $base . ' bg-gray-200 text-gray-800 hover:bg-gray-300 border border-gray-200';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
