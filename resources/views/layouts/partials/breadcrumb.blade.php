@props(['items' => []])

@if(count($items) > 0)
    <nav class="flex items-center gap-2 mb-6 text-sm">
        <!-- Home Link -->
        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Breadcrumb Items -->
        @foreach($items as $item)
            <span class="text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>

            @if(!empty($item['url']))
                <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="text-gray-900 font-medium">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </nav>
@endif
