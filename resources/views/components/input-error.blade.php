@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start mt-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18.858 5.146a.75.75 0 00-1.06-1.06l-6.573 6.573-2.97-2.97a.75.75 0 10-1.06 1.06l3.5 3.5a.75.75 0 001.06 0l7.103-7.103zM9.25 1a8.25 8.25 0 100 16.5A8.25 8.25 0 009.25 1zM1 9.25a8.25 8.25 0 1116.5 0A8.25 8.25 0 011 9.25z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-red-600">{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
