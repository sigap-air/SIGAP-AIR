<x-app-layout>
    <x-slot name="title">{{ $pageTitle }}</x-slot>
    @php
        $backToDashboardRoute = 'supervisor.dashboard';
        $backToDashboardLabel = 'Kembali ke Dashboard Supervisor';
    @endphp
    @include('pengaduan.partials.daftar-body')
</x-app-layout>
