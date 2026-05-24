@props(['rating' => 0, 'max' => 5, 'size' => 'sm', 'label' => null])

@php
    $rating = max(0, min((float) $rating, (float) $max));
    $sizeClass = match($size) {
        'lg' => 'text-xl',
        'md' => 'text-base',
        default => 'text-sm',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-0.5 {$sizeClass}"]) }}>
    @for ($i = 1; $i <= $max; $i++)
        <span class="material-symbols-outlined {{ $i <= round($rating) ? 'text-amber-400' : 'text-gray-300' }}"
              style="font-variation-settings: 'FILL' {{ $i <= round($rating) ? '1' : '0' }};">
            star
        </span>
    @endfor
    @if($label)
        <span class="ml-1 text-gray-600 font-semibold">{{ $label }}</span>
    @endif
</span>
