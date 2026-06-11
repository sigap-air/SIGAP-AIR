<x-app-admin-layout>
    @include('petugas-manajemen._show-content')
    @include('petugas-manajemen._status-modal-script', [
        'petugasList' => collect([$petugas]),
        'routePrefix' => $routePrefix,
    ])
</x-app-admin-layout>
