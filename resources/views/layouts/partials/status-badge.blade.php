@props(['status'])

@php
    $statusMap = [
        'menunggu_verifikasi' => [
            'class' => 'badge-status-waiting',
            'label' => 'Menunggu Verifikasi',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-.707.707a1 1 0 101.414 1.414l1-1A1 1 0 0011 9.586V6z" clip-rule="evenodd" />
                </svg>
            ',
        ],
        'disetujui' => [
            'class' => 'badge-status-approved',
            'label' => 'Disetujui',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            ',
        ],
        'ditugaskan' => [
            'class' => 'badge-status-assigned',
            'label' => 'Ditugaskan',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                </svg>
            ',
        ],
        'sedang_diproses' => [
            'class' => 'badge-status-processing',
            'label' => 'Sedang Diproses',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
            ',
        ],
        'selesai' => [
            'class' => 'badge-status-completed',
            'label' => 'Selesai',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.002A3.066 3.066 0 0117 11a3.066 3.066 0 01-2.812 3.002 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-3.002 3.066 3.066 0 01.835-2.545 3.066 3.066 0 00.835-2.455 3.066 3.066 0 002.812-3.002zm9.724 0c-.403-.963-1.411-1.646-2.589-1.646a2.025 2.025 0 00-1.010.25 2.025 2.025 0 01-2.25 0 2.025 2.025 0 00-1.01-.25c-1.178 0-2.186.683-2.589 1.646a2.025 2.025 0 01-.5 1.518 2.025 2.025 0 00-.5 1.518c0 .6.22 1.149.585 1.581a2.025 2.025 0 01.5 1.517 2.025 2.025 0 01-.5 1.518 2.025 2.025 0 00-.585 1.581c0 .6.22 1.149.585 1.58a2.025 2.025 0 01.5 1.518 2.025 2.025 0 01-.5 1.518c.41.834 1.41 1.416 2.589 1.416 1.178 0 2.179-.582 2.589-1.416a2.025 2.025 0 01.5-1.518 2.025 2.025 0 00.5-1.518 2.025 2.025 0 01.5-1.517 2.025 2.025 0 00.585-1.581 2.025 2.025 0 01-.585-1.58 2.025 2.025 0 00-.5-1.518 2.025 2.025 0 01-.5-1.512z" clip-rule="evenodd" />
                </svg>
            ',
        ],
        'ditolak' => [
            'class' => 'badge-status-rejected',
            'label' => 'Ditolak',
            'icon' => '
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            ',
        ],
    ];

    $current = $statusMap[$status] ?? null;
@endphp

@if($current)
    <span class="{{ $current['class'] }}">
        {!! $current['icon'] !!}
        <span>{{ $current['label'] }}</span>
    </span>
@else
    <span class="badge-status bg-gray-100 text-gray-800 border-gray-200">
        {{ $status }}
    </span>
@endif
