@props([
    'statusKey' => 'available',
    'label' => null,
    'size' => 'sm',
])

@php
    $meta = \App\Services\PetugasMonitoringService::statusMeta($statusKey);
    $text = $label ?? $meta['label'];
    $sizeClass = $size === 'md' ? 'px-3 py-1 text-sm' : 'px-2 py-0.5 text-xs';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border font-semibold {$sizeClass} {$meta['badge']}"]) }}>
    <span class="h-2 w-2 rounded-full {{ $meta['dot'] }} animate-pulse"></span>
    {{ $text }}
</span>
