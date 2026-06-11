@props([
    'statusKey' => 'tersedia',
    'label' => null,
    'size' => 'sm',
])

@php
    $meta = \App\Services\PetugasMonitoringService::statusMeta($statusKey);
    $text = $label ?? $meta['label'];
    $sizeClass = $size === 'md' ? 'px-3 py-1.5 text-sm' : 'px-3 py-1.5 text-xs';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-full font-bold {$sizeClass} {$meta['badge']}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $meta['dot'] }}"></span>
    {{ $text }}
</span>
