@props([
    'label',
    'name' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'mb-5']) }}>
    <label class="mb-1.5 block text-sm font-semibold text-gray-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    {{ $slot }}
    @if($name)
        @error($name)
            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
